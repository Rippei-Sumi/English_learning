<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];


?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ログアウト</title>

<style>
body {
  margin: 0;
  background-color: #155f7f;
  font-family: "Hiragino Kaku Gothic ProN", Meiryo, sans-serif;
  color: #fff;
}

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

/* 無効リンク */
.nav-disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

/* タイトル */
.title {
  text-align: center;
  margin-top: 60px;
  font-size: 36px;
  font-weight: bold;
}

/* メッセージ */
.message {
  width: 70%;
  margin: 40px auto 120px;
  padding: 20px;
  background-color: #fde6d8;
  color: red;
  font-size: 26px;
  text-align: center;
  border: 2px solid #ff7a2f;
}

/* ボタン */
.buttons {
  display: flex;
  justify-content: center;
  gap: 160px;
}

.btn {
  width: 260px;
  padding: 18px 0;
  font-size: 22px;
  cursor: pointer;
  border: 2px solid #1d4f2b;
  background-color: #fff;
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


<div class="title">ログアウト</div>

<div class="message">
  ログアウトしますか？
</div>

<div class="buttons">
  <form action="logout_process.php" method="post">
    <button class="btn" type="submit">はい</button>
  </form>

  <button class="btn" onclick="history.back()">いいえ</button>
</div>

</body>
</html>
