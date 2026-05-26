<?php
session_start();
require 'db.php';

/* =========================
   ① ログイン確認
========================= */
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];



/* =========================
   ③ 退会処理
========================= */
$pdo->beginTransaction();

try {

    // ユーザー削除
    $stmt = $pdo->prepare("
        DELETE FROM users
        WHERE id = :id
    ");
    $stmt->execute([':id' => $user_id]);

    $pdo->commit();

    // セッション破棄
    $_SESSION = [];
    session_destroy();

    header('Location: user_login.php?withdraw=1');
    exit;

} catch (Exception $e) {

    $pdo->rollBack();
    echo "エラーが発生しました。";
    exit;
}