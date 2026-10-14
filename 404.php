<?php
declare(strict_types=1);

if (!defined('SITE_NAME')) {
    require_once __DIR__ . '/includes/config.php';
}

http_response_code(404);
$pageSlug = '404';
$pageTitle = 'Сторінку не знайдено | PatchLab';
$metaDescription = 'Сторінку не знайдено. Поверніться на головну або перейдіть до потрібної категорії.';
$showHeader = !headers_sent();

require_once __DIR__ . '/includes/header.php';
?>
<section class="py-20 md:py-28">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <div class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">404</div>
        <h1 class="mt-6 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">Сторінку не знайдено</h1>
        <p class="mt-5 text-lg leading-8 text-slate-600">Можливо, URL змінився або сторінку відкрили за старим посиланням. Нижче є швидкі маршрути назад у робочі розділи.</p>
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="/" class="<?= button_primary_class() ?>">На головну</a>
            <a href="/custom-order" class="<?= button_secondary_class() ?>">До заявки</a>
        </div>
        <div class="mt-10 grid gap-4 md:grid-cols-3">
            <a href="/patches" class="<?= card_shell_class() ?> block text-left">
                <div class="text-lg font-semibold text-slate-950">Патчі</div>
                <div class="mt-2 text-sm text-slate-600">Кастомні патчі та серії</div>
            </a>
            <a href="/name-tapes" class="<?= card_shell_class() ?> block text-left">
                <div class="text-lg font-semibold text-slate-950">Іменні нашивки</div>
                <div class="mt-2 text-sm text-slate-600">Позивні, name tapes, комплекти</div>
            </a>
            <a href="/3d-products" class="<?= card_shell_class() ?> block text-left">
                <div class="text-lg font-semibold text-slate-950">3D-вироби</div>
                <div class="mt-2 text-sm text-slate-600">Органайзери, стенди, підставки</div>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php';
