#!/usr/bin/env python3
"""
ASEANAPOL — Alibaba Cloud initial server setup & deployment script.

Usage:
    python deploy_alicloud.py

Requirements:
    pip install paramiko

What this script does:
  1. Install Docker CE + Compose plugin on the server
  2. Clone the GitHub repository
  3. Write production .env via SFTP
  4. Build PHP image and start all containers (app, nginx, mysql)
  5. Run composer install inside the app container
  6. Run artisan key:generate, migrate, db:seed
  7. Import news_facebook_seed.sql
  8. Run npm install + npm run build for Vite assets
  9. Warm artisan caches (config / view / route)
 10. SFTP-upload public/media/ (gitignored media files)
 11. Run HTTP smoke-tests on all 12 language homepages + key pages
"""

import os
import sys
import time
import posixpath

try:
    import paramiko
except ImportError:
    sys.exit("paramiko not installed -- run:  pip install paramiko")

# Force UTF-8 output on Windows
if sys.stdout.encoding and sys.stdout.encoding.lower() != "utf-8":
    sys.stdout.reconfigure(encoding="utf-8", errors="replace")

# ─── Server ─────────────────────────────────────────────────────────────────
HOST     = "47.250.166.174"
SSH_USER = "root"
SSH_PASS = "RtW-9-nq3V0t"

# ─── App ────────────────────────────────────────────────────────────────────
APP_URL      = "http://47.250.166.174"
DB_PASS      = "AsEaNaPoL_2026_Prod"
DB_ROOT_PASS = "R00t_AsEaN_2026"
REPO_URL     = "https://github.com/fadilaiman/aseanapol.git"
WEB_ROOT     = "/var/www/aseanapol"

# ─── Local paths ────────────────────────────────────────────────────────────
PROJECT_DIR  = r"C:\laragon\www\aseanapol"
LOCAL_MEDIA  = r"C:\laragon\www\aseanapol\docs\aseanapol\exports\files"

# ─── Colours (Windows Terminal supports ANSI) ────────────────────────────────
R = "\033[0m"
BOLD = "\033[1m"
CYAN = "\033[96m"
GREEN = "\033[92m"
YELLOW = "\033[93m"
RED = "\033[91m"
GREY = "\033[90m"


def section(title):
    print(f"\n{BOLD}{CYAN}{'=' * 62}{R}")
    print(f"{BOLD}{CYAN}  {title}{R}")
    print(f"{BOLD}{CYAN}{'=' * 62}{R}")


def ok(msg):   print(f"{GREEN}  [OK]   {msg}{R}")
def info(msg): print(f"{CYAN}  [..]   {msg}{R}")
def warn(msg): print(f"{YELLOW}  [!!]   {msg}{R}")
def fail(msg): print(f"{RED}  [XX]   {msg}{R}")


# ─── SSH helpers ─────────────────────────────────────────────────────────────

def ssh_run(client, cmd, *, timeout=120, check=True, label=None):
    """Run a command via SSH. Stream stdout live. Return (exit_code, stdout, stderr)."""
    display = label or cmd[:90]
    info(display)

    _stdin, stdout, stderr = client.exec_command(cmd, timeout=timeout)
    _stdin.close()

    lines = []
    for raw in stdout:
        line = raw.rstrip("\n")
        if line.strip():
            print(f"  {GREY}{line}{R}")
            lines.append(line)

    code = stdout.channel.recv_exit_status()
    err_text = stderr.read().decode("utf-8", errors="replace").strip()

    if err_text and code != 0:
        for line in err_text.splitlines():
            if line.strip():
                print(f"  {RED}[stderr] {line}{R}")

    if check and code != 0:
        raise RuntimeError(f"Command failed (exit {code}): {display}\n{err_text[:400]}")

    ok(display)
    return code, "\n".join(lines), err_text


