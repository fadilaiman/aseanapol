# ASEANAPOL — public page tree and the CMS module that edits each page

Legend: **[REG]** registry page (admin: *Site Content → page*), **[MOD]** dedicated record module (admin sidebar), **[DB]** already DB-driven (existing module).

```
/ (landing)                                   [REG] site.landing  (+ [DB] latest news / upcoming events from News module)
Header + Footer + floating social bar (every page)   [REG] site.header-footer
Contact Us                                    [REG] site.contact
Coming-soon placeholder                       [REG] site.coming-soon
│
├─ About ASEANAPOL
│   ├─ About (index)                          [REG] about.index
│   ├─ Permanent Secretariat                  [REG] about.permanent-secretariat
│   ├─ Governance                             [REG] about.governance
│   ├─ Objectives & Functions                 [REG] about.objectives-and-functions
│   ├─ Chronology                             [REG] about.chronology
│   ├─ ASEANAPOL Logo                         [REG] about.logo
│   ├─ Vision & Mission                       [REG] about.vision-mission
│   ├─ Leadership (+ DPS, DPP)                [REG] about-people.leadership / leadership-dps / leadership-dpp
│   ├─ Observers' LME                         [REG] about-people.ob-lme
│   ├─ Dialogue Partners                      [REG] about-people.dialogue-partners
│   ├─ Observers                              [REG] about-people.observers
│   └─ Member Countries (index + 11 pages)    [REG] members.index, members.<country>
│
├─ News & Media
│   ├─ Hub                                    [REG] newsmedia.index
│   ├─ News list + article                    [DB]  Admin → News   (+ [REG] newsmedia.news-list / news-article static labels)
│   ├─ Press releases                         [REG] or [MOD] newsmedia.press-releases
│   ├─ Photo gallery                          [DB]  auto-indexed (gallery:reindex) (+ [REG] newsmedia.gallery header text)
│   ├─ Video gallery                          [DB]  Admin → Video Gallery (+ [REG] header text)
│   └─ Newsletters                            [DB]  Admin → Newsletters (+ [REG] header text)
│
├─ Data & Resources
│   ├─ Hub                                    [REG] data.hub
│   ├─ Crime statistics                       [REG] data.crime-statistics
│   ├─ Publications index + 8..15th editions  [MOD] Admin → Publications (+ [REG] data.publications-index / publication-edition)
│   ├─ Digital library                        [DB]  Admin → Digital Library (+ [REG] data.digital-library header text)
│   └─ E-learning                             301 → /training/e-learning  [REG] training.e-learning (no data.e-learning page)
│
├─ International Cooperation                  [REG] cooperation.index / interpol / europol / unodc / joint-projects
├─ Events & Training                          [MOD] Admin → Events (calendar, conferences, training programmes)
│                                             [REG] events.index / calendar / conferences / training-programs (static text)
├─ Training                                   [REG] training.index / online-training / in-person-training / e-learning
├─ Careers & Opportunities                    [REG] careers.index / vacancies / internships / exchange-programs
└─ Guidelines                                 [REG] guidelines.index / flag / contact-persons / donations / observers-dialogue
```

Admin entry points: `/admin` (dashboard) → `/admin/site-content` (page tree with search) and the module links.
Developer docs: `resources/cms/CMS_GUIDE.md`. QA tools: `tests/e2e/` (`regress.mjs`, `cms-audit.mjs`).
