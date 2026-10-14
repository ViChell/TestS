<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageSlug = 'about';
$pageTitle = 'Про бренд PatchLab';
$metaDescription = 'Мікробренд кастомних патчів, нашивок та малих 3D-виробів з індивідуальним підходом і роботою онлайн по Україні.';
$contacts = get_contacts();
$breadcrumbs = [
    ['label' => 'Головна', 'url' => '/'],
    ['label' => 'Про бренд'],
];

require_once __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/components/breadcrumbs.php';
?>
<section class="py-10 md:py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
        <div>
            <span class="inline-flex rounded-full border border-sand-200 bg-sand-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-sand-700">Про бренд</span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight text-slate-950 md:text-5xl">PatchLab — мікробренд, який працює на стику дизайну, вишивки та 3D</h1>
            <div class="prose-slate mt-6 max-w-3xl text-base leading-8 text-slate-700">
                <p>PatchLab не будується як галерея заради галереї. Це сервіс, який допомагає швидко перейти від ідеї до реального виробу без великої бюрократії.</p>
                <p>Фокус — малі партії, чіткий діалог і зрозумілий процес: кастомні патчі, іменні нашивки, корисні 3D-вироби та невеликі прототипи.</p>
                <p>Працюю онлайн по всій Україні. Якщо потрібно, достатньо описати ідею словами: далі я допоможу звузити формат, матеріал і реальний шлях до результату.</p>
            </div>
        </div>
        <div class="rounded-[36px] border border-white/80 bg-white p-5 shadow-soft">
            <img src="/assets/images/hero-collage.svg" alt="Про бренд PatchLab" class="w-full rounded-[28px] object-cover">
        </div>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
        <article class="<?= card_shell_class() ?> reveal-on-scroll">
            <h2 class="text-xl font-semibold text-slate-950">Онлайн по Україні</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">Основний контакт відбувається в месенджерах і через форму заявки. Це дозволяє не затягувати процес на старті.</p>
        </article>
        <article class="<?= card_shell_class() ?> reveal-on-scroll">
            <h2 class="text-xl font-semibold text-slate-950">Малі партії</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">Від одиничних замовлень до невеликих серій для команди, підрозділу чи бренду.</p>
        </article>
        <article class="<?= card_shell_class() ?> reveal-on-scroll">
            <h2 class="text-xl font-semibold text-slate-950">Індивідуальний підхід</h2>
            <p class="mt-4 text-sm leading-7 text-slate-600">Пояснюю компроміси, адаптую ідею під формат виробу і не обіцяю те, що технічно не спрацює.</p>
        </article>
    </div>
</section>

<?php
$ctaTitle = 'Якщо хочете обговорити задачу напряму, напишіть у контакти або одразу відкрийте Telegram.';
$primaryUrl = '/contacts';
$primaryLabel = 'Написати напряму';
$secondaryUrl = $contacts['telegram'];
$secondaryLabel = 'Telegram';
include __DIR__ . '/includes/components/cta-block.php';

require_once __DIR__ . '/includes/footer.php';
