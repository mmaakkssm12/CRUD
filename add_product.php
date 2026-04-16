<?php
require_once 'functions.php';
include 'header.php';

// Создаём папку uploads, если её нет
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $imageName = 'default.jpg'; // значение по умолчанию

    // Обработка загруженного файла
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);
            finfo_close($finfo);
            
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if (in_array($mimeType, $allowedTypes) && in_array($fileExt, $allowedExt)) {
                // Генерируем уникальное имя
                $imageName = time() . '_' . uniqid() . '.' . $fileExt;
                $destination = 'uploads/' . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    // Успешно загружено
                } else {
                    $error = 'Не удалось переместить загруженный файл. Проверьте права на папку uploads.';
                }
            } else {
                $error = 'Недопустимый формат изображения. Разрешены: JPG, JPEG, PNG, GIF, WEBP.';
            }
        } else {
            // Коды ошибок: https://www.php.net/manual/en/features.file-upload.errors.php
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE   => 'Файл превышает максимальный размер (upload_max_filesize).',
                UPLOAD_ERR_FORM_SIZE  => 'Файл превышает максимальный размер (MAX_FILE_SIZE).',
                UPLOAD_ERR_PARTIAL    => 'Файл был загружен только частично.',
                UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
                UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
                UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
                UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
            ];
            $code = $_FILES['image']['error'];
            $error = 'Ошибка загрузки: ' . ($uploadErrors[$code] ?? 'Неизвестная ошибка.');
        }
    }

    // Если нет ошибок, добавляем товар в БД
    if (empty($error)) {
        if (addProduct($name, $description, $price, $imageName)) {
            $message = 'Товар успешно добавлен!';
            // Очищаем поля формы
            $name = $description = $price = '';
        } else {
            $error = 'Ошибка при добавлении товара в базу данных.';
            // Если была загружена картинка, но БД не приняла – удаляем её, чтобы не было мусора
            if ($imageName !== 'default.jpg' && file_exists('uploads/' . $imageName)) {
                unlink('uploads/' . $imageName);
            }
        }
    }
}
?>

<main>
    <section style="padding: 20px; max-width: 600px; margin: 0 auto;">
        <h2>➕ Добавление товара</h2>
        
        <?php if ($message): ?>
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label for="name">Название товара:</label><br>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required 
                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="description">Описание:</label><br>
                <textarea id="description" name="description" rows="5" required 
                          style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"><?= htmlspecialchars($description ?? '') ?></textarea>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="price">Цена (руб):</label><br>
                <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($price ?? '') ?>" required 
                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="image">Изображение:</label><br>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
                <small style="color: #666;">Допустимые форматы: JPG, PNG, GIF, WEBP. Максимальный размер: <?= ini_get('upload_max_filesize') ?>B</small>
            </div>
            
            <button type="submit" style="background: green; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Добавить товар
            </button>
            <a href="admin.php" style="margin-left: 15px;">Назад в админку</a>
        </form>
    </section>
</main>

<?php include 'footer.php'; ?>