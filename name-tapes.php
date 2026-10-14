<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageSlug = 'name-tapes';
$pageTitle = 'Іменні нашивки та позивні | PatchLab';
$metaDescription = 'Позивні, name tapes, група крові та текстові нашивки на липучці. Швидке замовлення від 1 штуки.';
$products = get_products(['category' => 'nametape']);
$contacts = get_contacts();
$quickFormState = consume_form_state('quick_order');
$quickSuccess = success_banner_text($_GET['success'] ?? '');
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'Іменні нашивки'],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
        <div>
            <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Швидкий продукт</span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">Іменні нашивки та позивні</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">Стандартні й кастомні, від 1 штуки, з простою формою замовлення без складних рішень.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="#quick-order" data-event="click_cta" class="<?= button_primary_class() ?>">Замовити нашивку</a>
                <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="<?= button_secondary_class() ?>">Написати в Telegram</a>
            </div>
        </div>
        <div class="rounded-[36px] border border-white/80 bg-white p-5 shadow-soft">
            <img src="/assets/images/name-tape-card.svg" alt="Іменні нашивки PatchLab" class="w-full rounded-[28px] object-cover">
        </div>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <?php foreach (['Ім’я', 'Позивний', 'Група крові', 'Роль', 'Кастомний текст'] as $item): ?>
                <div class="<?= card_shell_class() ?> reveal-on-scroll text-center text-sm font-semibold text-slate-700"><?= e($item) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-2">
            <article class="<?= card_shell_class() ?> reveal-on-scroll">
                <h2 class="text-2xl font-bold text-slate-950">Варіанти стилів</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <?php foreach (tape_style_options() as $style): ?>
                        <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-5 text-sm font-medium text-slate-700"><?= e($style) ?></div>
                    <?php endforeach; ?>
                </div>
            </article>
            <article class="<?= card_shell_class() ?> reveal-on-scroll">
                <h2 class="text-2xl font-bold text-slate-950">Комплекти та ціни</h2>
                <div class="mt-6 grid gap-4">
                    <div class="rounded-[24px] bg-brand-50 p-5"><div class="text-sm text-slate-500">1 нашивка</div><div class="mt-2 text-2xl font-bold text-brand-700">120 грн</div></div>
                    <div class="rounded-[24px] bg-brand-50 p-5"><div class="text-sm text-slate-500">Комплект 3 шт.</div><div class="mt-2 text-2xl font-bold text-brand-700">300 грн</div></div>
                    <div class="rounded-[24px] bg-brand-50 p-5"><div class="text-sm text-slate-500">Комплект 5 шт.</div><div class="mt-2 text-2xl font-bold text-brand-700">450 грн</div></div>
                    <div class="rounded-[24px] bg-white p-5 text-sm text-slate-600">Нестандартний розмір, колір або шрифт: +30-50 грн до базової ціни.</div>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="py-16 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($products as $product): ?>
                <?php include __DIR__ . '/includes/components/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="quick-order" class="pb-16 md:pb-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="<?= card_shell_class() ?>">
            <div class="max-w-2xl">
                <h2 class="text-3xl font-bold tracking-tight text-slate-950">Швидке замовлення нашивки</h2>
                <p class="mt-4 text-base leading-7 text-slate-600">Мінімальна форма саме для name tapes: текст, кількість, кольори і контакт. Далі вже уточню деталі напряму.</p>
            </div>

            <?php if ($quickSuccess): ?>
                <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?= e($quickSuccess) ?></div>
            <?php endif; ?>

            <?php if ($quickFormState['errors'] !== []): ?>
                <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?= e($quickFormState['errors']['general'] ?? 'Перевірте форму і спробуйте ще раз.') ?></div>
            <?php endif; ?>

            <form action="/handlers/quick-order-handler.php" method="post" class="mt-8 grid gap-6" data-enhanced-form>
                <?= csrf_field() ?>
                <div class="grid gap-6 md:grid-cols-2">
                    <label class="block text-sm font-medium text-slate-700">
                        Текст напису
                        <input type="text" name="tape_text" value="<?= e((string) old_value($quickFormState, 'tape_text')) ?>" class="<?= input_class(isset($quickFormState['errors']['tape_text'])) ?>">
                    </label>
                    <label class="block text-sm font-medium text-slate-700">
                        Кількість
                        <input type="number" min="1" name="quantity" value="<?= e((string) old_value($quickFormState, 'quantity', '1')) ?>" class="<?= input_class(isset($quickFormState['errors']['quantity'])) ?>">
                    </label>
                    <label class="block text-sm font-medium text-slate-700">
                        Колір фону
                        <input type="text" name="background_color" value="<?= e((string) old_value($quickFormState, 'background_color')) ?>" class="<?= input_class(false) ?>">
                    </label>
                    <label class="block text-sm font-medium text-slate-700">
                        Колір нитки
                        <input type="text" name="thread_color" value="<?= e((string) old_value($quickFormState, 'thread_color')) ?>" class="<?= input_class(false) ?>">
                    </label>
                </div>

                <label class="block text-sm font-medium text-slate-700">
                    Контакт для зв’язку
                    <input type="text" name="contact" value="<?= e((string) old_value($quickFormState, 'contact')) ?>" class="<?= input_class(isset($quickFormState['errors']['contact'])) ?>">
                </label>

                <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    <input type="checkbox" name="consent" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-brand-600">
                    <span>Погоджуюся на обробку персональних даних згідно з <a href="/privacy" class="font-semibold text-brand-700">політикою конфіденційності</a>.</span>
                </label>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <button type="submit" class="<?= button_primary_class() ?>">Надіслати швидку заявку</button>
                    <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="<?= button_secondary_class() ?>">Telegram як альтернатива</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php
$ctaTitle = 'Потрібні позивні або комплект нашивок? Надішліть напис і кількість, далі я все доточу в діалозі.';
$primaryUrl = '#quick-order';
$primaryLabel = 'Замовити нашивку';
$secondaryUrl = $contacts['telegram'];
include __DIR__ . '/includes/components/cta-block.php';

require_once __DIR__ . '/includes/footer.php';
