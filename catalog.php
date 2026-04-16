<?php
require_once 'functions.php';
include 'header.php';

$products = getAllProducts();
?>

<main>
    <section class="popular-tovar">
        <h3>Каталог товаров</h3>
        <div class="tovar-list">
            <?php foreach ($products as $product): ?>
                <div class="tovar-card">
                    <div class="tovar-img">
                        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="350" height="350">
                    </div>
                    <div class="tovar-info">
                        <p><?= htmlspecialchars($product['name']) ?></p>
                        <p>Цена: <?= number_format($product['price'], 2) ?> руб.</p>
                        <a href="product.php?id=<?= $product['id'] ?>">Подробнее</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>