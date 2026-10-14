<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageSlug = 'contacts';
$pageTitle = 'Контакти | PatchLab';
$metaDescription = 'Telegram, Instagram, email, телефон та коротка форма для зв’язку з PatchLab.';
$contacts = get_contacts();
$formState = consume_form_state('contact_form');
$successMessage = success_banner_text($_GET['success'] ?? '');
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'Контакти'],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr]">
            <div>
                <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Контакти</span>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">Зв’язок з PatchLab</h1>
                <p class="mt-5 max-w-xl text-lg leading-8 text-slate-600">Telegram — основний швидкий канал. Instagram — вітрина. Email — для файлів і формальних звернень. Телефон — опціонально.</p>
                <div class="mt-8 grid gap-4">
                    <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="<?= card_shell_class() ?> block">
                        <div class="text-xs uppercase tracking-[0.24em] text-slate-500">Telegram</div>
                        <div class="mt-2 text-xl font-semibold text-slate-950"><?= e($contacts['telegram']) ?></div>
                    </a>
                    <a href="<?= e($contacts['instagram']) ?>" target="_blank" rel="noreferrer" data-event="click_instagram" class="<?= card_shell_class() ?> block">
                        <div class="text-xs uppercase tracking-[0.24em] text-slate-500">Instagram</div>
                        <div class="mt-2 text-xl font-semibold text-slate-950"><?= e($contacts['instagram']) ?></div>
                    </a>
                    <div class="<?= card_shell_class() ?>">
                        <div class="text-xs uppercase tracking-[0.24em] text-slate-500">Email / телефон / місто</div>
                        <div class="mt-2 grid gap-1 text-sm text-slate-700">
                            <a href="mailto:<?= e($contacts['email']) ?>" data-event="click_email" class="font-semibold text-brand-700"><?= e($contacts['email']) ?></a>
                            <a href="tel:<?= e(preg_replace('/\s+/', '', $contacts['phone'])) ?>" data-event="click_phone" class="font-semibold text-brand-700"><?= e($contacts['phone']) ?></a>
                            <div><?= e($contacts['city']) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="<?= card_shell_class() ?>">
                <?php if ($successMessage): ?>
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?= e($successMessage) ?></div>
                <?php endif; ?>
                <?php if ($formState['errors'] !== []): ?>
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?= e($formState['errors']['general'] ?? 'Перевірте форму перед повторною відправкою.') ?></div>
                <?php endif; ?>

                <h2 class="text-3xl font-bold tracking-tight text-slate-950">Короткий запит</h2>
                <p class="mt-4 text-base leading-7 text-slate-600">Ця форма підходить, коли ви ще не готові до детальної заявки, але хочете швидко вийти на діалог.</p>

                <form action="/handlers/contact-handler.php" method="post" class="mt-8 grid gap-6" data-enhanced-form>
                    <?= csrf_field() ?>
                    <label class="block text-sm font-medium text-slate-700">
                        Ім’я
                        <input type="text" name="name" value="<?= e((string) old_value($formState, 'name')) ?>" class="<?= input_class(isset($formState['errors']['name'])) ?>">
                    </label>
                    <label class="block text-sm font-medium text-slate-700">
                        Контакт
                        <input type="text" name="contact" value="<?= e((string) old_value($formState, 'contact')) ?>" class="<?= input_class(isset($formState['errors']['contact'])) ?>">
                    </label>
                    <label class="block text-sm font-medium text-slate-700">
                        Повідомлення
                        <textarea name="message" class="<?= textarea_class(isset($formState['errors']['message'])) ?>"><?= e((string) old_value($formState, 'message')) ?></textarea>
                    </label>
                    <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        <input type="checkbox" name="consent" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-brand-600">
                        <span>Погоджуюся на обробку персональних даних згідно з <a href="/privacy" class="font-semibold text-brand-700">політикою конфіденційності</a>.</span>
                    </label>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="submit" class="<?= button_primary_class() ?>">Надіслати повідомлення</button>
                        <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="<?= button_secondary_class() ?>">Відкрити Telegram</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php';