def sftp_upload_tree(sftp, local_dir, remote_dir):
    """Recursively upload a local directory via SFTP. Returns (uploaded, total)."""
    uploaded = total = 0
    for root, _dirs, files in os.walk(local_dir):
        rel = os.path.relpath(root, local_dir).replace("\\", "/")
        rdir = posixpath.join(remote_dir, rel) if rel != "." else remote_dir
        try:
            sftp.mkdir(rdir)
        except OSError:
            pass
        for fname in files:
            total += 1
            try:
                sftp.put(os.path.join(root, fname), posixpath.join(rdir, fname))
                uploaded += 1
                if uploaded % 50 == 0:
                    info(f"  {uploaded} files uploaded so far…")
            except Exception as exc:
                warn(f"  skip {fname}: {exc}")
    return uploaded, total


# ─── Phase functions ─────────────────────────────────────────────────────────

def phase_docker(c):
    section("1 · Install Docker CE & Compose plugin")

    ssh_run(c, "apt-get update -qq", timeout=120, label="apt-get update")
    ssh_run(c,
            "DEBIAN_FRONTEND=noninteractive apt-get install -y -qq "
            "ca-certificates curl gnupg lsb-release git",
            timeout=120, label="install prerequisites")

    code, _, _ = ssh_run(c, "docker --version 2>/dev/null", check=False, label="check docker")
    if code == 0:
        warn("Docker already present — skipping install")
    else:
        ssh_run(c, r"""
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg \
  | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
chmod a+r /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] \
https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" \
  | tee /etc/apt/sources.list.d/docker.list > /dev/null
        """, timeout=60, label="add Docker apt repo")

        ssh_run(c,
                "apt-get update -qq && "
                "DEBIAN_FRONTEND=noninteractive apt-get install -y -qq "
                "docker-ce docker-ce-cli containerd.io docker-compose-plugin",
                timeout=300, label="install Docker CE")

        ssh_run(c, "systemctl enable --now docker", label="enable docker service")

    ssh_run(c, "docker compose version", label="verify docker compose")

    # Open port 80 if ufw is active
    code, out, _ = ssh_run(c, "ufw status 2>/dev/null | head -1", check=False, label="check ufw")
    if "active" in out.lower():
        ssh_run(c, "ufw allow 80/tcp && ufw allow 443/tcp", check=False, label="ufw allow 80/443")


def phase_clone(c):
    section("2 · Clone Repository")

    ssh_run(c, "mkdir -p /var/www", label="ensure /var/www")

    # Allow root to run git in a www-data-owned directory
    ssh_run(c, f"git config --global --add safe.directory {WEB_ROOT}", check=False, label="git safe.directory")

    code, _, _ = ssh_run(c, f"test -d {WEB_ROOT}/.git", check=False)
    if code == 0:
        warn("Repo already exists — running git pull instead")
        ssh_run(c, f"cd {WEB_ROOT} && git pull origin main", timeout=120, label="git pull")
    else:
        ssh_run(c, f"git clone {REPO_URL} {WEB_ROOT}", timeout=300, label="git clone")

    ssh_run(c, f"chown -R www-data:www-data {WEB_ROOT}", label="chown www-data")
    ssh_run(c, f"chmod -R 755 {WEB_ROOT}", label="chmod 755")
    ssh_run(c,
            f"chmod -R 775 {WEB_ROOT}/storage {WEB_ROOT}/bootstrap/cache",
            label="chmod 775 storage & cache")


