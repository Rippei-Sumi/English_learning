<?php
session_start();

/* セッション破棄 */
$_SESSION = [];
session_destroy();

/* ログイン画面へ */
header('Location: user_login.php');
exit;
