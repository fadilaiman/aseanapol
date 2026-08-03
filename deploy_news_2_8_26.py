"""
Deploy 10 ASEANAPOL news items sourced from docs/fix/2-8-26/ (items 1-10).

Usage:
    python deploy_news_2_8_26.py local   -> copy images + insert into LOCAL MySQL (test)
    python deploy_news_2_8_26.py prod    -> copy images + sftp + insert into PRODUCTION via docker exec
"""
import os
import shutil
import sys
import base64

LOCAL_BASE = r"C:\laragon\www\aseanapol"
SRC_ROOT = os.path.join(LOCAL_BASE, r"docs\fix\2-8-26")
MEDIA_DIR = os.path.join(LOCAL_BASE, r"public\media\news\news")
REMOTE_MEDIA_DIR = "/var/www/aseanapol/public/media/news/news"

HOST, USER, PW = "47.250.166.174", "root", "RtW-9-nq3V0t"
DB_USER, DB_PASS, DB_NAME = "aseanapol", "AsEaNaPoL_2026_Prod", "aseanapol"

ARTICLES = [
    {
        "id": "6a4bf439-d6c0-49b5-8bc9-7cfdb8124d7c",
        "slug": "15th-aseanapol-executive-committee-meeting-2026-07-22",
        "title": "15th ASEANAPOL Executive Committee Meeting (ExCoM)",
        "summary": (
            "The 15th ASEANAPOL Executive Committee Meeting convened in Manila ahead of the 44th ASEANAPOL "
            "Conference, chaired by Police Major General Jay R. Cumigad, reviewing the Secretariat's activities "
            "and strengthening regional cooperation."
        ),
        "published_at": "2026-07-22 09:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item 4", "unzipped"),
        "images": [
            "WhatsApp Image 2026-07-31 at 13.42.59.jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.56.jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.49 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.25 (2).jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.29.jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.33.jpeg",
        ],
        "dest_prefix": "15th-excom-meeting-2026-07-22",
        "content_body": """<p>The ASEANAPOL Executive Committee (ExCoM) convened its 15th Meeting on 22 July 2026 in Manila, Philippines, ahead of the 44th ASEANAPOL Conference.</p>

<p>Chaired by <strong>Police Major General Jay R. Cumigad</strong> of the Philippine National Police, the meeting brought together the Deputy Heads of Delegation from all ASEANAPOL Member Countries, together with representatives from the ASEANAPOL Secretariat led by Executive Director <strong>Police Senior Colonel Dr. Kongkrissada Khittithiraphong</strong>.</p>

<p>The meeting served as an important platform to review the ASEANAPOL Secretariat's activities and programmes, deliberate on key policy and operational matters, and strengthen regional cooperation in combating transnational crime. Delegates reaffirmed their collective commitment to enhancing peace, security, and law enforcement collaboration across the ASEAN region.</p>

<p>The successful convening of the ExCoM reflects ASEANAPOL's continued dedication to fostering stronger partnerships, promoting effective policing, and addressing emerging security challenges through unity and cooperation.</p>""",
    },
    {
        "id": "0abffa0a-f18e-4074-8840-be6b4ed053f5",
        "slug": "commission-a-44th-aseanapol-conference-resolutions-2026-07-23",
        "title": "44th ASEANAPOL Conference | Commission A Discussion",
        "summary": (
            "The Commission A Discussion at the 44th ASEANAPOL Conference in Manila adopted resolutions on four "
            "priority crime areas: Illicit Drug Trafficking, Terrorism, Arms Smuggling, and Trafficking in Persons."
        ),
        "published_at": "2026-07-23 09:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item 3", "unzipped"),
        "images": [
            "WhatsApp Image 2026-07-31 at 13.42.17.jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.18.jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.13.jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.15.jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.20 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.10.jpeg",
        ],
        "dest_prefix": "commission-a-44th-aseanapol-conference-2026-07-23",
        "content_body": """<p>The ASEANAPOL Secretariat participated in the Commission A Discussion held during the 44th ASEANAPOL Conference in Manila, Philippines, from 22&ndash;26 July 2026. The meeting brought together delegates from ASEANAPOL Member Countries to deliberate on regional strategies for combating transnational crime and to review the implementation of resolutions adopted at the previous ASEANAPOL Conference.</p>

<p>Following constructive discussions conducted in the spirit of ASEAN friendship, cooperation and solidarity, the Commission adopted a series of resolutions focusing on four priority crime areas:</p>

<ul>
<li>Illicit Drug Trafficking</li>
<li>Terrorism</li>
<li>Arms Smuggling</li>
<li>Trafficking in Persons</li>
</ul>

<p>The adopted resolutions reaffirm the commitment of ASEANAPOL Member Countries to strengthen intelligence sharing, enhance operational cooperation, build institutional capacity, promote joint investigations, and deepen collaboration with regional and international partners. The Commission also recognised the important role of the ASEANAPOL Secretariat in coordinating regional initiatives, facilitating information exchange, supporting capacity-building programmes, and advancing collective efforts to address evolving transnational crime threats.</p>

<p>The ASEANAPOL Secretariat remains committed to fostering closer police cooperation and strengthening regional security through effective coordination, strategic partnerships, and the implementation of the resolutions adopted by ASEANAPOL Member Countries.</p>""",
    },
    {
        "id": "9f86969c-d80a-42c8-8035-2ef73dd2972c",
        "slug": "commission-b-transnational-crime-44th-aseanapol-conference-2026-07-23",
        "title": "Commission B Advances Regional Cooperation to Combat Transnational Crime at the 44th ASEANAPOL Conference",
        "summary": (
            "Commission B of the 44th ASEANAPOL Conference convened in Manila to strengthen regional cooperation, "
            "adopting key initiatives across four priority crime areas: Wildlife Crime, Financial Crime, "
            "Cybercrime, and Maritime Crime."
        ),
        "published_at": "2026-07-23 14:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item 1", "unzipped"),
        "images": [
            "WhatsApp Image 2026-07-31 at 13.41.27 (2).jpeg",
            "WhatsApp Image 2026-07-31 at 13.41.20 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.41.26 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.41.31 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.41.24 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.41.32 (2).jpeg",
        ],
        "dest_prefix": "commission-b-44th-aseanapol-conference-2026-07-23",
        "content_body": """<p>Manila, Philippines | 44th ASEANAPOL Conference</p>

<p>Commission B of the 44th ASEANAPOL Conference convened in Manila, Philippines, to strengthen regional cooperation in addressing transnational crime through strategic discussions among ASEANAPOL Member Countries. The Commission reviewed the implementation of previous resolutions and adopted key initiatives focusing on four priority crime areas: Wildlife Crime, Financial Crime, Cybercrime, and Maritime Crime.</p>

<p><strong>1. Wildlife Crime</strong><br>The Commission emphasised the importance of strengthening regional cooperation in combating wildlife trafficking through enhanced information sharing, coordinated enforcement efforts, and closer collaboration with international partners, including CITES, to address illegal activities affecting biodiversity and environmental security.</p>

<p><strong>2. Financial Crime</strong><br>Discussions focused on enhancing collective efforts to combat financial crimes, including scams, fraud, and emerging financial threats. The Commission highlighted the need for intelligence sharing, joint investigations, and improved operational coordination to strengthen regional responses against financial crime networks.</p>

<p><strong>3. Cybercrime</strong><br>The Commission recognised the growing challenges posed by cyber-enabled crimes and emphasised the importance of leveraging advanced technology, strengthening cyber capabilities, and enhancing cooperation among law enforcement agencies to address evolving digital threats.</p>

<p><strong>4. Maritime Crime</strong><br>The Commission highlighted the importance of strengthening maritime security cooperation through coordinated operations, information exchange, and collaborative efforts to address cross-border maritime criminal activities.</p>

<p>The outcomes of the meeting reaffirm ASEANAPOL&rsquo;s commitment to advancing intelligence-led policing, enhancing operational cooperation, strengthening capacity building, and promoting partnerships with regional and international organisations, including INTERPOL, UNODC, CITES, and the World Customs Organization (WCO), towards a safer and more secure ASEAN region.</p>""",
    },
    {
        "id": "0cef7daa-d42c-41d9-83bf-80da1838dbc4",
        "slug": "commission-c-regional-police-cooperation-44th-aseanapol-conference-2026-07-24",
        "title": "ASEANAPOL Commission C Advances Regional Police Cooperation During the 44th ASEANAPOL Conference",
        "summary": (
            "The Commission C Discussion convened at the 44th ASEANAPOL Conference in Manila, chaired by Police "
            "Brig. Gen. Aladdin M. Collado, reviewing e-ADS, mutual legal assistance, personnel exchange, and "
            "forensic cooperation among ASEANAPOL Member Countries."
        ),
        "published_at": "2026-07-24 10:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item 2", "unzipped"),
        "images": [
            "WhatsApp Image 2026-07-31 at 13.41.55.jpeg",
            "WhatsApp Image 2026-07-31 at 13.41.34.jpeg",
            "WhatsApp Image 2026-07-31 at 13.41.57 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.41.58.jpeg",
            "WhatsApp Image 2026-07-31 at 13.41.45 (2).jpeg",
            "WhatsApp Image 2026-07-31 at 13.42.06 (1).jpeg",
        ],
        "dest_prefix": "commission-c-44th-aseanapol-conference-2026-07-24",
        "content_body": """<p>Manila, Philippines | 23&ndash;24 July 2026</p>

<p>The Commission C Discussion was successfully convened during the 44th ASEANAPOL Conference at the Manila Marriott Hotel, Manila, Philippines, bringing together representatives from ASEANAPOL Member Countries to deliberate on key areas of regional police cooperation.</p>

<p>Chaired by <strong>Police Brigadier General Aladdin M. Collado</strong>, Acting Deputy Director of the Directorate for Plans (DPL), Philippine National Police, the meeting focused on reviewing the implementation of the resolutions adopted at the 43rd ASEANAPOL Conference and discussing strategic initiatives to further enhance cooperation among ASEAN law enforcement agencies.</p>

<p>During the session, ASEANAPOL Member Countries shared updates and future plans on several priority areas, including:</p>

<ul>
<li><strong>Electronic ASEANAPOL Database System (e-ADS)</strong> &ndash; strengthening information exchange, improving data sharing capabilities, and enhancing regional operational cooperation through effective utilisation of the modernised platform.</li>
<li><strong>Mutual Assistance in Criminal Matters</strong> &ndash; promoting closer coordination and communication among ASEAN law enforcement agencies to support timely responses and strengthened police-to-police cooperation.</li>
<li><strong>Exchange of Personnel and Training Programmes</strong> &ndash; advancing capacity-building initiatives and specialised training opportunities to enhance the capabilities of ASEAN police forces.</li>
<li><strong>ASEAN Police Forensic Science Network (APFSN)</strong> &ndash; enhancing forensic cooperation, coordination, and preparedness among Member Countries through continued collaboration and development of forensic capabilities.</li>
</ul>

<p>The discussions reflected the continued commitment of ASEANAPOL Member Countries towards strengthening regional partnerships, enhancing information sharing, and fostering greater cooperation in addressing transnational crimes.</p>

<p>The meeting concluded with appreciation expressed to all delegates for their active participation and valuable contributions, reaffirming the ASEAN spirit of friendship, cooperation, and solidarity in advancing collective efforts towards a safer ASEAN community.</p>""",
    },
    {
        "id": "058c11a4-3b9f-4270-a3b5-ff20a6364e9d",
        "slug": "aseanapol-reaffirms-regional-police-unity-44th-conference-2026-07-26",
        "title": "ASEANAPOL Reaffirms Regional Police Unity and Commitment in Strengthening Collaboration at the 44th ASEANAPOL Conference",
        "summary": (
            "The 44th ASEANAPOL Conference concluded in Manila, approving Timor-Leste's admission as the 11th "
            "Member Country and the ASEANAPOL Scam Combat Task Force (ASCOT), reaffirming regional unity under "
            "the theme 'United in Vision: Securing People and Strengthening Collaboration.'"
        ),
        "published_at": "2026-07-26 18:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item 5", "unzipped"),
        "images": [
            "WhatsApp Image 2026-07-31 at 13.43.23.jpeg",
            "WhatsApp Image 2026-07-31 at 13.43.01 (2).jpeg",
            "WhatsApp Image 2026-07-31 at 13.43.17 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.43.20.jpeg",
            "WhatsApp Image 2026-07-31 at 13.43.23 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.43.06.jpeg",
        ],
        "dest_prefix": "jc-report-44th-aseanapol-conference-closing-2026-07-26",
        "content_body": """<p>Manila, Philippines | 22 &ndash; 26 July 2026</p>

<p>The 44th ASEAN National Police (ASEANAPOL) Conference was successfully convened in Manila, Philippines from 22 to 26 July 2026, bringing together ASEAN Police Chiefs, senior law enforcement officials, Dialogue Partners, Observers, and international organisations under the theme:</p>

<p><em>&ldquo;United in Vision: Securing People and Strengthening Collaboration.&rdquo;</em></p>

<p>The Conference served as a significant platform for ASEAN law enforcement agencies to reaffirm their shared commitment towards strengthening regional security, enhancing police cooperation, and advancing collective efforts in combating increasingly complex and evolving transnational crimes.</p>

<p><strong>Strengthening Regional Partnership Through Collective Action</strong></p>

<p>The Conference gathered delegations from all ASEANAPOL Member Countries, namely Brunei Darussalam, Cambodia, Indonesia, Lao PDR, Malaysia, Myanmar, the Philippines, Singapore, Thailand, and Viet Nam, reflecting ASEAN&rsquo;s continued unity and commitment towards a safer and more secure region.</p>

<p>The event was further strengthened by the participation of Dialogue Partners and international law enforcement organisations, including INTERPOL, ASEAN Secretariat, Australian Federal Police, National Police Agency of Japan, Korea National Police Agency, United Kingdom National Crime Agency, and other strategic partners.</p>

<p>Their collective participation demonstrated the importance of international cooperation, intelligence exchange, and coordinated operational responses in addressing global security challenges.</p>

<p><strong>Key Areas of Cooperation and Strategic Outcomes</strong></p>

<p>Throughout the Conference, ASEANAPOL Member Countries reaffirmed their determination to enhance cooperation in addressing various forms of transnational crime, including:</p>

<p><strong>1. Enhancing Intelligence Sharing and Digital Cooperation</strong><br>The Conference highlighted the importance of strengthening the Electronic ASEANAPOL Database System (e-ADS) as a regional platform to facilitate secure and timely intelligence sharing among Member Countries. ASEANAPOL Member Countries were encouraged to actively utilise the modernised e-ADS by sharing accurate and relevant information across eight core crime areas to enhance regional intelligence exchange and operational cooperation.</p>

<p><strong>2. Combating Emerging Transnational Crime Threats</strong><br>The Conference endorsed enhanced cooperation in combating major regional security challenges, including cybercrime and online fraud, financial crime and illicit financial activities, terrorism and violent extremism, trafficking in persons, illicit drug trafficking, maritime crime, and other emerging transnational threats. Participants emphasised the need for stronger intelligence collaboration, capacity building, technological advancement, and coordinated enforcement efforts to effectively respond to evolving criminal networks.</p>

<p><strong>Significant Achievements and Decisions</strong></p>

<p>Among the notable outcomes achieved during the 44th ASEANAPOL Conference were:</p>

<p><strong>ASEANAPOL Expansion</strong><br>The Conference approved the membership of the National Police of Timor-Leste (PNTL) as the 11th ASEANAPOL Member Country, marking an important milestone in strengthening regional police cooperation and inclusivity.</p>

<p><strong>Strengthening Regional Response Against Scam-Related Crimes</strong><br>The Conference approved the concept of the ASEANAPOL Scam Combat Task Force (ASCOT), aimed at enhancing regional cooperation, coordination and collective responses in combating scam-related crimes affecting communities across the region.</p>

<p><strong>Advancing Capacity Building and Professional Development</strong><br>ASEANAPOL Member Countries reaffirmed continued support for training programmes, technical exchanges, and professional development initiatives to strengthen operational capabilities among ASEAN police forces.</p>

<p><strong>A Shared Vision for a Safer ASEAN Community</strong></p>

<p>The 44th ASEANAPOL Conference reaffirmed ASEAN&rsquo;s shared vision of strengthening trust, solidarity, and cooperation among law enforcement agencies. Through enhanced partnerships, innovation, intelligence sharing, and coordinated action, ASEANAPOL continues to play a pivotal role in safeguarding communities and promoting peace, security, and stability across Southeast Asia.</p>

<p>The Conference concluded with a renewed commitment to uphold the principles of unity, partnership, and collective responsibility in addressing future security challenges.</p>""",
    },
    {
        "id": "b779b7c9-0dcc-4cf9-b667-70e08b6d31c6",
        "slug": "44th-aseanapol-conference-2026-united-in-vision-recap",
        "title": '44th ASEANAPOL Conference 2026: "United in Vision: Securing People and Strengthening Collaboration"',
        "summary": (
            "The 44th ASEANAPOL Conference, hosted by the Philippine National Police in Manila from 22-26 July "
            "2026, drew 186 delegates under the theme 'United in Vision: Securing People and Strengthening "
            "Collaboration.'"
        ),
        "published_at": "2026-07-27 09:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item 5", "unzipped"),
        "images": [
            "WhatsApp Image 2026-07-31 at 13.43.26 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.43.19 (1).jpeg",
        ],
        "dest_prefix": "44th-aseanapol-conference-recap-article-2026-07-27",
        "content_body": """<p>The 44th ASEAN Chiefs of National Police Conference (ASEANAPOL) was successfully conducted in Manila, Philippines from 22 to 26 July 2026. The Philippines, through the Philippine National Police (PNP), hosted the region&rsquo;s premier law enforcement gathering this year.</p>

<p><strong>Overview and Theme</strong></p>

<p>Held in Pasay City, Manila, the five-day conference carried the theme &ldquo;United in Vision: Securing People and Strengthening Collaboration&rdquo;.</p>

<p>The event drew 186 confirmed delegates from ASEAN Member Countries, the ASEANAPOL Secretariat, Dialogue Partners, and Observer organizations. This conference reaffirmed ASEANAPOL&rsquo;s vision to be the premier regional police organization that fosters cooperation in policing among ASEAN Member States and beyond, ensuring a safe and secure region for all, with the motto &ldquo;Together We Keep This Region Safe&rdquo;.</p>

<p><strong>Key Objectives and Focus Areas</strong></p>

<p>The discussions centred on strengthening regional efforts against the most pressing transnational crime threats, including Illicit Drug Trafficking, Terrorism, Arms Smuggling, Trafficking in Persons, Wildlife Crimes, Financial Crime, Cybercrime, Maritime Crime and other emerging security challenges. PNP Chief <strong>Gen. Jose Melencio C. Nartatez Jr.</strong> emphasized that the conference was an opportunity to reinforce the Philippines&rsquo; role in regional law enforcement cooperation. He stated: &ldquo;The conference reinforces the Philippines&rsquo; commitment to regional security and collective action. It provides an opportunity to deepen operational cooperation, improve intelligence sharing, and strengthen our partnerships in addressing threats that no country can solve alone&rdquo;. These focus areas align with ASEANAPOL&rsquo;s long-standing commission work on issues such as illicit drug trafficking, arms smuggling, commercial crimes, cybercrime, fraudulent travel documents, mutual legal assistance, and exchange of personnel and training programs.</p>

<p><strong>Preparations and Hosting</strong></p>

<p>The hosting of the 44th Conference was part of the Philippines&rsquo; broader commitment to regional engagements in 2026. In line with the directive of President Ferdinand R. Marcos Jr., and through the guidance of Interior Secretary Jonvic Remulla, the PNP deployed significant resources to ensure a safe and orderly conduct: 1,559 personnel from various National Support Units, 282 mobility assets, and 3,765 communications and ICT equipment. Preparatory work began early, with a Pre-Conference Coordination Meeting hosted by the PNP on 3&ndash;5 June 2026 in Quezon City, chaired by PLTGEN Bernard M. Banac, covering conference management, security arrangements, protocol, logistics, accommodation, and transportation.</p>

<p><strong>Significance for Regional Cooperation</strong></p>

<p>The conference served as a key platform to:</p>

<ul>
<li><strong>Enhance Operational Networks:</strong> ASEANAPOL continues to strengthen cooperation through specialized working groups and capacity development programs to address emerging transnational crime threats.</li>
<li><strong>Strengthen Partnerships with Dialogue Partners:</strong> The meeting also provided a platform to strengthen coordination with partners such as INTERPOL and the Korean National Police Agency under initiatives like the KNPA-led KARE Project.</li>
<li><strong>Support Capacity Building:</strong> Programs under ASEANAPOL such as the ASEANAPOL Forensic Science Network (APFSN), Personnel Exchange, and Training Development remain central to building interoperability among member states.</li>
</ul>

<p><strong>Conclusion</strong></p>

<p>The 44th ASEANAPOL Conference in Manila underscored the collective resolve of ASEAN police chiefs to move from dialogue to concrete action. By focusing on intelligence sharing, joint operations, and capacity building under the theme &ldquo;United in Vision,&rdquo; the conference aimed to deliver tangible outcomes in securing the people of ASEAN and strengthening collaboration against evolving security threats. ASEANAPOL Secretariat reaffirmed its commitment to working closely with international partners to strengthen regional security and advance practical cooperation against transnational crime.</p>""",
    },
    {
        "id": "e737e0ae-7a0c-4ae4-a3ff-c825124f7626",
        "slug": "aseanapol-virtual-coordination-meeting-transnational-crime-2026-07-30",
        "title": "ASEANAPOL Advances Regional Collaboration to Strengthen the Fight Against Transnational Crime",
        "summary": (
            "The ASEANAPOL Secretariat convened a Virtual Coordination Meeting with Member Countries, the ASEAN "
            "Secretariat, INTERPOL, and the Japan Mission Team, advancing initiatives on scam centre operations, "
            "environmental crime, and wildlife crime training."
        ),
        "published_at": "2026-07-30 15:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item 7", "unzipped"),
        "images": [
            "WhatsApp Image 2026-07-31 at 13.43.30.jpeg",
            "WhatsApp Image 2026-07-31 at 13.43.31 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 13.43.31 (2).jpeg",
            "WhatsApp Image 2026-07-31 at 13.43.31.jpeg",
        ],
        "dest_prefix": "aseanapol-virtual-coordination-meeting-2026-07-30",
        "content_body": """<p>The ASEANAPOL Secretariat convened a Virtual Coordination Meeting on 30 July 2026, bringing together representatives from ASEANAPOL Member Countries (AMCs), the ASEAN Secretariat, INTERPOL, the Japan Mission Team (JMT), and other key stakeholders to strengthen collective efforts in addressing emerging transnational crime challenges.</p>

<p>Chaired by <strong>Police Senior Colonel Dr. Kongkrissada Kittithiraphong</strong>, Executive Director of the ASEANAPOL Secretariat, the meeting highlighted the importance of enhanced cooperation, operational coordination, and information sharing among ASEAN law enforcement partners.</p>

<p>Key discussions focused on several strategic initiatives, including the upcoming Study Visit on Scam Centre Operations in Thailand, Malaysia, and Singapore, which aims to promote the exchange of best practices and strengthen regional approaches in combating scam-related crimes.</p>

<p>The meeting also advanced discussions on the i2LEC Environmental Crime Operation, supported by the United Arab Emirates, to enhance cooperation in tackling environmental crimes; the establishment of official ASEANAPOL email accounts to strengthen secure communication among Member Countries; and the Freeland Tripod Wildlife Crime Training Programme to further enhance capacity-building efforts against wildlife trafficking.</p>

<p>Through these collaborative initiatives, ASEANAPOL continues to promote a unified regional response, strengthen law enforcement partnerships, and develop sustainable solutions to address complex transnational crime threats.</p>

<p><em>United through cooperation. Stronger through partnership. Together, building a safer and more secure ASEAN.</em></p>""",
    },
    {
        "id": "a1ac26f4-eceb-48db-9b61-6a7f14401406",
        "slug": "dwg-lawful-access-digital-evidence-2026-07-30",
        "title": "Advancing Regional Cooperation on Lawful Access to Digital Evidence",
        "summary": (
            "ASEANAPOL joined INTERPOL, AFRIPOL, AMERIPOL, and other regional organizations at the 2nd Dialogue "
            "Working Group Meeting on lawful access to digital evidence, discussing cybercrime, financial crime, "
            "and the ASCOT and ELDS initiatives."
        ),
        "published_at": "2026-07-30 17:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item 8"),
        "images": [
            "WhatsApp Image 2026-07-31 at 15.30.39 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 15.30.39 (2).jpeg",
        ],
        "dest_prefix": "dwg-lawful-access-digital-evidence-2026-07-30",
        "content_body": """<p>30 July 2026 | Virtual Meeting</p>

<p>The 2nd Dialogue Working Group (DWG) Meeting on &ldquo;The Lawful Access Challenge and Regional Cooperation on Digital Evidence&rdquo; brought together representatives from INTERPOL, AFRIPOL, AIMC, AMERIPOL, ASEANAPOL, Caribbean regional organizations, and international partners to address emerging challenges in accessing digital evidence for law enforcement investigations.</p>

<p>The meeting was attended by <strong>Police Senior Colonel Dr. Kongkrissada Kittithiraphong</strong>, Executive Director of the ASEANAPOL Secretariat, and <strong>Senior Superintendent of Police Huntal Tambunan</strong>, Director of Police Services, ASEANAPOL Secretariat, reaffirming the Secretariat&rsquo;s commitment to strengthening regional and international cooperation in combating transnational crime.</p>

<p>Discussions highlighted the growing impact of lawful access challenges on combating cybercrime, financial crime, fraud, and online child sexual exploitation, underscoring the importance of stronger international cooperation, harmonised legal frameworks, and enhanced engagement with technology service providers.</p>

<p>INTERPOL shared updates on its global initiatives and the preliminary findings of the Lawful Access Survey, which will contribute to discussions at the 94th INTERPOL General Assembly. Regional organizations also presented ongoing efforts to strengthen cyber cooperation, digital evidence management, and cross-border information sharing.</p>

<p>ASEANAPOL highlighted its continued commitment to regional collaboration through key initiatives, including the outcomes of the 44th ASEANAPOL Conference in Manila, the establishment of the ASEANAPOL Scam Combat Task Force (ASCOT), the development of the Electronic Lawful Data Sharing (ELDS) platform, and enhanced cooperation in addressing financial crime, cryptocurrency-related offences, and other forms of transnational crime.</p>

<p>The meeting concluded with a shared commitment to advancing lawful access mechanisms, strengthening partnerships with technology providers, and enhancing collective capabilities to ensure timely and effective access to digital evidence in support of global law enforcement efforts.</p>

<p><em>Together, we strengthen cooperation. Together, we combat transnational crime.</em></p>""",
    },
    {
        "id": "0d283b6c-7ebf-494d-99f9-5b2e1966d30b",
        "slug": "aseanapol-eads-coordination-meeting-unodc-groupib-tradax-2026-07-31",
        "title": "ASEANAPOL Advances Digital Transformation Through Strategic Collaboration on the Electronic ASEAN Database System (EADS)",
        "summary": (
            "The ASEANAPOL Secretariat, UNODC, Group-IB, and TRADAX reviewed progress on the Electronic ASEAN "
            "Database System (EADS), highlighting milestones across eight core crime modules and future "
            "API-first, tokenization-enabled capabilities."
        ),
        "published_at": "2026-07-31 17:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item 9"),
        "images": [
            "WhatsApp Image 2026-07-31 at 17.54.11 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 17.54.11.jpeg",
            "WhatsApp Image 2026-07-31 at 17.54.12 (1).jpeg",
            "WhatsApp Image 2026-07-31 at 17.54.12.jpeg",
        ],
        "dest_prefix": "aseanapol-eads-coordination-meeting-2026-07-31",
        "content_body": """<p>Kuala Lumpur, Malaysia | 31 July 2026</p>

<p>The ASEANAPOL Secretariat convened a virtual coordination meeting with the United Nations Office on Drugs and Crime (UNODC), Group-IB, and TRADAX to further strengthen the development of the Electronic ASEAN Database System (EADS), a key digital initiative aimed at enhancing secure information sharing and operational cooperation among ASEANAPOL Member Countries.</p>

<p>Chaired by <strong>Police Senior Colonel Dr. Kongkrissada Khittithiraphong</strong>, Executive Director of the ASEANAPOL Secretariat, the meeting reviewed the latest progress of EADS development and explored future enhancements to support cross-border law enforcement collaboration in addressing emerging transnational crime challenges.</p>

<p>The meeting highlighted significant milestones achieved in the implementation of EADS, including the successful deployment of the A-Network communication platform, the completion of the A-Database module covering eight core crime areas &mdash; Terrorism, Arms Smuggling, Illicit Drug Trafficking, Wildlife Crime, Financial Crime, Trafficking in Persons, Cybercrime, and Maritime Crime &mdash; as well as the operationalisation of the A-Academy event and meeting management functions.</p>

<p>Discussions also focused on strengthening the platform&rsquo;s future capabilities through an API-first architecture, enabling greater interoperability and potential integration with relevant partner systems. TRADAX provided updates on the database module development, while UNODC reaffirmed its commitment to supporting the enhancement of the Cybercrime and Financial Crime modules through dedicated technical cooperation.</p>

<p>Group-IB further shared its advanced tokenization technology, which has the potential to enhance secure cross-border information exchange while protecting sensitive data through privacy-preserving solutions. The technology was recognised as a valuable consideration in strengthening the security and reliability of the EADS platform.</p>

<p>The meeting concluded with a shared commitment among ASEANAPOL, UNODC, Group-IB, and TRADAX to continue advancing a secure, interoperable, and technology-driven information-sharing ecosystem to support cross-border criminal investigations and strengthen regional law enforcement cooperation.</p>

<p>The next coordination meeting is scheduled for 14 August 2026 to review ongoing implementation progress and address further technical developments.</p>""",
    },
    {
        "id": "48c2783d-0ee6-491a-834b-119c1fa4b326",
        "slug": "aseanapol-think-vision-act-unity-deliver-results-2026-08-02",
        "title": "Think with Vision. Act with Unity. Deliver with Results.",
        "summary": (
            "The ASEANAPOL Secretariat reflects on six months guided by 'Think with Vision, Act with Unity, "
            "Deliver with Results' \u2014 highlighting ASCOT, the eADSAI Platform, Timor-Leste's admission, and "
            "new partnerships with UAE and GCCPOL."
        ),
        "published_at": "2026-08-02 10:00:00",
        "src_dir": os.path.join(SRC_ROOT, "item 10", "unzipped"),
        "images": [
            "WhatsApp Image 2026-08-02 at 17.15.23 (1).jpeg",
            "WhatsApp Image 2026-08-02 at 17.15.23.jpeg",
            "WhatsApp Image 2026-08-02 at 17.15.24 (1).jpeg",
            "WhatsApp Image 2026-08-02 at 17.15.24.jpeg",
        ],
        "dest_prefix": "aseanapol-think-vision-act-unity-deliver-results-2026-08-02",
        "content_body": """<p><strong>Think with Vision. Act with Unity. Deliver with Results.</strong></p>

<p>For the ASEANAPOL Secretariat, these are not merely words. They are the principles that have guided our work and have been proven through real implementation over the past six months.</p>

<p><strong>Think with Vision</strong> means setting a simple, clear, and focused direction, identifying regional priorities, and concentrating on what creates meaningful and lasting value for our Member Countries.</p>

<p><strong>Act with Unity</strong> means working together with Member Countries, Dialogue Partners, and strategic partners through transparency, fairness, mutual trust, and a shared commitment to the common good. Without unity, coordination has no strength. If it does not lead to action, it does not meet the standard.</p>

<p><strong>Deliver with Results</strong> means transforming every commitment, discussion, and resolution into tangible outcomes that are measurable, implementable, and sustainable.</p>

<p>Through this approach, the ASEANAPOL Secretariat has achieved several significant milestones within just six months, including the advancement of the ASEANAPOL Scam Combat Taskforce (ASCOT), the eADSAI Platform, and the ASEANAPOL Plan of Action to Eliminate Scams.</p>

<p>Our network has also been strengthened through the admission of the Pol&iacute;cia Nacional de Timor-Leste (PNTL) into the ASEANAPOL family, the inclusion of the Ministry of Interior of the United Arab Emirates (MOI UAE) as a new Dialogue Partner, and the development of the Joint Action Plan between ASEANAPOL and GCCPOL to enhance practical cooperation between our two regions.</p>

<p>These achievements reflect the collective efforts of our Member Countries, Dialogue Partners, strategic partners, and the ASEANAPOL Secretariat, working together under a shared vision and common purpose.</p>

<p>Every moment matters. We therefore invest our time in thinking strategically, acting collectively, and delivering meaningful outcomes that strengthen regional security.</p>

<p>Success is not defined by words alone, but by the real results we deliver.</p>

<p>This is only the beginning.</p>

<p><em>Together, we Think with Vision.<br>Together, we Act with Unity.<br>Together, we Deliver with Results.<br>Together, we keep our region safe.</em></p>""",
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
