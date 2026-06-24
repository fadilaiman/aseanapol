"""
Deploy 3 ASEANAPOL news items sourced from docs/fix/23-6-26/{1..3}/.

Usage:
    python deploy_news_23_6_26.py local   -> copy images + insert into LOCAL MySQL (test)
    python deploy_news_23_6_26.py prod    -> copy images + sftp + insert into PRODUCTION via docker exec

Content is plain ASCII with HTML entities (&rsquo; &ndash; &ldquo; &rdquo;) instead of
literal curly quotes/dashes, so nothing can be mangled by console/SQL-pipe encoding
(the cause of the earlier BOM/encoding bug on landing.php lang files).
"""
import os
import shutil
import sys
import base64

LOCAL_BASE = r"C:\laragon\www\aseanapol"
SRC_BASE = os.path.join(LOCAL_BASE, r"docs\fix\23-6-26")
MEDIA_DIR = os.path.join(LOCAL_BASE, r"public\media\news\news")
REMOTE_MEDIA_DIR = "/var/www/aseanapol/public/media/news/news"

HOST, USER, PW = "47.250.166.174", "root", "RtW-9-nq3V0t"
DB_USER, DB_PASS, DB_NAME = "aseanapol", "AsEaNaPoL_2026_Prod", "aseanapol"

