<?php
/**
 * Лабораторная работа №3
 * Задание 3: Форма регистрации (POST)
 *
 * Демонстрирует:
 *  - сбор нескольких полей формы через $_POST
 *  - многоуровневую валидацию (обязательность, длина, формат)
 *  - проверку совпадения паролей
 *  - «sticky form» — сохранение введённых данных при ошибке
 */

// ── Начальные значения ──
$fields = [
    'username'  => '',
    'email'     => '',
    'password'  => '',
    'password2' => '',
    'gender'    => '',
];

$errors  = [];   // массив ошибок
$success = false;

// ── Обработка POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Считываем все поля
    foreach ($fields as $key => $_) {
        $fields[$key] = trim($_POST[$key] ?? '');
    }

    // 2. Валидация имени пользователя
    if ($fields['username'] === '') {
        $errors['username'] = 'Имя пользователя обязательно.';
    } elseif (strlen($fields['username']) < 3) {
        $errors['username'] = 'Минимум 3 символа.';
    } elseif (!preg_match('/^[a-zA-Zа-яА-Я0-9_]+$/u', $fields['username'])) {
        $errors['username'] = 'Допустимы буквы, цифры и символ «_».';
    }

    // 3. Валидация e-mail
    if ($fields['email'] === '') {
        $errors['email'] = 'E-mail обязателен.';
    } elseif (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
        // FILTER_VALIDATE_EMAIL — встроенный PHP-фильтр для проверки формата e-mail
        $errors['email'] = 'Введите корректный e-mail.';
    }

    // 4. Валидация пароля
    if ($fields['password'] === '') {
        $errors['password'] = 'Пароль обязателен.';
    } elseif (strlen($fields['password']) < 6) {
        $errors['password'] = 'Пароль должен содержать минимум 6 символов.';
    }

    // 5. Подтверждение пароля
    if ($fields['password'] !== '' && $fields['password2'] === '') {
        $errors['password2'] = 'Подтвердите пароль.';
    } elseif ($fields['password'] !== $fields['password2']) {
        $errors['password2'] = 'Пароли не совпадают.';
    }

    // 6. Пол (radio — необязательно, но проверяем корректность значения)
    $allowedGenders = ['male', 'female', 'other'];
    if ($fields['gender'] !== '' && !in_array($fields['gender'], $allowedGenders, true)) {
        $errors['gender'] = 'Некорректное значение пола.';
    }

    // 7. Если ошибок нет — «регистрируем»
    if (empty($errors)) {
        $success = true;
        // В реальном проекте здесь:
        // - хэшируем пароль: password_hash($fields['password'], PASSWORD_DEFAULT)
        // - сохраняем данные в БД
        // - перенаправляем: header('Location: profile.php');
    }
}

// Вспомогательная функция: выводит сообщение об ошибке для поля
function fieldError(array $errors, string $field): string {
    if (!isset($errors[$field])) return '';
    $msg = htmlspecialchars($errors[$field], ENT_QUOTES, 'UTF-8');
    return "<span class=\"field-error\">{$msg}</span>";
}

