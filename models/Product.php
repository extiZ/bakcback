<?php
// models/Product.php

class Product {
    
    // Получить список товаров с фильтрами, поиском и сортировкой
    public static function getProducts($page = 1, $limit = 6, $categoryId = null, $searchQuery = '', $sortOrder = 'id_desc') {
        $db = Db::getConnection();
        
        // Считаем отступ для пагинации (OFFSET)
        $offset = ($page - 1) * $limit;
        
        // Базовый SQL запрос
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];
        
        // Фильтрация по категории
        if ($categoryId) {
            $sql .= " AND category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }
        
        // Поиск по названию или описанию
        if (!empty($searchQuery)) {
            $sql .= " AND (name LIKE :search OR description LIKE :search)";
            $params[':search'] = "%" . $searchQuery . "%";
        }
        
        // Сортировка
        switch ($sortOrder) {
            case 'price_asc':
                $sql .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY price DESC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY name ASC";
                break;
            default:
                $sql .= " ORDER BY id DESC"; // Сначала новые
                break;
        }
        
        // Добавляем лимиты для пагинации
        $sql .= " LIMIT :limit OFFSET :offset";
        
        $result = $db->prepare($sql);
        
        // Привязываем параметры лимитов вручную, так как PDO требует для них INT
        $result->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $result->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        foreach ($params as $key => $val) {
            $result->bindValue($key, $val);
        }
        
        $result->execute();
        return $result->fetchAll();
    }

    // Подсчет общего количества товаров для пагинации
    public static function getTotalProducts($categoryId = null, $searchQuery = '') {
        $db = Db::getConnection();
        $sql = "SELECT COUNT(id) AS count FROM products WHERE 1=1";
        $params = [];

        if ($categoryId) {
            $sql .= " AND category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }
        if (!empty($searchQuery)) {
            $sql .= " AND (name LIKE :search OR description LIKE :search)";
            $params[':search'] = "%" . $searchQuery . "%";
        }

        $result = $db->prepare($sql);
        $result->execute($params);
        $row = $result->fetch();
        return $row['count'];
    }
}