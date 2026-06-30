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

        $mail->setFrom('ariovistofficle@gmail.com', 'Сайт');
        $mail->addAddress('ariovistofficle@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = 'Сообщение с сайта от ' . $_POST['name'];
        $mail->Body    = "Имя: " . $_POST['name'] . "<br>Email: " . $_POST['email'] . "<br>Сообщение:<br>" . nl2br($_POST['message']);

        $mail->send();
        echo "Сообщение отправлено!";
    } catch (Exception $e) {
        echo "Ошибка: {$mail->ErrorInfo}";
    }
    exit;
}
?>