<?php
/**
 * Лабораторная работа №3
 * Задание 1: Передача данных через адресную строку (GET-запрос)
 *
 * Демонстрирует:
 *  - формирование GET-ссылок вручную
 *  - чтение данных из суперглобального массива $_GET
 *  - базовую защиту от XSS через htmlspecialchars()
 */

// ──────────────────────────────────────────────
// 1. Обработка GET-параметров (если они пришли)
// ──────────────────────────────────────────────

$name = '';   // имя пользователя
$city = '';   // город
$age  = '';   // возраст
$message = ''; // итоговое сообщение для вывода

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {

    // Читаем параметры; isset() — проверка существования ключа
    $name = isset($_GET['name']) ? trim($_GET['name']) : '';
    $city = isset($_GET['city']) ? trim($_GET['city']) : '';
    $age  = isset($_GET['age'])  ? (int)$_GET['age']  : 0;

    // Формируем сообщение только если хотя бы имя передано
    if ($name !== '') {
        // htmlspecialchars() преобразует спец-символы HTML → безопасный вывод
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeCity = htmlspecialchars($city, ENT_QUOTES, 'UTF-8');

        $message  = "Привет, <strong>{$safeName}</strong>!";
        $message .= $safeCity ? " Ты из города <em>{$safeCity}</em>." : '';
        $message .= $age > 0  ? " Тебе <strong>{$age}</strong> лет." : '';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 1 — GET-запрос</title>
    <style>
        /* ── Базовые стили ── */
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
            max-width: 600px;
            box-shadow: 0 25px 50px rgba(0,0,0,.5);
        }

        /* Заголовок */
        .badge {
            display: inline-block;
            background: #0ea5e9;
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .25rem .75rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }

        h1 { font-size: 1.5rem; color: #f8fafc; margin-bottom: .5rem; }
        p.subtitle { color: #94a3b8; font-size: .9rem; margin-bottom: 2rem; }

        /* Блок с URL */
        .url-block {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.75rem;
        }
        .url-block p { font-size: .78rem; color: #64748b; margin-bottom: .5rem; }
        .url-block a {
            color: #38bdf8;
            font-size: .85rem;
            word-break: break-all;
            text-decoration: none;
        }
        .url-block a:hover { text-decoration: underline; }

        /* Форма */
        label { display: block; font-size: .85rem; color: #94a3b8; margin-bottom: .35rem; }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: .65rem 1rem;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: .95rem;
            margin-bottom: 1.1rem;
            outline: none;
            transition: border-color .2s;
        }
        input:focus { border-color: #0ea5e9; }

        button {
            width: 100%;
            padding: .75rem;
            background: #0ea5e9;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background .2s;
        }
        button:hover { background: #0284c7; }

        /* Результат */
        .result {
            margin-top: 1.75rem;
            padding: 1.25rem;
            background: #134e4a;
            border: 1px solid #14b8a6;
            border-radius: 10px;
            color: #99f6e4;
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Блок с $_GET-дампом */
        .dump {
            margin-top: 1.25rem;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 10px;
            padding: 1rem 1.25rem;
        }
        .dump h3 { font-size: .8rem; color: #64748b; margin-bottom: .6rem; letter-spacing: .05em; }
        .dump table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        .dump td { padding: .35rem .5rem; border-bottom: 1px solid #1e293b; }
        .dump td:first-child { color: #f472b6; width: 40%; }
        .dump td:last-child  { color: #86efac; }

        .nav { margin-top: 2rem; text-align: center; }
        .nav a {
            color: #0ea5e9; text-decoration: none; font-size: .85rem;
            margin: 0 .5rem;
        }
        .nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">

    <span class="badge">GET-запрос</span>
    <h1>Задание 1 — Передача данных через URL</h1>
    <p class="subtitle">
        Данные передаются в адресной строке браузера в виде пар <code>ключ=значение</code>,
        разделённых символом <code>&amp;</code>.
    </p>

    <!-- ──────────────────────────────────── -->
    <!-- Примеры готовых GET-ссылок          -->
    <!-- ──────────────────────────────────── -->
    <div class="url-block">
        <p>Примеры GET-ссылок (нажмите, чтобы попробовать):</p>
        <a href="?name=Алиса&city=Москва&age=21">?name=Алиса&amp;city=Москва&amp;age=21</a><br><br>
        <a href="?name=Иван&city=Санкт-Петербург&age=35">?name=Иван&amp;city=Санкт-Петербург&amp;age=35</a>
    </div>

    <!-- ──────────────────────────────────── -->
    <!-- Форма с method="get"                -->
    <!-- ──────────────────────────────────── -->
    <form method="get" action="">

        <label for="name">Ваше имя</label>
        <!-- value="..." — сохраняем введённое значение после отправки -->
        <input type="text" id="name" name="name"
               placeholder="Введите имя"
               value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">

        <label for="city">Город</label>
        <input type="text" id="city" name="city"
               placeholder="Введите город"
               value="<?= htmlspecialchars($city, ENT_QUOTES, 'UTF-8') ?>">

        <label for="age">Возраст</label>
        <input type="number" id="age" name="age"
               placeholder="Введите возраст" min="1" max="120"
               value="<?= (int)$age ?: '' ?>">

        <button type="submit">Отправить GET-запрос</button>
    </form>

    <!-- ──────────────────────────────────── -->
    <!-- Блок результата (только если есть)  -->
    <!-- ──────────────────────────────────── -->
    <?php if ($message): ?>
        <div class="result">
            <?= $message /* уже безопасно — htmlspecialchars применён выше */ ?>
        </div>

        <!-- Таблица: содержимое $_GET -->
        <div class="dump">
            <h3>СОДЕРЖИМОЕ $_GET</h3>
            <table>
                <?php foreach ($_GET as $key => $value): ?>
                <tr>
                    <td>$_GET['<?= htmlspecialchars($key) ?>']</td>
                    <td><?= htmlspecialchars($value) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>

    <nav class="nav">
        <a href="task2_auth.php">→ Задание 2: Авторизация</a>
    </nav>
</div>
</body>
</html>
