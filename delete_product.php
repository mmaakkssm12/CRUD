<?php
require_once 'functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    $product = getProductById($id);
    if ($product && $product['image'] !== 'default.jpg' && file_exists('uploads/' . $product['image'])) {
        unlink('uploads/' . $product['image']); // удаляем файл изображения
    }
    deleteProduct($id);
}
header('Location: admin.php');
exit;
?>