<div class="task">
    <h2>🧮 Задание 6: Пользовательская функция</h2>
    <?php
    function calculateZ($x, $y) {
        if ($x == 0) {
            return "Ошибка: деление на ноль (x = 0)";
        }
        if ($y < 0) {
            return "Ошибка: корень из отрицательного числа (y < 0)";
        }

        $z = ($x * $x + 18 * $y - sqrt($y)) / (7 * $x * $x);
        return round($z, 4);
    }

    $x = 2;
    $y = 9;

    echo "<p>Формула: z = (x² + 18y - √y) / (7x²)</p>";
    echo "<p>При x = $x, y = $y</p>";
    $result = calculateZ($x, $y);
    echo "<p>Результат: z = $result</p>";

    // Дополнительные тесты для проверки ошибок
    echo "<hr><p><strong>Проверка обработки ошибок:</strong></p>";
    echo "<p>x = 0, y = 9 → " . calculateZ(0, 9) . "</p>";
    echo "<p>x = 2, y = -4 → " . calculateZ(2, -4) . "</p>";
    ?>
</div>