<?php
// ============================================================
//  ГОСТЕВАЯ КНИГА — Задание №2. Работа с файлами в PHP
//  Файл: guestbook.php
//  Данные хранятся в: guestbook_data.txt
// ============================================================

define('DATA_FILE', 'guestbook_data.txt');
define('MAX_ENTRIES', 50);

// ---------- 1. ЗАПИСЬ новой записи в файл ----------
$success_message = '';
$error_message   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $name    = trim(htmlspecialchars($_POST['name']    ?? '', ENT_QUOTES, 'UTF-8'));
    $email   = trim(htmlspecialchars($_POST['email']   ?? '', ENT_QUOTES, 'UTF-8'));
    $message = trim(htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'));

    if ($name === '' || $message === '') {
        $error_message = 'Поля «Имя» и «Сообщение» обязательны для заполнения.';
    } elseif (mb_strlen($message) > 1000) {
        $error_message = 'Сообщение не должно превышать 1000 символов.';
    } else {
        $date  = date('d.m.Y H:i');
        // Формат одной строки в файле: поля разделены символом | , запись — переводом строки
        $entry = implode('|', [$date, $name, $email, $message]) . PHP_EOL;

        // Открываем файл на ДОЗАПИСЬ с блокировкой (LOCK_EX)
        $fp = fopen(DATA_FILE, 'a');
        if ($fp) {
            flock($fp, LOCK_EX);          // Эксклюзивная блокировка
            fwrite($fp, $entry);          // Запись строки
            flock($fp, LOCK_UN);          // Снятие блокировки
            fclose($fp);                  // Закрытие файла
            $success_message = '✓ Ваша запись успешно добавлена!';
        } else {
            $error_message = 'Не удалось открыть файл для записи. Проверьте права доступа.';
        }
    }
}

// ---------- 2. ЧТЕНИЕ записей из файла ----------
$entries = [];

