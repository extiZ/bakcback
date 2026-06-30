<?php
/**
 * Задание №2: Демонстрация механизма работы с Cookies в PHP
 * Лабораторная работа: Управление состоянием веб-приложений
 */

// setcookie() должен вызываться ДО любого вывода HTML
$message = '';
$action  = $_GET['action'] ?? '';

if ($action === 'set') {
    // Устанавливаем несколько cookies с разными параметрами
    setcookie('user_name',   'Иван Петров',    time() + 3600,        '/'); // 1 час
    setcookie('theme',       'dark',            time() + 86400 * 30,  '/'); // 30 дней
    setcookie('language',    'ru',              time() + 86400 * 365, '/'); // 1 год
    setcookie('visit_time',  date('d.m.Y H:i'), time() + 3600,        '/');
    $message = '✅ Cookies установлены! Страница перезагрузится для их отображения.';

} elseif ($action === 'secure') {
    // Безопасная кука: httponly + samesite (защита от XSS и CSRF)
    setcookie(
        'secure_token',
        bin2hex(random_bytes(16)), // случайный токен
        [
            'expires'  => time() + 3600,
            'path'     => '/',
            'secure'   => false,   // true только при HTTPS!
            'httponly' => true,    // недоступна из JavaScript
            'samesite' => 'Strict' // защита от CSRF
        ]
    );
    $message = '🔒 Безопасная кука «secure_token» установлена (HttpOnly, SameSite=Strict).';

} elseif ($action === 'delete') {
    // Удаление куки — устанавливаем дату истечения в прошлом
    $delete_key = $_GET['key'] ?? '';
    if ($delete_key) {
        setcookie($delete_key, '', time() - 3600, '/');
        $message = "🗑️ Кука «{$delete_key}» удалена (срок истёк).";
    }

} elseif ($action === 'delete_all') {
    // Удаляем все текущие cookies
    foreach ($_COOKIE as $key => $value) {
        setcookie($key, '', time() - 3600, '/');
    }
    $message = '💥 Все cookies удалены!';
}

