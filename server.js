// Змійка на городі — сервер гри з реєстрацією та таблицею рекордів.
// Без зовнішніх залежностей: потрібен лише Node.js 18+.
'use strict';

const http = require('node:http');
const fs = require('node:fs');
const path = require('node:path');
const crypto = require('node:crypto');

const PORT = Number(process.env.PORT) || 3000;
const DATA_DIR = process.env.DATA_DIR || path.join(__dirname, 'data');
const DB_FILE = path.join(DATA_DIR, 'db.json');
const PUBLIC_DIR = path.join(__dirname, 'public');
const SESSION_DAYS = 30;
const MAX_SCORE = 20 * 20 - 3; // більше насінин на полі 20×20 не зібрати

// ——— сховище: один JSON-файл, запис атомарний через тимчасовий файл ———
fs.mkdirSync(DATA_DIR, { recursive: true });
let db = { users: {}, sessions: {} };
try { db = JSON.parse(fs.readFileSync(DB_FILE, 'utf8')); } catch { /* перший запуск */ }

let saveTimer = null;
function save() {
  clearTimeout(saveTimer);
  saveTimer = setTimeout(() => {
    const tmp = DB_FILE + '.tmp';
    fs.writeFileSync(tmp, JSON.stringify(db));
    fs.renameSync(tmp, DB_FILE);
  }, 200);
}

// ——— паролі та сесії ———
function hashPassword(password, salt = crypto.randomBytes(16).toString('hex')) {
  const hash = crypto.scryptSync(password, salt, 64).toString('hex');
  return { salt, hash };
}
function checkPassword(password, user) {
  const { hash } = hashPassword(password, user.salt);
  return crypto.timingSafeEqual(Buffer.from(hash, 'hex'), Buffer.from(user.hash, 'hex'));
}
function newSession(key) {
  const token = crypto.randomBytes(32).toString('hex');
  db.sessions[token] = { key, expires: Date.now() + SESSION_DAYS * 864e5 };
  save();
  return token;
}
function currentUser(req) {
  const m = /(?:^|;\s*)sid=([a-f0-9]{64})/.exec(req.headers.cookie || '');
  const s = m && db.sessions[m[1]];
  if (!s) return null;
  if (s.expires < Date.now()) { delete db.sessions[m[1]]; save(); return null; }
  return db.users[s.key] || null;
}
function sessionCookie(token, maxAge, req) {
  const secure = req.headers['x-forwarded-proto'] === 'https' ? '; Secure' : '';
  return `sid=${token}; HttpOnly; SameSite=Lax; Path=/; Max-Age=${maxAge}${secure}`;
}

// ——— простий захист від підбору: 20 спроб входу за 10 хвилин з однієї IP ———
const attempts = new Map();
function tooMany(ip) {
  const now = Date.now();
  const list = (attempts.get(ip) || []).filter(t => now - t < 10 * 60e3);
  list.push(now);
  attempts.set(ip, list);
  return list.length > 20;
}

// ——— HTTP ———
function send(res, status, body, headers = {}) {
  res.writeHead(status, { 'Content-Type': 'application/json; charset=utf-8', ...headers });
  res.end(JSON.stringify(body));
}
function readJson(req) {
  return new Promise((resolve, reject) => {
    let data = '';
    req.on('data', chunk => { data += chunk; if (data.length > 10_000) req.destroy(); });
    req.on('end', () => { try { resolve(JSON.parse(data || '{}')); } catch { reject(new Error('bad json')); } });
    req.on('error', reject);
  });
}
const publicUser = u => ({ name: u.name, best: u.best, games: u.games });

function leaderboard() {
  return Object.values(db.users)
    .filter(u => u.best > 0)
    .sort((a, b) => b.best - a.best || a.bestAt - b.bestAt)
    .slice(0, 20)
    .map(u => ({ name: u.name, best: u.best, at: u.bestAt }));
}

const NAME_RE = /^[\p{L}\p{N}_.\- ]{3,20}$/u;

