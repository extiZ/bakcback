<?php 
// Подключаем модель корзины, чтобы шапка могла вызывать метод подсчета товаров Cart::countItems()
require_once 'models/Cart.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Store</title>
    <link rel="stylesheet" href="/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<header class="header">
    <div class="container nav">
        <div class="logo">
            <img src="https://loremflickr.com/40/40/videogame" alt="">
            <span>GAME</span>
        </div>
        <nav>
            <ul>
                <li><a href="/index.php?route=catalog">Игры</a></li> 
                <li><a href="#">Новинки</a></li>
                <li><a href="#">О нас</a></li>
                <li><a href="#">Популярное</a></li>
            </ul>
        </nav>
        <div class="nav-icons">
            <span>⌕</span>
            
            <a href="/index.php?route=cart" style="text-decoration: none; color: inherit; position: relative; display: inline-block; cursor: pointer;">
                <span>🛒</span>
                <?php if (Cart::countItems() > 0): ?>
                    <b style="background: #ff6a00; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; position: absolute; top: -10px; right: -10px; font-family: 'Poppins', sans-serif;">
                        <?= Cart::countItems() ?>
                    </b>
                <?php endif; ?>
            </a>
            
            <span>☰</span>
        </div>
    </div>
</header>
