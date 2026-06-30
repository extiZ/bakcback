<?php
/**
 * Задание №3: Авторизация доступа с помощью сессий
 * Файл: login.php — страница входа
 */
session_start();

// Если уже авторизован — перенаправляем в админ-панель
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit;
}

$error = '';

// --- Обработка формы входа ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Учётные данные администратора
    // В реальном проекте хранить в БД в виде хеша: password_hash()
    $ADMIN_LOGIN    = 'admin';
    $ADMIN_PASSWORD = 'secret123'; // для демонстрации — в реальности использовать хеш!

    $login    = trim($_POST['login']    ?? '');
    $password = trim($_POST['password'] ?? '');

    // Задержка против брут-форса
    sleep(1);

    if ($login === $ADMIN_LOGIN && $password === $ADMIN_PASSWORD) {
        // Успешный вход: фиксируем сессию
        session_regenerate_id(true); // генерируем новый ID — защита от Session Fixation
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login']     = $login;
        $_SESSION['login_time']      = date('d.m.Y H:i:s');
        $_SESSION['ip']              = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в панель администратора</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 50%, #1a365d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            padding: 40px 36px;
            width: 100%;
            max-width: 400px;
        }
        .logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .logo-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #2b6cb0, #4299e1);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            margin: 0 auto 12px;
            box-shadow: 0 4px 20px rgba(66,153,225,0.4);
        }
        h1 { font-size: 1.4rem; color: #1a202c; text-align: center; }
        .subtitle { color: #718096; font-size: 0.88rem; text-align: center; margin-top: 4px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: #4a5568; margin-bottom: 6px; }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #2d3748;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        input:focus { border-color: #4299e1; box-shadow: 0 0 0 3px rgba(66,153,225,0.15); }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2b6cb0, #4299e1);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 6px;
            transition: opacity .2s, transform .1s;
        }
        .btn-login:hover { opacity: .9; transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }
        .error {
            background: #fff5f5;
            color: #c53030;
            border: 1px solid #fed7d7;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.88rem;
            margin-bottom: 18px;
            text-align: center;
        }
        .hint {
            margin-top: 20px;
            padding: 12px 14px;
            background: #ebf8ff;
            border-radius: 8px;
            font-size: 0.82rem;
            color: #2c5282;
        }
        .hint strong { display: block; margin-bottom: 4px; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">
            <div class="logo-icon">🔐</div>
            <h1>Панель администратора</h1>
            <p class="subtitle">Мой личный сайт — защищённая область</p>
        </div>

        <?php if ($error): ?>
            <div class="error">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="login">Логин</label>
                <input type="text" id="login" name="login"
                       value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
                       placeholder="Введите логин" autocomplete="username" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password"
                       placeholder="Введите пароль" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn-login">Войти в панель</button>
        </form>

        <div class="hint">
            <strong>💡 Для демонстрации:</strong>
            Логин: <strong>admin</strong> / Пароль: <strong>secret123</strong>
        </div>
    </div>
</body>
</html>
