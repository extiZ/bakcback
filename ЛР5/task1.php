<?php
// ============================================================
//  ЗАДАНИЕ №1 — Типовые действия с файлами в PHP
// ============================================================

$output = [];   // массив для накопления результатов

// ----------------------------------------------------------
// 1. ЗАПИСЬ В ФАЙЛ (fopen + fwrite + fclose)
// ----------------------------------------------------------
$filename = 'demo_data.txt';

$file = fopen($filename, 'w');          // 'w' — открыть для записи, создать если нет
if ($file) {
    fwrite($file, "Строка 1: PHP умеет работать с файлами\n");
    fwrite($file, "Строка 2: Открытие, запись, чтение, закрытие\n");
    fwrite($file, "Строка 3: Блокировка предотвращает гонку данных\n");
    fclose($file);
    $output[] = ['ok', 'Файл создан и данные записаны успешно.'];
} else {
    $output[] = ['err', 'Не удалось создать файл для записи.'];
}

// ----------------------------------------------------------
// 2. ДОЗАПИСЬ В ФАЙЛ (режим 'a')
// ----------------------------------------------------------
$file = fopen($filename, 'a');          // 'a' — append, не удаляет существующее
if ($file) {
    fwrite($file, "Строка 4: Добавлено в режиме дозаписи\n");
    fclose($file);
    $output[] = ['ok', 'Строка успешно добавлена в конец файла.'];
}

// ----------------------------------------------------------
// 3. ЧТЕНИЕ СТРОК ЧЕРЕЗ fgets()
// ----------------------------------------------------------
$lines = [];
$file = fopen($filename, 'r');          // 'r' — только чтение
if ($file) {
    while (!feof($file)) {              // feof() — достигнут конец файла?
        $line = fgets($file);           // читаем по одной строке
        if ($line !== false) {
            $lines[] = htmlspecialchars(rtrim($line));
        }
    }
    fclose($file);
    $output[] = ['ok', 'Файл прочитан построчно через fgets().'];
}

// ----------------------------------------------------------
// 4. ЧТЕНИЕ ВСЕГО ФАЙЛА ЧЕРЕЗ file_get_contents()
// ----------------------------------------------------------
$allContent = file_get_contents($filename);
$output[] = ['ok', 'file_get_contents() — получено ' . strlen($allContent) . ' байт.'];

// ----------------------------------------------------------
// 5. ЧТЕНИЕ В МАССИВ СТРОК ЧЕРЕЗ file()
// ----------------------------------------------------------
$linesArray = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$output[] = ['ok', 'file() вернул массив из ' . count($linesArray) . ' строк.'];

// ----------------------------------------------------------
// 6. БЫСТРАЯ ЗАПИСЬ ЧЕРЕЗ file_put_contents()
// ----------------------------------------------------------
$quickFile = 'quick_write.txt';
$bytesWritten = file_put_contents($quickFile, "Быстрая запись через file_put_contents()\n");
$output[] = ['ok', "file_put_contents() записал $bytesWritten байт в '$quickFile'."];

// Дозапись с флагом FILE_APPEND
file_put_contents($quickFile, "Вторая строка (FILE_APPEND)\n", FILE_APPEND);
$output[] = ['ok', 'Дозапись с FILE_APPEND выполнена.'];

// ----------------------------------------------------------
// 7. БЛОКИРОВКА ФАЙЛА (flock)
// ----------------------------------------------------------
$lockFile = 'locked_counter.txt';

// Инициализируем счётчик если файл не существует
if (!file_exists($lockFile)) {
    file_put_contents($lockFile, '0');
}

$file = fopen($lockFile, 'r+');
if ($file) {
    if (flock($file, LOCK_EX)) {        // LOCK_EX — эксклюзивная блокировка
        $count = (int) fread($file, 20);
        $count++;
        rewind($file);                  // перемотать указатель в начало
        fwrite($file, $count);
        ftruncate($file, ftell($file)); // обрезать файл до текущей позиции
        flock($file, LOCK_UN);          // LOCK_UN — снять блокировку
        $output[] = ['ok', "flock(): счётчик посещений увеличен до $count."];
    } else {
        $output[] = ['err', 'Не удалось получить блокировку файла.'];
    }
    fclose($file);
}

