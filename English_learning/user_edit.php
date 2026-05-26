<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];



/* ===============================
   ② ユーザー情報取得
================================= */
$stmt = $pdo->prepare(
    'SELECT email FROM users WHERE id = :id'
);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit('ユーザー情報が取得できません');
}

/* ===============================
   ③ メール伏字処理（安全版）
================================= */
$email = $user['email'];

if (preg_match('/(^.).+(@.+$)/', $email, $matches)) {
    $masked_email = $matches[1] . '*****' . $matches[2];
} else {
    $masked_email = $email;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>アカウント情報</title>

<style>
body {
  margin: 0;
  background-color: #155f7f;
  font-family: "Hiragino Kaku Gothic ProN", Meiryo, sans-serif;
  color: #fff;
}

/* ===== ナビ ===== */
.nav {
  background-color: #000;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 40px;
}

.nav-title {
  font-size: 26px;
  font-weight: bold;
}

.nav-menu {
  display: flex;
  gap: 30px;
}

.nav-menu a {
  color: #fff;
  text-decoration: none;
  font-size: 18px;
}

.nav-menu a:hover {
  text-decoration: underline;
}

/* ===== タイトル ===== */
.title {
  text-align: center;
  margin: 60px 0;
  font-size: 36px;
  font-weight: bold;
}

/* ===== 情報 ===== */
.info {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 40px;
  font-size: 24px;
}

/* ===== ボタン ===== */
.buttons {
  display: flex;
  justify-content: center;
  gap: 120px;
  margin-top: 80px;
}

.btn {
  padding: 20px 60px;
  font-size: 22px;
  border: none;
  cursor: pointer;
}

.btn-green {
  background-color: #5cab2f;
  color: #fff;
}

.btn-red {
  background-color: red;
  color: #fff;
}

/* 退会 */
.withdraw {
  display: flex;
  justify-content: center;
  margin-top: 120px;
}
</style>
</head>
<body>

<div class="nav">
  <div class="nav-title">英語学習アプリ</div>
  <div class="nav-menu">
  <a href="user_home.php">ホーム</a>
  <a href="user_edit.php">各種設定</a>
  <a href="logout.php">ログアウト</a>
</div>

</div>

<div class="title">アカウント情報</div>

<div class="info">
  <div>メールアドレス：</div>
  <div><?= htmlspecialchars($masked_email) ?></div>
</div>

<div class="buttons">
  <form action="email_change.php" method="get">
    <button class="btn btn-green">メールアドレス変更</button>
  </form>

  <form action="password_change.php" method="get">
    <button class="btn btn-green">パスワード変更</button>
  </form>
</div>

<div class="withdraw">
  <form action="withdraw.php" method="post"
        onsubmit="return confirm('本当に退会しますか？');">
    <button class="btn btn-red">退会</button>
  </form>
</div>

</body>
</html>
