<?php
// config/db.php

class Db {
    /**
     * Устанавливает соединение с базой данных
     * @return PDO
     */
    public static function getConnection() {
        $host = '127.0.0.1'; // В Open Server надежнее указывать IP вместо localhost
        $db   = 'pet_store_db'; // Имя базы данных, которую ты создал в phpMyAdmin
        $user = 'root';         // Пользователь по умолчанию в Open Server
        $pass = '1234';             // Пароль по умолчанию (пустой)
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
        try {
            // Создаем объект PDO для безопасной работы с SQL
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Включаем выброс исключений при ошибках
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Возвращаем данные в виде ассоциативных массивов
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"     // Принудительно ставим кодировку
            ]);
            
            return $pdo;
        } catch (PDOException $e) {
            // Если подключиться не удалось — выводим ошибку на экран
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }
}