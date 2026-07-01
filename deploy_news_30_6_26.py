"""
Deploy 1 ASEANAPOL news item sourced from docs/fix/30-6-26/.

Usage:
    python deploy_news_30_6_26.py local   -> copy images + insert into LOCAL MySQL (test)
    python deploy_news_30_6_26.py prod    -> copy images + sftp + insert into PRODUCTION via docker exec
"""
import os
import shutil
import sys
import base64

LOCAL_BASE = r"C:\laragon\www\aseanapol"
SRC_BASE = os.path.join(LOCAL_BASE, r"docs\fix\30-6-26")
MEDIA_DIR = os.path.join(LOCAL_BASE, r"public\media\news\news")
REMOTE_MEDIA_DIR = "/var/www/aseanapol/public/media/news/news"

HOST, USER, PW = "47.250.166.174", "root", "RtW-9-nq3V0t"
DB_USER, DB_PASS, DB_NAME = "aseanapol", "AsEaNaPoL_2026_Prod", "aseanapol"

ARTICLES = [
    {
        "id": "c4473e46-7c6b-49fc-924b-aae4807e9104",
        "slug": "aseanapol-reaffirms-commitment-advancing-ascot-unodc-regional-experts-meeting",
        "title": "ASEANAPOL Reaffirms Commitment to Advancing ASCOT at UNODC Regional Experts Meeting",
        "summary": (
            "The ASEANAPOL Secretariat participated in the Regional Experts Group Meeting on Online Scams, "
            "organized by UNODC, reaffirming its commitment to advancing the ASEANAPOL Scam Combat Task "
            "Force (ASCOT)."
        ),
        "published_at": "2026-06-30 20:26:00",
        "images": [
            "WhatsApp Image 2026-06-30 at 20.26.01.jpeg",
            "WhatsApp Image 2026-06-30 at 20.26.02.jpeg",
        ],
        "dest_prefix": "ascot-unodc-regional-experts-meeting-2026-06-30",
        "content_body": """<p>30 June 2026 | Virtual Meeting</p>

<p>The ASEANAPOL Secretariat participated in the <strong>Regional Experts Group Meeting on Online Scams: Strengthening Financial Intelligence, AML/CFT Regulation, and Law Enforcement Cooperation in Southeast Asia</strong>, organized by the United Nations Office on Drugs and Crime (UNODC).</p>

<p>During the meeting, <strong>Pol. Snr. Col. Dr. Kongkrissada Kittithiraphong</strong>, Executive Director of the ASEANAPOL Secretariat, reaffirmed ASEANAPOL&rsquo;s commitment to advancing the ASEANAPOL Scam Combat Task Force (ASCOT) as a practical regional platform to strengthen information sharing, operational coordination, capacity building, and partnerships in combating transnational online scam networks.</p>

<p>The ASEANAPOL Secretariat also reaffirmed its readiness to work closely with UNODC and all partners to further strengthen regional cooperation against online scams.</p>""",
    },
]


def gallery_html(prefix, n):
    imgs = [
        f'<img src="/media/news/news/{prefix}-{i}.jpeg" alt="" class="rounded-xl shadow-md w-full h-48 object-cover">'
        for i in range(1, n + 1)
    ]
    return (
        '<div class="news-gallery grid grid-cols-2 md:grid-cols-3 gap-3 mt-8">\n'
        + "\n".join(imgs)
        + "\n</div>"
    )


def full_content(art):
    return art["content_body"] + "\n\n" + gallery_html(art["dest_prefix"], len(art["images"]))


def thumbnail_path(art):
    return f'media/news/news/{art["dest_prefix"]}-1.jpeg'


def copy_images():
    os.makedirs(MEDIA_DIR, exist_ok=True)
    for art in ARTICLES:
        for i, src_name in enumerate(art["images"], start=1):
            src = os.path.join(SRC_BASE, src_name)
            dst = os.path.join(MEDIA_DIR, f'{art["dest_prefix"]}-{i}.jpeg')
            shutil.copy2(src, dst)
        print(f'  copied {len(art["images"])} images -> {art["dest_prefix"]}-*.jpeg')


