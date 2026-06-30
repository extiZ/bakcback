<?php

class HomeController {
    public function actionIndex() {
        // Подключаем по очереди части нашей страницы
        require_once 'views/layouts/header.php';
        require_once 'views/home.php';
        require_once 'views/layouts/footer.php';
    }
}