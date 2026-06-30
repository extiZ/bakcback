<?php
/**
 * Лабораторная работа №3 — Задание 5
 * Фильтрация товаров через GET-запрос
 *
 * Страница отображает каталог товаров с возможностью фильтрации
 * по категории, цене и сортировки — всё передаётся через $_GET.
 */

// ─────────────────────────────────────────────
// 1. БАЗА ТОВАРОВ (эмулирует данные из БД)
// ─────────────────────────────────────────────
$products = [
    ['id' => 1,  'name' => 'Механическая клавиатура',  'category' => 'periphery',   'price' => 7990,  'brand' => 'Keychron', 'rating' => 4.8, 'img' => '⌨️'],
    ['id' => 2,  'name' => 'Игровая мышь',              'category' => 'periphery',   'price' => 3490,  'brand' => 'Logitech', 'rating' => 4.6, 'img' => '🖱️'],
    ['id' => 3,  'name' => 'Монитор 27" 4K',            'category' => 'monitors',    'price' => 34990, 'brand' => 'Samsung',  'rating' => 4.9, 'img' => '🖥️'],
    ['id' => 4,  'name' => 'Монитор 24" Full HD',       'category' => 'monitors',    'price' => 14990, 'brand' => 'LG',       'rating' => 4.4, 'img' => '🖥️'],
    ['id' => 5,  'name' => 'Наушники беспроводные',     'category' => 'audio',       'price' => 8990,  'brand' => 'Sony',     'rating' => 4.7, 'img' => '🎧'],
    ['id' => 6,  'name' => 'Колонки 2.1',               'category' => 'audio',       'price' => 5490,  'brand' => 'Edifier',  'rating' => 4.5, 'img' => '🔊'],
    ['id' => 7,  'name' => 'SSD 1 ТБ NVMe',             'category' => 'storage',     'price' => 6290,  'brand' => 'Samsung',  'rating' => 4.9, 'img' => '💾'],
    ['id' => 8,  'name' => 'HDD 4 ТБ',                  'category' => 'storage',     'price' => 4990,  'brand' => 'WD',       'rating' => 4.3, 'img' => '🗄️'],
    ['id' => 9,  'name' => 'Веб-камера Full HD',         'category' => 'periphery',   'price' => 2990,  'brand' => 'Logitech', 'rating' => 4.2, 'img' => '📷'],
    ['id' => 10, 'name' => 'USB-концентратор 7 портов',  'category' => 'periphery',   'price' => 1490,  'brand' => 'Orico',    'rating' => 4.1, 'img' => '🔌'],
    ['id' => 11, 'name' => 'ЦАП/усилитель для наушников','category' => 'audio',      'price' => 12990, 'brand' => 'FiiO',     'rating' => 4.8, 'img' => '🎵'],
    ['id' => 12, 'name' => 'Игровой коврик XL',         'category' => 'periphery',   'price' => 1990,  'brand' => 'SteelSeries','rating' => 4.6,'img' => '🖱️'],
];

// Словарь категорий для отображения в интерфейсе
$categoryLabels = [
    'all'       => 'Все категории',
    'periphery' => '🖱️ Периферия',
    'monitors'  => '🖥️ Мониторы',
    'audio'     => '🎧 Аудио',
    'storage'   => '💾 Хранение данных',
];

// ─────────────────────────────────────────────
// 2. ЧТЕНИЕ И САНИТИЗАЦИЯ GET-ПАРАМЕТРОВ
// ─────────────────────────────────────────────

/**
 * Функция безопасного получения строки из $_GET.
 * htmlspecialchars() защищает от XSS-атак.
 *
 * @param string $key     — ключ из $_GET
 * @param string $default — значение по умолчанию
 * @return string
 */
function getStr(string $key, string $default = ''): string {
    return isset($_GET[$key]) ? htmlspecialchars(trim($_GET[$key])) : $default;
}

/**
 * Функция безопасного получения числа из $_GET.
 *
 * @param string $key     — ключ из $_GET
 * @param int    $default — значение по умолчанию
 * @return int
 */
function getInt(string $key, int $default = 0): int {
    return isset($_GET[$key]) ? (int)$_GET[$key] : $default;
}

// Получаем параметры фильтрации из адресной строки
$selectedCategory = getStr('category', 'all');    // Выбранная категория
$minPrice         = getInt('min_price', 0);       // Минимальная цена
$maxPrice         = getInt('max_price', 50000);   // Максимальная цена
$sortBy           = getStr('sort', 'default');     // Порядок сортировки
$searchQuery      = getStr('search', '');          // Строка поиска