ARTICLES = [
    {
        "id": "bb61f625-260c-4a8f-9f9a-60f85f185c95",
        "slug": "aseanapol-promotes-regional-cooperation-against-terrorist-financing",
        "title": "ASEANAPOL Promotes Regional Cooperation Against Terrorist Financing",
        "summary": (
            "ASEANAPOL Secretariat, represented by Executive Director Police Senior Colonel "
            "Dr. Kongkrissada Kittithiraphong, participated as a Panelist and Closing Speaker at "
            "an IIJ and Government of Canada workshop on countering the financing of terrorism, "
            "held in Hanoi, Viet Nam on 23 June 2026."
        ),
        "published_at": "2026-06-23 10:00:00",
        "src_folder": "1",
        "dest_prefix": "terrorist-financing-workshop-hanoi-2026-06-23",
        "images": [
            r"WhatsApp Unknown 2026-06-24 at 21.03.17\WhatsApp Image 2026-06-23 at 19.03.56 (1).jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.03.17\WhatsApp Image 2026-06-23 at 19.03.56.jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.03.17\WhatsApp Image 2026-06-23 at 19.03.57 (1).jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.03.17\WhatsApp Image 2026-06-23 at 19.03.57 (2).jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.03.17\WhatsApp Image 2026-06-23 at 19.03.57 (3).jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.03.17\WhatsApp Image 2026-06-23 at 19.03.57.jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.03.17\WhatsApp Image 2026-06-23 at 19.03.58.jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.03.12\WhatsApp Image 2026-06-23 at 19.03.58 (1).jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.03.12\WhatsApp Image 2026-06-23 at 19.03.58.jpeg",
        ],
        "content_body": """<p>Hanoi, Viet Nam &ndash; 23 June 2026</p>

<p>ASEANAPOL Secretariat, represented by Police Senior Colonel Dr. Kongkrissada Kittithiraphong, Executive Director, participated as a Panelist and Closing Speaker at the workshop on Good Practices Manual on Establishing Effective Public-Private Cooperation in Countering the Financing of Terrorism in Southeast Asia, organized by the International Institute for Justice and the Rule of Law (IIJ) and the Government of Canada.</p>

<p>The workshop brought together approximately 30 participants from seven Southeast Asian countries, representing law enforcement agencies, prosecution authorities, and anti-money laundering agencies.</p>

<p>During the discussion, ASEANAPOL highlighted the importance of public-private partnerships, intelligence sharing, and regional cooperation in addressing terrorist financing and transnational crime. The Executive Director also emphasized the role of ASEANAPOL as a regional platform for information exchange, operational coordination, and capacity building among ASEAN police forces.</p>

<p>In his Closing Remarks, he reaffirmed that effective responses to terrorist financing require strong partnerships, coordinated action, and sustained regional cooperation.</p>

<p><em>&ldquo;Think with Vision. Act with Unity. Deliver with Results.&rdquo;</em></p>""",
    },
    {
        "id": "2e27207f-947f-4ec0-8c03-004f41718649",
        "slug": "aseanapol-highlights-cooperation-iij-workshop-ai-emerging-technologies",
        "title": "ASEANAPOL Secretariat Highlights Regional Law Enforcement Cooperation in IIJ Workshop on AI and Emerging Technologies",
        "summary": (
            "The ASEANAPOL Secretariat, represented by Police Colonel Jean Mary A. Mangahis, "
            "Director for Plans and Programmes, participated in an IIJ workshop in Jakarta, "
            "Indonesia on strengthening criminal justice capacity to counter terrorist exploitation "
            "of emerging technologies in Southeast Asia."
        ),
        "published_at": "2026-06-23 14:00:00",
        "src_folder": "2",
        "dest_prefix": "iij-workshop-ai-emerging-tech-jakarta-2026-06-23",
        "images": [
            r"WhatsApp Unknown 2026-06-24 at 21.05.22\WhatsApp Image 2026-06-24 at 11.56.39 (1).jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.05.22\WhatsApp Image 2026-06-24 at 11.56.39.jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.05.22\WhatsApp Image 2026-06-24 at 11.56.40 (1).jpeg",
            r"WhatsApp Unknown 2026-06-24 at 21.05.22\WhatsApp Image 2026-06-24 at 11.56.40.jpeg",
        ],
        "content_body": """<p>The ASEANAPOL Secretariat, represented by Police Colonel Jean Mary A. Mangahis, Director for Plans and Programmes, participated in the International Institute for Justice and the Rule of Law (IIJ) Workshop to Strengthen Criminal Justice Capacity to Counter Terrorist Exploitation of Emerging Technologies in Southeast Asia, held on 23 June 2026 at Hotel Park Hyatt Jakarta, Indonesia.</p>

<p>ASEANAPOL&rsquo;s participation underscored its continuing role in promoting regional law enforcement cooperation, capacity building, information sharing, and coordinated responses among ASEAN Member Countries in addressing emerging security threats, particularly those involving artificial intelligence, cyber-enabled terrorism, violent extremism, and transnational crime.</p>

<p>The workshop was opened with remarks and introductions by Mr. Winthrop Wells, Senior Manager and Officer-in-Charge of the Programmatic Unit, IIJ; Mr. Myochin Mitsuru, Charg&eacute; d&rsquo;Affaires ad interim, Embassy of Japan in Indonesia; and Mr. Nazarudin, Director of International Legal Instruments of BNPT, Indonesia.</p>

<p>The discussions focused on AI governance for law enforcement, highlighting how terrorist and violent extremist actors may exploit artificial intelligence, open-source tools, encrypted platforms, VPNs, deepfakes, synthetic media, cryptocurrency, and other emerging technologies. Key points also covered online propaganda, recruitment, youth vulnerability, gaming-related radicalization, language barriers, and the growing difficulty of detecting AI-generated content.</p>

<p>The workshop further emphasized the need for stronger digital evidence preservation, blockchain analysis, content moderation, transparency, mutual legal assistance, and practical cooperation with technology platforms, financial service providers, and international partners.</p>

<p>Through this engagement, the ASEANAPOL Secretariat reaffirmed its commitment to working closely with ASEAN Member Countries, Dialogue Partners, Observers, and like-minded entities to strengthen criminal justice capacity and enhance regional readiness against technology-enabled terrorism, cybercrime, and other transnational threats.</p>

<p>#ASEANAPOL #IIJ #BNPT #CounterTerrorism #ArtificialIntelligence #EmergingTechnologies #Cybercrime #DigitalEvidence #RegionalCooperation #LawEnforcement #SoutheastAsia #Jakarta2026</p>""",
    },
    {
        "id": "30439feb-f5dd-4b7f-8f0d-93fee8681b96",
        "slug": "aseanapol-strengthens-ties-vietnam-oipa-regional-security-cooperation",
        "title": "ASEANAPOL Strengthens Ties with Vietnam's OIPA to Enhance Regional Security Cooperation",
        "summary": (
            "A delegation from the ASEANAPOL Secretariat, led by the Executive Director, held an "
            "official meeting with the Deputy Director of Viet Nam's Office of International Police "
            "Affairs (OIPA) in Hanoi on 24 June 2026 to discuss closer regional security cooperation."
        ),
        "published_at": "2026-06-24 09:00:00",
        "src_folder": "3",
        "dest_prefix": "vietnam-oipa-meeting-hanoi-2026-06-24",
        "images": [
            "WhatsApp Image 2026-06-24 at 15.28.43 (1).jpeg",
            "WhatsApp Image 2026-06-24 at 15.28.43.jpeg",
            "WhatsApp Image 2026-06-24 at 15.28.44 (1).jpeg",
            "WhatsApp Image 2026-06-24 at 15.28.44 (2).jpeg",
            "WhatsApp Image 2026-06-24 at 15.28.44.jpeg",
            "WhatsApp Image 2026-06-24 at 15.28.45 (1).jpeg",
            "WhatsApp Image 2026-06-24 at 15.28.45.jpeg",
        ],
        "content_body": """<p>Hanoi, Viet Nam &ndash; 24 June 2026 &ndash; At 9:00 a.m. today, a delegation from the ASEANAPOL Secretariat, led by the Executive Director, paid a courtesy call on and held an official meeting with the Deputy Director of the Office of International Police Affairs (OIPA), Ministry of Public Security of Viet Nam, in Hanoi, Socialist Republic of Viet Nam.</p>

<p>The meeting served as an important platform to further strengthen the longstanding and cordial relationship between the ASEANAPOL Secretariat and the Vietnamese police authorities. Both sides exchanged views on current regional security challenges and discussed avenues for enhancing cooperation in combating transnational crime across ASEAN.</p>

<p>Discussions focused on strengthening information sharing, operational coordination, and capacity-building initiatives aimed at addressing emerging threats to regional peace and security. The meeting was conducted in a warm and constructive atmosphere, reflecting the strong spirit of partnership and mutual trust between the two institutions.</p>""",
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
        folder = os.path.join(SRC_BASE, art["src_folder"])
        for i, src_name in enumerate(art["images"], start=1):
            src = os.path.join(folder, src_name)
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
