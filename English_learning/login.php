<?php
session_start();
require 'db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: user_login.html?error=1');
    exit;
}

/* ユーザー検索 */
$stmt = $pdo->prepare(
    'SELECT id, password
     FROM users
     WHERE email = :email'
);
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* ログイン判定 */
if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $email;

    header('Location: user_home.php');
    exit;

} else {
    // ★ ここでエラー付きで戻す
    header('Location: user_login.php?error=1');
    exit;
}
