<?php
require_once 'functions.php';
include 'header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductById($id);
if (!$product) {
    echo "<main><p>Товар не найден.</p></main>";
    include 'footer.php';
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $imageName = $product['image']; // старое изображение по умолчанию
    
    // Если загружено новое изображение
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($fileExt, $allowed)) {
            // Удаляем старое изображение, если оно не default.jpg
            if ($product['image'] !== 'default.jpg' && file_exists('uploads/' . $product['image'])) {
                unlink('uploads/' . $product['image']);
            }
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
        } else {
            $error = 'Недопустимый формат изображения.';
        }
    }
    
    if (empty($error)) {
        if (updateProduct($id, $name, $description, $price, $imageName)) {
            $message = 'Товар успешно обновлён!';
            $product = getProductById($id); // обновляем данные для формы
        } else {
            $error = 'Ошибка при обновлении товара.';
        }
    }
}
?>

<main>
    <section style="padding: 20px; max-width: 600px; margin: 0 auto;">
        <h2>Редактирование товара</h2>
        <?php if ($message): ?>
            <p style="color: green;"><?= $message ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label>Название товара:</label><br>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Описание:</label><br>
                <textarea name="description" rows="5" required style="width: 100%; padding: 8px;"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div style="margin-bottom: 15px;">
                <label>Цена (руб):</label><br>
                <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Текущее изображение:</label><br>
                <img src="uploads/<?= htmlspecialchars($product['image']) ?>" width="100"><br>
                <label>Заменить изображение:</label><br>
                <input type="file" name="image" accept="image/*">
            </div>
            <button type="submit" style="background: orange; color: white; padding: 10px 20px; border: none; cursor: pointer;">Сохранить изменения</button>
            <a href="admin.php" style="margin-left: 10px;">Назад в админку</a>
        </form>
    </section>
</main>

<?php include 'footer.php'; ?>