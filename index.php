<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageSlug = 'home';
$pageTitle = 'PatchLab — кастомні патчі, нашивки та 3D-вироби';
$metaDescription = 'Онлайн-замовлення кастомних патчів, іменних нашивок і 3D-виробів по Україні. Малі партії, прорахунок перед запуском, швидкий зв’язок.';
$cases = get_cases(['limit' => 8]);
$faqs = get_faq(['limit' => 6]);
$categories = homepage_categories();

require_once __DIR__ . '/includes/header.php';
?>
<section class="relative overflow-hidden py-12 md:py-20">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
        <div class="hero-glow relative">
            <div class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-sand-700">PatchLab MVP</div>
            <h1 class="mt-6 max-w-3xl text-4xl font-extrabold tracking-tight text-slate-950 md:text-5xl">Кастомні патчі, іменні нашивки та 3D-вироби — під замовлення</h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">Малі партії, індивідуальний підхід, онлайн-замовлення по Україні та швидкий прорахунок перед запуском.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="/custom-order" data-event="click_cta" class="<?= button_primary_class() ?>">Замовити прорахунок</a>
                <a href="#cases" class="<?= button_secondary_class() ?>">Переглянути приклади</a>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-3">
                <div class="<?= card_shell_class() ?>">
                    <div class="text-2xl font-bold text-brand-700">3</div>
                    <div class="mt-2 text-sm text-slate-600">Окремі напрями в одному бренді</div>
                </div>
                <div class="<?= card_shell_class() ?>">
                    <div class="text-2xl font-bold text-brand-700">1</div>
                    <div class="mt-2 text-sm text-slate-600">Форма для заявки без зайвої бюрократії</div>
                </div>
                <div class="<?= card_shell_class() ?>">
                    <div class="text-2xl font-bold text-brand-700">UA</div>
                    <div class="mt-2 text-sm text-slate-600">Онлайн по Україні + самовивіз у Києві</div>
                </div>
            </div>
        </div>

        <div class="pattern-grid rounded-[36px] border border-white/80 bg-white p-5 shadow-soft">
            <img src="/assets/images/hero-collage.svg" alt="PatchLab hero collage" class="w-full rounded-[28px] object-cover">
        </div>
    </div>
</section>

<section class="py-16 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Що можна замовити</span>
            <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Три напрямки, з якими легко стартувати навіть без готового макета</h2>
        </div>
        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            <?php foreach ($categories as $category): ?>
                <article class="<?= card_shell_class() ?> reveal-on-scroll h-full">
                    <img src="<?= e($category['image']) ?>" alt="<?= e($category['title']) ?>" class="aspect-[4/3] w-full rounded-[24px] border border-slate-200 object-cover">
                    <div class="mt-5">
                        <h3 class="text-2xl font-semibold text-slate-950"><?= e($category['title']) ?></h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600"><?= e($category['description']) ?></p>
                        <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                            <?php foreach ($category['benefits'] as $benefit): ?>
                                <li>• <?= e($benefit) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?= e($category['url']) ?>" data-event="open_category" class="mt-6 inline-flex items-center text-sm font-semibold text-brand-700">Детальніше</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
$trustBadges = trust_badges_default();
include __DIR__ . '/includes/components/trust-badges.php';
?>

<section id="cases" class="py-16 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Кейси</span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Приклади робіт, які знімають заперечення ще до першого повідомлення</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button" data-case-filter="all" class="rounded-full border border-brand-600 bg-brand-600 px-4 py-2 text-sm font-semibold text-white">Усе</button>
                <button type="button" data-case-filter="patch" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700">Патчі</button>
                <button type="button" data-case-filter="nametape" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700">Нашивки</button>
                <button type="button" data-case-filter="3d" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700">3D</button>
            </div>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($cases as $case): ?>
                <?php include __DIR__ . '/includes/components/case-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
$steps = order_steps_default();
include __DIR__ . '/includes/components/steps-block.php';
?>

<section class="py-16 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Для кого</span>
            <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Сайт побудований навколо трьох типів клієнтів, а не навколо абстрактного каталогу</h2>
        </div>
        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            <?php foreach (audience_blocks() as $block): ?>
                <article class="<?= card_shell_class() ?> reveal-on-scroll">
                    <h3 class="text-xl font-semibold text-slate-950"><?= e($block['title']) ?></h3>
                    <p class="mt-4 text-sm leading-7 text-slate-600"><?= e($block['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-16 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">FAQ</span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 md:text-4xl">Короткі відповіді на запитання, які найчастіше блокують заявку</h2>
            </div>
            <a href="/faq" class="<?= button_secondary_class() ?>">Усі питання</a>
        </div>
        <div class="mt-10">
            <?php
            $accordionId = 'faq-home';
            include __DIR__ . '/includes/components/faq-accordion.php';
            ?>
        </div>
    </div>
</section>

<?php
$ctaTitle = 'Маєте ідею або готовий макет? Надішліть його, і я підкажу оптимальний формат та орієнтовну вартість.';
$ctaText = 'Якщо не впевнені, з чого почати, просто опишіть задачу словами. Далі розкладемо її на реальний формат виробу.';
include __DIR__ . '/includes/components/cta-block.php';

require_once __DIR__ . '/includes/footer.php';
