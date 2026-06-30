<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP: практические задания</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
            background-color: #f0f2f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .section {
            background: white;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .section h2 {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-top: 0;
        }
        .result {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #28a745;
            border-radius: 5px;
        }
        .code-block {
            background-color: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            margin: 15px 0;
        }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .image-container {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .image-container img {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <h1>📌 Практические задания по PHP</h1>
    
    <!-- ============================================
         ЗАДАНИЕ №1: ТИПЫ ДАННЫХ, ОПЕРАЦИИ, ОПЕРАТОРЫ
         ============================================ -->
    <div class="section">
        <h2>📊 ЗАДАНИЕ №1: Типы данных, операции и операторы PHP</h2>
        
        <?php
        // ========== 1.1 ДЕМОНСТРАЦИЯ ТИПОВ ДАННЫХ ==========
        echo "<div class='result'>";
        echo "<h3>1.1 Типы данных в PHP</h3>";
        
        // Разные типы данных
        $integerVar = 42;                    // integer (целое)
        $floatVar = 3.14159;                 // float (дробное)
        $stringVar = "Привет, мир!";         // string (строка)
        $boolVar = true;                     // boolean (логический)
        $nullVar = null;                     // NULL
        $arrayVar = [1, 2, 3, "четыре"];     // array (массив)
        
        // Вывод типов
        echo "<table>";
        echo "<tr><th>Переменная</th><th>Значение</th><th>Тип</th></tr>";
        echo "<tr><td>\$integerVar</td><td>$integerVar</td><td>" . gettype($integerVar) . "</td></tr>";
        echo "<tr><td>\$floatVar</td><td>$floatVar</td><td>" . gettype($floatVar) . "</td></tr>";
        echo "<tr><td>\$stringVar</td><td>$stringVar</td><td>" . gettype($stringVar) . "</td></tr>";
        echo "<tr><td>\$boolVar</td><td>" . ($boolVar ? "true" : "false") . "</td><td>" . gettype($boolVar) . "</td></tr>";
        echo "<tr><td>\$nullVar</td><td>null</td><td>" . gettype($nullVar) . "</td></tr>";
        echo "<tr><td>\$arrayVar</td><td>" . implode(", ", $arrayVar) . "</td><td>" . gettype($arrayVar) . "</td></tr>";
        echo "</table>";
        echo "</div>";
        
        // ========== 1.2 АРИФМЕТИЧЕСКИЕ ОПЕРАЦИИ ==========
        echo "<div class='result'>";
        echo "<h3>1.2 Арифметические операции</h3>";
        
        $a = 25;
        $b = 7;
        
        echo "<ul>";
        echo "<li><b>Сложение:</b> $a + $b = " . ($a + $b) . "</li>";
        echo "<li><b>Вычитание:</b> $a - $b = " . ($a - $b) . "</li>";
        echo "<li><b>Умножение:</b> $a * $b = " . ($a * $b) . "</li>";
        echo "<li><b>Деление:</b> $a / $b = " . ($a / $b) . "</li>";
        echo "<li><b>Деление по модулю (остаток):</b> $a % $b = " . ($a % $b) . "</li>";
        echo "<li><b>Возведение в степень:</b> $a ** 2 = " . ($a ** 2) . "</li>";
        echo "</ul>";
        echo "</div>";
        
        // ========== 1.3 ОПЕРАТОРЫ СРАВНЕНИЯ ==========
        echo "<div class='result'>";
        echo "<h3>1.3 Операторы сравнения</h3>";
        
        $x = 10;
        $y = "10";
        
        echo "<ul>";
        echo "<li><b>Равно (==):</b> $x == $y → " . ($x == $y ? "true" : "false") . "</li>";
        echo "<li><b>Тождественно равно (===):</b> $x === $y → " . ($x === $y ? "true" : "false") . "</li>";
        echo "<li><b>Не равно (!=):</b> $x != $y → " . ($x != $y ? "true" : "false") . "</li>";
        echo "<li><b>Больше (>):</b> $x > 5 → " . ($x > 5 ? "true" : "false") . "</li>";
        echo "<li><b>Меньше или равно (<=):</b> $x <= 10 → " . ($x <= 10 ? "true" : "false") . "</li>";
        echo "<li><b>Космический корабль (<=>):</b> $x <=> $y → " . ($x <=> $y) . "</li>";
        echo "</ul>";
        echo "<p><small>Космический корабль: -1 (меньше), 0 (равно), 1 (больше)</small></p>";
        echo "</div>";
        
        // ========== 1.4 ЛОГИЧЕСКИЕ ОПЕРАТОРЫ ==========
        echo "<div class='result'>";
        echo "<h3>1.4 Логические операторы</h3>";
        
        $isLogged = true;
        $hasPermission = false;
        
        echo "<ul>";
        echo "<li><b>И (AND):</b> " . ($isLogged && $hasPermission ? "Доступ разрешен" : "Доступ запрещен") . "</li>";
        echo "<li><b>ИЛИ (OR):</b> " . ($isLogged || $hasPermission ? "Одно из условий выполнено" : "Ни одно не выполнено") . "</li>";
        echo "<li><b>НЕ (!):</b> " . (!$hasPermission ? "Нет прав - доступ закрыт" : "Есть права") . "</li>";
        echo "<li><b>Исключающее ИЛИ (XOR):</b> " . ($isLogged xor $hasPermission ? "Только авторизован, но без прав" : "Оба или ни одно") . "</li>";
        echo "</ul>";
        echo "</div>";
        ?>
    </div>
    
    <!-- ============================================
         ЗАДАНИЕ №2: ПОЛЬЗОВАТЕЛЬСКИЕ ФУНКЦИИ
         ============================================ -->
    <div class="section">
        <h2>⚙️ ЗАДАНИЕ №2: Пользовательские функции</h2>
        
        <?php
        // ========== 2.1 ФУНКЦИЯ ДЛЯ РАСЧЕТА СКИДКИ ==========
        echo "<div class='result'>";
        echo "<h3>2.1 Функция расчета скидки</h3>";
        
        function calculateDiscount($price, $discountPercent = 10) {
            if ($price <= 0) {
                return "Цена должна быть положительной";
            }
            if ($discountPercent < 0 || $discountPercent > 100) {
                return "Скидка должна быть от 0 до 100%";
            }
            
            $discountAmount = $price * $discountPercent / 100;
            $finalPrice = $price - $discountAmount;
            
            return [
                'original' => $price,
                'discount' => $discountPercent,
                'amount' => round($discountAmount, 2),
                'final' => round($finalPrice, 2)
            ];
        }
        
        $price1 = 5000;
        $price2 = 12000;
        
        $result1 = calculateDiscount($price1, 15);
        $result2 = calculateDiscount($price2, 25);
        
        echo "<p><b>Товар 1:</b> Цена {$price1} руб. → Скидка {$result1['discount']}% → Сумма скидки: {$result1['amount']} руб. → Итого: {$result1['final']} руб.</p>";
        echo "<p><b>Товар 2:</b> Цена {$price2} руб. → Скидка {$result2['discount']}% → Сумма скидки: {$result2['amount']} руб. → Итого: {$result2['final']} руб.</p>";
        echo "</div>";
        
        // ========== 2.2 ФУНКЦИЯ С ПЕРЕМЕННЫМ ЧИСЛОМ ПАРАМЕТРОВ ==========
        echo "<div class='result'>";
        echo "<h3>2.2 Функция для суммирования чисел (с переменным числом параметров)</h3>";
        
        function sumAll(...$numbers) {
            $sum = 0;
            foreach ($numbers as $num) {
                if (is_numeric($num)) {
                    $sum += $num;
                }
            }
            return $sum;
        }
        
        echo "<p>Сумма чисел 5, 10, 15: " . sumAll(5, 10, 15) . "</p>";
        echo "<p>Сумма чисел 1, 2, 3, 4, 5, 6, 7: " . sumAll(1, 2, 3, 4, 5, 6, 7) . "</p>";
        echo "<p>Сумма чисел 100, 200, 50, 75: " . sumAll(100, 200, 50, 75) . "</p>";
        echo "</div>";
        
        // ========== 2.3 ФУНКЦИЯ С ВОЗВРАТОМ МАССИВА ==========
        echo "<div class='result'>";
        echo "<h3>2.3 Функция для генерации таблицы умножения</h3>";
        
        function multiplicationTable($size = 10) {
            $table = [];
            for ($i = 1; $i <= $size; $i++) {
                for ($j = 1; $j <= $size; $j++) {
                    $table[$i][$j] = $i * $j;
                }
            }
            return $table;
        }
        
        $table5 = multiplicationTable(5);
        
        echo "<table>";
        echo "<tr><th>×</th>";
        for ($i = 1; $i <= 5; $i++) {
            echo "<th>$i</th>";
        }
        echo "</tr>";
        
        for ($i = 1; $i <= 5; $i++) {
            echo "<tr><th>$i</th>";
            for ($j = 1; $j <= 5; $j++) {
                echo "<td>" . $table5[$i][$j] . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        ?>
    </div>
    
    <!-- ============================================
         ЗАДАНИЕ №3: МАССИВЫ, СТРОКИ, ВСТРОЕННЫЕ ФУНКЦИИ
         ============================================ -->
    <div class="section">
        <h2>📚 ЗАДАНИЕ №3: Массивы, строки, встроенные функции</h2>
        
        <?php
        // ========== 3.1 РАБОТА С МАССИВАМИ ==========
        echo "<div class='result'>";
        echo "<h3>3.1 Операции с массивами</h3>";
        
        $students = [
            ['name' => 'Иванов Иван', 'grade' => 85, 'group' => 'ПЗТ-41'],
            ['name' => 'Петров Петр', 'grade' => 92, 'group' => 'ПЗТ-41'],
            ['name' => 'Сидорова Анна', 'grade' => 78, 'group' => 'ПЗТ-42'],
            ['name' => 'Козлов Дмитрий', 'grade' => 95, 'group' => 'ПЗТ-42'],
            ['name' => 'Морозова Елена', 'grade' => 88, 'group' => 'ПЗТ-41']
        ];
        
        // Сортировка по оценке (по убыванию)
        usort($students, function($a, $b) {
            return $b['grade'] <=> $a['grade'];
        });
        
        echo "<h4>Список студентов (отсортирован по оценке):</h4>";
        echo "<table>";
        echo "<tr><th>ФИО</th><th>Группа</th><th>Оценка</th></tr>";
        foreach ($students as $student) {
            echo "<tr>";
            echo "<td>{$student['name']}</td>";
            echo "<td>{$student['group']}</td>";
            echo "<td><b>{$student['grade']}</b></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Фильтрация студентов с оценкой выше 85
        $topStudents = array_filter($students, function($student) {
            return $student['grade'] > 85;
        });
        
        echo "<h4>Студенты с оценкой выше 85 баллов:</h4>";
        foreach ($topStudents as $student) {
            echo "<li>{$student['name']} - {$student['grade']} баллов</li>";
        }
        
        // Подсчет средней оценки
        $averageGrade = array_sum(array_column($students, 'grade')) / count($students);
        echo "<p><b>Средняя оценка по всем студентам:</b> " . round($averageGrade, 2) . " баллов</p>";
        
        echo "</div>";
        
        // ========== 3.2 СТРОКОВЫЙ ТИП ДАННЫХ ==========
        echo "<div class='result'>";
        echo "<h3>3.2 Работа со строками</h3>";
        
        $text = "  PHP - это мощный язык программирования для веб-разработки!  ";
        
        echo "<ul>";
        echo "<li><b>Исходная строка:</b> \"$text\"</li>";
        echo "<li><b>Длина строки (strlen):</b> " . strlen($text) . " символов</li>";
        echo "<li><b>Без пробелов (trim):</b> \"" . trim($text) . "\"</li>";
        echo "<li><b>В верхний регистр (strtoupper):</b> " . strtoupper(trim($text)) . "</li>";
        echo "<li><b>В нижний регистр (strtolower):</b> " . strtolower(trim($text)) . "</li>";
        echo "<li><b>Поиск слова 'PHP' (strpos):</b> позиция " . strpos($text, "PHP") . "</li>";
        echo "<li><b>Замена 'PHP' на 'Python' (str_replace):</b> " . str_replace("PHP", "Python", $text) . "</li>";
        echo "<li><b>Разбить на слова (explode):</b> </li>";
        $words = explode(" ", trim($text));
        echo "<li>Слов: " . count($words) . " (" . implode(", ", $words) . ")</li>";
        echo "<li><b>Подстрока (substr):</b> " . substr(trim($text), 0, 20) . "...</li>";
        echo "</ul>";
        echo "</div>";
        
        // ========== 3.3 МАТЕМАТИЧЕСКИЕ ФУНКЦИИ ==========
        echo "<div class='result'>";
        echo "<h3>3.3 Встроенные математические функции</h3>";
        
        $numbers = [15, 28, 7, 42, 3, 19, 56, 8];
        
        echo "<ul>";
        echo "<li><b>Исходный массив:</b> " . implode(", ", $numbers) . "</li>";
        echo "<li><b>Минимум (min):</b> " . min($numbers) . "</li>";
        echo "<li><b>Максимум (max):</b> " . max($numbers) . "</li>";
        echo "<li><b>Сумма (array_sum):</b> " . array_sum($numbers) . "</li>";
        echo "<li><b>Среднее арифметическое:</b> " . round(array_sum($numbers) / count($numbers), 2) . "</li>";
        echo "<li><b>Квадратный корень из 64 (sqrt):</b> " . sqrt(64) . "</li>";
        echo "<li><b>Случайное число от 1 до 100 (rand):</b> " . rand(1, 100) . "</li>";
        echo "<li><b>Округление 3.14159 (round):</b> " . round(3.14159, 2) . "</li>";
        echo "<li><b>Абсолютное значение (abs):</b> " . abs(-25) . "</li>";
        echo "</ul>";
        echo "</div>";
        
        // ========== 3.4 РАБОТА С ДАТОЙ И ВРЕМЕНЕМ ==========
        echo "<div class='result'>";
        echo "<h3>3.4 Работа с датой, временем и календарем</h3>";
        
        date_default_timezone_set("Europe/Minsk");
        
        $currentDate = date("d.m.Y");
        $currentTime = date("H:i:s");
        $currentWeekday = date("l");
        $currentMonth = date("F");
        $currentYear = date("Y");
        
        echo "<ul>";
        echo "<li><b>Текущая дата (d.m.Y):</b> $currentDate</li>";
        echo "<li><b>Текущее время (H:i:s):</b> $currentTime</li>";
        echo "<li><b>День недели (l):</b> $currentWeekday</li>";
        echo "<li><b>Месяц (F):</b> $currentMonth</li>";
        echo "<li><b>Год (Y):</b> $currentYear</li>";
        echo "<li><b>Unix timestamp сейчас:</b> " . time() . "</li>";
        echo "</ul>";
        
        // Вычисление разницы между датами
        $startDate = strtotime("2025-01-01");
        $endDate = strtotime("2025-12-31");
        $daysDiff = round(($endDate - $startDate) / (60 * 60 * 24));
        
        echo "<p><b>Количество дней в 2025 году:</b> $daysDiff дней</p>";
        
        // Дата через 30 дней
        $futureDate = date("d.m.Y", strtotime("+30 days"));
        echo "<p><b>Дата через 30 дней:</b> $futureDate</p>";
        
        // Проверка високосного года
        $yearToCheck = 2024;
        echo "<p><b>$yearToCheck год:</b> " . (checkdate(2, 29, $yearToCheck) ? "високосный" : "не високосный") . "</p>";
        
        echo "</div>";
        
        // ========== 3.5 ДОПОЛНИТЕЛЬНЫЕ ФУНКЦИИ ==========
        echo "<div class='result'>";
        echo "<h3>3.5 Дополнительные встроенные функции</h3>";
        
        // Массив для демонстрации
        $testArray = ['apple', 'banana', 'cherry', 'date', 'elderberry'];
        
        echo "<ul>";
        echo "<li><b>Слияние массивов (array_merge):</b> " . implode(", ", array_merge($testArray, ['fig', 'grape'])) . "</li>";
        echo "<li><b>Удаление дубликатов (array_unique):</b> " . implode(", ", array_unique([1,2,2,3,3,3,4])) . "</li>";
        echo "<li><b>Обратный порядок (array_reverse):</b> " . implode(", ", array_reverse($testArray)) . "</li>";
        echo "<li><b>Случайный ключ (array_rand):</b> " . $testArray[array_rand($testArray)] . "</li>";
        echo "<li><b>Поиск в массиве (in_array):</b> " . (in_array("banana", $testArray) ? "banana найдена" : "не найдена") . "</li>";
        echo "<li><b>Форматирование числа (number_format):</b> " . number_format(1234567.89, 2, ',', ' ') . "</li>";
        echo "<li><b>Генерация хэша (md5):</b> " . md5("password123") . "</li>";
        echo "</ul>";
        echo "</div>";
        ?>
    </div>
    
    <!-- ============================================
         НОВЫЙ РАЗДЕЛ: INCLUDE и MATCH
         ============================================ -->
    <div class="section">
        <h2>🔧 ОПЕРАТОРЫ: include (подключение файлов) и match</h2>
        
        <?php
        // ========== 4.1 ДЕМОНСТРАЦИЯ INCLUDE (подключение изображения) ==========
        echo "<div class='result'>";
        echo "<h3>4.1 Оператор include — подключение внешнего файла (изображения)</h3>";
        echo "<p><b>include</b> — подключает и выполняет указанный файл. Если файл не найден, выдает предупреждение (warning), но скрипт продолжает работу.</p>";
        
        // Подключаем изображение из папки media
        $imagePath = __DIR__ . '/media/togue.jpg';
        
        // Проверяем существование файла
        if (file_exists($imagePath)) {
            echo "<div class='image-container'>";
            echo "<h4>📸 Изображение, подключенное через include:</h4>";
            echo "<img src='media/togue.jpg' alt='Изображение touge.jpg' style='max-width: 500px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);'>";
            echo "<p><small>Файл успешно найден и отображен. Путь: media/togue.jpg</small></p>";
            echo "</div>";
        } else {
            echo "<div class='image-container' style='background-color: #fff3cd; border-left-color: #ffc107;'>";
            echo "<h4>⚠️ Файл не найден</h4>";
            echo "<p>Файл <b>media/togue.jpg</b> не обнаружен. Убедитесь, что:</p>";
            echo "<ul>";
            echo "<li>Папка <b>media</b> существует в корне проекта</li>";
            echo "<li>В папке <b>media</b> есть файл <b>togue.jpg</b></li>";
            echo "</ul>";
            echo "<p>Создайте папку <code>media</code> и поместите в нее изображение <code>togue.jpg</code></p>";
            echo "</div>";
        }
        
        echo "<div class='code-block'>";
        echo "// Пример использования include для подключения изображения<br>";
        echo "if (file_exists('media/togue.jpg')) {<br>";
        echo "    echo '&lt;img src=\"media/togue.jpg\" alt=\"togue\">';<br>";
        echo "}";
        echo "</div>";
        
        echo "<p><b>include_once</b> — подключает файл только один раз, даже если вызывается несколько раз.</p>";
        echo "<div class='code-block'>include_once 'media/togue.jpg'; // подключает файл один раз</div>";
        echo "</div>";
        
        // ========== 4.2 ДЕМОНСТРАЦИЯ MATCH (PHP 8+) ==========
        echo "<div class='result'>";
        echo "<h3>4.2 Оператор match (PHP 8+)</h3>";
        echo "<p><b>match</b> — более строгая и лаконичная альтернатива switch. Возвращает значение, использует строгое сравнение (===).</p>";
        
        // Пример 1: Определение статуса заказа
        $orderStatus = "shipped";
        
        $statusMessage = match($orderStatus) {
            "pending" => "Заказ ожидает обработки",
            "processing" => "Заказ обрабатывается",
            "shipped" => "Заказ отправлен",
            "delivered" => "Заказ доставлен",
            "cancelled" => "Заказ отменен",
            default => "Статус неизвестен"
        };
        
        echo "<p><b>Статус заказа:</b> $orderStatus → $statusMessage</p>";
        
        // Пример 2: Расчет оценки студента
        $score = 85;
        
        $grade = match(true) {
            $score >= 90 => "Отлично (A)",
            $score >= 80 => "Хорошо (B)",
            $score >= 70 => "Удовлетворительно (C)",
            $score >= 60 => "Ниже среднего (D)",
            default => "Неудовлетворительно (F)"
        };
        
        echo "<p><b>Баллы:</b> $score → Оценка: $grade</p>";
        
        // Пример 3: Определение времени года по месяцу
        $month = date("n"); // 1-12
        
        $season = match($month) {
            12, 1, 2 => "❄️ Зима",
            3, 4, 5 => "🌸 Весна",
            6, 7, 8 => "☀️ Лето",
            9, 10, 11 => "🍂 Осень",
            default => "Неизвестно"
        };
        
        echo "<p><b>Текущий месяц (номер $month):</b> $season</p>";
        
        // Пример 4: Определение типа автомобиля по марке
        $carBrand = "Ford";
        
        $carType = match($carBrand) {
            "Toyota", "Honda", "Nissan" => "Японский автомобиль",
            "Ford", "Chevrolet", "Dodge" => "Американский автомобиль",
            "BMW", "Mercedes", "Audi", "Volkswagen" => "Немецкий автомобиль",
            default => "Автомобиль другого происхождения"
        };
        
        echo "<p><b>Марка:</b> $carBrand → $carType</p>";
        
        echo "<div class='code-block' style='margin-top:10px;'>";
        echo "// match возвращает значение<br>";
        echo "\$result = match(\$variable) {<br>";
        echo "    'value1' => 'результат для value1',<br>";
        echo "    'value2' => 'результат для value2',<br>";
        echo "    'value3', 'value4' => 'результат для value3 или value4',<br>";
        echo "    default => 'значение по умолчанию',<br>";
        echo "};";
        echo "</div>";
        
        echo "<div style='margin-top:15px; padding:10px; background:#e7f5e9; border-left:4px solid #28a745; border-radius:5px;'>";
        echo "<b>📌 Отличия match от switch:</b><br>";
        echo "<ul style='margin-top:5px;'>";
        echo "<li>match <b>возвращает значение</b>, switch — нет</li>";
        echo "<li>match использует <b>строгое сравнение (===)</b>, switch — нестрогое (==)</li>";
        echo "<li>match <b>обязательно</b> должен обрабатывать все варианты или иметь default</li>";
        echo "<li>match <b>не требует break</b></li>";
        echo "<li>match может обрабатывать несколько условий через запятую: 12, 1, 2 => 'Зима'</li>";
        echo "</ul>";
        echo "</div>";
        echo "</div>";
        ?>
    </div>
    
    <!-- Подвал -->
    <div style="text-align: center; margin-top: 30px; padding: 20px; background-color: #e9ecef; border-radius: 10px;">
        <p>© <?php echo date("Y"); ?> Практические задания по PHP. Все темы выполнены.</p>
        <p><small>Типы данных | Операции | Операторы | Функции | Массивы | Строки | Математические функции | Дата и время | include | match</small></p>
    </div>
</div>
</body>
</html>