<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

/* 現在のメール取得 */
$stmt = $pdo->prepare('SELECT email FROM users WHERE id = :id');
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit('ユーザー情報が取得できません');
}

/* 更新処理 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = trim($_POST['email'] ?? '');

    if ($new_email === '') {
        $error = 'メールアドレスを入力してください';
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = '正しいメールアドレスを入力してください';
    } else {
        $stmt = $pdo->prepare(
            'UPDATE users SET email = :email WHERE id = :id'
        );
        $stmt->execute([
            ':email' => $new_email,
            ':id' => $user_id
        ]);

        $success = 'メールアドレスを変更しました';
        $user['email'] = $new_email;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>アカウント情報変更</title>

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

/* タイトル */
.title {
  text-align: center;
  margin: 60px 0;
  font-size: 36px;
  font-weight: bold;
}

/* フォーム */
.form-area {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 30px;
  font-size: 24px;
}

input[type="email"] {
  width: 420px;
  padding: 14px;
  font-size: 22px;
  border: 2px solid #000;
}

/* ボタン */
.buttons {
  display: flex;
  justify-content: center;
  gap: 160px;
  margin-top: 120px;
}

.btn {
  padding: 20px 70px;
  font-size: 22px;
  border: none;
  cursor: pointer;
}

.btn-green {
  background-color: #5cab2f;
  color: #fff;
}

/* メッセージ */
.message {
  text-align: center;
  margin-top: 30px;
  font-size: 20px;
  color: #ffeb3b;
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

<div class="title">アカウント情報変更</div>

<form method="post">
  <div class="form-area">
    <div>メールアドレス：</div>
    <input type="email" name="email"
           value=""
           required>
  </div>

  <?php if ($error): ?>
    <div class="message"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="message"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <div class="buttons">
    <button class="btn btn-green" type="submit">変更する</button>
    <button class="btn btn-green"
            type="button"
            onclick="location.href='user_edit.php'">
      キャンセル
    </button>
  </div>
</form>

</body>
</html>
