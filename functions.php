<?php
// functions.php
require_once 'config.php';

// Получить все товары
function getAllProducts() {
    $conn = getDB();
    $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Получить один товар по ID
function getProductById($id) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Добавить товар
function addProduct($name, $description, $price, $image) {
    $conn = getDB();
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image);
    return $stmt->execute();
}

// Обновить товар
function updateProduct($id, $name, $description, $price, $image) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image, $id);
    return $stmt->execute();
}

// Удалить товар
function deleteProduct($id) {
    $conn = getDB();
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>