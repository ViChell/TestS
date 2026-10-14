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

$pageSlug = 'buy';
$pageTitle = 'Купити ' . $product['title'] . ' | PatchLab';
$metaDescription = 'Оформлення замовлення на готовий товар PatchLab з доставкою самовивозом або Новою поштою та оплатою на карту.';
$contacts = get_contacts();
$formState = consume_form_state('buy_order');
$successMessage = success_banner_text($_GET['success'] ?? '');
$npEnabled = nova_poshta_enabled();
$deliveryMethod = old_value($formState, 'delivery_method', 'pickup');
$images = array_values(array_unique(array_filter(array_merge([$product['main_image'] ?? category_meta($product['category'])['image']], normalize_gallery($product['gallery_images'] ?? [])))));
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => category_meta($product['category'])['label'], 'url' => category_meta($product['category'])['url']],
    ['label' => $product['title'], 'url' => build_product_url($product)],
    ['label' => 'Купити'],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.92fr_1.08fr]">
            <aside class="space-y-6">
                <div class="<?= card_shell_class() ?>">
                    <div class="overflow-hidden rounded-[24px] border border-slate-200 bg-slate-100">
                        <img src="<?= e($images[0] ?? category_meta($product['category'])['image']) ?>" alt="<?= e($product['title']) ?>" class="aspect-[4/3] w-full object-cover">
                    </div>
                    <div class="mt-5">
                        <div class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700"><?= e(category_meta($product['category'])['label']) ?></div>
                        <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-950"><?= e($product['title']) ?></h1>
                        <p class="mt-3 text-sm leading-7 text-slate-600"><?= e($product['short_description']) ?></p>
                        <div class="mt-5 rounded-[24px] bg-brand-50 p-4">
                            <div class="text-xs uppercase tracking-[0.24em] text-slate-500">Ціна</div>
                            <div class="mt-2 text-3xl font-bold text-brand-700"><?= e(product_price_label($product)) ?></div>
                        </div>
                    </div>
                </div>

                <div class="<?= card_shell_class() ?>">
                    <h2 class="text-xl font-semibold text-slate-950">Що входить у checkout</h2>
                    <ul class="mt-4 grid gap-3 text-sm leading-7 text-slate-600">
                        <li>• Обов’язкові дані: ім’я, телефон та email.</li>
                        <li>• Коментар до замовлення за потреби.</li>
                        <li>• Доставка: самовивіз або Нова пошта.</li>
                        <li>• Оплата тільки на карту після підтвердження.</li>
                    </ul>
                </div>
            </aside>

            <div class="<?= card_shell_class() ?>">
                <div class="max-w-2xl">
                    <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Checkout</span>
                    <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950">Оформлення замовлення</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">Після відправки замовлення я підтверджу наявність і надішлю реквізити для оплати на карту.</p>
                </div>

                <?php if ($successMessage): ?>
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?= e($successMessage) ?></div>
                <?php endif; ?>

                <?php if ($formState['errors'] !== []): ?>
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?= e($formState['errors']['general'] ?? 'Перевірте форму покупки і спробуйте ще раз.') ?></div>
                <?php endif; ?>

                <form action="/handlers/buy-handler.php" method="post" class="mt-8 grid gap-6" data-buy-form data-enhanced-form data-np-enabled="<?= $npEnabled ? '1' : '0' ?>" data-np-endpoint="/handlers/nova-poshta.php">
                    <?= csrf_field() ?>
                    <input type="hidden" name="slug" value="<?= e($product['slug']) ?>">

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-700">
                            Ім’я
                            <input type="text" name="name" value="<?= e((string) old_value($formState, 'name')) ?>" class="<?= input_class(isset($formState['errors']['name'])) ?>">
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Кількість
                            <input type="number" min="1" name="quantity" value="<?= e((string) old_value($formState, 'quantity', '1')) ?>" class="<?= input_class(isset($formState['errors']['quantity'])) ?>">
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Телефон
                            <input type="tel" name="phone" value="<?= e((string) old_value($formState, 'phone')) ?>" class="<?= input_class(isset($formState['errors']['phone'])) ?>">
                        </label>
                        <label class="block text-sm font-medium text-slate-700">
                            Email
                            <input type="email" name="email" value="<?= e((string) old_value($formState, 'email')) ?>" class="<?= input_class(isset($formState['errors']['email'])) ?>">
                        </label>
                    </div>

                    <label class="block text-sm font-medium text-slate-700">
                        Коментар до замовлення
                        <textarea name="comment" class="<?= textarea_class(false) ?>"><?= e((string) old_value($formState, 'comment')) ?></textarea>
                    </label>

                    <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-5">
                        <div class="text-sm font-semibold text-slate-900">Спосіб доставки</div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-4 text-sm text-slate-700">
                                <input type="radio" name="delivery_method" value="pickup" <?= $deliveryMethod === 'pickup' ? 'checked' : '' ?> class="mt-1 h-4 w-4 border-slate-300 text-brand-600">
                                <span><span class="block font-semibold text-slate-900">Самовивіз</span><span class="mt-1 block text-slate-500">Київ, за попередньою домовленістю.</span></span>
                            </label>
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-4 text-sm text-slate-700">
                                <input type="radio" name="delivery_method" value="nova_poshta" <?= $deliveryMethod === 'nova_poshta' ? 'checked' : '' ?> class="mt-1 h-4 w-4 border-slate-300 text-brand-600">
                                <span><span class="block font-semibold text-slate-900">Нова пошта</span><span class="mt-1 block text-slate-500">Вибір міста та відділення на сторінці оформлення.</span></span>
                            </label>
                        </div>

                        <div class="<?= $deliveryMethod === 'pickup' ? '' : 'hidden' ?> mt-5 rounded-2xl border border-sand-200 bg-sand-50 px-4 py-4 text-sm text-sand-800" data-delivery-block="pickup">
                            Самовивіз доступний у Києві. Після оформлення узгодимо дату та час окремо.
                        </div>

                        <div class="<?= $deliveryMethod === 'nova_poshta' ? '' : 'hidden' ?> mt-5 grid gap-4" data-delivery-block="nova_poshta">
                            <?php if ($npEnabled): ?>
                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <label class="block text-sm font-medium text-slate-700">
                                        Місто / населений пункт
                                        <input type="text" name="np_city_query" value="<?= e((string) old_value($formState, 'np_city_query', old_value($formState, 'np_city_name'))) ?>" class="<?= input_class(isset($formState['errors']['np_city_name'])) ?>" data-np-city-query autocomplete="off">
                                    </label>
                                    <input type="hidden" name="np_city_ref" value="<?= e((string) old_value($formState, 'np_city_ref')) ?>">
                                    <input type="hidden" name="np_city_name" value="<?= e((string) old_value($formState, 'np_city_name')) ?>">
                                    <div class="mt-3 hidden rounded-2xl border border-slate-200 bg-slate-50 p-2" data-np-city-results></div>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <label class="block text-sm font-medium text-slate-700">
                                        Відділення / поштомат
                                        <select name="np_warehouse_ref" class="<?= select_class(isset($formState['errors']['np_warehouse_name'])) ?>" data-np-warehouse-select data-selected-ref="<?= e((string) old_value($formState, 'np_warehouse_ref')) ?>">
                                            <option value="">Спочатку оберіть місто</option>
                                        </select>
                                    </label>
                                    <input type="hidden" name="np_warehouse_name" value="<?= e((string) old_value($formState, 'np_warehouse_name')) ?>">
                                    <p class="mt-2 text-xs text-slate-500">Якщо поле не заповнилося автоматично, перевірте вибір міста ще раз.</p>
                                </div>
                            <?php else: ?>
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 text-sm text-slate-600">
                                    Інтеграція з API Нової пошти увімкнеться після додавання `NOVA_POSHTA_API_KEY` у `includes/config.php`. Поки що можна ввести місто та відділення вручну.
                                </div>
                                <label class="block text-sm font-medium text-slate-700">
                                    Місто / населений пункт
                                    <input type="text" name="np_city_name" value="<?= e((string) old_value($formState, 'np_city_name')) ?>" class="<?= input_class(isset($formState['errors']['np_city_name'])) ?>">
                                </label>
                                <label class="block text-sm font-medium text-slate-700">
                                    Відділення / поштомат
                                    <input type="text" name="np_warehouse_name" value="<?= e((string) old_value($formState, 'np_warehouse_name')) ?>" class="<?= input_class(isset($formState['errors']['np_warehouse_name'])) ?>">
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-5">
                        <div class="text-sm font-semibold text-slate-900">Оплата</div>
                        <div class="mt-3 rounded-2xl border border-slate-200 bg-white px-4 py-4 text-sm text-slate-700">
                            <span class="font-semibold text-slate-900">Тільки на карту.</span>
                            <span class="mt-1 block text-slate-500">Після підтвердження замовлення я надішлю реквізити для оплати.</span>
                        </div>
                        <input type="hidden" name="payment_method" value="card_transfer">
                    </div>

                    <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        <input type="checkbox" name="consent" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-brand-600">
                        <span>Погоджуюся на обробку персональних даних згідно з <a href="/privacy" class="font-semibold text-brand-700">політикою конфіденційності</a>.</span>
                    </label>

                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        <button type="submit" class="<?= button_primary_class() ?>">Підтвердити замовлення</button>
                        <a href="<?= e(build_product_url($product)) ?>" class="<?= button_secondary_class() ?>">Повернутись до товару</a>
                        <a href="<?= e($contacts['telegram']) ?>" target="_blank" rel="noreferrer" data-event="click_telegram" class="<?= button_secondary_class() ?>">Telegram</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php';
