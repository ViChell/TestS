<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageSlug = '3d-products';
$pageTitle = '3D-друковані вироби та органайзери | PatchLab';
$metaDescription = 'Стенди для патчів, органайзери, підставки та малі кастомні 3D-рішення під конкретну задачу.';
$products = get_products(['category' => '3d']);
$contacts = get_contacts();
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => '3D-вироби'],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
        <div>
            <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">3D-каталог</span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">3D-друковані вироби</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">Органайзери, тримачі, підставки, стенди для патчів та малі кастомні рішення. Не “будь-який 3D-друк”, а конкретні корисні вироби.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="/custom-order?category=3d" data-event="click_cta" class="<?= button_primary_class() ?>">Запитати про 3D-виріб</a>
                <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="<?= button_secondary_class() ?>">Написати в Telegram</a>
            </div>
        </div>
        <div class="rounded-[36px] border border-white/80 bg-white p-5 shadow-soft">
            <img src="/assets/images/3d-card.svg" alt="3D вироби PatchLab" class="w-full rounded-[28px] object-cover">
        </div>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-2">
            <article class="<?= card_shell_class() ?> reveal-on-scroll">
                <h2 class="text-2xl font-bold text-slate-950">Готові вироби з каталогу</h2>
                <ul class="mt-4 grid gap-3 text-sm leading-7 text-slate-600">
                    <li>• Органайзер для робочого столу</li>
                    <li>• Тримач для кабелів, адаптерів і флешок</li>
                    <li>• Стенд для патчів або монет</li>
                    <li>• Настільні аксесуари малого формату</li>
                </ul>
            </article>
            <article class="<?= card_shell_class() ?> reveal-on-scroll">
                <h2 class="text-2xl font-bold text-slate-950">Індивідуальне виготовлення</h2>
                <ul class="mt-4 grid gap-3 text-sm leading-7 text-slate-600">
                    <li>• Кастомна підставка під конкретну задачу</li>
                    <li>• Брендований аксесуар</li>
                    <li>• Подарунковий виріб</li>
                    <li>• Прототип малої форми</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="py-16 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <h2 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Готові 3D-позиції і приклади виробів</h2>
            <p class="mt-4 text-base leading-7 text-slate-600">Фіксовані позиції для каталогу та зрозумілі точки входу для кастомних запитів.</p>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($products as $product): ?>
                <?php include __DIR__ . '/includes/components/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <article class="<?= card_shell_class() ?> reveal-on-scroll">
            <h2 class="text-2xl font-bold text-slate-950">Орієнтовні ціни</h2>
            <div class="mt-6 grid gap-4">
                <div class="rounded-[24px] bg-brand-50 p-5"><div class="text-sm text-slate-500">Готовий виріб з каталогу</div><div class="mt-2 text-2xl font-bold text-brand-700">150–500 грн</div></div>
                <div class="rounded-[24px] bg-brand-50 p-5"><div class="text-sm text-slate-500">Кастомний виріб малої форми</div><div class="mt-2 text-2xl font-bold text-brand-700">від 200 грн</div></div>
                <div class="rounded-[24px] bg-brand-50 p-5"><div class="text-sm text-slate-500">Складний кастом</div><div class="mt-2 text-2xl font-bold text-brand-700">після узгодження</div></div>
            </div>
        </article>
        <article class="<?= card_shell_class() ?> reveal-on-scroll">
            <h2 class="text-2xl font-bold text-slate-950">Обмеження</h2>
            <ul class="mt-4 grid gap-3 text-sm leading-7 text-slate-600">
                <?php foreach (product_types_3d() as $item): ?>
                    <li>• <?= e($item) ?></li>
                <?php endforeach; ?>
                <li>• Працюю з малими форматами до приблизно 20-25 см.</li>
                <li>• FDM-друк: PLA / PETG, на складних формах можуть бути видимі шари.</li>
            </ul>
        </article>
    </div>
</section>

<?php
$ctaTitle = 'Потрібен 3D-виріб під вашу задачу? Опишіть сценарій використання, а не просто “хочу щось надрукувати”.';
$primaryUrl = '/custom-order?category=3d';
$primaryLabel = 'Запитати про 3D-виріб';
$secondaryUrl = $contacts['telegram'];
include __DIR__ . '/includes/components/cta-block.php';

require_once __DIR__ . '/includes/footer.php';