// Куки доступны только при СЛЕДУЮЩЕМ запросе (после их установки)
$cookies = $_COOKIE;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание №2 — Работа с Cookies PHP</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f7f3ff; color: #2d3748; padding: 30px 20px; }
        .container { max-width: 860px; margin: 0 auto; }
        h1 { font-size: 1.7rem; color: #6b46c1; margin-bottom: 6px; }
        .subtitle { color: #718096; font-size: 0.9rem; margin-bottom: 30px; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 24px; margin-bottom: 20px; }
        .card h2 { font-size: 1.1rem; color: #2d3748; margin-bottom: 16px; border-bottom: 2px solid #f3ebff; padding-bottom: 8px; }
        .btn { display: inline-block; padding: 9px 20px; border-radius: 6px; text-decoration: none; font-size: 0.88rem; font-weight: 600; margin: 4px; transition: opacity .2s; }
        .btn:hover { opacity: .85; }
        .btn-purple { background: #6b46c1; color: #fff; }
        .btn-teal   { background: #2c7a7b; color: #fff; }
        .btn-red    { background: #e53e3e; color: #fff; }
        .btn-gray   { background: #a0aec0; color: #fff; }
        .message { padding: 12px 16px; border-radius: 8px; background: #faf5ff; color: #553c9a; border-left: 4px solid #6b46c1; margin-bottom: 20px; font-size: 0.95rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; font-size: 0.9rem; }
        th { background: #f3ebff; color: #553c9a; font-weight: 600; }
        td code { background: #f7f3ff; padding: 2px 6px; border-radius: 4px; font-size: 0.85rem; }
        .empty { color: #a0aec0; font-style: italic; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.78rem; font-weight: 600; }
        .badge-php  { background: #fed7e2; color: #97266d; }
        .badge-js   { background: #feebc8; color: #c05621; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .code-block { background: #2d2d2d; color: #f8f8f2; border-radius: 8px; padding: 16px; font-size: 0.82rem; font-family: 'Courier New', monospace; line-height: 1.7; overflow-x: auto; margin-top: 10px; }
        .code-block .kw { color: #66d9e8; }
        .code-block .str { color: #a6e22e; }
        .code-block .cm { color: #75715e; }
        .code-block .fn { color: #e6db74; }
        @media (max-width: 600px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="container">
    <h1>🍪 Задание №2: Работа с Cookies PHP</h1>
    <p class="subtitle">Демонстрация установки, чтения и удаления cookies — данные хранятся в браузере клиента</p>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="grid-2">
        <!-- Управление -->
        <div class="card">
            <h2>⚙️ Управление Cookies</h2>
            <a href="?action=set"        class="btn btn-purple">Установить cookies</a>
            <a href="?action=secure"     class="btn btn-teal"  >Установить безопасную куку</a>
            <a href="?action=delete_all" class="btn btn-red"   >Удалить все cookies</a>
            <a href="?"                  class="btn btn-gray"  >Обновить страницу</a>

            <br><br>
            <div style="background:#faf5ff; border:1px solid #d6bcfa; border-radius:8px; padding:14px; font-size:0.85rem; line-height:1.6;">
                <strong style="color:#553c9a;">⚠️ Важно знать:</strong><br>
                Cookies, установленные через <code>setcookie()</code>, становятся
                доступны в <code>$_COOKIE</code> только при <strong>следующем</strong> запросе.
                В текущем запросе они уже отправлены браузеру, но PHP ещё
                не получал их обратно.
            </div>
        </div>

        <!-- Текущие cookies -->
        <div class="card">
            <h2>📦 Текущие Cookies ($_COOKIE)</h2>
            <?php if (!empty($cookies)): ?>
                <table>
                    <tr><th>Имя</th><th>Значение</th><th>Удалить</th></tr>
                    <?php foreach ($cookies as $name => $value): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($name) ?></code></td>
                            <td style="word-break:break-all; max-width:180px;">
                                <?= htmlspecialchars(mb_strimwidth((string)$value, 0, 40, '…')) ?>
                            </td>
                            <td>
                                <a href="?action=delete&key=<?= urlencode($name) ?>"
                                   class="btn btn-red" style="padding:4px 10px; font-size:0.8rem;">✕</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="empty">Cookies не найдены. Нажмите «Установить cookies».</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Примеры кода -->
    <div class="card">
        <h2>💻 Примеры использования setcookie()</h2>
        <div class="code-block">
<span class="cm">// 1. Простая кука на 1 час</span>
<span class="fn">setcookie</span>(<span class="str">'username'</span>, <span class="str">'Иван'</span>, <span class="fn">time</span>() + <span class="kw">3600</span>, <span class="str">'/'</span>);

<span class="cm">// 2. Кука на 30 дней</span>
<span class="fn">setcookie</span>(<span class="str">'theme'</span>, <span class="str">'dark'</span>, <span class="fn">time</span>() + <span class="kw">86400</span> * <span class="kw">30</span>, <span class="str">'/'</span>);

<span class="cm">// 3. Безопасная кука (PHP 7.3+ синтаксис массива)</span>
<span class="fn">setcookie</span>(<span class="str">'token'</span>, <span class="str">'abc123'</span>, [
    <span class="str">'expires'</span>  => <span class="fn">time</span>() + <span class="kw">3600</span>,
    <span class="str">'path'</span>     => <span class="str">'/'</span>,
    <span class="str">'secure'</span>   => <span class="kw">true</span>,   <span class="cm">// только HTTPS</span>
    <span class="str">'httponly'</span> => <span class="kw">true</span>,   <span class="cm">// блокирует JS-доступ</span>
    <span class="str">'samesite'</span> => <span class="str">'Strict'</span> <span class="cm">// CSRF-защита</span>
]);

<span class="cm">// 4. Чтение куки</span>
<span class="kw">if</span> (<span class="fn">isset</span>(<span class="kw">$_COOKIE</span>[<span class="str">'username'</span>])) {
    <span class="kw">$name</span> = <span class="fn">htmlspecialchars</span>(<span class="kw">$_COOKIE</span>[<span class="str">'username'</span>]);
}

<span class="cm">// 5. Удаление куки — срок в прошлом</span>
<span class="fn">setcookie</span>(<span class="str">'username'</span>, <span class="str">''</span>, <span class="fn">time</span>() - <span class="kw">3600</span>, <span class="str">'/'</span>);
        </div>
    </div>

    <!-- Сравнение сессий и cookies -->
    <div class="card">
        <h2>📊 Cookies vs Сессии — сравнение</h2>
        <table>
            <tr><th>Характеристика</th><th><span class="badge badge-php">Sessions</span></th><th><span class="badge badge-js">Cookies</span></th></tr>
            <tr><td>Место хранения</td><td>Сервер (/tmp/sess_…)</td><td>Браузер клиента</td></tr>
            <tr><td>Максимальный размер</td><td>Не ограничен</td><td>~4 КБ на куку</td></tr>
            <tr><td>Время жизни</td><td>До закрытия браузера</td><td>Задаётся явно</td></tr>
            <tr><td>Доступность из JS</td><td>Нет</td><td>Да (если не HttpOnly)</td></tr>
            <tr><td>Передача по сети</td><td>Только ID (PHPSESSID)</td><td>Все данные каждый раз</td></tr>
            <tr><td>Безопасность паролей</td><td>✅ Хранить можно</td><td>❌ Никогда не хранить!</td></tr>
        </table>
    </div>
</div>
</body>
</html>
