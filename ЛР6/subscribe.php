<?php
$file = "maillist.txt";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscribe'])) {
    $email = trim($_POST['email']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Некорректный email!";
    } else {
        $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
        if (in_array($email, $emails)) {
            $error = "Этот email уже подписан!";
        } else {
            file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
            $success = "Вы успешно подписались на рассылку!";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unsubscribe'])) {
    $email = trim($_POST['email']);
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    $new_emails = array_filter($emails, function($e) use ($email) {
        return trim($e) !== $email;
    });
    file_put_contents($file, implode(PHP_EOL, $new_emails));
    $success = "Вы отписались от рассылки.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Подписка на рассылку</title>
</head>
<body>
    <h1>Подписка на рассылку</h1>
    <?php if (isset($success)) echo "<p style='color:green'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    
    <form method="POST">
        <input type="email" name="email" placeholder="Ваш email" required>
        <button type="submit" name="subscribe">Подписаться</button>
        <button type="submit" name="unsubscribe">Отписаться</button>
    </form>

    <hr>

    <h2>Форма обратной связи</h2>
    <form method="POST" action="send_feedback_phpmailer.php">
        <input type="text" name="name" placeholder="Ваше имя" required><br>
        <input type="email" name="email" placeholder="Ваш email" required><br>
        <textarea name="message" rows="5" cols="40" placeholder="Ваше сообщение" required></textarea><br>
        <button type="submit">Отправить</button>
    </form>
</body>
</html>