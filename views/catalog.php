<section class="products" style="padding-top: 120px;"> 
    <div class="container">
        
        <div class="section-title">
            <h2>Наш каталог игр</h2>
        </div>

        <form method="GET" action="/index.php" style="margin-bottom: 30px; display: flex; gap: 15px; flex-wrap: wrap; align-items: center;">
            <input type="hidden" name="route" value="catalog">

            <input type="text" name="search" value="<?= htmlspecialchars($searchQuery ?? '') ?>" placeholder="Поиск игр..." style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">

            <select name="category" style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                <option value="">Все жанры</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($categoryId) && $categoryId == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?> 
            </select>

            <select name="sort" style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                <option value="id_desc" <?= ($sortOrder == 'id_desc') ? 'selected' : '' ?>>Сначала новые</option>
                <option value="price_asc" <?= ($sortOrder == 'price_asc') ? 'selected' : '' ?>>Цена: по возрастанию</option>
                <option value="price_desc" <?= ($sortOrder == 'price_desc') ? 'selected' : '' ?>>Цена: по убыванию</option>
                <option value="name_asc" <?= ($sortOrder == 'name_asc') ? 'selected' : '' ?>>По алфавиту (А-Я)</option>
            </select>

            <button type="submit" class="btn-orange" style="padding: 10px 20px; cursor: pointer;">Применить</button>
            <a href="/index.php?route=catalog" style="text-decoration: none; color: #666;">Сбросить</a>
        </form>

        <div class="product-bottom" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px;">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="small-product" style="margin: 0; width: 100%;">
                        <div class="product-box small-box">
                            <img src="https://loremflickr.com/300/300/videogame,gaming?lock=<?= $product['id'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <h4><?= htmlspecialchars($product['name']) ?></h4>
                        <p style="font-weight: 600; color: #ff6a00; margin: 10px 0;"><?= $product['price'] ?> руб.</p>
                        
                        <a href="/index.php?route=cart/add&id=<?= $product['id'] ?>" class="btn-orange" style="display: inline-block; padding: 8px 15px; margin-top: 10px; text-decoration: none; font-size: 14px; border-radius: 5px; text-align: center;">
                            Добавить в корзину
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Игры не найдены.</p>
            <?php endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination" style="margin-top: 40px; display: flex; justify-content: center; gap: 10px;">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php 
                        $link = "/index.php?route=catalog&page=$i";
                        if ($categoryId) $link .= "&category=$categoryId";
                        if (!empty($searchQuery)) $link .= "&search=" . urlencode($searchQuery);
                        if ($sortOrder) $link .= "&sort=$sortOrder";
                    ?>
                    <a href="<?= $link ?>" style="padding: 10px 15px; border: 1px solid #ff6a00; border-radius: 5px; text-decoration: none; <?= $page == $i ? 'background-color: #ff6a00; color: white;' : 'color: #ff6a00;' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
