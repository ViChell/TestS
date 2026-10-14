<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageSlug = 'patches';
$pageTitle = 'Кастомні патчі під замовлення | PatchLab';
$metaDescription = 'Патчі від 1 штуки: логотипні, текстові, morale patches, серії для команди. Працюю з макетом або тільки з ідеєю.';
$products = get_products(['category' => 'patch']);
$contacts = get_contacts();
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'Кастомні патчі'],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
        <div>
            <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Категорія</span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">Кастомні патчі під замовлення</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">Патч під вашу задачу реально зробити навіть тоді, коли немає готового файлу. Достатньо ідеї, тексту або референсу.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="/custom-order?category=patch" data-event="click_cta" class="<?= button_primary_class() ?>">Замовити патч</a>
                <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="<?= button_secondary_class() ?>">Написати в Telegram</a>
            </div>
        </div>
        <div class="rounded-[36px] border border-white/80 bg-white p-5 shadow-soft">
            <img src="/assets/images/patch-card.svg" alt="Кастомні патчі PatchLab" class="w-full rounded-[28px] object-cover">
        </div>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <?php foreach (product_types_patch() as $type): ?>
                <div class="<?= card_shell_class() ?> reveal-on-scroll text-sm leading-7 text-slate-700"><?= e($type) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-16 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <h2 class="text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Приклади патчів</h2>
                <p class="mt-4 text-base leading-7 text-slate-600">Логотипні, текстові, morale patches, подарункові та командні рішення.</p>
            </div>
            <a href="/custom-order?category=patch" class="<?= button_secondary_class() ?>">Замовити патч</a>
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
            <h2 class="text-2xl font-bold text-slate-950">Що потрібно від вас</h2>
            <ul class="mt-4 grid gap-3 text-sm leading-7 text-slate-600">
                <li>• Ідея, текст, ескіз або логотип у будь-якому форматі.</li>
                <li>• Бажані кольори, розмір і кількість.</li>
                <li>• Якщо макета немає, достатньо описати задум словами.</li>
            </ul>
        </article>
        <article class="<?= card_shell_class() ?> reveal-on-scroll">
            <h2 class="text-2xl font-bold text-slate-950">Обмеження та рекомендації</h2>
            <ul class="mt-4 grid gap-3 text-sm leading-7 text-slate-600">
                <li>• Надто дрібні елементи можуть спрощуватися під виробництво.</li>
                <li>• Вартість залежить від розміру, складності, кольорів і тиражу.</li>
                <li>• Малі партії можливі, включно з одиничними патчами.</li>
            </ul>
        </article>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="<?= card_shell_class() ?>">
            <h2 class="text-2xl font-bold text-slate-950">Орієнтовні ціни</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-[24px] bg-brand-50 p-5">
                    <div class="text-sm text-slate-500">Простий текстовий патч</div>
                    <div class="mt-2 text-2xl font-bold text-brand-700">від 180 грн</div>
                </div>
                <div class="rounded-[24px] bg-brand-50 p-5">
                    <div class="text-sm text-slate-500">Патч з логотипом</div>
                    <div class="mt-2 text-2xl font-bold text-brand-700">від 250 грн</div>
                </div>
                <div class="rounded-[24px] bg-brand-50 p-5">
                    <div class="text-sm text-slate-500">Серія від 10 шт.</div>
                    <div class="mt-2 text-2xl font-bold text-brand-700">за домовленістю</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$ctaTitle = 'Потрібен патч під вашу задачу? Почнімо з короткого опису і попереднього прорахунку.';
$primaryUrl = '/custom-order?category=patch';
$primaryLabel = 'Замовити патч';
$secondaryUrl = $contacts['telegram'];
include __DIR__ . '/includes/components/cta-block.php';

require_once __DIR__ . '/includes/footer.php';
