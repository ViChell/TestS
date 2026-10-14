# PatchLab PHP MVP

MVP-сайт для PatchLab на `PHP 8.1+`, `MySQL 8+`, `Tailwind CDN` і vanilla JS.

## Що вже є

- публічні сторінки під специфікацію;
- fallback seed-контент, щоб сайт рендерився навіть до підключення БД;
- форма замовлення, швидка форма нашивок, контактна форма;
- PHP-адмінка з авторизацією і CRUD;
- `schema.sql`, `.htaccess`, `robots.txt`, `uploads/.htaccess`.

## Швидкий старт

1. Імпортуйте `schema.sql` у MySQL.
2. Відредагуйте `includes/config.php`: `DB_*`, `TELEGRAM_*`, `NOTIFICATION_EMAIL`, `GA4_ID`, `META_PIXEL_ID`, `ADMIN_EMAIL`, `ADMIN_PASSWORD_HASH`.
3. Переконайтеся, що на Apache увімкнено `mod_rewrite`.
4. Перевірте, що папки `uploads/products`, `uploads/cases`, `uploads/orders` доступні для запису.

## Дефолтний адмін-доступ

- Email: `admin@patchlab.local`
- Password hint: `ChangeMe123!`

Змініть ці значення перед продакшеном.
