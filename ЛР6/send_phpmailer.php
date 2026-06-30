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

        $mail->setFrom('ariovistofficle@gmail.com', 'Отправитель');

        $recipients = explode(',', $_POST['recipients']);
        foreach ($recipients as $recipient) {
            $mail->addAddress(trim($recipient));
        }

        if (!empty($_FILES['attachment']['tmp_name'])) {
            $mail->addAttachment($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);
        }

        $mail->isHTML(true);
        $mail->Subject = $_POST['subject'];
        $mail->Body    = nl2br($_POST['message']);

        $mail->send();
        echo "Письмо успешно отправлено!";
    } catch (Exception $e) {
        echo "Ошибка: {$mail->ErrorInfo}";
    }
    exit;
}
?>