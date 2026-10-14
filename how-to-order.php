<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageSlug = 'how-to-order';
$pageTitle = 'Як замовити | PatchLab';
$metaDescription = 'Пояснення процесу замовлення: від ідеї та прорахунку до виготовлення, оплати й доставки.';
$contacts = get_contacts();
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'Як замовити'],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Освітня сторінка</span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">Як замовити</h1>
            <p class="mt-5 text-lg leading-8 text-slate-600">Це короткий сценарій для тих, хто хоче зрозуміти процес до першого повідомлення. Без складних workflow і зайвих умов.</p>
        </div>
    </div>
</section>

<?php
$steps = [
    'Оберіть категорію або просто опишіть ідею.',
    'Надішліть заявку через форму або Telegram.',
    'Отримайте прорахунок і узгодьте деталі.',
    'Підтвердіть замовлення й оплатіть.',
    'Отримайте виріб Новою Поштою або самовивозом у Києві.',
];
$stepsTitle = 'Загальний сценарій у 5 кроків';
$stepsIntro = 'Саме так виглядає MVP-процес від першого контакту до готового виробу.';
include __DIR__ . '/includes/components/steps-block.php';
?>

<section class="py-16">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
        <article class="<?= card_shell_class() ?> reveal-on-scroll">
            <h2 class="text-2xl font-bold text-slate-950">Для патчів і нашивок</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">Опишіть ідею, погодимо макет, зробимо прорахунок, виготовимо і відправимо.</p>
        </article>
        <article class="<?= card_shell_class() ?> reveal-on-scroll">
            <h2 class="text-2xl font-bold text-slate-950">Для 3D-виробів</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">Фокус на задачі та сценарії використання: форма, матеріал, друк, відправка.</p>
        </article>
        <article class="<?= card_shell_class() ?> reveal-on-scroll">
            <h2 class="text-2xl font-bold text-slate-950">Термінове замовлення</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">Напишіть у Telegram із позначкою “терміново”, і я чесно скажу, що реально встигнути.</p>
        </article>
    </div>
</section>

<?php
$ctaTitle = 'Готові перейти від опису до заявки? Форма замовлення вже чекає.';
$primaryUrl = '/custom-order';
$primaryLabel = 'Замовити прорахунок';
$secondaryUrl = $contacts['telegram'];
include __DIR__ . '/includes/components/cta-block.php';

require_once __DIR__ . '/includes/footer.php';