// ----------------------------------------------------------
// 8. ОПЕРАЦИИ С ФАЙЛАМИ / МЕТАДАННЫЕ
// ----------------------------------------------------------
$output[] = ['info', 'file_exists(): ' . ($filename ? 'true' : 'false')];
$output[] = ['info', 'filesize(): '    . filesize($filename) . ' байт'];
$output[] = ['info', 'filemtime(): '   . date('d.m.Y H:i:s', filemtime($filename))];
$output[] = ['info', 'is_readable(): ' . (is_readable($filename) ? 'true' : 'false')];
$output[] = ['info', 'is_writable(): ' . (is_writable($filename) ? 'true' : 'false')];

// ----------------------------------------------------------
// 9. ПОИСК В ФАЙЛЕ (file() + grep-подобный поиск)
// ----------------------------------------------------------
$keyword = 'Блокировка';
$found   = array_filter($linesArray, fn($l) => stripos($l, $keyword) !== false);
$output[] = ['ok', "Поиск «$keyword»: найдено " . count($found) . ' совпадение(й).'];

// ----------------------------------------------------------
// 10. УДАЛЕНИЕ ВРЕМЕННОГО ФАЙЛА
// ----------------------------------------------------------
if (file_exists($quickFile)) {
    unlink($quickFile);
    $output[] = ['ok', "Временный файл '$quickFile' удалён."];
}