// Валидация: максимальная цена не должна быть меньше минимальной
if ($maxPrice < $minPrice) {
    $maxPrice = 50000;
}

// Допустимые варианты сортировки (белый список для безопасности)
$allowedSorts = ['default', 'price_asc', 'price_desc', 'rating_desc', 'name_asc'];
if (!in_array($sortBy, $allowedSorts)) {
    $sortBy = 'default'; // Сброс к безопасному значению
}

// ─────────────────────────────────────────────
// 3. ПРИМЕНЕНИЕ ФИЛЬТРОВ
// ─────────────────────────────────────────────
$filtered = $products; // Начинаем с полного списка

// Фильтр по категории
if ($selectedCategory !== 'all' && array_key_exists($selectedCategory, $categoryLabels)) {
    $filtered = array_filter($filtered, function($p) use ($selectedCategory) {
        return $p['category'] === $selectedCategory;
    });
}

// Фильтр по диапазону цен
$filtered = array_filter($filtered, function($p) use ($minPrice, $maxPrice) {
    return $p['price'] >= $minPrice && $p['price'] <= $maxPrice;
});

// Фильтр по строке поиска (ищем в названии и бренде, регистронезависимо)
if ($searchQuery !== '') {
    $filtered = array_filter($filtered, function($p) use ($searchQuery) {
        $haystack = mb_strtolower($p['name'] . ' ' . $p['brand']);
        return mb_strpos($haystack, mb_strtolower($searchQuery)) !== false;
    });
}

// ─────────────────────────────────────────────
// 4. СОРТИРОВКА РЕЗУЛЬТАТОВ
// ─────────────────────────────────────────────
$filtered = array_values($filtered); // Сбрасываем ключи после array_filter

usort($filtered, function($a, $b) use ($sortBy) {
    switch ($sortBy) {
        case 'price_asc':    return $a['price']  <=> $b['price'];
        case 'price_desc':   return $b['price']  <=> $a['price'];
        case 'rating_desc':  return $b['rating'] <=> $a['rating'];
        case 'name_asc':     return strcmp($a['name'], $b['name']);
        default:             return $a['id']     <=> $b['id'];
    }
});

// Количество найденных товаров
$foundCount = count($filtered);

// ─────────────────────────────────────────────
// 5. ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
// ─────────────────────────────────────────────

/**
 * Форматирует цену с разделителями тысяч и знаком рубля.
 */
function formatPrice(int $price): string {
    return number_format($price, 0, ',', ' ') . ' ₽';
}

/**
 * Возвращает звёздочки рейтинга в виде HTML.
 */
function renderStars(float $rating): string {
    $full  = floor($rating);       // Полные звёзды
    $empty = 5 - ceil($rating);    // Пустые звёзды
    $half  = 5 - $full - $empty;   // Половина звезды

    return str_repeat('★', $full)
         . str_repeat('½', $half)
         . str_repeat('☆', $empty);
}

/**
 * Строит URL с текущими параметрами + переопределяет один параметр.
 * Используется для построения ссылок категорий.
 *
 * @param array  $override — параметры для переопределения
 * @return string
 */
