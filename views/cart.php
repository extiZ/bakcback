<section class="cart-section" style="padding-top: 120px; padding-bottom: 50px;">
    <div class="container">
        <div class="section-title">
            <h2>Ваша корзина</h2>
        </div>

        <?php if (!empty($products)): ?>
            <p style="margin-bottom: 20px;">Вы выбрали следующие товары:</p>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px; font-family: 'Poppins', sans-serif;">
                <thead>
                    <tr style="background-color: #f8f8f8; border-bottom: 2px solid #ddd; text-align: left;">
                        <th style="padding: 12px;">Название игры</th>
                        <th style="padding: 12px;">Цена</th>
                        <th style="padding: 12px;">Количество</th>
                        <th style="padding: 12px;">Стоимость</th>
                        <th style="padding: 12px; text-align: center;">Удалить</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 12px; font-weight: 500;"><?= htmlspecialchars($product['name']) ?></td>
                            <td style="padding: 12px;"><?= $product['price'] ?> руб.</td>
                            <td style="padding: 12px;"><?= $productsInCart[$product['id']] ?> шт.</td>
                            <td style="padding: 12px; font-weight: 600; color: #ff6a00;">
                                <?= $product['price'] * $productsInCart[$product['id']] ?> руб.
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="/index.php?route=cart/delete&id=<?= $product['id'] ?>" style="color: #d9534f; text-decoration: none; font-size: 18px;" title="Удалить товар">
                                    <i class="fa-solid fa-trash-can"></i> </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="background-color: #fdfdfd; font-size: 18px; font-weight: 700;">
                        <td colspan="3" style="padding: 15px; text-align: right;">Итого к оплате:</td>
                        <td style="padding: 15px; color: #ff6a00;"><?= $totalPrice ?> руб.</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: right;">
                <a href="/index.php?route=order" class="btn-orange" style="display: inline-block; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: 600;">
                    Оформить заказ
                </a>
            </div>

        <?php else: ?>
            <p style="font-size: 16px; color: #666;">В корзине пока пусто. Перейдите в <a href="/index.php?route=catalog" style="color: #ff6a00; text-decoration: none; font-weight: 600;">каталог</a>, чтобы выбрать игры.</p>
        <?php endif; ?>
    </div>
</section>