// ============================================================
//  HTML-вывод
// ============================================================
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Задание №1 — Работа с файлами в PHP</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Unbounded:wght@400;700&display=swap');

  :root {
    --bg:      #0d0f14;
    --surface: #161921;
    --border:  #252a36;
    --accent:  #4ade80;
    --accent2: #60a5fa;
    --warn:    #fb923c;
    --err:     #f87171;
    --text:    #e2e8f0;
    --muted:   #64748b;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    background: var(--bg);
    color: var(--text);
    font-family: 'JetBrains Mono', monospace;
    min-height: 100vh;
    padding: 40px 20px;
  }

  .container { max-width: 860px; margin: 0 auto; }

  .header {
    border-left: 4px solid var(--accent);
    padding-left: 20px;
    margin-bottom: 40px;
  }
  .header .lab { font-size: 11px; color: var(--muted); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 8px; }
  .header h1  { font-family: 'Unbounded', sans-serif; font-size: 22px; line-height: 1.3; }
  .header h1 span { color: var(--accent); }

  /* ---- sections ---- */
  .section {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 10px;
    margin-bottom: 28px;
    overflow: hidden;
  }
  .section-header {
    background: var(--border);
    padding: 10px 18px;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--accent2);
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .section-header .num {
    background: var(--accent2);
    color: var(--bg);
    border-radius: 4px;
    padding: 1px 7px;
    font-size: 10px;
    font-weight: 700;
  }
  .section-body { padding: 18px; }

  /* ---- log items ---- */
  .log { display: flex; flex-direction: column; gap: 8px; }
  .log-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 13px;
    line-height: 1.55;
    border-left: 3px solid transparent;
  }
  .log-item.ok   { background: #0d2818; border-color: var(--accent); }
  .log-item.err  { background: #2a0e0e; border-color: var(--err); }
  .log-item.info { background: #0e1a2a; border-color: var(--accent2); }

  .badge {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;
    padding: 2px 8px;
    border-radius: 4px;
    white-space: nowrap;
    flex-shrink: 0;
    margin-top: 1px;
  }
  .ok   .badge { background: var(--accent);  color: #0d0f14; }
  .err  .badge { background: var(--err);     color: #0d0f14; }
  .info .badge { background: var(--accent2); color: #0d0f14; }

  /* ---- file content ---- */
  .file-block {
    background: #0a0c10;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
  }
  .file-block .file-title {
    font-size: 11px;
    color: var(--muted);
    letter-spacing: 1px;
    margin-bottom: 10px;
  }
  .file-block .file-line {
    display: flex;
    gap: 16px;
    padding: 4px 0;
    border-bottom: 1px solid #1a1f2a;
    font-size: 13px;
  }
  .file-block .file-line:last-child { border-bottom: none; }
  .file-block .ln { color: var(--muted); min-width: 24px; text-align: right; font-size: 12px; }
  .file-block .lc { color: var(--accent); }

  /* ---- code snippet ---- */
  .code-block {
    background: #0a0c10;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 16px;
    font-size: 12px;
    line-height: 1.7;
    overflow-x: auto;
    margin-top: 16px;
  }
  .kw  { color: #c084fc; }
  .fn  { color: #60a5fa; }
  .str { color: #86efac; }
  .cm  { color: #475569; font-style: italic; }
  .var { color: #fbbf24; }

  .footer { text-align: center; color: var(--muted); font-size: 11px; margin-top: 40px; letter-spacing: 1px; }
</style>
</head>
<body>
<div class="container">

  <div class="header">
    <div class="lab">Лабораторная работа №5 · PHP</div>
    <h1>Задание №1 — <span>Типовые операции с файлами</span></h1>
  </div>

  <!-- RESULTS LOG -->
  <div class="section">
    <div class="section-header">
      <span class="num">LOG</span>
      Результаты выполнения операций
    </div>
    <div class="section-body">
      <div class="log">
        <?php foreach ($output as [$type, $msg]): ?>
        <div class="log-item <?= $type ?>">
          <span class="badge"><?= strtoupper($type) ?></span>
          <span><?= $msg ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- FILE CONTENT -->
  <div class="section">
    <div class="section-header">
      <span class="num">VIEW</span>
      Содержимое файла «demo_data.txt»
    </div>
    <div class="section-body">
      <div class="file-block">
        <div class="file-title">📄 demo_data.txt</div>
        <?php foreach ($lines as $i => $line): ?>
        <div class="file-line">
          <span class="ln"><?= $i + 1 ?></span>
          <span class="lc"><?= $line ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- KEY FUNCTIONS REFERENCE -->
  <div class="section">
    <div class="section-header">
      <span class="num">REF</span>
      Ключевые функции PHP для работы с файлами
    </div>
    <div class="section-body">
      <div class="code-block">
<span class="cm">// === ОТКРЫТИЕ / ЗАКРЫТИЕ ===</span>
<span class="var">$f</span> = <span class="fn">fopen</span>(<span class="str">'file.txt'</span>, <span class="str">'r'</span>);   <span class="cm">// r w a r+ w+ a+</span>
<span class="fn">fclose</span>(<span class="var">$f</span>);

<span class="cm">// === ЧТЕНИЕ ===</span>
<span class="fn">fgets</span>(<span class="var">$f</span>);                          <span class="cm">// одна строка</span>
<span class="fn">fread</span>(<span class="var">$f</span>, <span class="var">$bytes</span>);                  <span class="cm">// N байт</span>
<span class="fn">file_get_contents</span>(<span class="str">'file.txt'</span>);    <span class="cm">// весь файл → строка</span>
<span class="fn">file</span>(<span class="str">'file.txt'</span>);                   <span class="cm">// весь файл → массив строк</span>

<span class="cm">// === ЗАПИСЬ ===</span>
<span class="fn">fwrite</span>(<span class="var">$f</span>, <span class="str">'текст'</span>);
<span class="fn">file_put_contents</span>(<span class="str">'file.txt'</span>, <span class="str">'текст'</span>);          <span class="cm">// перезапись</span>
<span class="fn">file_put_contents</span>(<span class="str">'file.txt'</span>, <span class="str">'текст'</span>, <span class="kw">FILE_APPEND</span>); <span class="cm">// дозапись</span>

<span class="cm">// === БЛОКИРОВКА ===</span>
<span class="fn">flock</span>(<span class="var">$f</span>, <span class="kw">LOCK_EX</span>);  <span class="cm">// эксклюзивная (запись)</span>
<span class="fn">flock</span>(<span class="var">$f</span>, <span class="kw">LOCK_SH</span>);  <span class="cm">// разделяемая (чтение)</span>
<span class="fn">flock</span>(<span class="var">$f</span>, <span class="kw">LOCK_UN</span>);  <span class="cm">// снять блокировку</span>

<span class="cm">// === МЕТАДАННЫЕ ===</span>
<span class="fn">file_exists</span>(<span class="var">$path</span>);  <span class="fn">filesize</span>(<span class="var">$path</span>);  <span class="fn">filemtime</span>(<span class="var">$path</span>);
<span class="fn">is_readable</span>(<span class="var">$path</span>);  <span class="fn">is_writable</span>(<span class="var">$path</span>);

<span class="cm">// === НАВИГАЦИЯ В ФАЙЛЕ ===</span>
<span class="fn">rewind</span>(<span class="var">$f</span>);           <span class="cm">// в начало</span>
<span class="fn">fseek</span>(<span class="var">$f</span>, <span class="var">$offset</span>);  <span class="cm">// на позицию</span>
<span class="fn">ftell</span>(<span class="var">$f</span>);            <span class="cm">// текущая позиция</span>
<span class="fn">feof</span>(<span class="var">$f</span>);             <span class="cm">// конец файла?</span>

<span class="cm">// === УДАЛЕНИЕ ===</span>
<span class="fn">unlink</span>(<span class="str">'file.txt'</span>);
      </div>
    </div>
  </div>

  <div class="footer">PHP · Работа с файлами · Задание №1</div>
</div>
</body>
</html>