if (file_exists(DATA_FILE)) {
    // Открываем файл на ЧТЕНИЕ
    $fp = fopen(DATA_FILE, 'r');
    if ($fp) {
        flock($fp, LOCK_SH);              // Разделяемая (читающая) блокировка
        while (!feof($fp)) {
            $line = fgets($fp);           // Читаем строку
            if (trim($line) === '') continue;
            $parts = explode('|', trim($line));
            if (count($parts) === 4) {
                $entries[] = [
                    'date'    => $parts[0],
                    'name'    => $parts[1],
                    'email'   => $parts[2],
                    'message' => $parts[3],
                ];
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);
    }
    // Показываем записи в обратном порядке (новые — первые)
    $entries = array_reverse($entries);
    // Ограничиваем количество отображаемых записей
    $entries = array_slice($entries, 0, MAX_ENTRIES);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Гостевая книга</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <style>
        /* ===== СБРОС И БАЗОВЫЕ СТИЛИ ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cream:    #f5f0e8;
            --paper:    #fdf8f0;
            --ink:      #2c2416;
            --ink-mid:  #5a4a32;
            --ink-light:#9a8870;
            --accent:   #8b3a2a;
            --accent2:  #4a6741;
            --border:   #d4c9b0;
            --shadow:   rgba(44, 36, 22, 0.12);
        }

        body {
            font-family: 'Lora', Georgia, serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
            background-image:
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 27px,
                    rgba(180,165,135,0.18) 27px,
                    rgba(180,165,135,0.18) 28px
                );
        }

        /* ===== ШАПКА ===== */
        header {
            background: var(--ink);
            color: var(--cream);
            text-align: center;
            padding: 3rem 1rem 2.5rem;
            position: relative;
            overflow: hidden;
        }
        header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 20px,
                rgba(255,255,255,0.02) 20px,
                rgba(255,255,255,0.02) 21px
            );
        }
        header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3.2rem);
            letter-spacing: 0.04em;
            position: relative;
        }
        header h1 span { color: #c8a97a; }
        header p {
            margin-top: 0.6rem;
            font-style: italic;
            color: var(--ink-light);
            font-size: 1rem;
            position: relative;
        }
        .header-ornament {
            display: block;
            margin: 1rem auto 0;
            width: 120px;
            height: 2px;
            background: linear-gradient(to right, transparent, #c8a97a, transparent);
            position: relative;
        }

        /* ===== КОНТЕЙНЕР ===== */
        .container {
            max-width: 820px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 4rem;
        }

        /* ===== ФОРМА ДОБАВЛЕНИЯ ===== */
        .form-card {
            background: var(--paper);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2.2rem 2.5rem;
            margin-bottom: 3rem;
            box-shadow: 0 4px 20px var(--shadow), inset 0 0 0 4px rgba(255,255,255,0.6);
            position: relative;
        }
        .form-card::before {
            content: '';
            position: absolute;
            top: 10px; left: 10px; right: 10px; bottom: 10px;
            border: 1px dashed var(--border);
            border-radius: 2px;
            pointer-events: none;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem;
            color: var(--ink);
            margin-bottom: 1.5rem;
            padding-bottom: 0.7rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .section-title .icon { font-size: 1.1rem; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.1rem;
            margin-bottom: 1.1rem;
        }
        @media (max-width: 540px) { .form-grid { grid-template-columns: 1fr; } }

        .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .form-group.full { grid-column: 1 / -1; }

        label {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--ink-mid);
            font-family: 'Lora', serif;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            font-family: 'Lora', Georgia, serif;
            font-size: 0.97rem;
            color: var(--ink);
            background: #fffef9;
            border: 1px solid var(--border);
            border-radius: 3px;
            padding: 0.65rem 0.85rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        input:focus, textarea:focus {
            border-color: var(--accent2);
            box-shadow: 0 0 0 3px rgba(74, 103, 65, 0.12);
        }
        textarea { resize: vertical; min-height: 110px; line-height: 1.6; }

        .char-count {
            text-align: right;
            font-size: 0.78rem;
            color: var(--ink-light);
            margin-top: 0.2rem;
        }
        .char-count.over { color: var(--accent); }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1.2rem;
            padding: 0.75rem 2rem;
            background: var(--ink);
            color: var(--cream);
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            letter-spacing: 0.05em;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-submit:hover { background: var(--accent); transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        /* ===== УВЕДОМЛЕНИЯ ===== */
        .alert {
            padding: 0.85rem 1.2rem;
            border-radius: 3px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .alert-success { background: #eef5ec; border-left: 3px solid var(--accent2); color: #2e5229; }
        .alert-error   { background: #fdf0ee; border-left: 3px solid var(--accent);  color: #6b2019; }

        /* ===== ЗАПИСИ ГОСТЕВОЙ КНИГИ ===== */
        .entries-section { }
        .entries-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 0.7rem;
            border-bottom: 2px solid var(--ink);
        }
        .entries-count {
            font-size: 0.85rem;
            color: var(--ink-light);
            font-style: italic;
        }

        .entry-card {
            background: var(--paper);
            border: 1px solid var(--border);
            border-radius: 3px;
            padding: 1.5rem 1.8rem;
            margin-bottom: 1.2rem;
            position: relative;
            box-shadow: 0 2px 8px var(--shadow);
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

        .entry-card::before {
            content: '"';
            position: absolute;
            top: 0.5rem;
            left: 1.2rem;
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            color: var(--border);
            line-height: 1;
        }

        .entry-meta {
            display: flex;
            align-items: baseline;
            gap: 0.8rem;
            margin-bottom: 0.7rem;
            flex-wrap: wrap;
        }
        .entry-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: var(--ink);
        }
        .entry-email {
            font-size: 0.82rem;
            color: var(--ink-light);
            font-style: italic;
        }
        .entry-date {
            margin-left: auto;
            font-size: 0.78rem;
            color: var(--ink-light);
            letter-spacing: 0.04em;
            white-space: nowrap;
        }
        .entry-text {
            line-height: 1.75;
            color: var(--ink-mid);
            font-size: 0.97rem;
            padding-left: 0.5rem;
        }

        .no-entries {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--ink-light);
            font-style: italic;
        }
        .no-entries .big { font-size: 2.5rem; display: block; margin-bottom: 0.5rem; }

        /* ===== PHP-КОММЕНТАРИИ-КОД (для пояснения в лабораторной) ===== */
        .code-note {
            background: #1e1b16;
            color: #c8b99a;
            border-radius: 4px;
            padding: 1.2rem 1.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.82rem;
            line-height: 1.7;
            margin-bottom: 2rem;
            overflow-x: auto;
            border-left: 3px solid #c8a97a;
        }
        .code-note .kw  { color: #79a876; }
        .code-note .fn  { color: #7ab8d4; }
        .code-note .str { color: #e09a6a; }
        .code-note .cmt { color: #6a6050; font-style: italic; }
    </style>
</head>
<body>

<header>
    <h1>Гостевая <span>книга</span></h1>
    <p>Оставьте своё сообщение — оно сохранится в файле на сервере</p>
    <span class="header-ornament"></span>
</header>

<div class="container">

    <!-- Пояснение для лабораторной -->
    <div class="code-note">
        <span class="cmt">// Задание №2 — Работа с файлами в PHP</span><br>
        <span class="cmt">// Используемые функции:</span><br>
        &nbsp;&nbsp;<span class="fn">fopen</span>(<span class="str">'guestbook_data.txt'</span>, <span class="str">'a'</span>)&nbsp;&nbsp;&nbsp;<span class="cmt">// открытие файла на дозапись</span><br>
        &nbsp;&nbsp;<span class="fn">flock</span>($fp, LOCK_EX)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="cmt">// эксклюзивная блокировка</span><br>
        &nbsp;&nbsp;<span class="fn">fwrite</span>($fp, $entry)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="cmt">// запись строки в файл</span><br>
        &nbsp;&nbsp;<span class="fn">flock</span>($fp, LOCK_UN)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="cmt">// снятие блокировки</span><br>
        &nbsp;&nbsp;<span class="fn">fclose</span>($fp)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="cmt">// закрытие файла</span><br>
        &nbsp;&nbsp;<span class="fn">fopen</span>(<span class="str">'guestbook_data.txt'</span>, <span class="str">'r'</span>)&nbsp;&nbsp;&nbsp;<span class="cmt">// открытие на чтение</span><br>
        &nbsp;&nbsp;<span class="fn">fgets</span>($fp)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="cmt">// чтение строки из файла</span>
    </div>

    <!-- Форма добавления записи -->
    <div class="form-card">
        <h2 class="section-title"><span class="icon">✒</span> Оставить запись</h2>

        <?php if ($success_message): ?>
            <div class="alert alert-success">✓ <?= $success_message ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-error">✗ <?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Имя *</label>
                    <input type="text" id="name" name="name" maxlength="80"
                           placeholder="Ваше имя"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                           required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail (необязательно)</label>
                    <input type="email" id="email" name="email" maxlength="120"
                           placeholder="example@mail.ru"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group full">
                    <label for="message">Сообщение *</label>
                    <textarea id="message" name="message" maxlength="1000"
                              placeholder="Напишите что-нибудь..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    <div class="char-count" id="charCount">0 / 1000</div>
                </div>
            </div>
            <button type="submit" name="submit" class="btn-submit">
                ✉ Отправить запись
            </button>
        </form>
    </div>

    <!-- Список записей -->
    <div class="entries-section">
        <div class="entries-header">
            <h2 class="section-title" style="border:none;margin:0;padding:0;">
                <span class="icon">📖</span> Записи
            </h2>
            <span class="entries-count"><?= count($entries) ?> сообщ.</span>
        </div>

        <?php if (empty($entries)): ?>
            <div class="no-entries">
                <span class="big">📝</span>
                Записей пока нет. Будьте первым!
            </div>
        <?php else: ?>
            <?php foreach ($entries as $e): ?>
                <div class="entry-card">
                    <div class="entry-meta">
                        <span class="entry-name"><?= $e['name'] ?></span>
                        <?php if ($e['email'] !== ''): ?>
                            <span class="entry-email"><?= $e['email'] ?></span>
                        <?php endif; ?>
                        <span class="entry-date">🕐 <?= $e['date'] ?></span>
                    </div>
                    <p class="entry-text"><?= nl2br($e['message']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<script>
// Счётчик символов в textarea
const ta    = document.getElementById('message');
const count = document.getElementById('charCount');
function updateCount() {
    const len = ta.value.length;
    count.textContent = len + ' / 1000';
    count.classList.toggle('over', len > 900);
}
ta.addEventListener('input', updateCount);
updateCount();
</script>

</body>
</html>
