<?php
/**
 * Лабораторная работа №3
 * Задание 4: Форма заказа пиццы (работа с различными типами элементов)
 *
 * Демонстрирует обработку:
 *  - текстовых полей (text, tel)
 *  - radio buttons (выбор размера)
 *  - checkboxes (топпинги — множественный выбор)
 *  - select (тип теста)
 *  - range (количество)
 *  - textarea (комментарий)
 */

// ── Справочники (константы меню) ──
const SIZES = [
    'small'  => ['label' => 'Маленькая (25 см)',  'price' => 299],
    'medium' => ['label' => 'Средняя (30 см)',    'price' => 449],
    'large'  => ['label' => 'Большая (35 см)',    'price' => 599],
];

const TOPPINGS = [
    'cheese'     => ['label' => '🧀 Дополнительный сыр', 'price' => 79],
    'mushrooms'  => ['label' => '🍄 Грибы',              'price' => 69],
    'pepperoni'  => ['label' => '🍕 Пепперони',          'price' => 99],
    'olives'     => ['label' => '🫒 Оливки',             'price' => 59],
    'jalapeno'   => ['label' => '🌶️ Халапеньо',         'price' => 59],
    'pineapple'  => ['label' => '🍍 Ананас',             'price' => 49],
];

const CRUSTS = [
    'thin'    => 'Тонкое',
    'thick'   => 'Толстое',
    'stuffed' => 'С начинкой из сыра',
];

// ── Начальные значения ──
$data = [
    'name'     => '',
    'phone'    => '',
    'size'     => 'medium',   // значение по умолчанию
    'toppings' => [],          // массив выбранных топпингов
    'crust'    => 'thin',
    'quantity' => 1,
    'comment'  => '',
];

$errors  = [];
$success = false;
$total   = 0;   // итоговая цена

// ── Обработка POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Считываем поля
    $data['name']     = trim($_POST['name']     ?? '');
    $data['phone']    = trim($_POST['phone']    ?? '');
    $data['size']     = trim($_POST['size']     ?? '');
    $data['crust']    = trim($_POST['crust']    ?? '');
    $data['comment']  = trim($_POST['comment']  ?? '');
    $data['quantity'] = max(1, min(10, (int)($_POST['quantity'] ?? 1)));

    // 2. Checkboxes: $_POST['toppings'] — массив выбранных значений
    //    Если ни один не выбран — ключ вообще не попадёт в $_POST
    $rawToppings = $_POST['toppings'] ?? [];
    // Оставляем только допустимые значения (защита от подмены)
    $data['toppings'] = array_filter($rawToppings, fn($t) => array_key_exists($t, TOPPINGS));

    // 3. Валидация
    if ($data['name'] === '') {
        $errors['name'] = 'Введите ваше имя.';
    }

    if ($data['phone'] === '') {
        $errors['phone'] = 'Введите номер телефона.';
    } elseif (!preg_match('/^[\d\+\-\(\) ]{7,15}$/', $data['phone'])) {
        $errors['phone'] = 'Некорректный формат телефона.';
    }

    if (!array_key_exists($data['size'], SIZES)) {
        $errors['size'] = 'Выберите размер пиццы.';
    }

    if (!array_key_exists($data['crust'], CRUSTS)) {
        $errors['crust'] = 'Выберите тип теста.';
    }

    // 4. Подсчёт суммы (только если нет ошибок)
    if (empty($errors)) {
        $basePrice = SIZES[$data['size']]['price'];

        $toppingPrice = 0;
        foreach ($data['toppings'] as $t) {
            $toppingPrice += TOPPINGS[$t]['price'];
        }

        $total   = ($basePrice + $toppingPrice) * $data['quantity'];
        $success = true;
    }
}