const routes = {
  'GET /api/me': (req, res) => {
    const u = currentUser(req);
    send(res, 200, { user: u ? publicUser(u) : null });
  },

  'GET /api/leaderboard': (req, res) => send(res, 200, { top: leaderboard() }),

  'POST /api/register': async (req, res) => {
    if (tooMany(req.socket.remoteAddress)) return send(res, 429, { error: 'Забагато спроб. Зачекайте кілька хвилин.' });
    const { name = '', password = '' } = await readJson(req);
    const clean = String(name).trim().replace(/\s+/g, ' ');
    if (!NAME_RE.test(clean)) return send(res, 400, { error: 'Нік має містити від 3 до 20 літер, цифр, пробілів або знаків _ . -' });
    if (String(password).length < 6) return send(res, 400, { error: 'Пароль має бути щонайменше з 6 символів.' });
    const key = clean.toLowerCase();
    if (db.users[key]) return send(res, 409, { error: 'Такий нік уже зайнятий. Оберіть інший.' });
    db.users[key] = { name: clean, ...hashPassword(String(password)), best: 0, bestAt: 0, games: 0, created: Date.now() };
    const token = newSession(key);
    send(res, 201, { user: publicUser(db.users[key]) }, { 'Set-Cookie': sessionCookie(token, SESSION_DAYS * 86400, req) });
  },

  'POST /api/login': async (req, res) => {
    if (tooMany(req.socket.remoteAddress)) return send(res, 429, { error: 'Забагато спроб. Зачекайте кілька хвилин.' });
    const { name = '', password = '' } = await readJson(req);
    const key = String(name).trim().replace(/\s+/g, ' ').toLowerCase();
    const u = db.users[key];
    if (!u || !checkPassword(String(password), u)) return send(res, 401, { error: 'Неправильний нік або пароль.' });
    const token = newSession(key);
    send(res, 200, { user: publicUser(u) }, { 'Set-Cookie': sessionCookie(token, SESSION_DAYS * 86400, req) });
  },

  'POST /api/logout': (req, res) => {
    const m = /(?:^|;\s*)sid=([a-f0-9]{64})/.exec(req.headers.cookie || '');
    if (m) { delete db.sessions[m[1]]; save(); }
    send(res, 200, { ok: true }, { 'Set-Cookie': sessionCookie('', 0, req) });
  },

  'POST /api/score': async (req, res) => {
    const u = currentUser(req);
    if (!u) return send(res, 401, { error: 'Увійдіть, щоб зберігати рекорди.' });
    const { score } = await readJson(req);
    if (!Number.isInteger(score) || score < 0 || score > MAX_SCORE) return send(res, 400, { error: 'Неможливий результат.' });
    u.games++;
    const record = score > u.best;
    if (record) { u.best = score; u.bestAt = Date.now(); }
    save();
    send(res, 200, { record, user: publicUser(u) });
  },
};

const MIME = { '.html': 'text/html; charset=utf-8', '.js': 'text/javascript; charset=utf-8', '.css': 'text/css; charset=utf-8', '.svg': 'image/svg+xml', '.ico': 'image/x-icon' };

function serveStatic(req, res) {
  const url = decodeURIComponent(req.url.split('?')[0]);
  const file = path.normalize(path.join(PUBLIC_DIR, url === '/' ? 'index.html' : url));
  if (!file.startsWith(PUBLIC_DIR + path.sep)) { res.writeHead(403); return res.end(); }
  fs.readFile(file, (err, data) => {
    if (err) { res.writeHead(404, { 'Content-Type': 'text/plain; charset=utf-8' }); return res.end('Сторінку не знайдено'); }
    res.writeHead(200, { 'Content-Type': MIME[path.extname(file)] || 'application/octet-stream' });
    res.end(data);
  });
}

http.createServer(async (req, res) => {
  const route = routes[`${req.method} ${req.url.split('?')[0]}`];
  if (!route) return req.method === 'GET' ? serveStatic(req, res) : send(res, 404, { error: 'Не знайдено' });
  try { await route(req, res); }
  catch (e) { if (!res.headersSent) send(res, 400, { error: 'Некоректний запит.' }); }
}).listen(PORT, () => console.log(`Змійка працює на http://localhost:${PORT}`));
