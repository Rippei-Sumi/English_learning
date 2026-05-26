<?php
/* =========================
   データベース接続設定
========================= */

$host = 'localhost';
$dbname = 'english_learning';   // ← あなたのDB名に変更
$user = 'root';
$pass = '';

/* =========================
   PDO接続
========================= */
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    exit('データベース接続エラー: ' . $e->getMessage());
}