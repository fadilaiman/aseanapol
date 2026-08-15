"""
Deploy 4 ASEANAPOL news items sourced from docs/fix/14-8-26/ (item1-item4).

Usage:
    python deploy_news_14_8_26.py local   -> copy images + insert into LOCAL MySQL (test)
    python deploy_news_14_8_26.py prod    -> copy images + sftp + insert into PRODUCTION via docker exec
"""
import os
import shutil
import sys
import base64

LOCAL_BASE = r"C:\laragon\www\aseanapol"
SRC_ROOT = os.path.join(LOCAL_BASE, r"docs\fix\14-8-26")
MEDIA_DIR = os.path.join(LOCAL_BASE, r"public\media\news\news")
REMOTE_MEDIA_DIR = "/var/www/aseanapol/public/media/news/news"

HOST, USER, PW = "47.250.166.174", "asnpladmin", "8e-y3£78GanR"
REMOTE_STAGING_DIR = "/home/asnpladmin/deploy_tmp"
DB_USER, DB_PASS, DB_NAME = "aseanapol", "AsEaNaPoL_2026_Prod", "aseanapol"

ARTICLES = [
    {
        "id": "aa1eb88d-9c58-4292-9c8c-5bb907f0a609",
        "slug": "aseanapol-asean-secretariat-strategic-consultation-2026-07-23",
        "title": "Strategic Consultation between ASEANAPOL Secretariat and ASEAN Secretariat",
        "summary": (
            "On the sidelines of the 44th ASEANAPOL Conference in Manila, Executive Director Police Senior "
            "Colonel Dr. Kongkrissada Kittithiraphong held a strategic consultation with ASEAN Deputy "
            "Secretary-General Dato' Astanah Abdul Aziz on institutional framework and partnerships."
        ),
        "published_at": "2026-07-23 09:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item3"),
        "images": [
            "WhatsApp Image 2026-08-14 at 10.40.17.jpeg",
            "WhatsApp Image 2026-08-14 at 10.40.17 (1).jpeg",
            "WhatsApp Image 2026-08-14 at 10.40.18.jpeg",
        ],
        "dest_prefix": "aseanapol-asean-secretariat-consultation-2026-07-23",
        "content_body": """<p>Manila, Philippines | 23 July 2026</p>

<p>On 23 July 2026, in conjunction with the 44th ASEANAPOL Conference 2026, which was successfully held in Manila, Philippines, <strong>Police Senior Colonel Dr. Kongkrissada Kittithiraphong</strong>, Executive Director of the ASEANAPOL Secretariat, held a strategic consultation with <strong>Dato&rsquo; Astanah Abdul Aziz</strong>, Deputy Secretary-General of ASEAN for the ASEAN Political-Security Community.</p>

<p>The discussion covered ASEANAPOL&rsquo;s institutional framework, legal personality, externally funded initiatives, and mechanisms to support its expanding regional and international partnerships.</p>

<p>Both sides exchanged views on strengthening institutional arrangements, financial governance, and ASEAN coordination mechanisms to support ASEANAPOL&rsquo;s evolving operational needs and regional mandate.</p>""",
    },
    {
        "id": "c9728d20-1866-4080-9eb9-1d21f169f3f5",
        "slug": "aseanapol-iacp-bilateral-meeting-2026-07-24",
        "title": "ASEANAPOL Secretariat\u2013International Association of Chiefs of Police (IACP) Bilateral Meeting",
        "summary": (
            "The ASEANAPOL Secretariat held a bilateral meeting with IACP President David B. Rausch during the "
            "44th ASEANAPOL Conference in Manila, exploring the proposed ASEANAPOL Academy initiative, training "
            "network access and women's leadership development."
        ),
        "published_at": "2026-07-24 09:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item4"),
        "images": [
            "WhatsApp Image 2026-08-14 at 10.40.26.jpeg",
            "WhatsApp Image 2026-08-14 at 10.40.27.jpeg",
            "WhatsApp Image 2026-08-14 at 10.40.27 (1).jpeg",
            "WhatsApp Image 2026-08-14 at 10.40.27 (2).jpeg",
        ],
        "dest_prefix": "aseanapol-iacp-bilateral-meeting-2026-07-24",
        "content_body": """<p>Manila, Philippines | 24 July 2026</p>

<p>Along with the 44th ASEANAPOL Conference, in Manila, Philippines, on 24 July 2026, <strong>Police Senior Colonel Dr. Kongkrissada Kittithiraphong</strong>, Executive Director of the ASEANAPOL Secretariat, held a bilateral meeting with <strong>Mr. David B. Rausch</strong>, President of the International Association of Chiefs of Police (IACP).</p>

<p>The discussion focused on strengthening strategic cooperation in capacity building, professional development, training, membership engagement, and international law enforcement collaboration.</p>

<p>Both sides explored opportunities for closer institutional engagement, including the proposed ASEANAPOL Academy initiative, access to IACP&rsquo;s global training network, women&rsquo;s leadership development, sustainable funding partnerships, and potential pathways for enhanced ASEANAPOL&ndash;IACP cooperation.</p>

<p>The engagement reflects the shared commitment to strengthening professional policing networks and advancing regional and international law enforcement cooperation.</p>""",
    },
    {
        "id": "6159b47a-b90f-4e4a-8678-58fc9fba7943",
        "slug": "aseanapol-knpa-bilateral-meeting-2026-07-25",
        "title": "ASEANAPOL\u2013KNPA: Strengthening Partnerships, Advancing Regional Security",
        "summary": (
            "The ASEANAPOL Secretariat held a bilateral meeting with the Korean National Police Agency (KNPA) "
            "during the 44th ASEANAPOL Conference in Manila, reaffirming the partnership and discussing joint "
            "programmes, study visits and expert engagement."
        ),
        "published_at": "2026-07-25 09:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item1"),
        "images": [
            "IMG_4704.JPG.jpeg",
            "IMG_4749.JPG.jpeg",
            "IMG_4755.JPG.jpeg",
            "IMG_4758.JPG.jpeg",
            "IMG_4761.JPG.jpeg",
        ],
        "dest_prefix": "aseanapol-knpa-bilateral-meeting-2026-07-25",
        "content_body": """<p>Marriott Hotel, Manila, Philippines | 25 July 2026</p>

<p>The ASEANAPOL Secretariat held a Bilateral Meeting with the Korean National Police Agency (KNPA) on the sidelines of the 44th ASEANAPOL Conference, led by <strong>Director General Superintendent General Park Jun Sung</strong>, Head of the Korean Delegation, and chaired by <strong>Police Senior Colonel Dr. Kongkrissada Khittithiraphong</strong>, Executive Director of the ASEANAPOL Secretariat. The meeting reaffirmed the strong partnership between ASEANAPOL and KNPA and provided a valuable platform to exchange views on strengthening regional law enforcement cooperation.</p>

<p>Both parties highlighted the importance of enhancing information exchange, operational cooperation, capacity building and professional exchanges, while exploring opportunities for joint programmes, study visits and expert engagement. Discussions also covered the proposed rescheduling of the Royal Brunei Police Force Study Visit from 2028 to 2029, as well as the proposed deployment of an additional KNPA officer to further support ASEANAPOL-related cooperation, subject to the relevant approval process.</p>

<p>The meeting concluded in a warm, constructive and cordial atmosphere, reflecting the shared commitment of KNPA and ASEANAPOL to maintaining close communication, strengthening mutual cooperation and developing practical initiatives to address emerging transnational crime challenges. Through continued partnership and collective efforts, both sides remain committed to building stronger regional police networks and contributing towards a safer, more secure and resilient ASEAN region.</p>

<p><em>ASEANAPOL Secretariat &mdash; Connecting Police, Strengthening Partnerships, Securing the Region.</em></p>""",
    },
    {
        "id": "ec10c5d6-2dcc-42f9-9b27-63a5c2cda903",
        "slug": "aseanapol-gccpol-bilateral-meeting-2026-07-25",
        "title": "ASEANAPOL\u2013GCCPOL: Advancing the Joint Plan of Action",
        "summary": (
            "The ASEANAPOL Secretariat and the Gulf Cooperation Council Police (GCCPOL) held a bilateral meeting "
            "in Manila to follow up on the Joint Plan of Action, with ASEANAPOL having endorsed the framework "
            "while GCCPOL completes its internal approval process."
        ),
        "published_at": "2026-07-25 14:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item2"),
        "images": [
            "IMG_4854.JPG.jpeg",
            "IMG_4855.JPG.jpeg",
            "IMG_4864.JPG.jpeg",
            "IMG_4879.JPG.jpeg",
            "IMG_4882.JPG.jpeg",
        ],
        "dest_prefix": "aseanapol-gccpol-bilateral-meeting-2026-07-25",
        "content_body": """<p>Marriott Hotel, Manila, Philippines | 25 July 2026</p>

<p>The ASEANAPOL Secretariat and the Gulf Cooperation Council Police (GCCPOL) held a bilateral meeting to follow up on the Joint Plan of Action previously agreed between both organisations.</p>

<p>The meeting was chaired by <strong>Police Senior Colonel Dr. Kongkrissada Kittithiraphong</strong>, Executive Director of the ASEANAPOL Secretariat, with the GCCPOL delegation led by <strong>Colonel Ahmed Alkaabi</strong>, Head of the GCCPOL Delegation.</p>

<p>The Joint Plan of Action provides a framework for ASEANAPOL and GCCPOL to strengthen cooperation in combating multiple forms of transnational crime that pose common and interconnected threats across Southeast Asia and the Gulf region.</p>

<p>ASEANAPOL has completed its internal process and endorsed the Joint Plan of Action, while GCCPOL is currently undertaking its respective internal process to obtain the necessary approval.</p>

<p>Upon completion of this process, ASEANAPOL and GCCPOL will continue discussions on the details, priority areas and implementation arrangements, with the aim of translating the agreed framework into practical cooperation and coordinated action between the two regions.</p>

<p>This progress marks another important step towards strengthening ASEANAPOL&ndash;GCCPOL cooperation and developing a more effective cross-regional response to shared transnational crime threats.</p>

<p><em>ASEANAPOL Secretariat &mdash; Connecting Regions, Strengthening Cooperation, Turning Commitment into Action.</em></p>""",
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
                    cur.execute(
                        "UPDATE news_items SET title=%s, summary=%s, content=%s, thumbnail=%s "
                        "WHERE slug=%s",
                        (art["title"], art["summary"], full_content(art), thumbnail_path(art), art["slug"]),
                    )
                    print(f'  updated (local): {art["slug"]}')
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


