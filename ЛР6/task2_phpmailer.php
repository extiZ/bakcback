<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHPMailer - отправка писем</title>
</head>
<body>
    <h1>Отправка писем через PHPMailer</h1>
    <form method="POST" action="send_phpmailer.php" enctype="multipart/form-data">
        Получатели (через запятую): <input type="text" name="recipients" size="50" required><br>
        Тема: <input type="text" name="subject" required><br>
        Сообщение:<br>
        <textarea name="message" rows="5" cols="40" required></textarea><br>
        Вложение: <input type="file" name="attachment"><br>
        <button type="submit">Отправить</button>
    </form>
</body>
</html>