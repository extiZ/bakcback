<?php
require_once 'models/Cart.php';

class CartController {
    
    // Экшен добавления товара в корзину
    public static function actionAdd($id) {
        // Добавляем товар в корзину
        Cart::addProduct($id);
        
        // Возвращаем пользователя обратно на ту страницу, откуда он нажал кнопку
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    // Отображение страницы корзины
    public function actionIndex() {
        // 1. Получаем список ID и количества товаров из сессии
        $productsInCart = Cart::getProducts();
        
        $products = [];
        $totalPrice = 0;
        
        if ($productsInCart) {
            // Получаем ключи (это ID товаров)
            $productsIds = array_keys($productsInCart);
            
            // Запрашиваем из БД инфо по этим ID
            $db = Db::getConnection();
            $idsString = implode(',', array_fill(0, count($productsIds), '?'));
            $sql = "SELECT * FROM products WHERE id IN ($idsString)";
            
            $result = $db->prepare($sql);
            $result->execute($productsIds);
            $products = $result->fetchAll();
            
            // Считаем общую стоимость
            $totalPrice = Cart::getTotalPrice($products, $productsInCart);
        }
        
        // 2. Подключаем виды
        require_once 'views/layouts/header.php';
        require_once 'views/cart.php';
        require_once 'views/layouts/footer.php';
    }
    // Экшен для удаления товара из корзины
    public static function actionDelete($id) {
        // Удаляем товар
        Cart::deleteProduct($id);
        
        // Перенаправляем обратно на страницу корзины
        header("Location: /index.php?route=cart");
        exit();
    }
}