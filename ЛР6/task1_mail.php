<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ariovistofficle@gmail.com';
        $mail->Password   = 'xnbqcrzregwozuju';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($_POST['email'], $_POST['name'] ?? 'Пользователь');
        $mail->addAddress('ariovistofficle@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = $_POST['subject'];
        $mail->Body    = nl2br($_POST['message']);

        $mail->send();
        echo "Письмо отправлено успешно!";
    } catch (Exception $e) {
        echo "Ошибка: {$mail->ErrorInfo}";
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Отправка почты</title>
</head>
<body>
    <h1>Отправка письма</h1>
    <form method="POST">
        Ваше имя: <input type="text" name="name" required><br>
        Ваш Email: <input type="email" name="email" required><br>
        Тема: <input type="text" name="subject" required><br>
        Сообщение:<br>
        <textarea name="message" rows="5" cols="40" required></textarea><br>
        <button type="submit">Отправить</button>
    </form>
</body>
</html>