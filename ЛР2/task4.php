<div class="task">
    <h2>📊 Задание 4: Массивы</h2>
    <?php
    $arr = [5, -2, 10, -8, 3];
    echo "<p>Исходный массив: " . implode(", ", $arr) . "</p>";

    $sum = 0;
    foreach ($arr as $val) {
        if ($val > 0) $sum += $val;
    }
    echo "<p>Сумма положительных элементов: $sum</p>";

    sort($arr);
    echo "<p>Отсортированный по возрастанию: " . implode(", ", $arr) . "</p>";
    ?>
</div>