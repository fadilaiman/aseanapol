"""
Add 3 additional photos to the ASCOT/UNODC news item (30-6-26), updating the
gallery HTML in both local and production DB.

Usage:
    python add_photos_30_6_26.py local
    python add_photos_30_6_26.py prod
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

SLUG = "aseanapol-reaffirms-commitment-advancing-ascot-unodc-regional-experts-meeting"
PREFIX = "ascot-unodc-regional-experts-meeting-2026-06-30"

NEW_IMAGES = [
    "WhatsApp Image 2026-07-01 at 11.09.48.jpeg",
    "WhatsApp Image 2026-07-01 at 11.09.48 (1).jpeg",
    "WhatsApp Image 2026-07-01 at 11.09.49.jpeg",
]
START_INDEX = 3  # existing article already has images 1-2
TOTAL_IMAGES = START_INDEX - 1 + len(NEW_IMAGES)  # 5

CONTENT_BODY = """<p>30 June 2026 | Virtual Meeting</p>

<p>The ASEANAPOL Secretariat participated in the <strong>Regional Experts Group Meeting on Online Scams: Strengthening Financial Intelligence, AML/CFT Regulation, and Law Enforcement Cooperation in Southeast Asia</strong>, organized by the United Nations Office on Drugs and Crime (UNODC).</p>

<p>During the meeting, <strong>Pol. Snr. Col. Dr. Kongkrissada Kittithiraphong</strong>, Executive Director of the ASEANAPOL Secretariat, reaffirmed ASEANAPOL&rsquo;s commitment to advancing the ASEANAPOL Scam Combat Task Force (ASCOT) as a practical regional platform to strengthen information sharing, operational coordination, capacity building, and partnerships in combating transnational online scam networks.</p>

<p>The ASEANAPOL Secretariat also reaffirmed its readiness to work closely with UNODC and all partners to further strengthen regional cooperation against online scams.</p>"""


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


def full_content():
    return CONTENT_BODY + "\n\n" + gallery_html(PREFIX, TOTAL_IMAGES)


def copy_images():
    os.makedirs(MEDIA_DIR, exist_ok=True)
    for i, src_name in enumerate(NEW_IMAGES, start=START_INDEX):
        src = os.path.join(SRC_BASE, src_name)
        dst = os.path.join(MEDIA_DIR, f'{PREFIX}-{i}.jpeg')
        shutil.copy2(src, dst)
    print(f"  copied {len(NEW_IMAGES)} new images -> {PREFIX}-{START_INDEX}..{TOTAL_IMAGES}.jpeg")


def update_local():
    import pymysql

    conn = pymysql.connect(
        host="127.0.0.1", port=3306, user="root", password="",
        database="aseanapol", charset="utf8mb4",
    )
    try:
        with conn.cursor() as cur:
            cur.execute(
                "UPDATE news_items SET content=%s, updated_at=NOW() WHERE slug=%s",
                (full_content(), SLUG),
            )
            print(f"  updated (local): {cur.rowcount} row(s)")
        conn.commit()
    finally:
        conn.close()


def deploy_production():
    import paramiko

    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(HOST, username=USER, password=PW, timeout=30)
    print("Connected to server")

    sftp = client.open_sftp()
    uploaded = 0
    for i in range(START_INDEX, TOTAL_IMAGES + 1):
        fname = f'{PREFIX}-{i}.jpeg'
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

    content_b64 = base64.b64encode(full_content().encode("utf-8")).decode()
    sql = (
        "UPDATE news_items SET "
        f"content=FROM_BASE64('{content_b64}'), updated_at=NOW() "
        f"WHERE slug='{SLUG}';"
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
        print(f"  updated (prod): {SLUG}")

    for artisan_cmd in [
        "docker exec aseanapol_app php artisan cache:clear",
        "docker exec aseanapol_app php artisan view:clear",
    ]:
        _, out, err = client.exec_command(artisan_cmd, timeout=30)
        print(out.read().decode().strip() or err.read().decode().strip())

    client.close()
    print("\nProduction update complete")


if __name__ == "__main__":
    mode = sys.argv[1] if len(sys.argv) > 1 else "local"
    print(f"Mode: {mode}")
    copy_images()
    if mode == "local":
        update_local()
    elif mode == "prod":
        deploy_production()
    else:
        print("Unknown mode. Use 'local' or 'prod'.")
