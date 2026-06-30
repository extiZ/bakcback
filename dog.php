<?php
// index.php в корне проекта

// 1. Инициализируем сессию для корзины
session_start();

// 2. Включаем отображение ошибок для отладки
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 3. Подключаем конфигурацию базы данных
require_once 'config/db.php';

// 4. Получаем текущий маршрут из URL (по умолчанию 'home')
$route = $_GET['route'] ?? 'home';

// 5. Роутер: вызываем соответствующий контроллер в зависимости от маршрута
if ($route === 'catalog') {
    require_once 'controllers/ProductController.php';
    $controller = new ProductController();
    $controller->actionIndex();
} 
elseif ($route === 'cart/add') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    require_once 'controllers/CartController.php';
    CartController::actionAdd($id);
} 
// НОВЫЙ МАРШРУТ ДЛЯ УДАЛЕНИЯ ИЗ КОРЗИНЫ:
elseif ($route === 'cart/delete') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    require_once 'controllers/CartController.php';
    CartController::actionDelete($id);
}
elseif ($route === 'cart') {
    require_once 'controllers/CartController.php';
    $controller = new CartController();
    $controller->actionIndex();
}
else {
    require_once 'controllers/HomeController.php';
    $controller = new HomeController();
    $controller->actionIndex();
}