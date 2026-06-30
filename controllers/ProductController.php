<?php
// controllers/ProductController.php
require_once 'models/Product.php';
require_once 'models/Category.php';

class ProductController {
    
    public function actionIndex() {
        // 1. Получаем параметры фильтрации из GET-запроса
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
        $sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'id_desc';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        $limit = 4; // Выведем по 4 товара на страницу для теста пагинации
        
        // 2. Берем данные из моделей
        $categories = Category::getCategoriesList();
        $products = Product::getProducts($page, $limit, $categoryId, $searchQuery, $sortOrder);
        
        // Данные для пагинации
        $totalProducts = Product::getTotalProducts($categoryId, $searchQuery);
        $totalPages = ceil($totalProducts / $limit);
        
        // 3. Передаем всё в View
        require_once 'views/layouts/header.php';
        require_once 'views/catalog.php';
        require_once 'views/layouts/footer.php';
    }
}