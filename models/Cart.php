<?php
// models/Cart.php

class Cart {
    
    // Добавить товар в корзину
    public static function addProduct($id) {
        $id = (int)$id;
        
        // Массив для товаров в корзине
        $productsInCart = [];
        
        // Если в корзине уже есть товары, загружаем их
        if (isset($_SESSION['products'])) {
            $productsInCart = $_SESSION['products'];
        }
        
        // Если товар уже есть в корзине, увеличиваем количество, иначе добавляем 1
        if (array_key_exists($id, $productsInCart)) {
            $productsInCart[$id]++;
        } else {
            $productsInCart[$id] = 1;
        }
        
        $_SESSION['products'] = $productsInCart;
        
        return self::countItems();
    }

    // Подсчитать общее количество товаров в корзине (для иконки в шапке)
    public static function countItems() {
        if (isset($_SESSION['products'])) {
            $count = 0;
            foreach ($_SESSION['products'] as $id => $quantity) {
                $count += $quantity;
            }
            return $count;
        }
        return 0;
    }
    // Получить массив товаров, которые лежат в корзине
    public static function getProducts() {
        if (!isset($_SESSION['products']) || empty($_SESSION['products'])) {
            return false;
        }
        return $_SESSION['products'];
    }

    // Посчитать общую стоимость всех товаров в корзине
    public static function getTotalPrice($products, $productsInCart) {
        $total = 0;
        foreach ($products as $product) {
            $total += $product['price'] * $productsInCart[$product['id']];
        }
        return $total;
    }
    // Удалить товар из корзины полностью
    public static function deleteProduct($id) {
        $id = (int)$id;
        
        if (isset($_SESSION['products'])) {
            $productsInCart = $_SESSION['products'];
            
            // Если товар есть в сессии, удаляем его из массива
            if (array_key_exists($id, $productsInCart)) {
                unset($productsInCart[$id]);
            }
            
            // Записываем обновленный массив обратно в сессию
            $_SESSION['products'] = $productsInCart;
        }
        
        return true;
    }
}