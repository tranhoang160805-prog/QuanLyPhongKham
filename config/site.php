<?php
require_once 'database.php';
$site = [];

try {
    $stmt = $pdo->query("SELECT khoaccauhinh, giatri FROM CAUHINHHETHONG");
    
    $site = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
} catch (PDOException $e) {
    die("Lỗi kết nối cấu hình: " . $e->getMessage());
}
?>