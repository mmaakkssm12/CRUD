<?php
// config.php
define('DB_HOST', 'pma.kutkin.info');
define('DB_USER', 'maksim');
define('DB_PASS', '12345');
define('DB_NAME', 'electronic-shop');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
    return $conn;
}
?>