<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$slug = sanitize_text($_GET['slug'] ?? '');
$product = $slug !== '' ? get_product_by_slug($slug) : null;

if (!$product) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    return;
}

$category = category_meta($product['category']);
$pageSlug = 'product';
$pageTitle = $product['title'] . ' | PatchLab';
$metaDescription = $product['short_description'];
$ogImage = $product['main_image'] ?? $category['image'];
$contacts = get_contacts();
$relatedProducts = array_values(array_filter(get_products(['category' => $product['category']]), static fn(array $item): bool => $item['slug'] !== $product['slug']));
$relatedProducts = array_slice($relatedProducts, 0, 4);
$images = array_values(array_unique(array_filter(array_merge([$product['main_image'] ?? $category['image']], normalize_gallery($product['gallery_images'] ?? [])))));
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => $category['label'], 'url' => $category['url']],
    ['label' => $product['title']],
];
$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product['title'],
    'description' => $product['short_description'],
    'image' => array_map(static fn(string $image): string => site_url(ltrim($image, '/')), $images),
    'brand' => ['@type' => 'Brand', 'name' => 'PatchLab'],
    'offers' => [
        '@type' => 'Offer',
        'priceCurrency' => 'UAH',
        'availability' => 'https://schema.org/InStock',
        'url' => current_full_url(),
    ],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
        <div>
            <?php
            $title = $product['title'];
            $images = $images;
            include __DIR__ . '/includes/components/image-gallery.php';
            ?>
        </div>
        <div>
            <span class="inline-flex rounded-full bg-brand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-brand-700"><?= e($category['label']) ?></span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= e($product['title']) ?></h1>
            <p class="mt-4 text-lg leading-8 text-slate-600"><?= e($product['short_description']) ?></p>

            <div class="mt-6 rounded-[28px] border border-slate-200 bg-white p-6 shadow-soft">
                <div class="text-sm uppercase tracking-[0.24em] text-slate-500">Ціна товару</div>
                <div class="mt-2 text-3xl font-bold text-brand-700"><?= e(product_price_label($product)) ?></div>
            </div>

            <div class="prose-slate mt-8 text-base leading-8 text-slate-700"><?= render_rich_text($product['full_description']) ?></div>

            <div class="mt-8 grid gap-4">
                <div class="<?= card_shell_class() ?>">
                    <h2 class="text-xl font-semibold text-slate-950">Кому підходить</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600"><?= e($product['target_audience'] ?? 'Підходить для приватних клієнтів, команд і малого B2B.') ?></p>
                </div>
                <div class="<?= card_shell_class() ?>">
                    <h2 class="text-xl font-semibold text-slate-950">Що можна змінити кастомно</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600"><?= e($product['customization_options'] ?? 'Розмір, форма, колір, текст і окремі деталі під задачу.') ?></p>
                </div>
                <div class="<?= card_shell_class() ?>">
                    <h2 class="text-xl font-semibold text-slate-950">Покупка готового товару</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Для цього товару працює окрема сторінка покупки з обов’язковими полями: ім’я, телефон, email, коментар, спосіб доставки і оплата тільки на карту.</p>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <a href="<?= e(build_buy_url($product)) ?>" data-event="click_buy" class="<?= button_primary_class() ?>">Купити</a>
                <a href="/custom-order?category=<?= e($product['category']) ?>" data-event="click_cta" class="<?= button_secondary_class() ?>">Замовити схожий</a>
                <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="<?= button_secondary_class() ?>">Уточнити в Telegram</a>
            </div>
        </div>
    </div>
</section>

<?php if ($relatedProducts !== []): ?>
    <section class="py-16 md:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <h2 class="text-3xl font-bold tracking-tight text-slate-950">Супутні товари</h2>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <?php foreach ($relatedProducts as $product): ?>
                    <?php include __DIR__ . '/includes/components/product-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php';