function buildUrl(array $override): string {
    // Берём текущие GET-параметры и мержим с новыми
    $params = array_merge($_GET, $override);
    // Убираем пустые значения
    $params = array_filter($params, fn($v) => $v !== '' && $v !== '0' || in_array($v, ['0']));
    return '?' . http_build_query($params);
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechShop — Каталог товаров | ЛР №3, Задание 5</title>

    <!-- Google Fonts: пара шрифтов для характерного вида -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

    <style>
        /* ──────────────────────────────────────────
           CSS-переменные — цветовая система
        ────────────────────────────────────────── */
        :root {
            --bg:          #0e0f13;
            --surface:     #16181f;
            --surface2:    #1e2029;
            --border:      #2a2d38;
            --accent:      #5b7fff;
            --accent-glow: rgba(91, 127, 255, 0.25);
            --accent2:     #ff6b6b;
            --text:        #e8eaf0;
            --text-dim:    #7a7f94;
            --text-xdim:   #44475a;
            --success:     #4ade80;
            --radius:      12px;
            --radius-lg:   18px;
        }

        /* ──────────────────────────────────────────
           Сброс и базовые стили
        ────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Фоновая сетка для атмосферы */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(91,127,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(91,127,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* ──────────────────────────────────────────
           Шапка
        ────────────────────────────────────────── */
        header {
            position: relative;
            z-index: 10;
            border-bottom: 1px solid var(--border);
            background: rgba(14, 15, 19, 0.92);
            backdrop-filter: blur(12px);
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }

        .logo {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text);
        }

        .logo span {
            color: var(--accent);
        }

        .lab-badge {
            font-size: 12px;
            color: var(--text-dim);
            background: var(--surface2);
            border: 1px solid var(--border);
            padding: 4px 12px;
            border-radius: 20px;
            font-family: 'DM Sans', sans-serif;
        }

        /* ──────────────────────────────────────────
           Основной макет
        ────────────────────────────────────────── */
        .page-wrapper {
            position: relative;
            z-index: 1;
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 24px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 28px;
            align-items: start;
        }

        /* ──────────────────────────────────────────
           Боковая панель фильтров
        ────────────────────────────────────────── */
        .sidebar {
            position: sticky;
            top: 24px;
        }

        .filter-form {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .filter-form h2 {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-form h2::before {
            content: '';
            display: inline-block;
            width: 3px;
            height: 16px;
            background: var(--accent);
            border-radius: 2px;
        }

        /* Группа фильтра */
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .filter-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-dim);
        }

        /* Поле поиска */
        .search-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            padding: 10px 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .search-input::placeholder {
            color: var(--text-xdim);
        }

        /* Кнопки-категории */
        .category-buttons {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .cat-btn {
            display: block;
            width: 100%;
            padding: 9px 14px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-dim);
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
            transition: all 0.15s;
        }

        .cat-btn:hover {
            background: var(--surface2);
            color: var(--text);
            border-color: var(--accent);
        }

        .cat-btn.active {
            background: var(--accent-glow);
            border-color: var(--accent);
            color: var(--text);
        }

        /* Ценовой диапазон */
        .price-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .price-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            padding: 10px 12px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .price-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        /* Выпадающий список сортировки */
        .sort-select {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            padding: 10px 14px;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%237a7f94' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            transition: border-color 0.2s;
        }

        .sort-select:focus {
            border-color: var(--accent);
        }

        /* Кнопки формы */
        .btn-apply {
            width: 100%;
            padding: 12px;
            background: var(--accent);
            border: none;
            border-radius: var(--radius);
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.03em;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-apply:hover  { opacity: 0.9; }
        .btn-apply:active { transform: scale(0.98); }

        .btn-reset {
            width: 100%;
            padding: 10px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-dim);
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            display: block;
            transition: all 0.15s;
        }

        .btn-reset:hover {
            border-color: var(--accent2);
            color: var(--accent2);
        }

        /* ──────────────────────────────────────────
           Основной контент — каталог
        ────────────────────────────────────────── */
        .catalog-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Заголовок раздела с результатами */
        .catalog-header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 12px;
        }

        .catalog-title {
            font-family: 'Syne', sans-serif;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .results-count {
            font-size: 13px;
            color: var(--text-dim);
        }

        /* Активные фильтры — теги */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-glow);
            border: 1px solid var(--accent);
            color: var(--text);
            border-radius: 20px;
            font-size: 12px;
            padding: 4px 12px;
        }

        .filter-tag-label {
            color: var(--text-dim);
            margin-right: 2px;
        }

        /* Сетка карточек */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        /* Карточка товара */
        .product-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .product-card:hover {
            border-color: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 8px 32px rgba(91, 127, 255, 0.15);
        }

        .product-emoji {
            font-size: 40px;
            line-height: 1;
        }

        .product-brand {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--accent);
        }

        .product-name {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.3;
            color: var(--text);
            flex-grow: 1;
        }

        .product-rating {
            font-size: 13px;
            color: #f59e0b;
            letter-spacing: 1px;
        }

        .rating-value {
            color: var(--text-dim);
            margin-left: 4px;
            font-size: 12px;
            letter-spacing: 0;
        }

        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 8px;
            border-top: 1px solid var(--border);
        }

        .product-price {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
        }

        .btn-cart {
            background: var(--accent);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            padding: 7px 14px;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: opacity 0.2s;
        }

        .btn-cart:hover { opacity: 0.85; }

        /* Пустой результат */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 20px;
            color: var(--text-dim);
        }

        .empty-state .icon {
            font-size: 56px;
            display: block;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        /* URL отладочная панель */
        .debug-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-left: 3px solid var(--accent);
            border-radius: var(--radius);
            padding: 16px 20px;
        }

        .debug-panel h3 {
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 10px;
        }

        .debug-url {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: var(--accent);
            word-break: break-all;
            background: var(--surface2);
            padding: 10px 14px;
            border-radius: 8px;
            display: block;
        }

        .debug-params {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .debug-param {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 12px;
            font-family: 'Courier New', monospace;
        }

        .debug-param .key   { color: var(--accent2); }
        .debug-param .val   { color: var(--success); }
        .debug-param .eq    { color: var(--text-dim); }

        /* Информационный блок для учителя */
        .info-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px 20px;
            font-size: 13px;
            color: var(--text-dim);
            line-height: 1.7;
        }

        .info-box strong { color: var(--text); }
    </style>