def phase_env(client):
    section("3 · Write .env (SFTP)")

    env = (
        'APP_NAME="ASEAN APOL"\n'
        'APP_ENV=production\n'
        'APP_KEY=\n'
        f'APP_DEBUG=false\n'
        f'APP_URL={APP_URL}\n'
        '\n'
        'APP_LOCALE=en\n'
        'APP_FALLBACK_LOCALE=en\n'
        'APP_FAKER_LOCALE=en_US\n'
        '\n'
        'APP_MAINTENANCE_DRIVER=file\n'
        'BCRYPT_ROUNDS=12\n'
        '\n'
        'LOG_CHANNEL=stack\n'
        'LOG_STACK=single\n'
        'LOG_DEPRECATIONS_CHANNEL=null\n'
        'LOG_LEVEL=error\n'
        '\n'
        'DB_CONNECTION=mysql\n'
        'DB_HOST=mysql\n'
        'DB_PORT=3306\n'
        'DB_DATABASE=aseanapol\n'
        'DB_USERNAME=aseanapol\n'
        f'DB_PASSWORD={DB_PASS}\n'
        '\n'
        f'DB_ROOT_PASSWORD={DB_ROOT_PASS}\n'
        '\n'
        'SESSION_DRIVER=database\n'
        'SESSION_LIFETIME=120\n'
        'SESSION_ENCRYPT=false\n'
        'SESSION_PATH=/\n'
        'SESSION_DOMAIN=null\n'
        '\n'
        'BROADCAST_CONNECTION=log\n'
        'FILESYSTEM_DISK=local\n'
        'QUEUE_CONNECTION=database\n'
        'CACHE_STORE=database\n'
        '\n'
        'MAIL_MAILER=log\n'
        'MAIL_SCHEME=null\n'
        'MAIL_HOST=127.0.0.1\n'
        'MAIL_PORT=2525\n'
        'MAIL_USERNAME=null\n'
        'MAIL_PASSWORD=null\n'
        'MAIL_FROM_ADDRESS="noreply@aseanapol.org"\n'
        'MAIL_FROM_NAME="${APP_NAME}"\n'
        '\n'
        'VITE_APP_NAME="${APP_NAME}"\n'
    )

    sftp = client.open_sftp()
    with sftp.file(f"{WEB_ROOT}/.env", "w") as f:
        f.write(env)
    sftp.close()
    ok(f"Written {WEB_ROOT}/.env")


def phase_containers(c):
    section("4 · Build Docker image & start containers")

    # This server already has system nginx on port 80 (eads.aseanapol.org).
    # Expose PHP-FPM on 127.0.0.1:9000 and disable Docker nginx (system nginx will serve us).
    new_compose = (
        "services:\n"
        "\n"
        "  app:\n"
        "    build:\n"
        "      context: .\n"
        "      dockerfile: Dockerfile\n"
        "    container_name: aseanapol_app\n"
        "    restart: unless-stopped\n"
        "    working_dir: /var/www/html\n"
        "    ports:\n"
        '      - "127.0.0.1:9000:9000"\n'
        "    volumes:\n"
        "      - .:/var/www/html\n"
        "    networks:\n"
        "      - aseanapol\n"
        "    depends_on:\n"
        "      mysql:\n"
        "        condition: service_healthy\n"
        "\n"
        "  nginx:\n"
        "    image: nginx:1.25-alpine\n"
        "    container_name: aseanapol_nginx\n"
        '    restart: "no"\n'
        "    ports:\n"
        '      - "8080:80"\n'
        "    volumes:\n"
        "      - .:/var/www/html\n"
        "      - ./docker/nginx/conf.d:/etc/nginx/conf.d:ro\n"
        "    networks:\n"
        "      - aseanapol\n"
        "    depends_on:\n"
        "      - app\n"
        "\n"
        "  mysql:\n"
        "    image: mysql:8.0\n"
        "    container_name: aseanapol_mysql\n"
        "    restart: unless-stopped\n"
        "    environment:\n"
        "      MYSQL_DATABASE: ${DB_DATABASE:-aseanapol}\n"
        "      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD:-rootsecret}\n"
        "      MYSQL_USER: ${DB_USERNAME:-aseanapol}\n"
        "      MYSQL_PASSWORD: ${DB_PASSWORD:-secret}\n"
        "    volumes:\n"
        "      - mysql_data:/var/lib/mysql\n"
        "    ports:\n"
        '      - "3306:3306"\n'
        "    networks:\n"
        "      - aseanapol\n"
        "    healthcheck:\n"
        '      test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-u", "root", "-p${DB_ROOT_PASSWORD:-rootsecret}"]\n'
        "      interval: 10s\n"
        "      timeout: 5s\n"
        "      retries: 5\n"
        "\n"
        "networks:\n"
        "  aseanapol:\n"
        "    driver: bridge\n"
        "\n"
        "volumes:\n"
        "  mysql_data:\n"
        "    driver: local\n"
    )
    sftp = c.open_sftp()
    with sftp.file(f"{WEB_ROOT}/docker-compose.yml", "w") as f:
        f.write(new_compose)
    sftp.close()
    ok("docker-compose.yml updated (PHP-FPM on 127.0.0.1:9000, Docker nginx disabled)")

    ssh_run(c,
            f"cd {WEB_ROOT} && docker compose build",
            timeout=720, label="docker compose build  (5-10 min — building PHP image)")

    ssh_run(c,
            f"cd {WEB_ROOT} && docker compose up -d app mysql",
            timeout=120, label="docker compose up -d app mysql")

    info("Waiting for MySQL health check (up to 2 min)…")
    for attempt in range(12):
        time.sleep(10)
        code, out, _ = ssh_run(c,
            "docker inspect --format='{{.State.Health.Status}}' aseanapol_mysql",
            check=False, label="mysql health")
        if "healthy" in out:
            ok("MySQL is healthy")
            return
        warn(f"  MySQL not ready yet ({attempt + 1}/12)…")

    raise RuntimeError("MySQL failed to become healthy after 120 s — check: docker logs aseanapol_mysql")


