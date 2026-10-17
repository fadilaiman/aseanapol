// "One entity = one place" proof.  node tests/e2e/single-source.mjs
// For every shared entity: scan ALL public pages in pristine state, edit the value ONCE in the Shared editor,
// scan again, and assert that every page that showed the old value now shows the new one (no stale copy left),
// plus explicit "must show it" pages. Self-cleaning (reverts every shared.* page).
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { launch, login, check, finish, openEditor, save, revertPage, BASE } from './lib.mjs';

const here = path.dirname(fileURLToPath(import.meta.url));
const URLS = fs.readFileSync(path.join(here, 'urls.txt'), 'utf8').split(/\r?\n/).filter(Boolean);
for (const u of ['/ms', '/ms/contact-us']) URLS.push(u);

const SHARED = ['organisation', 'secretariat', 'members', 'roster', 'partners', 'dialogue'].map(s => 'shared.' + s);
const dec = s => s.replace(/&amp;/g, '&').replace(/&#0?39;|&#x27;|&rsquo;/g, "'").replace(/&quot;/g, '"').replace(/&nbsp;/g, ' ').replace(/&ndash;/g, '–').replace(/&mdash;/g, '—');

async function scan() {
  const out = {};
  for (const u of URLS) {
    const r = await fetch(BASE + u, { redirect: 'follow' });
    out[u] = { status: r.status, html: dec(await r.text()) };
  }
  return out;
}

const { browser, page, errors } = await launch();
const R = [];
await login(page);
for (const k of SHARED) await revertPage(page, k);

const before = await scan();
check(R, 'pristine scan: no page returns 5xx', Object.values(before).every(p => p.status < 500), Object.entries(before).filter(([, p]) => p.status >= 500).map(([u]) => u).join());

// ---- helpers to edit a repeater row found by identity
async function rowIdx(prefix, match) {
  return page.evaluate(([prefix, match]) => {
    const rows = {};
    document.querySelectorAll(`#cms-form [name^="${prefix}["]`).forEach(el => {
      const m = el.name.slice(prefix.length).match(/^\[(\d+)\]\[(\w+)\]$/);
      if (m) (rows[m[1]] ??= {})[m[2]] = el.value;
    });
    for (const [i, r] of Object.entries(rows)) if (Object.entries(match).every(([k, v]) => r[k] === v)) return +i;
    return -1;
  }, [prefix, match]);
}
async function editRow(pageKey, prefix, match, values) {
  await openEditor(page, pageKey);
  const i = await rowIdx(prefix, match);
  if (i < 0) throw new Error('row not found ' + JSON.stringify(match));
  for (const [f, v] of Object.entries(values)) await page.fill(`[name="${prefix}[${i}][${f}]"]`, v);
  await save(page);
}

// ---- entity definitions: label, old text(s) (present in pristine pages), accepted new text(s), pages that MUST show it
const proofs = [];
const P = (label, old, news, must = [], allowStale = []) => proofs.push({ label, old, news: [].concat(news), must, allowStale });

// ================= Secretariat
await openEditor(page, 'shared.secretariat');
const S = { phone: '+60 QA 11111', fax: '+60 QA 22222', es: 'qa-sec@example.test', eg: 'qa-info@example.test',
  addr: 'QA Tower 7\nQA Road 8\nQA City 9, QA', addr1: 'QA Oneline Tower 5, QA Road, QA City', link: 'tel:+6011111', hrs: 'QA office hours 9-9', map: 'https://maps.google.com/maps?q=QA+Place&output=embed&z=17', eads: 'QA e-ADS System' };
await page.fill('[name="f[contact][phone]"]', S.phone); await page.fill('[name="f[contact][fax]"]', S.fax);
await page.fill('[name="f[contact][email_secretariat]"]', S.es); await page.fill('[name="f[contact][email_general]"]', S.eg);
await page.fill('[name="f[contact][address]"]', S.addr); await page.fill('[name="f[contact][address_oneline]"]', S.addr1);
await page.fill('[name="f[contact][phone_link]"]', S.link); await page.fill('[name="f[contact][office_hours]"]', S.hrs);
await page.fill('[name="f[contact][map_embed_src]"]', S.map); await page.fill('[name="f[eads][name]"]', S.eads);
await save(page);
P('secretariat phone', '+603-22602222', S.phone, ['/en', '/en/contact-us']);
P('secretariat phone_link', 'tel:+60322602222', S.link, ['/en/contact-us']);
P('secretariat fax', '+603-22602205', S.fax, ['/en', '/en/contact-us']);
P('secretariat email_secretariat', 'aseanapolsec@aseanapol.org', S.es, ['/en', '/en/contact-us', '/en/guidelines/guidelines-on-the-use-of-the-aseanapol-flag', '/en/guidelines/guidelines-for-accepting-donations-and-sponsorships', '/en/publication/15th-edition-aseanapol-magazine']);
P('secretariat email_general', 'info@aseanapol.org', S.eg, ['/en/careers/vacancies', '/en/news-media/press-releases', '/en/news-media/newsletters', '/en/data-resources/crime-statistics', '/en/events-training/training-programs']);
P('secretariat address', 'Level 13', ['QA Tower 7', 'QA Oneline Tower 5'], ['/en', '/en/contact-us']);
P('secretariat address (street)', 'Jalan Rakyat', ['QA Road 8', 'QA Road, QA City'], ['/en', '/en/contact-us']);
P('secretariat office hours', 'Monday–Friday, 8:30 AM', S.hrs, []);
P('secretariat map src', 'Menara+Kembar+Bank+Rakyat', 'QA+Place', ['/en/contact-us']);
P('secretariat e-ADS name', 'Electronic ASEANAPOL Database System (e-ADS)', S.eads, ['/en/international-cooperation/interpol'], ['/en/international-cooperation/interpol']);

// ================= Organisation
await openEditor(page, 'shared.organisation');
const O = { tag: 'QA tagline for the region', logo: 'images/qa-org-logo.png', alt: 'QA Logo Alt', year: '1977', dlabel: '1 QA 1977', conf: '99+', first: 'QA first conf date, QAplace, QA', secest: '1 QAM 2005', word: 'qaword', num: '11' };
await page.fill('[name="f[identity][tagline]"]', O.tag); await page.fill('[name="f[identity][logo]"]', O.logo); await page.fill('[name="f[identity][logo_alt]"]', O.alt);
await page.fill('[name="f[key_dates][founded_year]"]', O.year); await page.fill('[name="f[key_dates][founded_date_label]"]', O.dlabel);
await page.fill('[name="f[key_dates][conference_count_label]"]', O.conf); await page.fill('[name="f[key_dates][first_conference_label]"]', O.first);
await page.fill('[name="f[key_dates][secretariat_established_label]"]', O.secest); await page.fill('[name="f[counts][member_count_word]"]', O.word);
await save(page);
P('organisation tagline', 'Toward a peaceful and crime-free ASEAN region', O.tag, ['/en/about-aseanapol/vision-and-mission']);
P('organisation logo', 'images/aseanapol-logo.png', 'qa-org-logo.png', ['/en/about-aseanapol/permanent-secretariat']);
P('organisation logo_alt', 'alt="ASEANAPOL Logo"', 'alt="QA Logo Alt"', ['/en/about-aseanapol/permanent-secretariat']);
P('organisation founding date label', '21 October 1981', O.dlabel, ['/en/about-aseanapol/chronology'], []);
P('organisation founded year (chronology subtitle)', 'from its founding in 1981', 'founding in 1977', ['/en/about-aseanapol/chronology']);
P('organisation first-conference place', 'held in Manila, Philippines', 'held in QAplace', ['/en/about-aseanapol/chronology']);
P('organisation conference count', '44+', O.conf, ['/en']);
P('organisation member count word', 'eleven', O.word, ['/en/about-aseanapol/permanent-secretariat']);

// ================= Members (indonesia)
await editRow('shared.members', 'f[list][rows]', { key: 'indonesia' }, {
  force: 'QA Force Name', abbreviation: 'QAF', joined_year: '1977', logo: 'media/qa/qa-crest.png', landing_logo: 'qa-landing.png',
  website: 'https://qa-inp.example.test', ncb_email: 'qa-inp@example.test', tel: '+99 QA TEL1', tel2: '+99 QA TEL2', fax: '+99 QA FAX' });
const MP = '/en/about-aseanapol/member-countries/indonesian-national-police';
P('member force name', 'Indonesian National Police', 'QA Force Name', [MP, '/en/about-aseanapol/member-countries', '/en', '/en/contact-us'],
  [MP, '/en/information/indonesian-national-police']); // member page prose ("... is the national police force") stays local narrative
P('member tel', '+021 739 3650', '+99 QA TEL1', [MP, '/en/contact-us']);
P('member tel2', '+021 721 8467', '+99 QA TEL2', [MP]);
P('member fax', '+021 720 1402', '+99 QA FAX', [MP, '/en/contact-us']);
P('member email', 'inp@aseanapol.org', 'qa-inp@example.test', [MP, '/en/contact-us']);
P('member website', 'polri.go.id', 'qa-inp.example.test', [MP]);
P('member logo / landing logo', 'indonesia-polri.png', ['qa-crest.png', 'qa-landing.png'], [MP, '/en']);
P('member joined year', 'QA-NONE', '1977', ['/en/about-aseanapol/member-countries']);

// ================= Roster
await editRow('shared.roster', 'f[people][rows]', { post: 'ed', ordinal: '9th' }, { rank: 'QA Rank', name: 'QA Edperson', photo: 'media/qa/ed-photo.png' });
await editRow('shared.roster', 'f[people][rows]', { post: 'ed', ordinal: '8th' }, { name: 'QA PastEd', term_start: '2001', term_end: '2002' });
await editRow('shared.roster', 'f[people][rows]', { post: 'dps', ordinal: '7th' }, { name: 'QA Dpsperson', force_label: 'QA Dps Force Label' });
await editRow('shared.roster', 'f[people][rows]', { post: 'dpp', ordinal: '7th' }, { name: 'QA Dppperson' });
P('roster ED current name', 'Kongkrissada Kittithiraphong', 'QA Edperson', ['/en/about-aseanapol/leadership', '/en/about-aseanapol/permanent-secretariat'], ['/en/about-aseanapol/leadership', '/en/about-aseanapol/permanent-secretariat', '/en', '/en/news', '/en/news-media/news', '/ms']); // bio prose repeats the name (separate free-text field); DB news articles quote it
P('roster ED past name', 'David Martinez Vinluan', 'QA PastEd', ['/en/about-aseanapol/leadership', '/en/about-aseanapol/permanent-secretariat'], ['/en/international-cooperation/unodc', '/en/international-cooperation/joint-projects']);
P('roster ED photo', 'media/ed/9.jpg', 'ed-photo.png', ['/en/about-aseanapol/leadership']);
P('roster DPS current name', 'Huntal Tambunan', 'QA Dpsperson', ['/en/about-aseanapol/leadership/director-for-police-services', '/en/about-aseanapol/leadership', '/en/about-aseanapol/permanent-secretariat'], ['/en/about-aseanapol/leadership/director-for-police-services', '/en/about-aseanapol/leadership', '/en/about-aseanapol/permanent-secretariat']);
P('roster DPP current name', 'Jean Mary A. Mangahis', 'QA Dppperson', ['/en/about-aseanapol/leadership/director-for-plans-and-programmes', '/en/about-aseanapol/leadership', '/en/about-aseanapol/permanent-secretariat'], ['/en/about-aseanapol/leadership/director-for-plans-and-programmes', '/en/about-aseanapol/leadership', '/en/about-aseanapol/permanent-secretariat']);
P('roster force label (dps)', 'QA-NONE', 'QA Dps Force Label', ['/en/about-aseanapol/leadership/director-for-police-services']);

// ================= Partners
await editRow('shared.partners', 'f[list][rows]', { key: 'interpol' }, { short_name: 'QA-INTERPOL', full_name: 'QA Interpol Full Name', website: 'https://qa-interpol.example.test' });
await editRow('shared.partners', 'f[list][rows]', { key: 'apta' }, { short_name: 'QA-APTA', full_name: 'QA Apta Full Name', website: 'https://qa-apta.example.test', logo: 'media/qa/apta-logo.png' });
P('partner interpol short', 'INTERPOL', 'QA-INTERPOL', ['/en'], []); // old text is everywhere in prose; only "does the card show the new name" is asserted
P('partner interpol website', 'www.interpol.int', 'qa-interpol.example.test', ['/en', '/en/information/dialogue-partners']);
P('partner interpol full name', 'International Criminal Police Organization', 'QA Interpol Full Name', ['/en', '/en/information/dialogue-partners'], ['/en/international-cooperation/interpol', '/en/international-cooperation', '/en/international-collaboration']);
P('partner apta name', 'Association of Police Training Institutions in Asia', 'QA Apta Full Name', ['/en/about-aseanapol/lme', '/en/international-collaboration']);
P('partner apta website', 'aptahome.org', 'qa-apta.example.test', ['/en/about-aseanapol/lme', '/en/international-collaboration']);
P('partner apta logo', 'media/lme/apta.jpeg', 'apta-logo.png', ['/en/about-aseanapol/lme']);

// ================= Dialogue partner + observer
await editRow('shared.dialogue', 'f[list][rows]', { key: 'nzp' }, { name: 'QA NZ Police', full_name: 'QA NZ Police Full', website: 'https://qa-nzp.example.test', desc: 'QA NZP description text' });
await editRow('shared.dialogue', 'f[list][rows]', { key: 'bangladesh-police' }, { name: 'QA Bangla Police', website: 'https://qa-bd.example.test', observer_conference: '99th QA, Nowhere' });
P('dialogue nzp name', 'New Zealand Police (NZP)', 'QA NZ Police', ['/en/information/dialogue-partners', '/en/about-aseanapol/dialogue-partners']);
P('dialogue nzp website', 'police.govt.nz', 'qa-nzp.example.test', ['/en/information/dialogue-partners']);
P('dialogue nzp desc', 'New Zealand Police actively participates', 'QA NZP description text', ['/en/information/dialogue-partners']);
P('observer name', 'Bangladesh Police', 'QA Bangla Police', ['/en/information/observers', '/en/about-aseanapol/observers']);
P('observer website', 'police.gov.bd', 'qa-bd.example.test', ['/en/information/observers']);
P('observer conference', '40th AC, Cambodia', '99th QA, Nowhere', []);

const after = await scan();
check(R, 'edited scan: no page returns 5xx', Object.values(after).every(p => p.status < 500), Object.entries(after).filter(([, p]) => p.status >= 500).map(([u]) => u).join());

// ---- evaluate
const hasAny = (html, arr) => arr.some(t => html.toLowerCase().includes(t.toLowerCase()));
for (const p of proofs) {
  const oldPages = Object.keys(before).filter(u => before[u].html.toLowerCase().includes(p.old.toLowerCase()));
  const newPages = Object.keys(after).filter(u => hasAny(after[u].html, p.news));
  const stale = oldPages.filter(u => !newPages.includes(u) && !p.allowStale.includes(u) && after[u].html.toLowerCase().includes(p.old.toLowerCase()));
  const missing = p.must.filter(u => !newPages.includes(u));
  check(R, `${p.label}: shown on ${newPages.length} page(s); old copy on ${oldPages.length}`, missing.length === 0 && stale.length === 0,
    (missing.length ? 'MISSING on ' + missing.join(', ') : '') + (stale.length ? ' STALE on ' + stale.join(', ') : ''));
  if (process.env.VERBOSE) console.log('   new on: ' + newPages.join(' '));
}

// ---- revert + prove clean
for (const k of SHARED) await revertPage(page, k);
const end = await scan();
const leftovers = Object.entries(end).filter(([, p]) => /QA Force Name|qa-sec@example|QA Edperson|QA-INTERPOL|QA tagline|QA NZ Police|QA Bangla/.test(p.html)).map(([u]) => u);
check(R, 'revert: no QA marker left on any public page', leftovers.length === 0, leftovers.join());

await browser.close();
finish(R, errors);
