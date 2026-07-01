<?php
// models/Category.php

class Category {
    public static function getCategoriesList() {
        $db = Db::getConnection();
        $result = $db->query("SELECT id, name, code FROM categories ORDER BY id ASC");
        return $result->fetchAll();
    }
}