// ── Хелперы ──
function fe(array $errors, string $field): string {
    if (!isset($errors[$field])) return '';
    return '<span class="field-error">' . htmlspecialchars($errors[$field]) . '</span>';
}
function ec(array $errors, string $field): string {
    return isset($errors[$field]) ? ' error-input' : '';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 4 — Заказ пиццы</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #1a0a00;
            background-image: radial-gradient(ellipse at 20% 50%, #3d1a0020 0%, transparent 60%),
                              radial-gradient(ellipse at 80% 20%, #7c2d1220 0%, transparent 60%);
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem;
        }

        .card {
            background: #1c1008;
            border: 1px solid #7c3512;
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 680px;
            box-shadow: 0 25px 60px rgba(0,0,0,.7);
        }

        .badge {
            display: inline-block;
            background: #ea580c;
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .25rem .75rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }

        h1 { font-size: 1.6rem; color: #fdba74; margin-bottom: .4rem; }
        p.subtitle { color: #92400e; font-size: .88rem; margin-bottom: 2rem; }

        /* Секции формы */
        .section {
            border-top: 1px solid #2d1a0a;
            padding-top: 1.5rem;
            margin-top: 1.5rem;
        }
        .section-title {
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #ea580c;
            margin-bottom: 1rem;
        }

        .form-group { margin-bottom: 1.2rem; }
        label.field-label {
            display: block;
            font-size: .85rem;
            color: #9ca3af;
            margin-bottom: .35rem;
        }

        input[type="text"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: .7rem 1rem;
            background: #0d0700;
            border: 1px solid #44220a;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: .95rem;
            outline: none;
            transition: border-color .2s;
        }
        input:focus, select:focus, textarea:focus { border-color: #ea580c; }
        input.error-input, select.error-input { border-color: #ef4444; }

        .field-error { display: block; color: #f87171; font-size: .78rem; margin-top: .35rem; }

        textarea { resize: vertical; min-height: 90px; }

        /* Radio — размер пиццы */
        .size-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .75rem;
        }
        .size-option {
            position: relative;
        }
        .size-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0; height: 0;
        }
        .size-option label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: .9rem .5rem;
            background: #0d0700;
            border: 2px solid #44220a;
            border-radius: 12px;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            text-align: center;
            gap: .3rem;
        }
        .size-option input:checked + label {
            border-color: #ea580c;
            background: #1f0e00;
        }
        .size-option label:hover { border-color: #7c3512; }
        .size-icon { font-size: 1.6rem; }
        .size-name { font-size: .8rem; color: #d1d5db; }
        .size-price { font-size: .9rem; color: #fb923c; font-weight: 700; }

        /* Checkboxes — топпинги */
        .toppings-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: .6rem;
        }
        .topping-option {
            position: relative;
        }
        .topping-option input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0; height: 0;
        }
        .topping-option label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .6rem 1rem;
            background: #0d0700;
            border: 1px solid #44220a;
            border-radius: 8px;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            font-size: .85rem;
        }
        .topping-option input:checked + label {
            border-color: #ea580c;
            background: #1f0e00;
            color: #fdba74;
        }
        .topping-option label:hover { border-color: #7c3512; }
        .topping-price { color: #fb923c; font-weight: 600; font-size: .8rem; }

        /* Range — количество */
        .range-wrap {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        input[type="range"] {
            flex: 1;
            padding: 0;
            background: transparent;
            border: none;
            -webkit-appearance: none;
        }
        input[type="range"]::-webkit-slider-runnable-track {
            height: 6px;
            border-radius: 3px;
            background: linear-gradient(to right, #ea580c var(--val,10%), #44220a var(--val,10%));
        }
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 20px; height: 20px;
            border-radius: 50%;
            background: #ea580c;
            margin-top: -7px;
            cursor: pointer;
        }
        .range-value {
            min-width: 2.5rem;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 700;
            color: #fdba74;
        }

        /* Итого */
        .order-summary {
            background: #0d0700;
            border: 1px solid #7c3512;
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1.5rem;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: .88rem;
            color: #9ca3af;
            padding: .3rem 0;
            border-bottom: 1px solid #1c1008;
        }
        .summary-row:last-child { border-bottom: none; }
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.1rem;
            font-weight: 700;
            color: #fdba74;
            padding-top: .75rem;
            margin-top: .5rem;
            border-top: 1px solid #7c3512;
        }

        button[type="submit"] {
            width: 100%;
            padding: .9rem;
            background: linear-gradient(135deg, #ea580c, #dc2626);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            margin-top: 1.5rem;
            letter-spacing: .03em;
            transition: opacity .2s;
        }
        button[type="submit"]:hover { opacity: .88; }

        /* Успешный заказ */
        .success-card {
            background: #052e16;
            border: 1px solid #22c55e;
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            color: #86efac;
        }
        .success-card h2 { color: #4ade80; margin-bottom: .75rem; }
        .order-detail {
            font-size: .88rem;
            line-height: 1.8;
        }
        .order-detail span { color: #bbf7d0; font-weight: 600; }

        .nav { margin-top: 2rem; text-align: center; }
        .nav a { color: #ea580c; text-decoration: none; font-size: .85rem; margin: 0 .5rem; }
        .nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">

    <span class="badge">🍕 POST-форма</span>
    <h1>Задание 4 — Заказ пиццы</h1>
    <p class="subtitle">
        Демонстрация работы с radio, checkbox, select, range и textarea через $_POST
    </p>

    <!-- ── Блок успеха ── -->
    <?php if ($success): ?>
        <div class="success-card">
            <h2>✅ Заказ принят!</h2>
            <div class="order-detail">
                Клиент: <span><?= htmlspecialchars($data['name']) ?></span><br>
                Телефон: <span><?= htmlspecialchars($data['phone']) ?></span><br>
                Размер: <span><?= SIZES[$data['size']]['label'] ?></span><br>
                Тесто: <span><?= CRUSTS[$data['crust']] ?></span><br>

                <?php if (!empty($data['toppings'])): ?>
                Топпинги:
                <span>
                    <?php
                    $toppingLabels = array_map(
                        fn($t) => TOPPINGS[$t]['label'],
                        $data['toppings']
                    );
                    echo implode(', ', $toppingLabels);
                    ?>
                </span><br>
                <?php endif; ?>

                Количество: <span><?= $data['quantity'] ?> шт.</span><br>

                <?php if ($data['comment']): ?>
                Комментарий: <span><?= htmlspecialchars($data['comment']) ?></span><br>
                <?php endif; ?>

                <br>
                <strong style="font-size:1.1rem;color:#4ade80;">
                    Итого: <?= number_format($total, 0, '.', ' ') ?> ₽
                </strong>
            </div>
        </div>

        <!-- Показываем содержимое $_POST (учебный блок) -->
        <details style="margin-bottom:1rem;">
            <summary style="cursor:pointer;color:#92400e;font-size:.85rem;">
                Содержимое $_POST (учебный вывод)
            </summary>
            <pre style="margin-top:.75rem;background:#0d0700;padding:1rem;border-radius:8px;
                        font-size:.78rem;color:#86efac;overflow-x:auto;"><?php
                // var_export — печатает PHP-представление переменной
                var_export($_POST);
            ?></pre>
        </details>

        <form method="get" action="">
            <button type="submit" style="background:#44220a;">← Оформить новый заказ</button>
        </form>

    <?php else: ?>

    <!-- ── Форма заказа ── -->
    <form method="post" action="" novalidate id="orderForm">

        <!-- Контактные данные -->
        <div class="section">
            <div class="section-title">Контактные данные</div>

            <div class="form-group">
                <label class="field-label" for="name">Имя</label>
                <input type="text" id="name" name="name"
                       placeholder="Ваше имя"
                       class="<?= ec($errors, 'name') ?>"
                       value="<?= htmlspecialchars($data['name']) ?>">
                <?= fe($errors, 'name') ?>
            </div>

            <div class="form-group">
                <label class="field-label" for="phone">Телефон</label>
                <input type="tel" id="phone" name="phone"
                       placeholder="+7 (999) 123-45-67"
                       class="<?= ec($errors, 'phone') ?>"
                       value="<?= htmlspecialchars($data['phone']) ?>">
                <?= fe($errors, 'phone') ?>
            </div>
        </div>

        <!-- Выбор размера (radio buttons) -->
        <div class="section">
            <div class="section-title">Размер пиццы</div>
            <?= fe($errors, 'size') ?>

            <div class="size-grid">
                <?php
                $sizeIcons = ['small' => '🍕', 'medium' => '🍕🍕', 'large' => '🍕🍕🍕'];
                foreach (SIZES as $val => $info):
                    $checked = ($data['size'] === $val) ? 'checked' : '';
                ?>
                <div class="size-option">
                    <input type="radio" id="size_<?= $val ?>" name="size"
                           value="<?= $val ?>" <?= $checked ?>>
                    <label for="size_<?= $val ?>">
                        <span class="size-icon"><?= $sizeIcons[$val] ?></span>
                        <span class="size-name"><?= $info['label'] ?></span>
                        <span class="size-price"><?= $info['price'] ?> ₽</span>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Топпинги (checkboxes) -->
        <div class="section">
            <div class="section-title">Дополнительные топпинги</div>
            <!--
                name="toppings[]" — квадратные скобки говорят PHP, что это массив.
                $_POST['toppings'] будет массивом выбранных значений.
                Если ничего не выбрано — ключ 'toppings' в $_POST отсутствует.
            -->
            <div class="toppings-grid">
                <?php foreach (TOPPINGS as $val => $info):
                    $checked = in_array($val, $data['toppings']) ? 'checked' : '';
                ?>
                <div class="topping-option">
                    <input type="checkbox" id="top_<?= $val ?>"
                           name="toppings[]" value="<?= $val ?>" <?= $checked ?>>
                    <label for="top_<?= $val ?>">
                        <span><?= $info['label'] ?></span>
                        <span class="topping-price">+<?= $info['price'] ?> ₽</span>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Тип теста (select) -->
        <div class="section">
            <div class="section-title">Тип теста</div>
            <div class="form-group">
                <label class="field-label" for="crust">Выберите тесто</label>
                <!--
                    select — выпадающий список. selected — атрибут выбранного option.
                -->
                <select id="crust" name="crust" class="<?= ec($errors, 'crust') ?>">
                    <?php foreach (CRUSTS as $val => $label):
                        $selected = ($data['crust'] === $val) ? 'selected' : '';
                    ?>
                    <option value="<?= $val ?>" <?= $selected ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <?= fe($errors, 'crust') ?>
            </div>
        </div>

        <!-- Количество (range) -->
        <div class="section">
            <div class="section-title">Количество</div>
            <div class="form-group">
                <!--
                    input[type="range"] — ползунок. min/max/step задают диапазон.
                    Значение передаётся в $_POST как обычная строка.
                -->
                <div class="range-wrap">
                    <input type="range" id="quantity" name="quantity"
                           min="1" max="10" step="1"
                           value="<?= (int)$data['quantity'] ?>"
                           oninput="
                               document.getElementById('qty-val').textContent = this.value;
                               this.style.setProperty('--val', (this.value-1)/9*100 + '%');
                           "
                           style="--val: <?= ($data['quantity']-1)/9*100 ?>%">
                    <span class="range-value" id="qty-val"><?= (int)$data['quantity'] ?></span>
                </div>
            </div>
        </div>

        <!-- Комментарий (textarea) -->
        <div class="section">
            <div class="section-title">Комментарий к заказу</div>
            <div class="form-group">
                <label class="field-label" for="comment">Пожелания (необязательно)</label>
                <textarea id="comment" name="comment"
                          placeholder="Например: без лука, позвонить за 10 минут..."
                          ><?= htmlspecialchars($data['comment']) ?></textarea>
            </div>
        </div>

        <!-- Предварительный итог (JavaScript) -->
        <div class="order-summary" id="liveSummary" style="display:none;">
            <div class="summary-row">
                <span>Пицца</span>
                <span id="s-size">—</span>
            </div>
            <div class="summary-row">
                <span>Топпинги</span>
                <span id="s-top">—</span>
            </div>
            <div class="summary-row">
                <span>Количество</span>
                <span id="s-qty">—</span>
            </div>
            <div class="summary-total">
                <span>Итого</span>
                <span id="s-total">—</span>
            </div>
        </div>

        <button type="submit">🍕 Оформить заказ</button>
    </form>

    <?php endif; ?>

    <nav class="nav">
        <a href="task3_register.php">← Задание 3</a>
        <a href="task5_filter.php">→ Задание 5: Фильтрация</a>
    </nav>
</div>

<script>
// ── Живой подсчёт стоимости (клиентский JS) ──

// Цены из PHP (безопасно вставляем как JSON)
const SIZES_JS = <?= json_encode(array_map(fn($s) => $s['price'], SIZES)) ?>;
const TOPS_JS  = <?= json_encode(array_map(fn($t) => $t['price'], TOPPINGS)) ?>;

function updateSummary() {
    const form   = document.getElementById('orderForm');
    if (!form) return;

    // Размер
    const sizeEl = form.querySelector('input[name="size"]:checked');
    const size   = sizeEl ? sizeEl.value : null;
    const sizeP  = size ? SIZES_JS[size] : 0;

    // Топпинги
    const topEls = Array.from(form.querySelectorAll('input[name="toppings[]"]:checked'));
    const topP   = topEls.reduce((s, el) => s + (TOPS_JS[el.value] || 0), 0);

    // Количество
    const qty = parseInt(form.querySelector('#quantity').value) || 1;

    const total = (sizeP + topP) * qty;

    // Обновляем UI
    const summary = document.getElementById('liveSummary');
    if (sizeP > 0) {
        summary.style.display = 'block';
        document.getElementById('s-size').textContent = sizeP + ' ₽';
        document.getElementById('s-top').textContent  = topP > 0 ? '+' + topP + ' ₽' : '—';
        document.getElementById('s-qty').textContent  = '× ' + qty;
        document.getElementById('s-total').textContent = total.toLocaleString('ru-RU') + ' ₽';
    }
}

// Вешаем обработчики на все поля
document.querySelectorAll('input[name="size"], input[name="toppings[]"], #quantity')
        .forEach(el => el.addEventListener('change', updateSummary));
document.getElementById('quantity').addEventListener('input', updateSummary);

updateSummary(); // первичный расчёт
</script>
</body>
</html>
