<?php
session_start();
require 'db.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
  exit('未入力があります');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  exit('メール形式が不正です');
}



/* 重複チェック */
$stmt = $pdo->prepare("
  SELECT id FROM users
  WHERE email = ?
  AND is_deleted = 0
");
$stmt->execute([$email]);

if ($stmt->fetch()) {
  exit('このメールアドレスは既に登録されています');
}

/* ハッシュ化 */
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* 登録 */
$stmt = $pdo->prepare("
  INSERT INTO users (name, email, password)
  VALUES (?, ?, ?)
");
$stmt->execute([$name, $email, $hashedPassword]);

header('Location:user_registration_confirm.php');
exit;