// Shared Playwright helpers for CMS QA. Run with: node tests/e2e/<file>.mjs
// Env: CMS_BASE (default http://127.0.0.1:8123), CMS_USER / CMS_PASS
import { chromium } from 'playwright';

export const BASE = process.env.CMS_BASE || 'http://127.0.0.1:8123';
export const USER = process.env.CMS_USER || 'qa-admin';
export const PASS = process.env.CMS_PASS || 'QaAdmin#2026';

export async function launch() {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ viewport: { width: 1400, height: 1000 } });
  const page = await context.newPage();
  const errors = [];
  page.on('pageerror', e => errors.push('pageerror: ' + e.message));
  page.on('console', m => { if (m.type() === 'error' && !/favicon|net::ERR|Failed to load resource/.test(m.text())) errors.push('console: ' + m.text()); });
  return { browser, context, page, errors };
}

export async function login(page) {
  await page.goto(BASE + '/admin/login');
  await page.fill('input[name=username]', USER);
  await page.fill('input[name=password]', PASS);
  await Promise.all([page.waitForURL(/\/admin(\/)?$/), page.click('button[type=submit]')]);
}

export function check(results, name, ok, detail = '') {
  results.push({ name, ok: !!ok, detail });
  console.log((ok ? 'PASS ' : 'FAIL ') + name + (ok ? '' : '  -> ' + detail));
}

export function finish(results, errors) {
  const failed = results.filter(r => !r.ok);
  if (errors && errors.length) console.log('JS errors:\n' + errors.join('\n'));
  console.log(`\n${results.length - failed.length}/${results.length} passed`);
  process.exit(failed.length ? 1 : 0);
}

/** Open the CMS editor for a page key, e.g. 'about.vision-mission' */
export async function openEditor(page, key) {
  await page.goto(`${BASE}/admin/site-content/${key}`);
  await page.waitForSelector('#cms-form');
}

export async function save(page) {
  await page.click('[data-testid=save-page]');
  await page.waitForSelector('.bg-green-50');
}

export async function revertPage(page, key) {
  await openEditor(page, key);
  page.once('dialog', d => d.accept());
  await page.click('[data-testid=reset-page]');
  await page.waitForSelector('.bg-green-50');
}
