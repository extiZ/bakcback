<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все лабораторные работы</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a0a2e;
            color: #e0e0e0;
            padding: 40px 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #00ff88;
            margin-bottom: 10px;
        }
        .subtitle {
            text-align: center;
            color: #aaa;
            margin-bottom: 40px;
            border-bottom: 1px solid #333;
            padding-bottom: 20px;
        }
        .lab-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }
        .lab-card {
            background: #1e1e2e;
            border-radius: 12px;
            padding: 20px;
            border-left: 4px solid #00ff88;
            transition: 0.3s;
        }
        .lab-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,255,136,0.2);
        }
        .lab-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #00ff88;
            margin-bottom: 8px;
        }
        .lab-desc {
            font-size: 0.85rem;
            color: #ccc;
            margin-bottom: 10px;
        }
        .lab-path {
            font-size: 0.75rem;
            background: #0a0a1a;
            padding: 6px 10px;
            border-radius: 6px;
            font-family: monospace;
            margin-bottom: 12px;
            color: #aaa;
        }
        .btn {
            display: inline-block;
            background: #00ff88;
            color: #0a0a2e;
            padding: 6px 16px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.8rem;
            transition: 0.2s;
        }
        .btn:hover {
            background: #00cc66;
            transform: scale(1.02);
        }
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 5px;
        }
        .btn-orange {
            background: #ff6b4a;
        }
        .btn-orange:hover {
            background: #e55a3a;
        }
        footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #333;
            color: #666;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>📚 Все лабораторные работы</h1>
    <div class="subtitle">FrontEnd + BackEnd — полный список</div>

    <div class="lab-grid">

        <!-- ЛР1 -->
        <div class="lab-card">
            <div class="lab-title">📄 Лабораторная №1</div>
            <div class="lab-desc">HTML: базовая структура, мета-теги, заголовки</div>
            <div class="lab-path">📁 Папка: <code>ЛР1</code></div>
            <div class="btn-group">
                <a href="./ЛР1/index.php" class="btn">Открыть</a>
            </div>
        </div>

        <!-- ЛР2 -->
        <div class="lab-card">
            <div class="lab-title">🎨 Лабораторная №2</div>
            <div class="lab-desc">CSS: блочная модель, селекторы, цвета</div>
            <div class="lab-path">📁 Папка: <code>ЛР2</code></div>
            <div class="btn-group">
                <a href="./ЛР2/index.php" class="btn">Открыть</a>
            </div>
        </div>

        <!-- ЛР3 -->
        <div class="lab-card">
            <div class="lab-title">🧩 Лабораторная №3</div>
            <div class="lab-desc">Flexbox: align-items, justify-content, order</div>
            <div class="lab-path">📁 Папка: <code>ЛР3</code></div>
            <div class="btn-group">
                <a href="./ЛР3/index.php" class="btn">Открыть</a>
            </div>
        </div>

        <!-- ЛР4 -->
        <div class="lab-card">
            <div class="lab-title">📐 Лабораторная №4</div>
            <div class="lab-desc">Grid: grid-template-columns, repeat, areas</div>
            <div class="lab-path">📁 Папка: <code>ЛР4</code></div>
            <div class="btn-group">
                <a href="./ЛР4/task1.php" class="btn">task1</a>
                <a href="./ЛР4/task2.php" class="btn">task2</a>
                <a href="./ЛР4/login.php" class="btn">login</a>
                <a href="./ЛР4/admin.php" class="btn">admin</a>
                <a href="./ЛР4/logout.php" class="btn">logout</a>
            </div>
        </div>

        <!-- ЛР5 -->
        <div class="lab-card">
            <div class="lab-title">📱 Лабораторная №5</div>
            <div class="lab-desc">PHP: работа с файлами (запись, чтение, удаление)</div>
            <div class="lab-path">📁 Папка: <code>ЛР5</code></div>
            <div class="btn-group">
                <a href="./ЛР5/task1.php" class="btn">task1</a>
                <a href="./ЛР5/guestbook.php" class="btn">Гостевая книга</a>
            </div>
        </div>

        <!-- ЛР6 (исправлен) -->
        <div class="lab-card">
            <div class="lab-title">✉️ Лабораторная №6</div>
            <div class="lab-desc">PHP: отправка почты (mail(), PHPMailer, подписка)</div>
            <div class="lab-path">📁 Папка: <code>ЛР6</code></div>
            <div class="btn-group">
                <a href="./ЛР6/task1_mail.php" class="btn">task1 (mail)</a>
                <a href="./ЛР6/task2_phpmailer.php" class="btn">task2 (PHPMailer)</a>
                <a href="./ЛР6/subscribe.php" class="btn">Подписка</a>
            </div>
        </div>

        <!-- ПРОЕКТ FLEXGRID -->
        <div class="lab-card" style="border-left-color: #ff6b4a;">
            <div class="lab-title">Проект FLEXGRID</div>
            <div class="lab-desc">Магазин товаров с играми (MVC + MySQL)</div>
            <div class="lab-path">📁 Папка: <code>корень сайта</code></div>
            <div class="btn-group">
                <a href="dog.php" class="btn btn-orange">🚀 Перейти в проект</a>
            </div>
        </div>

    </div>

    <footer>
        ✅ Все лабораторные работы сданы<br>
        📁 ЛР6 — все файлы для работы с почтой
    </footer>
</div>

</body>
</html>