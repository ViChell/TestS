<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageSlug = 'payment-delivery';
$pageTitle = 'Оплата і доставка | PatchLab';
$metaDescription = 'Передоплата, LiqPay, післяплата Новою Поштою, самовивіз у Києві та базові умови доставки.';
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'Оплата і доставка'],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Умови</span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">Оплата і доставка</h1>
            <p class="mt-5 text-lg leading-8 text-slate-600">Сторінка для зняття базових заперечень: як оплачується замовлення, коли йде відправка і які є опції по Україні.</p>
        </div>
        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <article class="<?= card_shell_class() ?>">
                <h2 class="text-2xl font-bold text-slate-950">Оплата</h2>
                <ul class="mt-4 grid gap-3 text-sm leading-7 text-slate-600">
                    <li>• Передоплата на карту: повна або часткова.</li>
                    <li>• LiqPay як онлайн-оплата на наступному етапі розвитку.</li>
                    <li>• Післяплата / накладений платіж Новою Поштою за погодженням.</li>
                </ul>
            </article>
            <article class="<?= card_shell_class() ?>">
                <h2 class="text-2xl font-bold text-slate-950">Доставка</h2>
                <ul class="mt-4 grid gap-3 text-sm leading-7 text-slate-600">
                    <li>• Нова Пошта по всій Україні.</li>
                    <li>• Відправка зазвичай 1-2 робочі дні після виготовлення.</li>
                    <li>• Самовивіз у Києві за попередньою домовленістю.</li>
                    <li>• Вартість доставки — за тарифами перевізника.</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<?php
$ctaTitle = 'Після ознайомлення з умовами можна одразу перейти до заявки і прорахунку.';
$primaryUrl = '/custom-order';
$primaryLabel = 'Перейти до заявки';
include __DIR__ . '/includes/components/cta-block.php';

require_once __DIR__ . '/includes/footer.php';