</head>
<body>

<!-- ══════════════════════════════════════════════════════
     ШАПКА СТРАНИЦЫ
══════════════════════════════════════════════════════ -->
<header>
    <div class="logo">Tech<span>Shop</span></div>
    <div class="lab-badge">ЛР №3 · Задание 5 · GET-фильтрация</div>
</header>

<!-- ══════════════════════════════════════════════════════
     ОСНОВНОЙ МАКЕТ
══════════════════════════════════════════════════════ -->
<div class="page-wrapper">

    <!-- ─────────────────── БОКОВАЯ ПАНЕЛЬ ─────────────────── -->
    <aside class="sidebar">
        <!--
            МЕТОД: GET
            Данные формы добавляются в URL как параметры:
            ?category=audio&min_price=1000&max_price=20000&sort=price_asc
            Это позволяет сохранять/шарить ссылку с фильтрами.
        -->
        <form class="filter-form" method="GET" action="">

            <h2>Фильтры</h2>

            <!-- ── Поиск ── -->
            <div class="filter-group">
                <span class="filter-label">🔍 Поиск по названию</span>
                <input
                    type="text"
                    class="search-input"
                    name="search"
                    placeholder="Введите название или бренд..."
                    value="<?= htmlspecialchars($searchQuery) ?>"
                >
            </div>

            <!-- ── Категории (ссылки, а не кнопки формы) ── -->
            <div class="filter-group">
                <span class="filter-label">📦 Категория</span>
                <div class="category-buttons">
                    <?php foreach ($categoryLabels as $key => $label): ?>
                        <!--
                            buildUrl() строит URL, оставляя остальные GET-параметры
                            и заменяя только 'category'. Это удобнее, чем отдельная форма.
                        -->
                        <a href="<?= buildUrl(['category' => $key]) ?>"
                           class="cat-btn <?= ($selectedCategory === $key) ? 'active' : '' ?>">
                            <?= $label ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <!--
                    Скрытое поле: если пользователь выбрал категорию через ссылку,
                    при отправке формы категория сохраняется.
                -->
                <input type="hidden" name="category" value="<?= $selectedCategory ?>">
            </div>

            <!-- ── Ценовой диапазон ── -->
            <div class="filter-group">
                <span class="filter-label">💰 Цена (₽)</span>
                <div class="price-row">
                    <input
                        type="number"
                        class="price-input"
                        name="min_price"
                        placeholder="От"
                        min="0"
                        max="100000"
                        value="<?= $minPrice > 0 ? $minPrice : '' ?>"
                    >
                    <input
                        type="number"
                        class="price-input"
                        name="max_price"
                        placeholder="До"
                        min="0"
                        max="100000"
                        value="<?= $maxPrice < 50000 ? $maxPrice : '' ?>"
                    >
                </div>
            </div>

            <!-- ── Сортировка ── -->
            <div class="filter-group">
                <span class="filter-label">⬆️ Сортировка</span>
                <select name="sort" class="sort-select">
                    <option value="default"    <?= $sortBy === 'default'    ? 'selected' : '' ?>>По умолчанию</option>
                    <option value="price_asc"  <?= $sortBy === 'price_asc'  ? 'selected' : '' ?>>Цена: по возрастанию</option>
                    <option value="price_desc" <?= $sortBy === 'price_desc' ? 'selected' : '' ?>>Цена: по убыванию</option>
                    <option value="rating_desc"<?= $sortBy === 'rating_desc'? 'selected' : '' ?>>Рейтинг: по убыванию</option>
                    <option value="name_asc"   <?= $sortBy === 'name_asc'   ? 'selected' : '' ?>>Название: А → Я</option>
                </select>
            </div>

            <!-- ── Кнопки ── -->
            <button type="submit" class="btn-apply">Применить фильтры</button>
            <a href="?" class="btn-reset">✕ Сбросить все фильтры</a>

        </form>
    </aside>

    <!-- ─────────────────── КАТАЛОГ ─────────────────── -->
    <main class="catalog-section">

        <!-- Заголовок + счётчик результатов -->
        <div class="catalog-header">
            <h1 class="catalog-title">
                <?= $selectedCategory !== 'all'
                    ? $categoryLabels[$selectedCategory] ?? 'Каталог'
                    : 'Все товары' ?>
            </h1>
            <span class="results-count">
                Найдено: <strong><?= $foundCount ?></strong> товара(ов)
            </span>
        </div>

        <!-- Активные фильтры-теги (только если что-то задано) -->
        <?php
        $hasFilters = ($selectedCategory !== 'all') || ($minPrice > 0)
                   || ($maxPrice < 50000) || ($sortBy !== 'default')
                   || ($searchQuery !== '');
        ?>
        <?php if ($hasFilters): ?>
            <div class="active-filters">
                <?php if ($selectedCategory !== 'all'): ?>
                    <span class="filter-tag">
                        <span class="filter-tag-label">Категория:</span>
                        <?= $categoryLabels[$selectedCategory] ?? $selectedCategory ?>
                    </span>
                <?php endif; ?>

                <?php if ($searchQuery !== ''): ?>
                    <span class="filter-tag">
                        <span class="filter-tag-label">Поиск:</span>
                        «<?= $searchQuery ?>»
                    </span>
                <?php endif; ?>

                <?php if ($minPrice > 0 || $maxPrice < 50000): ?>
                    <span class="filter-tag">
                        <span class="filter-tag-label">Цена:</span>
                        <?= formatPrice($minPrice) ?> — <?= formatPrice($maxPrice) ?>
                    </span>
                <?php endif; ?>

                <?php if ($sortBy !== 'default'): ?>
                    <?php $sortLabels = ['price_asc'=>'Дешевле','price_desc'=>'Дороже','rating_desc'=>'По рейтингу','name_asc'=>'А→Я']; ?>
                    <span class="filter-tag">
                        <span class="filter-tag-label">Сортировка:</span>
                        <?= $sortLabels[$sortBy] ?? $sortBy ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- ──── Сетка карточек товаров ──── -->
        <div class="products-grid">
            <?php if ($foundCount > 0): ?>
                <?php foreach ($filtered as $product): ?>
                    <div class="product-card">
                        <!-- Эмодзи-иконка вместо изображения (упрощение для ЛР) -->
                        <div class="product-emoji"><?= $product['img'] ?></div>

                        <!-- Бренд -->
                        <div class="product-brand"><?= htmlspecialchars($product['brand']) ?></div>

                        <!-- Название -->
                        <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>

                        <!-- Рейтинг -->
                        <div class="product-rating">
                            <?= renderStars($product['rating']) ?>
                            <span class="rating-value"><?= $product['rating'] ?></span>
                        </div>

                        <!-- Цена + кнопка -->
                        <div class="product-footer">
                            <div class="product-price"><?= formatPrice($product['price']) ?></div>
                            <button class="btn-cart">В корзину</button>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <!-- Пустое состояние -->
                <div class="empty-state">
                    <span class="icon">🔍</span>
                    <h3>Ничего не найдено</h3>
                    <p>Попробуйте изменить параметры фильтра.<br>
                       Или <a href="?" style="color: var(--accent);">сбросьте фильтры</a>.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- ──── Отладочная панель: содержимое $_GET ──── -->
        <div class="debug-panel">
            <h3>🛠 Отладка — содержимое $_GET</h3>

            <code class="debug-url">
                <?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>
            </code>

            <?php if (!empty($_GET)): ?>
                <div class="debug-params">
                    <?php foreach ($_GET as $key => $value): ?>
                        <span class="debug-param">
                            <span class="key">$_GET['<?= htmlspecialchars($key) ?>']</span>
                            <span class="eq"> = </span>
                            <span class="val">"<?= htmlspecialchars($value) ?>"</span>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="margin-top: 10px; color: var(--text-dim); font-size: 13px;">
                    $_GET пуст — фильтры не применены
                </div>
            <?php endif; ?>
        </div>

        <!-- ──── Пояснение для проверяющего ──── -->
        <div class="info-box">
            <strong>Как работает GET-фильтрация:</strong><br>
            При отправке формы (method="GET") все поля добавляются в URL как строка запроса:<br>
            <code style="color: var(--accent); font-size: 12px;">
                ?category=audio&amp;min_price=3000&amp;max_price=15000&amp;sort=price_asc&amp;search=наушники
            </code><br><br>
            На сервере PHP читает значения через суперглобальный массив
            <strong>$_GET</strong>, применяет фильтры к массиву товаров и возвращает отфильтрованный результат.
            Ссылку с любым набором фильтров можно сохранить или поделиться.
        </div>

    </main>
</div>

</body>
</html>