def phase_system_nginx(c):
    section("4b · Configure system nginx for aseanapol")

    nginx_conf = (
        "server {\n"
        "    listen 80;\n"
        f"    server_name aseanapol.org www.aseanapol.org {HOST};\n"
        "\n"
        f"    root {WEB_ROOT}/public;\n"
        "    index index.php;\n"
        "    charset utf-8;\n"
        "\n"
        "    gzip on;\n"
        "    gzip_types text/plain text/css application/json application/javascript text/xml application/xml image/svg+xml;\n"
        "    gzip_min_length 1000;\n"
        "\n"
        "    location / {\n"
        "        try_files $uri $uri/ /index.php?$query_string;\n"
        "    }\n"
        "\n"
        "    location = /favicon.ico { access_log off; log_not_found off; }\n"
        "    location = /robots.txt  { access_log off; log_not_found off; }\n"
        "\n"
        "    error_page 404 /index.php;\n"
        "\n"
        "    location ~ \\.php$ {\n"
        "        fastcgi_pass 127.0.0.1:9000;\n"
        "        fastcgi_param SCRIPT_FILENAME /var/www/html/public$fastcgi_script_name;\n"
        "        include fastcgi_params;\n"
        "        fastcgi_hide_header X-Powered-By;\n"
        "        fastcgi_read_timeout 300;\n"
        "        fastcgi_buffers 16 16k;\n"
        "        fastcgi_buffer_size 32k;\n"
        "    }\n"
        "\n"
        "    location ~ /\\.(?!well-known).* {\n"
        "        deny all;\n"
        "    }\n"
        "\n"
        "    location ~* \\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {\n"
        "        expires 1y;\n"
        '        add_header Cache-Control "public, immutable";\n'
        "        access_log off;\n"
        "    }\n"
        "\n"
        "    client_max_body_size 20M;\n"
        "}\n"
    )

    sftp = c.open_sftp()
    with sftp.file("/etc/nginx/sites-available/aseanapol", "w") as f:
        f.write(nginx_conf)
    sftp.close()

    ssh_run(c, "rm -f /etc/nginx/sites-enabled/default", check=False, label="remove default site")
    ssh_run(c, "ln -sf /etc/nginx/sites-available/aseanapol /etc/nginx/sites-enabled/aseanapol",
            label="enable aseanapol site")
    ssh_run(c, "nginx -t", label="nginx config test")
    ssh_run(c, "systemctl reload nginx", label="reload system nginx")


