<?php
require_once 'functions.php';
include 'header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductById($id);

if (!$product):
?>
    <main><p style="text-align:center; margin:50px;">Товар не найден.</p></main>
<?php else: ?>
<main>
    <section class="product-page">
        <div class="tovar-img-page">
            <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="500" height="500">
        </div>
        <div class="tovar-info-page">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p><strong>Описание:</strong></p>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <div class="price">
                <?= number_format($product['price'], 2) ?> руб.
            </div>
            <button id="buyBtn">Купить</button>
        </div>
    </section>
</main>

<script>
    document.getElementById('buyBtn')?.addEventListener('click', () => {
        alert('Поздравляем с покупкой!');
    });
</script>

<?php endif; ?>
<?php include 'footer.php'; ?>