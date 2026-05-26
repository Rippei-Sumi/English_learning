<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>パスワード変更</title>
<style>
body{
    font-family:Meiryo;
    background:#1e647f;
    color:#fff;
    text-align:center;
    margin-top:100px;
}
form{
    background:#fff;
    color:#000;
    display:inline-block;
    padding:30px;
    border-radius:10px;
}
input{
    display:block;
    margin:15px auto;
    padding:10px;
    width:250px;
}
button{
    padding:10px 30px;
    cursor:pointer;
}
</style>
</head>
<body>

<h1>パスワード変更</h1>

<form action="password_complete.php" method="post">

<input type="password" name="current_password"
       placeholder="現在のパスワード" required>

<input type="password" name="new_password"
       placeholder="新しいパスワード" required>

<input type="password" name="new_password_confirm"
       placeholder="新しいパスワード（確認）" required>

<button type="submit">変更する</button>

</form>

<div style="margin-top:20px;">
  <a href="user_edit.php" 
     style="color:#fff; text-decoration:underline;">
     ← ユーザー情報画面に戻る
  </a>
</div>

</body>
</html>