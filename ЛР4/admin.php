<?php
/**
 * Задание №3: Авторизация доступа с помощью сессий
 * Файл: admin.php — панель администратора (защищённая страница)
 */
session_start();

// =====================================================
// ПРОВЕРКА АВТОРИЗАЦИИ — начало каждой защищённой страницы
// =====================================================
if (empty($_SESSION['admin_logged_in'])) {
    // Не авторизован — перенаправляем на страницу входа
    header('Location: login.php');
    exit; // Обязательно! Прекращаем выполнение скрипта
}

// Обработка выхода
if (($_GET['action'] ?? '') === 'logout') {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Данные администратора из сессии
$admin_login  = htmlspecialchars($_SESSION['admin_login']  ?? 'admin');
$login_time   = htmlspecialchars($_SESSION['login_time']   ?? '—');
$session_ip   = htmlspecialchars($_SESSION['ip']           ?? '—');
$session_id   = htmlspecialchars(session_id());

// Имитация данных сайта (в реальном проекте — из БД)
$stats = [
    'pages'    => 12,
    'articles' => 7,
    'visitors' => 1284,
    'messages' => 3,
];

$recent_pages = [
    ['Главная страница',     '/',              '2 часа назад'],
    ['О сайте',              '/about',         'Вчера'],
    ['Портфолио',            '/portfolio',     '3 дня назад'],
    ['Контакты',             '/contacts',      'Неделю назад'],
    ['Блог',                 '/blog',          '2 недели назад'],
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора — Мой сайт</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; color: #2d3748; }

        /* Боковая панель */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 240px; height: 100vh;
            background: #1a202c;
            display: flex; flex-direction: column;
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 24px 20px 20px;
            border-bottom: 1px solid #2d3748;
        }
        .sidebar-header h2 { color: #fff; font-size: 1.05rem; }
        .sidebar-header p  { color: #718096; font-size: 0.8rem; margin-top: 3px; }

        .nav-section { padding: 8px 0; }
        .nav-label { color: #4a5568; font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
                     letter-spacing: .08em; padding: 6px 20px 4px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 20px; color: #a0aec0; text-decoration: none;
            font-size: 0.9rem; cursor: pointer; transition: background .15s, color .15s;
        }
        .nav-item:hover, .nav-item.active { background: #2d3748; color: #fff; }
        .nav-item .icon { font-size: 1.1rem; width: 20px; text-align: center; }
        .nav-logout { color: #fc8181 !important; margin-top: auto; }

        /* Основное содержимое */
        .main { margin-left: 240px; padding: 30px; min-height: 100vh; }

        /* Шапка */
        .top-bar {
            display: flex; align-items: center; justify-content: space-between;
            background: #fff; border-radius: 10px; padding: 14px 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06); margin-bottom: 24px;
        }
        .top-bar h1 { font-size: 1.3rem; color: #1a202c; }
        .user-badge {
            display: flex; align-items: center; gap: 10px;
        }
        .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #2b6cb0, #4299e1);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 0.9rem;
        }
        .user-info { text-align: right; }
        .user-info strong { display: block; font-size: 0.9rem; color: #2d3748; }
        .user-info span   { font-size: 0.78rem; color: #718096; }

        /* Карточки статистики */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: #fff; border-radius: 10px; padding: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border-top: 3px solid;
        }
        .stat-card:nth-child(1) { border-color: #4299e1; }
        .stat-card:nth-child(2) { border-color: #48bb78; }
        .stat-card:nth-child(3) { border-color: #ed8936; }
        .stat-card:nth-child(4) { border-color: #9f7aea; }
        .stat-icon { font-size: 1.6rem; margin-bottom: 8px; }
        .stat-num  { font-size: 1.8rem; font-weight: 700; color: #1a202c; }
        .stat-lbl  { font-size: 0.82rem; color: #718096; margin-top: 2px; }

        /* Карточки контента */
        .card { background: #fff; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); padding: 22px; margin-bottom: 20px; }
        .card h3 { font-size: 1rem; color: #2d3748; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-size: 0.88rem; }
        th { color: #718096; font-weight: 600; font-size: 0.82rem; text-transform: uppercase; }
        .btn-sm { padding: 5px 12px; border-radius: 5px; text-decoration: none; font-size: 0.8rem; font-weight: 600; border: none; cursor: pointer; }
        .btn-blue  { background: #ebf8ff; color: #2b6cb0; }
        .btn-green { background: #f0fff4; color: #276749; }
        .btn-red   { background: #fff5f5; color: #c53030; }

        /* Блок сессии */
        .session-block { background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; }
        .session-block dt { font-size: 0.8rem; color: #718096; font-weight: 600; margin-top: 10px; }
        .session-block dt:first-child { margin-top: 0; }
        .session-block dd { font-size: 0.88rem; color: #2d3748; font-family: monospace; word-break: break-all; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        @media (max-width: 900px) {
            .sidebar { width: 60px; }
            .sidebar-header h2, .sidebar-header p, .nav-label, .nav-item span { display: none; }
            .nav-item { justify-content: center; padding: 12px; }
            .main { margin-left: 60px; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Боковая навигация -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h2>🌐 Мой сайт</h2>
            <p>Панель администратора</p>
        </div>
        <div class="nav-section">
            <div class="nav-label">Управление</div>
            <a class="nav-item active" href="admin.php">
                <span class="icon">🏠</span><span>Главная</span>
            </a>
            <a class="nav-item" href="#">
                <span class="icon">📄</span><span>Страницы</span>
            </a>
            <a class="nav-item" href="#">
                <span class="icon">✍️</span><span>Статьи</span>
            </a>
            <a class="nav-item" href="#">
                <span class="icon">🖼️</span><span>Медиафайлы</span>
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Система</div>
            <a class="nav-item" href="#">
                <span class="icon">⚙️</span><span>Настройки</span>
            </a>
            <a class="nav-item" href="#">
                <span class="icon">👥</span><span>Пользователи</span>
            </a>
        </div>
        <div style="margin-top: auto; padding: 16px 0; border-top: 1px solid #2d3748;">
            <a class="nav-item nav-logout" href="?action=logout">
                <span class="icon">🚪</span><span>Выйти</span>
            </a>
        </div>
    </nav>

    <!-- Основной контент -->
    <main class="main">
        <!-- Шапка -->
        <div class="top-bar">
            <h1>Панель администратора</h1>
            <div class="user-badge">
                <div class="user-info">
                    <strong><?= $admin_login ?></strong>
                    <span>Вход: <?= $login_time ?></span>
                </div>
                <div class="avatar"><?= mb_strtoupper(mb_substr($admin_login, 0, 1)) ?>A</div>
            </div>
        </div>

        <!-- Статистика -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📄</div>
                <div class="stat-num"><?= $stats['pages'] ?></div>
                <div class="stat-lbl">Страниц на сайте</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✍️</div>
                <div class="stat-num"><?= $stats['articles'] ?></div>
                <div class="stat-lbl">Статей в блоге</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👁️</div>
                <div class="stat-num"><?= number_format($stats['visitors'], 0, '.', ' ') ?></div>
                <div class="stat-lbl">Посетителей всего</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✉️</div>
                <div class="stat-num"><?= $stats['messages'] ?></div>
                <div class="stat-lbl">Новых сообщений</div>
            </div>
        </div>

        <div class="grid-2">
            <!-- Управление страницами -->
            <div class="card">
                <h3>📄 Управление страницами</h3>
                <table>
                    <tr><th>Страница</th><th>URL</th><th>Изменена</th><th></th></tr>
                    <?php foreach ($recent_pages as [$title, $url, $modified]): ?>
                        <tr>
                            <td><?= htmlspecialchars($title) ?></td>
                            <td style="color:#718096; font-size:0.82rem;"><?= htmlspecialchars($url) ?></td>
                            <td style="color:#718096; font-size:0.82rem;"><?= htmlspecialchars($modified) ?></td>
                            <td>
                                <a href="#" class="btn-sm btn-blue">✏️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div style="margin-top:12px;">
                    <a href="#" class="btn-sm btn-green" style="padding:7px 14px;">+ Добавить страницу</a>
                </div>
            </div>

            <!-- Информация о сессии -->
            <div class="card">
                <h3>🔐 Данные текущей сессии</h3>
                <div class="session-block">
                    <dl>
                        <dt>Переменная сессии</dt>
                        <dd>$_SESSION['admin_logged_in'] = true</dd>

                        <dt>Администратор</dt>
                        <dd><?= $admin_login ?></dd>

                        <dt>Время входа</dt>
                        <dd><?= $login_time ?></dd>

                        <dt>IP-адрес</dt>
                        <dd><?= $session_ip ?></dd>

                        <dt>ID сессии (PHPSESSID)</dt>
                        <dd><?= substr($session_id, 0, 20) ?>…</dd>
                    </dl>
                </div>
                <div style="margin-top:14px; padding:12px; background:#fff5f5; border-radius:8px; font-size:0.83rem; color:#742a2a;">
                    <strong>⚠️ Механизм защиты:</strong><br>
                    Каждая страница проверяет <code>$_SESSION['admin_logged_in']</code>.
                    Если переменная не установлена — редирект на <code>login.php</code>.
                </div>
                <div style="margin-top:12px;">
                    <a href="?action=logout" class="btn-sm btn-red" style="padding:8px 16px; font-size:0.9rem;">
                        🚪 Выйти из системы
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
