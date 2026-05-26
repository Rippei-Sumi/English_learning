<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>アカウント登録完了</title>
  <style>
    body {
      margin: 0;
      height: 100vh;
      background-color: #155f7f;
      font-family: "Hiragino Kaku Gothic ProN", Meiryo, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #ffffff;
    }

    .container {
      width: 500px;
      text-align: center;
    }

    h1 {
      font-size: 36px;
      margin-bottom: 30px;
    }

    .message {
      background-color: #c9f7c9;
      color: #1f7a1f;
      padding: 20px;
      font-size: 20px;
      border-radius: 4px;
      margin-bottom: 40px;
    }

    .confirm-button {
      padding: 16px 40px;
      font-size: 18px;
      background-color: #5cab2f;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .confirm-button:hover {
      opacity: 0.9;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>アカウント登録完了</h1>

    <div class="message">
      アカウント登録が完了しました
    </div>

    <form action="user_login.php" method="get">
      <button type="submit" class="confirm-button">
        確認
      </button>
    </form>
  </div>

</body>
</html>
