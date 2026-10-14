<?php
// Змійка на городі — API реєстрації та рекордів. Потрібен лише PHP 7.4+.
// Дані зберігаються у data/db.php (рядок-заглушка на початку не дає прочитати файл через браузер).
declare(strict_types=1);

const DATA_FILE = __DIR__ . '/data/db.php';
const GUARD = "<?php http_response_code(404); exit; ?>\n";
const MAX_SCORE = 20 * 20 - 3; // більше насінин на полі 20×20 не зібрати
const SESSION_DAYS = 30;

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

session_set_cookie_params([
    'lifetime' => SESSION_DAYS * 86400,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
]);
ini_set('session.gc_maxlifetime', (string)(SESSION_DAYS * 86400));
session_start();

function reply(int $status, array $body): void {
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}

// ——— сховище: один файл під ексклюзивним блокуванням на час запиту ———
function db_open() {
    if (!is_dir(dirname(DATA_FILE))) mkdir(dirname(DATA_FILE), 0700, true);
    $fh = fopen(DATA_FILE, 'c+');
    if (!$fh) reply(500, ['error' => 'Сервер не може зберегти дані. Перевірте права на теку data.']);
    flock($fh, LOCK_EX);
    $raw = stream_get_contents($fh);
    $data = json_decode(substr($raw, strlen(GUARD)) ?: '', true);
    if (!is_array($data)) $data = ['users' => [], 'attempts' => []];
    return [$fh, $data];
}
function db_save($fh, array $data): void {
    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, GUARD . json_encode($data, JSON_UNESCAPED_UNICODE));
    fflush($fh);
}
function db_close($fh): void {
    flock($fh, LOCK_UN);
    fclose($fh);
}

function body(): array {
    $b = json_decode(file_get_contents('php://input') ?: '{}', true);
    return is_array($b) ? $b : [];
}
function clean_name($name): string {
    return trim(preg_replace('/\s+/u', ' ', (string)$name));
}
function key_of(string $name): string {
    return mb_strtolower($name, 'UTF-8');
}
function public_user(array $u): array {
    return ['name' => $u['name'], 'best' => $u['best'], 'games' => $u['games']];
}
// 20 спроб входу чи реєстрації за 10 хвилин з однієї IP
function too_many(array &$data): bool {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '-';
    $now = time();
    foreach ($data['attempts'] as $k => $list) {
        $data['attempts'][$k] = array_values(array_filter($list, fn($t) => $now - $t < 600));
        if (!$data['attempts'][$k]) unset($data['attempts'][$k]);
    }
    $data['attempts'][$ip][] = $now;
    return count($data['attempts'][$ip]) > 20;
}
function sign_in(string $key): void {
    session_regenerate_id(true);
    $_SESSION['user'] = $key;
}

$action = $_GET['a'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
[$fh, $data] = db_open();
$me = isset($_SESSION['user']) ? ($data['users'][$_SESSION['user']] ?? null) : null;

try {
    switch ("$method $action") {
        case 'GET me':
            reply(200, ['user' => $me ? public_user($me) : null]);

        case 'GET leaderboard':
            $top = array_filter($data['users'], fn($u) => $u['best'] > 0);
            usort($top, fn($a, $b) => [$b['best'], $a['bestAt']] <=> [$a['best'], $b['bestAt']]);
            $top = array_map(fn($u) => ['name' => $u['name'], 'best' => $u['best']], array_slice($top, 0, 20));
            reply(200, ['top' => $top]);

        case 'POST register':
            $tooMany = too_many($data);
            db_save($fh, $data);
            if ($tooMany) reply(429, ['error' => 'Забагато спроб. Зачекайте кілька хвилин.']);
            $b = body();
            $name = clean_name($b['name'] ?? '');
            $password = (string)($b['password'] ?? '');
            if (!preg_match('/^[\p{L}\p{N}_.\- ]{3,20}$/u', $name))
                reply(400, ['error' => 'Нік має містити від 3 до 20 літер, цифр, пробілів або знаків _ . -']);
            if (mb_strlen($password) < 6) reply(400, ['error' => 'Пароль має бути щонайменше з 6 символів.']);
            $key = key_of($name);
            if (isset($data['users'][$key])) reply(409, ['error' => 'Такий нік уже зайнятий. Оберіть інший.']);
            $data['users'][$key] = [
                'name' => $name, 'hash' => password_hash($password, PASSWORD_DEFAULT),
                'best' => 0, 'bestAt' => 0, 'games' => 0, 'created' => time(),
            ];
            db_save($fh, $data);
            sign_in($key);
            reply(201, ['user' => public_user($data['users'][$key])]);

        case 'POST login':
            $tooMany = too_many($data);
            db_save($fh, $data);
            if ($tooMany) reply(429, ['error' => 'Забагато спроб. Зачекайте кілька хвилин.']);
            $b = body();
            $key = key_of(clean_name($b['name'] ?? ''));
            $u = $data['users'][$key] ?? null;
            if (!$u || !password_verify((string)($b['password'] ?? ''), $u['hash']))
                reply(401, ['error' => 'Неправильний нік або пароль.']);
            sign_in($key);
            reply(200, ['user' => public_user($u)]);

        case 'POST logout':
            $_SESSION = [];
            session_destroy();
            reply(200, ['ok' => true]);

        case 'POST score':
            if (!$me) reply(401, ['error' => 'Увійдіть, щоб зберігати рекорди.']);
            $score = body()['score'] ?? null;
            if (!is_int($score) || $score < 0 || $score > MAX_SCORE) reply(400, ['error' => 'Неможливий результат.']);
            $key = $_SESSION['user'];
            $u = &$data['users'][$key];
            $u['games']++;
            $record = $score > $u['best'];
            if ($record) { $u['best'] = $score; $u['bestAt'] = time(); }
            db_save($fh, $data);
            reply(200, ['record' => $record, 'user' => public_user($u)]);

        default:
            reply(404, ['error' => 'Не знайдено']);
    }
} finally {
    db_close($fh);
}
