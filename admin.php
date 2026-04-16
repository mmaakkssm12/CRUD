<?php
require_once 'functions.php';
include 'header.php';

$products = getAllProducts();
?>

<main>
    <section style="padding: 20px;">
        <h2>Управление товарами</h2>
        <a href="add_product.php" style="display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: green; color: white; text-decoration: none; border-radius: 5px;">➕ Добавить товар</a>
        
        <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><img src="uploads/<?= htmlspecialchars($product['image']) ?>" width="50" height="50"></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= number_format($product['price'], 2) ?> руб.</td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['id'] ?>" style="background: orange; padding: 5px 10px; color: white; text-decoration: none;">✏️ Редактировать</a>
                        <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Удалить товар?')" style="background: red; padding: 5px 10px; color: white; text-decoration: none;">🗑️ Удалить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include 'footer.php'; ?>