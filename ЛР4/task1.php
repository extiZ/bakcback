<?php
/**
 * Задание №1: Демонстрация механизма работы с сессиями в PHP
 * Лабораторная работа: Управление состоянием веб-приложений
 */

// Запуск сессии — должен быть ДО любого вывода HTML
session_start();

$action  = $_GET['action'] ?? '';
$message = '';

// --- Обработка действий ---
if ($action === 'set') {
    // Записываем данные в сессию
    $_SESSION['username']  = 'Иван Петров';
    $_SESSION['role']      = 'student';
    $_SESSION['visits']    = ($_SESSION['visits'] ?? 0) + 1;
    $_SESSION['last_visit'] = date('d.m.Y H:i:s');
    $message = '✅ Данные успешно записаны в сессию!';

} elseif ($action === 'delete_key') {
    // Удаляем конкретный ключ из сессии
    $key = $_GET['key'] ?? '';
    if ($key && isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
        $message = "🗑️ Ключ «{$key}» удалён из сессии.";
    }

} elseif ($action === 'destroy') {
    // Полное уничтожение сессии
    session_unset();   // очищаем все переменные сессии
    session_destroy(); // уничтожаем файл сессии на сервере
    // После destroy — создаём новую сессию для отображения страницы
    session_start();
    $message = '💥 Сессия полностью уничтожена!';

} elseif ($action === 'increment') {
    // Демонстрация накопления значения между запросами
    $_SESSION['counter'] = ($_SESSION['counter'] ?? 0) + 1;
    $message = '🔢 Счётчик увеличен на 1.';
}

// --- Идентификатор текущей сессии ---
$session_id   = session_id();
$session_name = session_name(); // по умолчанию "PHPSESSID"
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание №1 — Работа с сессиями PHP</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; color: #2d3748; padding: 30px 20px; }
        .container { max-width: 860px; margin: 0 auto; }
        h1 { font-size: 1.7rem; color: #2b6cb0; margin-bottom: 6px; }
        .subtitle { color: #718096; font-size: 0.9rem; margin-bottom: 30px; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 24px; margin-bottom: 20px; }
        .card h2 { font-size: 1.1rem; color: #2d3748; margin-bottom: 16px; border-bottom: 2px solid #ebf4ff; padding-bottom: 8px; }
        .btn { display: inline-block; padding: 9px 20px; border-radius: 6px; text-decoration: none; font-size: 0.88rem; font-weight: 600; margin: 4px; cursor: pointer; border: none; transition: opacity .2s; }
        .btn:hover { opacity: .85; }
        .btn-blue   { background: #3182ce; color: #fff; }
        .btn-green  { background: #38a169; color: #fff; }
        .btn-red    { background: #e53e3e; color: #fff; }
        .btn-orange { background: #dd6b20; color: #fff; }
        .btn-gray   { background: #a0aec0; color: #fff; }
        .message { padding: 12px 16px; border-radius: 8px; background: #ebf8ff; color: #2c5282; border-left: 4px solid #3182ce; margin-bottom: 20px; font-size: 0.95rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; font-size: 0.9rem; }
        th { background: #ebf4ff; color: #2b6cb0; font-weight: 600; }
        td code { background: #edf2f7; padding: 2px 6px; border-radius: 4px; font-size: 0.85rem; }
        .empty { color: #a0aec0; font-style: italic; }
        .info-box { background: #fffaf0; border: 1px solid #f6ad55; border-radius: 8px; padding: 14px 18px; font-size: 0.88rem; }
        .info-box strong { color: #c05621; }
        .session-id { font-family: monospace; font-size: 0.8rem; background: #edf2f7; padding: 4px 8px; border-radius: 4px; word-break: break-all; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 600px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="container">
    <h1>📋 Задание №1: Работа с сессиями PHP</h1>
    <p class="subtitle">Демонстрация механизма работы с сессиями — передача данных между запросами без cookies</p>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="grid-2">
        <!-- Панель управления -->
        <div class="card">
            <h2>⚙️ Управление сессией</h2>
            <a href="?action=set"       class="btn btn-blue"  >Записать данные в сессию</a>
            <a href="?action=increment" class="btn btn-green" >Увеличить счётчик (+1)</a>
            <a href="?action=delete_key&key=username" class="btn btn-orange">Удалить ключ «username»</a>
            <a href="?action=destroy"   class="btn btn-red"  >Уничтожить сессию</a>
            <a href="?"                 class="btn btn-gray"  >Обновить страницу</a>

            <br><br>
            <div class="info-box">
                <strong>Имя сессии:</strong> <?= htmlspecialchars($session_name) ?><br>
                <strong>ID сессии:</strong><br>
                <span class="session-id"><?= htmlspecialchars($session_id) ?></span><br><br>
                <strong>Файл сессии на сервере:</strong><br>
                <code>/tmp/sess_<?= htmlspecialchars(substr($session_id, 0, 12)) ?>…</code>
            </div>
        </div>

        <!-- Содержимое $_SESSION -->
        <div class="card">
            <h2>📦 Содержимое $_SESSION</h2>
            <?php if (!empty($_SESSION)): ?>
                <table>
                    <tr><th>Ключ</th><th>Значение</th><th>Действие</th></tr>
                    <?php foreach ($_SESSION as $key => $value): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($key) ?></code></td>
                            <td><?= htmlspecialchars((string)$value) ?></td>
                            <td>
                                <a href="?action=delete_key&key=<?= urlencode($key) ?>"
                                   class="btn btn-red" style="padding:4px 10px; font-size:0.8rem;">
                                    ✕
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="empty">Сессия пуста. Нажмите «Записать данные в сессию».</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Теоретический блок -->
    <div class="card">
        <h2>📖 Как работают сессии PHP — ключевые функции</h2>
        <table>
            <tr><th>Функция</th><th>Описание</th><th>Важно</th></tr>
            <tr>
                <td><code>session_start()</code></td>
                <td>Инициализирует или возобновляет сессию</td>
                <td>Вызывать ДО любого вывода!</td>
            </tr>
            <tr>
                <td><code>$_SESSION['key'] = val</code></td>
                <td>Записывает переменную в сессию</td>
                <td>Данные сохраняются между запросами</td>
            </tr>
            <tr>
                <td><code>isset($_SESSION['key'])</code></td>
                <td>Проверяет существование переменной</td>
                <td>Безопасная проверка перед чтением</td>
            </tr>
            <tr>
                <td><code>unset($_SESSION['key'])</code></td>
                <td>Удаляет одну переменную сессии</td>
                <td>Остальные данные сохраняются</td>
            </tr>
            <tr>
                <td><code>session_unset()</code></td>
                <td>Очищает все переменные сессии</td>
                <td>Файл сессии ещё существует</td>
            </tr>
            <tr>
                <td><code>session_destroy()</code></td>
                <td>Удаляет файл сессии с сервера</td>
                <td>Вызывать ПОСЛЕ session_unset()</td>
            </tr>
            <tr>
                <td><code>session_id()</code></td>
                <td>Возвращает идентификатор сессии</td>
                <td>Хранится в куки PHPSESSID</td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
