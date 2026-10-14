<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageSlug = 'custom-order';
$pageTitle = 'Замовити прорахунок | PatchLab';
$metaDescription = 'Надішліть опис задачі, кількість, розмір або референси. Форма для замовлення патчів, name tapes та 3D-виробів.';
$contacts = get_contacts();
$formState = consume_form_state('custom_order');
$requestedCategory = $_GET['category'] ?? '';
$selectedCategory = old_value($formState, 'category', in_array($requestedCategory, ['patch', 'nametape', '3d', 'other'], true) ? $requestedCategory : '');
$successMessage = success_banner_text($_GET['success'] ?? '');
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'Замовлення'],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.95fr_1.05fr]">
            <div>
                <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Форма заявки</span>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">Замовити прорахунок</h1>
                <p class="mt-5 max-w-xl text-lg leading-8 text-slate-600">Ключова сторінка сайту. Достатньо коротко описати задачу, додати кількість і, за потреби, прикріпити файл або референс.</p>
                <div class="mt-8 grid gap-4">
                    <div class="<?= card_shell_class() ?>">
                        <div class="text-sm font-semibold uppercase tracking-[0.24em] text-sand-700">Що буде далі</div>
                        <ul class="mt-4 grid gap-3 text-sm leading-7 text-slate-600">
                            <li>• Перегляну задачу і повернуся з уточненнями.</li>
                            <li>• Зроблю попередній прорахунок до запуску.</li>
                            <li>• Узгодимо формат, строки й фінальний напрям.</li>
                        </ul>
                    </div>
                    <div class="<?= card_shell_class() ?>">
                        <div class="text-sm font-semibold uppercase tracking-[0.24em] text-sand-700">Швидкий канал</div>
                        <p class="mt-4 text-sm leading-7 text-slate-600">Якщо треба терміново, паралельно напишіть у Telegram з позначкою “терміново”.</p>
                        <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="mt-4 inline-flex text-sm font-semibold text-brand-700">Відкрити Telegram</a>
                    </div>
                </div>
            </div>

            <div class="<?= card_shell_class() ?>">
                <?php if ($successMessage): ?>
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?= e($successMessage) ?></div>
                <?php endif; ?>

                <?php if ($formState['errors'] !== []): ?>
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <?= e($formState['errors']['general'] ?? 'Перевірте поля форми: є кілька моментів, які потрібно виправити.') ?>
                    </div>
                <?php endif; ?>

                <form action="/handlers/order-handler.php" method="post" enctype="multipart/form-data" class="grid gap-6" data-order-form data-enhanced-form>
                    <?= csrf_field() ?>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Що ви хочете замовити?
                            <select name="category" class="<?= select_class(isset($formState['errors']['category'])) ?>" data-order-category>
                                <option value="">Оберіть категорію</option>
                                <?php foreach (order_category_options() as $value => $label): ?>
                                    <option value="<?= e($value) ?>" <?= $selectedCategory === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Кількість
                            <input type="number" min="1" name="quantity" value="<?= e((string) old_value($formState, 'quantity', '1')) ?>" class="<?= input_class(isset($formState['errors']['quantity'])) ?>">
                        </label>
                    </div>

                    <label class="block text-sm font-medium text-slate-700">
                        Короткий опис задачі
                        <textarea name="description" class="<?= textarea_class(isset($formState['errors']['description'])) ?>"><?= e((string) old_value($formState, 'description')) ?></textarea>
                    </label>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Текст напису
                            <input type="text" name="text_line" value="<?= e((string) old_value($formState, 'text_line')) ?>" class="<?= input_class(isset($formState['errors']['text_line'])) ?>">
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Орієнтовний розмір
                            <select name="size" class="<?= select_class(false) ?>">
                                <option value="">Оберіть або залиште порожнім</option>
                                <?php foreach (order_size_options() as $option): ?>
                                    <option value="<?= e($option) ?>" <?= old_value($formState, 'size') === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Файл / макет
                            <input type="file" name="attachment" class="<?= input_class(false) ?>">
                            <span class="mt-2 block text-xs text-slate-500">PNG, JPG, SVG, PDF, AI. Максимум 5 МБ.</span>
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Посилання на референс
                            <input type="url" name="reference_url" value="<?= e((string) old_value($formState, 'reference_url')) ?>" class="<?= input_class(isset($formState['errors']['reference_url'])) ?>">
                        </label>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Бажаний строк
                            <select name="deadline" class="<?= select_class(false) ?>">
                                <?php foreach (deadline_options() as $value => $label): ?>
                                    <option value="<?= e($value) ?>" <?= old_value($formState, 'deadline') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Ім’я
                            <input type="text" name="name" value="<?= e((string) old_value($formState, 'name')) ?>" class="<?= input_class(isset($formState['errors']['name'])) ?>">
                        </label>
                    </div>

                    <label class="block text-sm font-medium text-slate-700">
                        Telegram / телефон / email
                        <input type="text" name="contact" value="<?= e((string) old_value($formState, 'contact')) ?>" class="<?= input_class(isset($formState['errors']['contact'])) ?>">
                    </label>

                    <div data-conditional-group="patch" class="<?= $selectedCategory === 'patch' ? '' : 'hidden' ?> grid gap-6 rounded-[24px] border border-slate-200 bg-slate-50 p-5 md:grid-cols-3">
                        <label class="block text-sm font-medium text-slate-700">
                            Форма патча
                            <select name="patch_shape" class="<?= select_class(false) ?>">
                                <option value="">Оберіть</option>
                                <?php foreach (order_shape_options() as $option): ?>
                                    <option value="<?= e($option) ?>" <?= old_value($formState, 'patch_shape') === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Бажані кольори
                            <input type="text" name="patch_colors" value="<?= e((string) old_value($formState, 'patch_colors')) ?>" class="<?= input_class(false) ?>">
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Тип основи
                            <select name="patch_base" class="<?= select_class(false) ?>">
                                <option value="">Оберіть</option>
                                <?php foreach (order_base_options() as $option): ?>
                                    <option value="<?= e($option) ?>" <?= old_value($formState, 'patch_base') === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <div data-conditional-group="nametape" class="<?= $selectedCategory === 'nametape' ? '' : 'hidden' ?> grid gap-6 rounded-[24px] border border-slate-200 bg-slate-50 p-5 md:grid-cols-3">
                        <label class="block text-sm font-medium text-slate-700">
                            Текст нашивки
                            <input type="text" name="nametape_required_text" value="<?= e((string) old_value($formState, 'nametape_required_text', old_value($formState, 'text_line'))) ?>" class="<?= input_class(false) ?>" data-conditional-required>
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Стиль
                            <select name="nametape_style" class="<?= select_class(false) ?>">
                                <option value="">Оберіть</option>
                                <?php foreach (tape_style_options() as $option): ?>
                                    <option value="<?= e($option) ?>" <?= old_value($formState, 'nametape_style') === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Розмір
                            <input type="text" name="nametape_size" value="<?= e((string) old_value($formState, 'nametape_size')) ?>" class="<?= input_class(false) ?>">
                        </label>
                    </div>

                    <div data-conditional-group="3d" class="<?= $selectedCategory === '3d' ? '' : 'hidden' ?> grid gap-6 rounded-[24px] border border-slate-200 bg-slate-50 p-5 md:grid-cols-3">
                        <label class="block text-sm font-medium text-slate-700">
                            Призначення виробу
                            <input type="text" name="object_purpose" value="<?= e((string) old_value($formState, 'object_purpose')) ?>" class="<?= input_class(false) ?>">
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Бажаний розмір
                            <input type="text" name="object_size" value="<?= e((string) old_value($formState, 'object_size')) ?>" class="<?= input_class(false) ?>">
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Чи є ескіз / STL / фото
                            <input type="text" name="object_assets" value="<?= e((string) old_value($formState, 'object_assets')) ?>" class="<?= input_class(false) ?>">
                        </label>
                    </div>

                    <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        <input type="checkbox" name="consent" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-brand-600">
                        <span>Погоджуюся на обробку персональних даних згідно з <a href="/privacy" class="font-semibold text-brand-700">політикою конфіденційності</a>.</span>
                    </label>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <button type="submit" class="<?= button_primary_class() ?>">Надіслати заявку</button>
                        <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="<?= button_secondary_class() ?>">Telegram як швидкий канал</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php';