def sudo_cmd(client, cmd, timeout=60):
    full = f"sudo -S -p '' {cmd}"
    stdin, stdout, stderr = client.exec_command(full, timeout=timeout)
    stdin.write(PW + "\n")
    stdin.flush()
    return stdout.read().decode("utf-8", errors="replace"), stderr.read().decode("utf-8", errors="replace")


def deploy_production():
    import paramiko

    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(HOST, username=USER, password=PW, timeout=30)
    print("Connected to server")

    sftp = client.open_sftp()
    mkdir_p(sftp, REMOTE_STAGING_DIR)

    uploaded = 0
    for art in ARTICLES:
        for i in range(1, len(art["images"]) + 1):
            fname = f'{art["dest_prefix"]}-{i}.jpeg'
            local_path = os.path.join(MEDIA_DIR, fname)
            remote_path = f"{REMOTE_MEDIA_DIR}/{fname}"
            staging_path = f"{REMOTE_STAGING_DIR}/{fname}"
            sftp.put(local_path, staging_path)
            sudo_cmd(client, f"cp '{staging_path}' '{remote_path}'")
            sudo_cmd(client, f"rm '{staging_path}'")
            uploaded += 1
            print(f"  uploaded: {fname}")
    sftp.close()
    print(f"\nUploaded {uploaded} images")

    for art in ARTICLES:
        check_cmd = (
            f"docker exec aseanapol_mysql mysql -u {DB_USER} -p{DB_PASS} {DB_NAME} -N "
            f"-e \"SELECT id FROM news_items WHERE slug='{art['slug']}';\""
        )
        out, _ = sudo_cmd(client, check_cmd, timeout=15)
        exists = bool(out.strip())

        title_b64 = base64.b64encode(art["title"].encode("utf-8")).decode()
        summary_b64 = base64.b64encode(art["summary"].encode("utf-8")).decode()
        content_b64 = base64.b64encode(full_content(art).encode("utf-8")).decode()
        thumb_b64 = base64.b64encode(thumbnail_path(art).encode("utf-8")).decode()

        if exists:
            sql = (
                "UPDATE news_items SET "
                f"title=FROM_BASE64('{title_b64}'), "
                f"summary=FROM_BASE64('{summary_b64}'), "
                f"content=FROM_BASE64('{content_b64}'), "
                f"thumbnail=FROM_BASE64('{thumb_b64}') "
                f"WHERE slug='{art['slug']}';"
            )
            verb = "updated"
        else:
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
                f"FROM_BASE64('{thumb_b64}'), "
                "0, NOW(), NOW());"
            )
            verb = "inserted"
        cmd = f'docker exec aseanapol_mysql mysql -u {DB_USER} -p{DB_PASS} {DB_NAME} -e "{sql}"'
        stdout, stderr = sudo_cmd(client, cmd, timeout=30)
        if stdout:
            print("OUT:", stdout)
        if stderr and "Warning" not in stderr:
            print("ERR:", stderr)
        else:
            print(f'  {verb} (prod): {art["slug"]}')

    for artisan_cmd in [
        "docker exec aseanapol_app php artisan cache:clear",
        "docker exec aseanapol_app php artisan view:clear",
        "docker exec aseanapol_app php artisan gallery:reindex",
    ]:
        out, err = sudo_cmd(client, artisan_cmd, timeout=30)
        print(out.strip() or err.strip())

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