// Вспомогательная функция: добавляет CSS-класс ошибки к input
function errorClass(array $errors, string $field): string {
    return isset($errors[$field]) ? 'error-input' : '';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 3 — Регистрация</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 25px 50px rgba(0,0,0,.5);
        }

        .badge {
            display: inline-block;
            background: #10b981;
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .25rem .75rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }

        h1 { font-size: 1.5rem; color: #f8fafc; margin-bottom: .4rem; }
        p.subtitle { color: #94a3b8; font-size: .88rem; margin-bottom: 2rem; }

        .form-group { margin-bottom: 1.3rem; }
        label { display: block; font-size: .85rem; color: #94a3b8; margin-bottom: .35rem; }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: .7rem 1rem;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: .95rem;
            outline: none;
            transition: border-color .2s;
        }
        input:focus { border-color: #10b981; }
        input.error-input { border-color: #ef4444; }

        .field-error {
            display: block;
            color: #f87171;
            font-size: .78rem;
            margin-top: .35rem;
        }

        /* Прогресс пароля */
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #334155;
            margin-top: .5rem;
            overflow: hidden;
        }
        .strength-fill {
            height: 100%;
            width: 0;
            transition: width .3s, background .3s;
            border-radius: 2px;
        }
        .strength-label { font-size: .75rem; color: #64748b; margin-top: .3rem; }

        /* Radio группа */
        .radio-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .88rem;
            color: #cbd5e1;
            cursor: pointer;
        }
        .radio-option input { width: auto; cursor: pointer; }

        button[type="submit"] {
            width: 100%;
            padding: .8rem;
            background: #10b981;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: .5rem;
            transition: background .2s;
        }
        button[type="submit"]:hover { background: #059669; }

        /* Сообщения */
        .alert {
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: .9rem;
        }
        .alert-success {
            background: #052e16;
            border: 1px solid #22c55e;
            color: #86efac;
            line-height: 1.7;
        }
        .alert-error {
            background: #450a0a;
            border: 1px solid #ef4444;
            color: #fca5a5;
        }
        .success-icon { font-size: 2.5rem; text-align: center; margin-bottom: .75rem; }

        /* Таблица принятых данных */
        .data-table {
            margin-top: 1rem;
            background: #0f172a;
            border-radius: 8px;
            overflow: hidden;
        }
        .data-table table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        .data-table td { padding: .5rem .75rem; border-bottom: 1px solid #1e293b; }
        .data-table td:first-child { color: #94a3b8; width: 45%; }
        .data-table td:last-child  { color: #a7f3d0; }

        .nav { margin-top: 2rem; text-align: center; }
        .nav a { color: #10b981; text-decoration: none; font-size: .85rem; margin: 0 .5rem; }
        .nav a:hover { text-decoration: underline; }

        /* Счётчик символов */
        .char-count { font-size: .75rem; color: #64748b; float: right; }
    </style>
</head>
<body>
<div class="card">

    <span class="badge">POST — Регистрация</span>
    <h1>Задание 3 — Форма регистрации</h1>
    <p class="subtitle">
        Многоуровневая валидация на стороне сервера. Данные сохраняются в полях при ошибке&nbsp;(«sticky form»).
    </p>

    <!-- ── Блок успеха ── -->
    <?php if ($success): ?>
        <div class="alert alert-success">
            <div class="success-icon">🎉</div>
            <strong>Регистрация успешна!</strong><br>
            Добро пожаловать, <strong><?= htmlspecialchars($fields['username']) ?></strong>!
        </div>

        <!-- Показываем принятые данные из $_POST -->
        <div class="data-table">
            <table>
                <tr><td>Имя пользователя</td><td><?= htmlspecialchars($fields['username']) ?></td></tr>
                <tr><td>E-mail</td><td><?= htmlspecialchars($fields['email']) ?></td></tr>
                <tr><td>Пол</td>
                    <td><?php
                        $genderMap = ['male' => 'Мужской', 'female' => 'Женский', 'other' => 'Другой'];
                        echo $genderMap[$fields['gender']] ?? 'Не указан';
                    ?></td>
                </tr>
                <tr><td>Пароль (хэш)</td>
                    <td style="font-size:.75rem;word-break:break-all;color:#6ee7b7;">
                        <?= password_hash($fields['password'], PASSWORD_DEFAULT) ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="nav">
            <a href="task3_register.php">← Назад к форме</a>
            <a href="task4_pizza.php">→ Задание 4: Пицца</a>
        </div>

    <?php else: ?>

    <!-- ── Форма регистрации ── -->
    <form method="post" action="" novalidate>
        <!--
            novalidate — отключаем браузерную валидацию, чтобы демонстрировать
            исключительно серверную валидацию PHP
        -->

        <!-- Имя пользователя -->
        <div class="form-group">
            <label for="username">
                Имя пользователя
                <span class="char-count" id="un-count">0 / 20</span>
            </label>
            <input
                type="text"
                id="username"
                name="username"
                maxlength="20"
                placeholder="Минимум 3 символа"
                class="<?= errorClass($errors, 'username') ?>"
                value="<?= htmlspecialchars($fields['username']) ?>"
                oninput="document.getElementById('un-count').textContent = this.value.length + ' / 20'">
            <?= fieldError($errors, 'username') ?>
        </div>

        <!-- E-mail -->
        <div class="form-group">
            <label for="email">E-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="example@mail.ru"
                class="<?= errorClass($errors, 'email') ?>"
                value="<?= htmlspecialchars($fields['email']) ?>">
            <?= fieldError($errors, 'email') ?>
        </div>

        <!-- Пароль -->
        <div class="form-group">
            <label for="password">Пароль</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Минимум 6 символов"
                class="<?= errorClass($errors, 'password') ?>"
                oninput="checkStrength(this.value)">
            <!-- Индикатор надёжности пароля -->
            <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
            <span class="strength-label" id="strength-label">Введите пароль</span>
            <?= fieldError($errors, 'password') ?>
        </div>

        <!-- Подтверждение пароля -->
        <div class="form-group">
            <label for="password2">Подтверждение пароля</label>
            <input
                type="password"
                id="password2"
                name="password2"
                placeholder="Повторите пароль"
                class="<?= errorClass($errors, 'password2') ?>">
            <?= fieldError($errors, 'password2') ?>
        </div>

        <!-- Пол (radio buttons) -->
        <div class="form-group">
            <label>Пол (необязательно)</label>
            <div class="radio-group">
                <?php
                // Формируем radio-кнопки в цикле
                $genderOptions = ['male' => 'Мужской', 'female' => 'Женский', 'other' => 'Другой'];
                foreach ($genderOptions as $val => $label):
                    $checked = ($fields['gender'] === $val) ? 'checked' : '';
                ?>
                <label class="radio-option">
                    <input type="radio" name="gender" value="<?= $val ?>" <?= $checked ?>>
                    <?= $label ?>
                </label>
                <?php endforeach; ?>
            </div>
            <?= fieldError($errors, 'gender') ?>
        </div>

        <button type="submit">Зарегистрироваться</button>
    </form>

    <nav class="nav">
        <a href="task2_auth.php">← Задание 2</a>
        <a href="task4_pizza.php">→ Задание 4: Заказ пиццы</a>
    </nav>
    <?php endif; ?>

</div>

<script>
// Индикатор надёжности пароля (только визуальный — клиентский JS)
function checkStrength(val) {
    const fill  = document.getElementById('strength-fill');
    const label = document.getElementById('strength-label');
    let score = 0;

    if (val.length >= 6)  score++;          // длина
    if (val.length >= 10) score++;          // длинный
    if (/[A-ZА-Я]/.test(val)) score++;     // заглавные
    if (/[0-9]/.test(val))    score++;     // цифры
    if (/[^a-zA-Zа-яА-Я0-9]/.test(val)) score++; // спецсимволы

    const levels = [
        { pct: '0%',   color: '#475569', text: 'Введите пароль' },
        { pct: '20%',  color: '#ef4444', text: 'Очень слабый' },
        { pct: '40%',  color: '#f97316', text: 'Слабый' },
        { pct: '60%',  color: '#eab308', text: 'Средний' },
        { pct: '80%',  color: '#22c55e', text: 'Сильный' },
        { pct: '100%', color: '#10b981', text: 'Очень сильный' },
    ];

    const lv = val.length === 0 ? levels[0] : levels[Math.min(score, 5)];
    fill.style.width      = lv.pct;
    fill.style.background = lv.color;
    label.textContent     = lv.text;
    label.style.color     = lv.color;
}
</script>
</body>
</html>