def phase_laravel(c):
    section("5 · Laravel setup (composer, key, migrate, seed)")

    app = "docker exec aseanapol_app"

    # Install PHP dependencies (vendor/ is gitignored)
    ssh_run(c,
            f"{app} composer install --no-dev --optimize-autoloader --no-interaction",
            timeout=300, label="composer install")

    # Generate app key
    ssh_run(c, f"{app} php artisan key:generate --force", label="artisan key:generate")

    # Storage symlink
    ssh_run(c, f"{app} php artisan storage:link --force",
            label="artisan storage:link", check=False)

    # Migrations
    ssh_run(c, f"{app} php artisan migrate --force",
            timeout=180, label="artisan migrate --force")

    # Seed admin user (required — creates the first admin account)
    ssh_run(c, f"{app} php artisan db:seed --class=AdminUserSeeder --force",
            timeout=60, label="seed AdminUserSeeder")

    # Optional seeders (best-effort; may silently fail if data already present)
    for seeder in [
        "VideoGallerySeeder",
        "DigitalLibrarySeeder",
        "NewsItemSeeder",
    ]:
        ssh_run(c, f"{app} php artisan db:seed --class={seeder} --force",
                timeout=120, label=f"seed {seeder}", check=False)


def phase_import_sql(c):
    section("6 · Import news_facebook_seed.sql")

    sql_file = f"{WEB_ROOT}/news_facebook_seed.sql"
    code, _, _ = ssh_run(c, f"test -f {sql_file}", check=False)
    if code != 0:
        warn("news_facebook_seed.sql not found — skipping")
        return

    ssh_run(c,
            f"cat {sql_file} | docker exec -i aseanapol_mysql "
            f"mysql -u aseanapol -p{DB_PASS} aseanapol",
            timeout=120, label="import news_facebook_seed.sql", check=False)


def phase_assets(c):
    section("7 · Build frontend assets (Vite / Tailwind)")

    app = "docker exec aseanapol_app"

    # node_modules/ is gitignored — install inside the container
    ssh_run(c, f"{app} npm install", timeout=300, label="npm install")
    ssh_run(c, f"{app} npm run build", timeout=300, label="npm run build")

    # Fix ownership of built assets
    ssh_run(c,
            f"chown -R www-data:www-data {WEB_ROOT}/public/build",
            label="chown public/build", check=False)

    # Warm Laravel caches (must be after assets and key generation)
    ssh_run(c, f"{app} php artisan config:cache", label="artisan config:cache")
    ssh_run(c, f"{app} php artisan view:cache",   label="artisan view:cache")
    ssh_run(c, f"{app} php artisan route:cache",  label="artisan route:cache")

    ok("Assets built and caches warmed")


def phase_media(client):
    section("8 · Upload public/media/ via SFTP")

    if not os.path.isdir(LOCAL_MEDIA):
        warn(f"Local media dir not found: {LOCAL_MEDIA}")
        warn("Skipping media upload — run manually if needed")
        return

    remote_media = f"{WEB_ROOT}/public/media"
    ssh_run(client, f"mkdir -p {remote_media}", label="create remote media dir")

    sftp = client.open_sftp()
    info(f"Uploading  {LOCAL_MEDIA}")
    info(f"        →  {remote_media}")
    uploaded, total = sftp_upload_tree(sftp, LOCAL_MEDIA, remote_media)
    sftp.close()

    ok(f"Media upload done: {uploaded}/{total} files")

    ssh_run(client,
            f"chown -R www-data:www-data {remote_media}",
            label="chown media dir", check=False)


