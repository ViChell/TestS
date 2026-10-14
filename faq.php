<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$faqs = get_faq();
$pageSlug = 'faq';
$pageTitle = 'FAQ | PatchLab';
$metaDescription = 'Відповіді на часті питання про тираж, строки, макети, доставку, термінові замовлення та оплату.';
$contacts = get_contacts();
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'FAQ'],
];
$schemaData = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(static fn(array $faq): array => [
        '@type' => 'Question',
        'name' => $faq['question'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $faq['answer'],
        ],
    ], $faqs),
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">FAQ</span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">Питання і відповіді</h1>
            <p class="mt-5 text-lg leading-8 text-slate-600">Сторінка для швидкого зняття базових бар’єрів перед заявкою: тираж, строки, файли, доставка, терміновість.</p>
        </div>
        <div class="mt-10">
            <?php $accordionId = 'faq-page'; include __DIR__ . '/includes/components/faq-accordion.php'; ?>
        </div>
    </div>
</section>

<?php
$ctaTitle = 'Якщо відповідь не знайшлася, краще одразу залишити заявку або написати в Telegram.';
$primaryUrl = '/custom-order';
$primaryLabel = 'Залишити заявку';
$secondaryUrl = $contacts['telegram'];
include __DIR__ . '/includes/components/cta-block.php';

require_once __DIR__ . '/includes/footer.php';
