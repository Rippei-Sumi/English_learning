<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>英語学習アプリ ログイン</title>
  <style>
    body {
      margin: 0;
      height: 100vh;
      background-color: #155f7f;
      font-family: "Hiragino Kaku Gothic ProN", Meiryo, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
    }

    .login-container {
      text-align: center;
      width: 400px;
    }

    h1 {
      margin-bottom: 40px;
      font-size: 32px;
    }

    .input-box {
      width: 100%;
      padding: 15px;
      margin-bottom: 20px;
      font-size: 16px;
      border: 2px solid #7bc043;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .login-button {
      width: 100%;
      padding: 15px;
      font-size: 18px;
      background-color: #5cab2f;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .login-button:hover {
      opacity: 0.9;
    }

    .links {
      margin-top: 30px;
    }

    .links a {
      display: block;
      margin-top: 10px;
      color: #ffffff;
      text-decoration: underline;
      font-size: 14px;
    }

    .error-message {
  color: #ff0000;
  background-color: #FCE4D6;
  border:1px , solid;
  margin-bottom: 20px;
  font-size: 14px;
}
  </style>
</head>
<body>

  <div class="login-container">
    <h1>英語学習アプリ<br>ログインページ</h1>

    <?php if (isset($_GET['error'])): ?>
  <p class="error-message">メールアドレスまたはパスワードが違います</p>
<?php endif; ?>

    <form action="login.php" method="post">
      <input type="email" name="email" class="input-box" placeholder="メールアドレス" required autocomplete="off">
      <input type="password" name="password" class="input-box" placeholder="パスワード" required>

      <button type="submit" class="login-button">ログイン</button>
    </form>

    <div class="links">
      <a href="user_registration.html">新規登録</a>
      <!-- <a href="user_reset_password.html">パスワードの再設定</a> -->
    </div>
  </div>

</body>
</html>
