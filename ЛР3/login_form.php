<?php
/**
 * Лабораторная работа №3
 * Задание 2: Форма авторизации (POST-запрос)
 *
 * Демонстрирует:
 *  - приём данных через $_POST
 *  - проверку метода запроса ($_SERVER['REQUEST_METHOD'])
 *  - базовую валидацию логина и пароля
 *  - сравнение с «захардкоженными» учётными данными (учебный пример)
 */

// ── Учётные данные (в реальном проекте берутся из БД) ──
$validUsers = [
    'admin'   => 'admin123',
    'student' => 'php2024',
    'user'    => 'qwerty',
];

// ── Переменные состояния ──
$login   = '';        // сохраняем логин для повторного заполнения поля
$errors  = [];        // массив ошибок валидации
$success = false;     // флаг успешной авторизации
$role    = '';        // роль авторизованного пользователя (для демонстрации)

// ── Обработка POST-запроса ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Считываем и «очищаем» данные из $_POST
    $login    = trim($_POST['login']    ?? '');
    $password = trim($_POST['password'] ?? '');

    // 2. Валидация: пустые поля
    if ($login === '') {
        $errors[] = 'Логин не может быть пустым.';
    }
    if ($password === '') {
        $errors[] = 'Пароль не может быть пустым.';
    }

    // 3. Проверка учётных данных (только если нет ошибок)
    if (empty($errors)) {
        if (array_key_exists($login, $validUsers) && $validUsers[$login] === $password) {
            // Авторизация успешна
            $success = true;
            // Определяем «роль» для демонстрации
            $role = ($login === 'admin') ? 'Администратор' : 'Пользователь';
        } else {
            $errors[] = 'Неверный логин или пароль.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 2 — Авторизация</title>
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
            max-width: 460px;
            box-shadow: 0 25px 50px rgba(0,0,0,.5);
        }

        .badge {
            display: inline-block;
            background: #8b5cf6;
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

        /* Подсказка с доступными логинами */
        .hint {
            background: #1e3a5f;
            border: 1px solid #2563eb;
            border-radius: 10px;
            padding: .9rem 1.1rem;
            margin-bottom: 1.75rem;
            font-size: .82rem;
            color: #93c5fd;
            line-height: 1.6;
        }
        .hint strong { color: #bfdbfe; }

        /* Форма */
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-size: .85rem; color: #94a3b8; margin-bottom: .35rem; }

        input[type="text"],
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
        input:focus { border-color: #8b5cf6; }
        input.error-input { border-color: #ef4444; }

        /* Чекбокс «Показать пароль» */
        .show-pass {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-top: .5rem;
            font-size: .8rem;
            color: #64748b;
            cursor: pointer;
        }
        .show-pass input { width: auto; }

        button[type="submit"] {
            width: 100%;
            padding: .8rem;
            background: #8b5cf6;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: .5rem;
            transition: background .2s;
        }
        button[type="submit"]:hover { background: #7c3aed; }

        /* Сообщения */
        .alert {
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: .9rem;
            line-height: 1.5;
        }
        .alert-error {
            background: #450a0a;
            border: 1px solid #ef4444;
            color: #fca5a5;
        }
        .alert-success {
            background: #052e16;
            border: 1px solid #22c55e;
            color: #86efac;
        }
        .alert ul { padding-left: 1.2rem; margin-top: .5rem; }
        .alert li { margin-bottom: .25rem; }

        .success-icon { font-size: 2.5rem; text-align: center; margin-bottom: .75rem; }

        .nav { margin-top: 2rem; text-align: center; }
        .nav a { color: #8b5cf6; text-decoration: none; font-size: .85rem; margin: 0 .5rem; }
        .nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">

    <span class="badge">POST-запрос</span>
    <h1>Задание 2 — Авторизация</h1>
    <p class="subtitle">
        POST-запрос скрывает данные от адресной строки &mdash; идеален для передачи паролей.
    </p>

    <!-- Подсказка для учебного примера -->
    <div class="hint">
        <strong>Доступные учётные записи:</strong><br>
        admin / admin123 &nbsp;|&nbsp; student / php2024 &nbsp;|&nbsp; user / qwerty
    </div>

    <!-- ── Блок ошибок ── -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Ошибка входа:</strong>
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- ── Успешная авторизация ── -->
    <?php if ($success): ?>
        <div class="alert alert-success">
            <div class="success-icon">✅</div>
            <strong>Добро пожаловать, <?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?>!</strong><br>
            Роль: <em><?= htmlspecialchars($role) ?></em><br>
            Авторизация прошла успешно.
        </div>
    <?php endif; ?>

    <!-- ── Форма авторизации ── -->
    <?php if (!$success): ?>
    <form method="post" action="">
        <!--
            method="post" — данные передаются в теле HTTP-запроса, не в URL.
            action=""     — отправляем на тот же файл.
        -->

        <div class="form-group">
            <label for="login">Логин</label>
            <input
                type="text"
                id="login"
                name="login"
                placeholder="Введите логин"
                autocomplete="username"
                class="<?= !empty($errors) ? 'error-input' : '' ?>"
                value="<?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?>">
            <!-- Сохраняем логин в value, чтобы пользователь не вводил повторно -->
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Введите пароль"
                autocomplete="current-password"
                class="<?= !empty($errors) ? 'error-input' : '' ?>">

            <!-- Функция «Показать пароль» — только JS, данные не меняются -->
            <label class="show-pass">
                <input type="checkbox" onchange="
                    var p = document.getElementById('password');
                    p.type = this.checked ? 'text' : 'password';
                ">
                Показать пароль
            </label>
        </div>

        <button type="submit">Войти</button>
    </form>
    <?php else: ?>
        <!-- Кнопка «Выйти» — просто перезагружает страницу -->
        <form method="get" action="">
            <button type="submit" style="background:#475569;">Выйти (сбросить)</button>
        </form>
    <?php endif; ?>

    <nav class="nav">
        <a href="task1_get.php">← Задание 1</a>
        <a href="task3_register.php">→ Задание 3: Регистрация</a>
    </nav>
</div>
</body>
</html>