def insert_local():
    import pymysql

    conn = pymysql.connect(
        host="127.0.0.1", port=3306, user="root", password="",
        database="aseanapol", charset="utf8mb4",
    )
    try:
        with conn.cursor() as cur:
            for art in ARTICLES:
                cur.execute("SELECT id FROM news_items WHERE slug=%s", (art["slug"],))
                if cur.fetchone():
                    print(f'  skip (already in local DB): {art["slug"]}')
                    continue
                cur.execute(
                    "INSERT INTO news_items "
                    "(id, title, summary, content, author, published_at, slug, thumbnail, views_count, created_at, updated_at) "
                    "VALUES (%s,%s,%s,%s,%s,%s,%s,%s,0,NOW(),NOW())",
                    (
                        art["id"], art["title"], art["summary"], full_content(art),
                        "ASEANAPOL Secretariat", art["published_at"], art["slug"], thumbnail_path(art),
                    ),
                )
                print(f'  inserted (local): {art["slug"]}')
        conn.commit()
    finally:
        conn.close()


def mkdir_p(sftp, remote_path):
    parts = remote_path.split("/")
    path = ""
    for part in parts:
        if not part:
            path = "/"
            continue
        path = f"{path}/{part}" if path != "/" else f"/{part}"
        try:
            sftp.stat(path)
        except FileNotFoundError:
            sftp.mkdir(path)


def deploy_production():
    import paramiko

    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(HOST, username=USER, password=PW, timeout=30)
    print("Connected to server")

    sftp = client.open_sftp()
    mkdir_p(sftp, REMOTE_MEDIA_DIR)

    uploaded = 0
    for art in ARTICLES:
        for i in range(1, len(art["images"]) + 1):
            fname = f'{art["dest_prefix"]}-{i}.jpeg'
            local_path = os.path.join(MEDIA_DIR, fname)
            remote_path = f"{REMOTE_MEDIA_DIR}/{fname}"
            try:
                sftp.stat(remote_path)
                print(f"  skip (exists): {fname}")
            except FileNotFoundError:
                sftp.put(local_path, remote_path)
                uploaded += 1
                print(f"  uploaded: {fname}")
    sftp.close()
    print(f"\nUploaded {uploaded} images")

    for art in ARTICLES:
        check_cmd = (
            f"docker exec aseanapol_mysql mysql -u {DB_USER} -p{DB_PASS} {DB_NAME} -N "
            f"-e \"SELECT id FROM news_items WHERE slug='{art['slug']}';\""
        )
        _, out, _ = client.exec_command(check_cmd, timeout=15)
        if out.read().decode().strip():
            print(f'  skip (already in prod DB): {art["slug"]}')
            continue

        title_b64 = base64.b64encode(art["title"].encode("utf-8")).decode()
        summary_b64 = base64.b64encode(art["summary"].encode("utf-8")).decode()
        content_b64 = base64.b64encode(full_content(art).encode("utf-8")).decode()

        sql = (
            "INSERT INTO news_items "
            "(id, title, summary, content, author, published_at, slug, thumbnail, views_count, created_at, updated_at) "
            "VALUES ("
            f"'{art['id']}', "
            f"FROM_BASE64('{title_b64}'), "
            f"FROM_BASE64('{summary_b64}'), "
            f"FROM_BASE64('{content_b64}'), "
            "'ASEANAPOL Secretariat', "
            f"'{art['published_at']}', "
            f"'{art['slug']}', "
            f"'{thumbnail_path(art)}', "
            "0, NOW(), NOW());"
        )
        cmd = f'docker exec aseanapol_mysql mysql -u {DB_USER} -p{DB_PASS} {DB_NAME} -e "{sql}"'
        _, out, err = client.exec_command(cmd, timeout=30)
        stdout = out.read().decode("utf-8", errors="replace")
        stderr = err.read().decode("utf-8", errors="replace")
        if stdout:
            print("OUT:", stdout)
        if stderr and "Warning" not in stderr:
            print("ERR:", stderr)
        else:
            print(f'  inserted (prod): {art["slug"]}')

    for artisan_cmd in [
        "docker exec aseanapol_app php artisan cache:clear",
        "docker exec aseanapol_app php artisan view:clear",
    ]:
        _, out, err = client.exec_command(artisan_cmd, timeout=30)
        print(out.read().decode().strip() or err.read().decode().strip())

    client.close()
    print("\nProduction deployment complete")


if __name__ == "__main__":
    mode = sys.argv[1] if len(sys.argv) > 1 else "local"
    print(f"Mode: {mode}")
    copy_images()
    if mode == "local":
        insert_local()
    elif mode == "prod":
        deploy_production()
    else:
        print("Unknown mode. Use 'local' or 'prod'.")
