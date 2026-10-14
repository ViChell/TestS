<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageSlug = 'privacy';
$pageTitle = 'Політика конфіденційності | PatchLab';
$metaDescription = 'Як обробляються персональні дані, файли, cookies і технічна інформація на сайті PatchLab.';
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'Політика конфіденційності'],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="<?= card_shell_class() ?> prose-slate text-base leading-8 text-slate-700">
            <h1 class="text-4xl font-bold tracking-tight text-slate-950">Політика конфіденційності</h1>
            <p class="mt-6">PatchLab збирає лише ті дані, які потрібні для відповіді на запит, прорахунку замовлення, зв’язку з клієнтом і виконання домовленостей.</p>
            <p>До таких даних належать ім’я, контактний спосіб зв’язку, опис задачі, прикріплені файли, технічна інформація про браузер і IP-адреса для базового rate limiting.</p>
            <p>Дані не продаються третім сторонам. Вони можуть використовуватися лише для виконання замовлення, комунікації, резервного email-сповіщення та надсилання повідомлень у Telegram bot.</p>
            <p>Файли і заявки зберігаються стільки, скільки це потрібно для обробки замовлення, подальшої комунікації та резервного архівування.</p>
            <p>Сайт використовує cookies для GA4 і Meta Pixel лише після явної згоди користувача через cookie-банер.</p>
            <p>Якщо потрібно уточнити, змінити або видалити персональні дані, звертайтеся через сторінку контактів.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php';
