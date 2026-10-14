CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    category ENUM('patch', 'nametape', '3d') NOT NULL,
    short_description TEXT NULL,
    full_description TEXT NULL,
    price_type ENUM('fixed', 'from', 'request') NOT NULL DEFAULT 'request',
    price_value INT UNSIGNED NULL,
    main_image VARCHAR(500) NULL,
    gallery_images JSON NULL,
    target_audience TEXT NULL,
    customization_options TEXT NULL,
    status ENUM('published', 'draft') NOT NULL DEFAULT 'draft',
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cases (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    category ENUM('patch', 'nametape', '3d') NOT NULL,
    short_description TEXT NULL,
    full_description TEXT NULL,
    main_image VARCHAR(500) NULL,
    gallery_images JSON NULL,
    status ENUM('published', 'draft') NOT NULL DEFAULT 'draft',
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact VARCHAR(255) NOT NULL,
    category VARCHAR(50) NULL,
    description TEXT NOT NULL,
    quantity INT NULL,
    deadline VARCHAR(100) NULL,
    extra_fields JSON NULL,
    file_path VARCHAR(500) NULL,
    reference_url VARCHAR(500) NULL,
    status ENUM('new', 'in_progress', 'done', 'cancelled') NOT NULL DEFAULT 'new',
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS faq (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published', 'draft') NOT NULL DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contacts (
    id INT UNSIGNED NOT NULL PRIMARY KEY DEFAULT 1,
    phone VARCHAR(50) NULL,
    email VARCHAR(255) NULL,
    telegram VARCHAR(255) NULL,
    instagram VARCHAR(255) NULL,
    city VARCHAR(100) NULL,
    description TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS seo_pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_slug VARCHAR(100) NOT NULL UNIQUE,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    og_image VARCHAR(500) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO contacts (id, phone, email, telegram, instagram, city, description)
VALUES (1, '+380 00 000 00 00', 'hello@patchlab.local', 'https://t.me/patchlab', 'https://instagram.com/patchlab', 'Київ', 'PatchLab працює онлайн по всій Україні: кастомні патчі, іменні нашивки та малі 3D-вироби з швидким зворотним зв’язком.')
ON DUPLICATE KEY UPDATE
    phone = VALUES(phone),
    email = VALUES(email),
    telegram = VALUES(telegram),
    instagram = VALUES(instagram),
    city = VALUES(city),
    description = VALUES(description);

INSERT INTO faq (id, question, answer, sort_order, status) VALUES
    (1, 'Який мінімальний тираж?', 'Від 1 штуки. Для серій також роблю прорахунок окремо.', 10, 'published'),
    (2, 'Чи потрібен готовий макет?', 'Ні. Достатньо описати ідею словами або надіслати референс.', 20, 'published'),
    (3, 'Що впливає на вартість?', 'Розмір, складність дизайну, кількість кольорів, тип кріплення і тираж.', 30, 'published'),
    (4, 'Скільки триває виготовлення?', 'Зазвичай 3-7 робочих днів після погодження деталей.', 40, 'published'),
    (5, 'Чи можна зробити за логотипом?', 'Так, якщо логотип або знак адаптується під формат виробу.', 50, 'published'),
    (6, 'Чи відправляєте по Україні?', 'Так, працюю з відправкою Новою Поштою та самовивозом у Києві.', 60, 'published'),
    (7, 'Які файли краще надсилати?', 'PNG, JPG, SVG, PDF, AI. Якщо нічого немає, підійде навіть фото з телефона.', 70, 'published'),
    (8, 'Чи можна замовити терміново?', 'Так, напишіть у Telegram із поміткою “терміново”, і я скажу реальні строки.', 80, 'published')
ON DUPLICATE KEY UPDATE
    question = VALUES(question),
    answer = VALUES(answer),
    sort_order = VALUES(sort_order),
    status = VALUES(status);

INSERT INTO seo_pages (page_slug, meta_title, meta_description, og_image) VALUES
    ('home', 'PatchLab — кастомні патчі, нашивки та 3D-вироби', 'Онлайн-замовлення кастомних патчів, іменних нашивок і 3D-виробів по Україні. Малі партії, прорахунок перед запуском, швидкий зв’язок.', '/assets/images/hero-collage.svg'),
    ('patches', 'Кастомні патчі під замовлення | PatchLab', 'Патчі від 1 штуки: логотипні, текстові, morale patches, серії для команди. Працюю з макетом або тільки з ідеєю.', '/assets/images/patch-card.svg'),
    ('name-tapes', 'Іменні нашивки та позивні | PatchLab', 'Позивні, name tapes, група крові та текстові нашивки на липучці. Швидке замовлення від 1 штуки.', '/assets/images/name-tape-card.svg'),
    ('3d-products', '3D-друковані вироби та органайзери | PatchLab', 'Стенди для патчів, органайзери, підставки та малі кастомні 3D-рішення під конкретну задачу.', '/assets/images/3d-card.svg'),
    ('custom-order', 'Замовити прорахунок | PatchLab', 'Надішліть опис задачі, кількість, розмір або референси. Форма для замовлення патчів, name tapes та 3D-виробів.', '/assets/images/hero-collage.svg'),
    ('how-to-order', 'Як замовити | PatchLab', 'Пояснення процесу замовлення: від ідеї та прорахунку до виготовлення, оплати й доставки.', '/assets/images/hero-collage.svg'),
    ('about', 'Про бренд PatchLab', 'Мікробренд кастомних патчів, нашивок та малих 3D-виробів з індивідуальним підходом і роботою онлайн по Україні.', '/assets/images/hero-collage.svg'),
    ('faq', 'FAQ | PatchLab', 'Відповіді на часті питання про тираж, строки, макети, доставку, термінові замовлення та оплату.', '/assets/images/case-card.svg'),
    ('contacts', 'Контакти | PatchLab', 'Telegram, Instagram, email, телефон та коротка форма для зв’язку з PatchLab.', '/assets/images/hero-collage.svg')
ON DUPLICATE KEY UPDATE
    meta_title = VALUES(meta_title),
    meta_description = VALUES(meta_description),
    og_image = VALUES(og_image);
