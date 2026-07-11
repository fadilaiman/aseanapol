"""
Deploy 3 ASEANAPOL news items sourced from docs/fix/10-7-26/{3,4,5}.event/.

Usage:
    python deploy_news_10_7_26.py local   -> copy images + insert into LOCAL MySQL (test)
    python deploy_news_10_7_26.py prod    -> copy images + sftp + insert into PRODUCTION via docker exec
"""
import os
import shutil
import sys
import base64

LOCAL_BASE = r"C:\laragon\www\aseanapol"
FIX_BASE = os.path.join(LOCAL_BASE, r"docs\fix\10-7-26")
MEDIA_DIR = os.path.join(LOCAL_BASE, r"public\media\news\news")
REMOTE_MEDIA_DIR = "/var/www/aseanapol/public/media/news/news"

HOST, USER, PW = "47.250.166.174", "root", "RtW-9-nq3V0t"
DB_USER, DB_PASS, DB_NAME = "aseanapol", "AsEaNaPoL_2026_Prod", "aseanapol"

ARTICLES = [
    {
        "id": "6fd7640d-0045-4aea-8e06-d3743dcca509",
        "slug": "aseanapol-secretariat-ameripol-strengthen-cooperation-transnational-crime",
        "title": "ASEANAPOL Secretariat and AMERIPOL Strengthen Cooperation Against Transnational Crime",
        "summary": (
            "The ASEANAPOL Secretariat hosted a Virtual Coordination Meeting with the Police "
            "Community of the Americas (AMERIPOL) to strengthen international law enforcement "
            "cooperation against transnational crime, with a particular focus on child "
            "exploitation and cybercrime."
        ),
        "published_at": "2026-07-08 15:00:00",
        "src_dir": os.path.join(FIX_BASE, "3.event"),
        "images": [
            "WhatsApp Image 2026-07-10 at 13.52.59.jpeg",
            "WhatsApp Image 2026-07-10 at 13.52.59 (1).jpeg",
            "WhatsApp Image 2026-07-10 at 13.52.59 (2).jpeg",
            "WhatsApp Image 2026-07-10 at 13.52.59 (3).jpeg",
        ],
        "dest_prefix": "ameripol-cooperation-meeting-2026-07-08",
        "content_body": """<p>Kuala Lumpur, Malaysia | 8 July 2026 &ndash; The ASEANAPOL Secretariat hosted a Virtual Coordination Meeting with the Police Community of the Americas (<strong>AMERIPOL</strong>) to strengthen international law enforcement cooperation in combating transnational crime, with a particular focus on child exploitation and cybercrime.</p>

<p>Chaired by the Executive Director of the ASEANAPOL Secretariat, <strong>Police Senior Colonel Dr. Kongkrissada Kittithiraphong</strong>, the meeting brought together senior officials from both organisations to discuss enhanced cooperation in addressing online child sexual exploitation, cybercrime, human trafficking, drug-related offences, and other forms of transnational organised crime.</p>

<p>AMERIPOL briefed participants on an ongoing investigation led by the Colombian National Police involving online child exploitation with links to several countries, including ASEAN Member Countries. The ASEANAPOL Secretariat reaffirmed its commitment to facilitating information sharing and operational coordination through established law enforcement channels.</p>

<p>Both organisations agreed to strengthen long-term collaboration through enhanced intelligence exchange, operational cooperation, reciprocal participation in strategic meetings, and future capacity-building initiatives. As a follow-up, AMERIPOL will provide official case information to the ASEANAPOL Secretariat, while a follow-up virtual coordination meeting is scheduled for 29 July 2026 to review progress and determine the next phase of cooperation.</p>

<p>The meeting concluded with a shared commitment to reinforcing international partnerships in combating child exploitation, cybercrime, and other forms of transnational organised crime, while promoting a safer and more secure global community.</p>

<p><strong>Together, Stronger Against Transnational Crime.</strong></p>""",
    },
    {
        "id": "dfe680ec-0ba2-4cfc-b7e6-9469663d42e6",
        "slug": "ascot-investigation-workshop-2026",
        "title": "United Against Scams: ASCOT Investigation Workshop 2026",
        "summary": (
            "The ASEANAPOL Scam Combat Task Force (ASCOT) Investigation Workshop was successfully "
            "conducted from 29 June to 03 July 2026 at JCLEC, Semarang, Indonesia, bringing together "
            "more than 60 participants to strengthen regional cooperation against transnational "
            "scam crimes."
        ),
        "published_at": "2026-07-03 17:00:00",
        "src_dir": os.path.join(FIX_BASE, "4.event"),
        "images": [
            "WhatsApp Image 2026-07-10 at 14.46.18.jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.19 (1).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.19 (2).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.19.jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.20 (1).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.20 (2).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.20 (3).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.20.jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.21 (1).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.21 (2).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.21.jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.22 (1).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.22 (2).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.22 (3).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.22.jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.23 (1).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.23 (2).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.23.jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.24 (1).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.24 (2).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.24 (3).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.24.jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.25 (1).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.25 (2).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.25.jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.26 (1).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.26 (2).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.26.jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.27 (1).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.27 (2).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.27 (3).jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.27.jpeg",
            "WhatsApp Image 2026-07-10 at 14.46.28.jpeg",
            "WhatsApp Image 2026-07-10 at 14.49.21.jpeg",
        ],
        "dest_prefix": "ascot-investigation-workshop-2026-07-03",
        "content_body": """<p>The ASEANAPOL Scam Combat Task Force (<strong>ASCOT</strong>) Investigation Workshop was successfully conducted from 29 June to 03 July 2026 at the Jakarta Centre for Law Enforcement Cooperation (JCLEC), Semarang, Indonesia. The workshop represented another important milestone in ASEANAPOL&rsquo;s and JCLEC&rsquo;s collective efforts to strengthen regional cooperation and operational capabilities in combating transnational scam crimes.</p>

<p>The five-day programme brought together more than 60 participants from ASEANAPOL Member Countries, supported by renowned experts from law enforcement, the private sector, and the technology and cryptocurrency industries. Through expert-led presentations, practical case studies, scenario-based exercises, and operational discussions, participants enhanced their knowledge and practical skills in digital evidence management, financial investigations, blockchain and cryptocurrency investigations, and international law enforcement cooperation.</p>

<p>Beyond strengthening investigative capabilities, the workshop achieved significant progress in advancing regional operational cooperation. Participants discussed the enhancement of the ASEANAPOL Electronic Database System (eADS), the development of practical mechanisms for timely and secure cross-border information sharing, and standardized operational approaches to facilitate coordinated investigations among ASEANAPOL Member Countries. These outcomes will provide a stronger foundation for future joint operations and more effective regional responses against increasingly sophisticated transnational scam networks.</p>

<p>The workshop also served as an important platform for participants to exchange operational experiences, share best practices, strengthen professional networks, and foster greater mutual trust among investigators across the region. The common understanding developed throughout the programme is expected to further enhance operational coordination and information exchange, reinforcing ASEANAPOL&rsquo;s vision of a more connected, resilient, and collaborative regional law enforcement community.</p>

<p>The successful conclusion of the workshop reaffirms ASEANAPOL&rsquo;s commitment to delivering practical, sustainable, and results-oriented regional cooperation. By combining professional capacity building, innovative technology, and strengthened operational coordination, ASCOT continues to lay the foundation for a unified regional response against cyber-enabled and transnational scam crimes, contributing to a safer and more secure ASEAN community.</p>""",
    },
    {
        "id": "d3f3f4da-8d05-41de-b58f-6b3a88452652",
        "slug": "aseanapol-knpa-virtual-discussion-kare-project",
        "title": "Virtual Discussion Between the ASEANAPOL Secretariat and the Korean National Police Agency (KNPA)",
        "summary": (
            "The ASEANAPOL Secretariat held a virtual discussion with the Korean National Police "
            "Agency (KNPA) on the proposed Korea–ASEAN Response and Enforcement against "
            "Scam-Related Crime (KARE) Project."
        ),
        "published_at": "2026-07-09 11:00:00",
        "src_dir": os.path.join(FIX_BASE, "5.event"),
        "images": ["WhatsApp Image 2026-07-10 at 22.21.56.jpeg"],
        "dest_prefix": "aseanapol-knpa-virtual-discussion-2026-07-09",
        "content_body": """<p>The ASEANAPOL Secretariat, led by Executive Director <strong>Police Senior Colonel Dr. Kongkrissada Kittithiraphong</strong>, held a virtual discussion with the Korean National Police Agency (<strong>KNPA</strong>), represented by Superintendent JUNG Su ON, Chief of the Southeast Asia Investigative Assistance Section, International Investigative Assistance Division 1, on 9 July 2026.</p>

<p>The meeting focused on the proposed Korea&ndash;ASEAN Response and Enforcement against Scam-Related Crime (KARE) Project, including its current status, key implementation considerations, and the need to obtain the endorsement and approval of ASEANAPOL Member Countries before implementation.</p>

<p>Both sides agreed to continue discussions and review the outstanding issues during a bilateral meeting on the sidelines of the 44th ASEANAPOL Conference in Manila.</p>""",
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
            src = os.path.join(art["src_dir"], src_name)
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