def phase_test(c):
    section("9 · HTTP smoke tests")

    languages = ["en", "ms", "id", "th", "vi", "km", "lo", "my", "tl", "zh", "es", "ru"]

    paths = (
        ["/"]
        + [f"/{lang}" for lang in languages]
        + [
            "/en/about-aseanapol",
            "/en/about-aseanapol/chronology",
            "/en/about-aseanapol/vision-and-mission",
            "/en/about-aseanapol/leadership",
            "/en/about-aseanapol/governance",
            "/en/about-aseanapol/member-countries",
            "/en/about-aseanapol/member-countries/royal-thai-police",
            "/en/news-media/news",
            "/en/news-media/video-gallery",
            "/en/news-media/gallery",
            "/en/news-media/newsletters",
            "/en/data-resources/digital-library",
            "/en/data-resources/crime-statistics",
            "/en/data-resources/publications",
            "/en/events-training/calendar",
            "/en/events-training/conferences",
            "/en/training/online-training",
            "/en/careers/vacancies",
            "/en/careers/internships",
            "/en/contact-us",
            "/en/international-cooperation/interpol",
            "/en/international-cooperation/europol",
            "/en/international-cooperation/unodc",
            "/en/guidelines",
            "/en/publication/14th-edition-aseanapol-bulletin",
            "/ms/about-aseanapol",
            "/zh/about-aseanapol",
            "/th/news-media/news",
            "/admin/login",
        ]
    )

    results = {}
    for path in paths:
        _, out, _ = ssh_run(
            c,
            f"curl -s -L -o /dev/null -w '%{{http_code}}' http://localhost{path}",
            check=False,
            label=f"GET {path}",
        )
        # curl output may be mixed with prior lines; grab last token
        status = (out.strip().split()[-1] if out.strip() else "???")
        results[path] = status

    print()
    section("Test Results")
    failures = []
    for path, status in results.items():
        passed = status in ("200", "301", "302")
        marker = f"{GREEN}PASS{R}" if passed else f"{RED}FAIL{R}"
        print(f"  {marker}  {status:>3}  {path}")
        if not passed:
            failures.append((path, status))

    print()
    if failures:
        warn(f"{len(failures)} page(s) returned unexpected HTTP codes:")
        for path, status in failures:
            warn(f"  {path}  →  {status}")
    else:
        ok(f"All {len(results)} pages passed (200 / 3xx)")

    return results


# ─── Main ────────────────────────────────────────────────────────────────────

def main():
    section("ASEANAPOL - Alibaba Cloud Server Setup & Deployment")
    print(f"  Target  : {SSH_USER}@{HOST}")
    print(f"  App URL : {APP_URL}")
    print(f"  Web root: {WEB_ROOT}")

    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    info(f"Connecting to {HOST}…")
    client.connect(HOST, username=SSH_USER, password=SSH_PASS, timeout=30)
    ok(f"SSH session open")

    try:
        phase_docker(client)
        phase_clone(client)
        phase_env(client)
        phase_containers(client)
        phase_system_nginx(client)
        phase_laravel(client)
        phase_import_sql(client)
        phase_assets(client)
        phase_media(client)
        phase_test(client)

        section("DEPLOYMENT COMPLETE")
        print(f"  {GREEN}{BOLD}Site:       {APP_URL}{R}")
        print(f"  {CYAN}Admin:      {APP_URL}/admin/login{R}")
        print(f"  {CYAN}DB host:    mysql  (internal Docker network){R}")
        print()
        print(f"  {YELLOW}Next steps:{R}")
        print(f"    - Point aseanapol.org DNS A-record to {HOST}")
        print(f"    - Update APP_URL in {WEB_ROOT}/.env to https://aseanapol.org")
        print(f"    - Add SSL (certbot) once DNS propagates")
        print(f"    - Remove port 3306 from docker-compose.yml after confirming DB OK")
        print()

    except Exception as exc:
        fail(f"Deployment failed: {exc}")
        sys.exit(1)
    finally:
        client.close()
        info("SSH session closed")


if __name__ == "__main__":
    main()
