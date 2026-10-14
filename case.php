<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$slug = sanitize_text($_GET['slug'] ?? '');
$case = $slug !== '' ? get_case_by_slug($slug) : null;

if (!$case) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    return;
}

$category = category_meta($case['category']);
$pageSlug = 'case';
$pageTitle = $case['title'] . ' | PatchLab';
$metaDescription = $case['short_description'];
$contacts = get_contacts();
$images = array_values(array_unique(array_filter(array_merge([$case['main_image'] ?? $category['image']], normalize_gallery($case['gallery_images'] ?? [])))));
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'Кейси', 'url' => '/#cases'],
    ['label' => $case['title']],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
        <div>
            <?php
            $title = $case['title'];
            $images = $images;
            include __DIR__ . '/includes/components/image-gallery.php';
            ?>
        </div>
        <div>
            <span class="inline-flex rounded-full bg-brand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-brand-700"><?= e($category['label']) ?></span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= e($case['title']) ?></h1>
            <p class="mt-4 text-lg leading-8 text-slate-600"><?= e($case['short_description']) ?></p>
            <div class="prose-slate mt-8 text-base leading-8 text-slate-700"><?= render_rich_text($case['full_description']) ?></div>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="/custom-order?category=<?= e($case['category']) ?>" data-event="click_cta" class="<?= button_primary_class() ?>">Замовити схожий</a>
                <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="<?= button_secondary_class() ?>">Обговорити в Telegram</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php';
