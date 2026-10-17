// Opens every registered page editor + every module index and checks for 200 / no PHP error text.
import { execSync } from 'node:child_process';
import { launch, login, check, finish, BASE } from './lib.mjs';
const pages = JSON.parse(execSync('php artisan cms:pages --json', { encoding: 'utf8' }).trim().split('\n').pop());
const { browser, page, errors } = await launch();
const R = [];
await login(page);
const urls = [...pages.map(p => '/admin/site-content/' + p.key), '/admin', '/admin/site-content', '/admin/news', '/admin/newsletters', '/admin/video-gallery', '/admin/digital-library/items', '/admin/digital-library/collections', '/admin/events', '/admin/publications'];
for (const u of urls) {
  const r = await page.goto(BASE + u);
  const html = await page.content();
  check(R, u, r.status() === 200 && !/Whoops|Undefined (variable|array key)|Call to |SQLSTATE/.test(html), 'status ' + r.status());
}
finish(R, errors);
await browser.close();
