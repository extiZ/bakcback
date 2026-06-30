<div class="task">
    <h2>📅 Задание 2: Дни недели в месяце</h2>
    <?php
    $month = 4; // апрель
    $year = 2026;

    $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    echo "<p>Месяц: $month / $year</p>";
    echo "<ul>";
    for ($d = 1; $d <= $days; $d++) {
        $date = mktime(0, 0, 0, $month, $d, $year);
        $weekday = date('l', $date);
        echo "<li>$d - $weekday</li>";
    }
    echo "</ul>";
    ?>
</div>