<?php
require 'db.php';
session_start();

// 定数定義
const ZERO = 0;
const CHOICE = 65;

if (!isset($_SESSION['user_id'])) {
  header('Location: user_login.php');
  exit;
}

$user_id = $_SESSION['user_id'];

$answers = $_POST['answers'] ?? [];
$indexes = $_POST['indexes'] ?? [];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>結果</title>
</head>
<body>

<h1>回答結果</h1>

<?php foreach ($answers as $question_id => $choice_id): ?>

<?php
$index = $indexes[$question_id] ?? '?';

/* ===== 問題取得 ===== */
$stmt = $pdo->prepare("SELECT question_statement, explanation, japanese FROM questions WHERE id = ?");
$stmt->execute([$question_id]);
$q = $stmt->fetch();

/* ===== 選択肢取得 ===== */
$stmt = $pdo->prepare("SELECT id, choice_text, is_correct FROM choices WHERE question_id = ? ORDER BY id");
$stmt->execute([$question_id]);
$allChoices = $stmt->fetchAll();

$correctLabel = '';
$correctText  = '';
$userLabel    = '';
$userText     = '';
$isCorrect    = ZERO;

/* ===== 判定 ===== */
foreach ($allChoices as $i => $choice) {

    if ($choice['id'] == $choice_id) {
        $userLabel = chr(CHOICE + $i);
        $userText  = $choice['choice_text'];
        $isCorrect = $choice['is_correct'];
    }

    if ($choice['is_correct']) {
        $correctLabel = chr(CHOICE + $i);
        $correctText  = $choice['choice_text'];
    }
}

/* ===== DB保存 ===== */
$stmt = $pdo->prepare("
INSERT INTO answers 
(user_id, question_id, selected_choice_id, is_correct, date)
VALUES (?, ?, ?, ?, NOW())

");
$stmt->execute([$user_id, $question_id, $choice_id, $isCorrect]);
?>

<div style="margin-bottom:30px;">

  <strong>Q<?= $index ?>.</strong><br>

  <?= htmlspecialchars($q['question_statement'], ENT_QUOTES, 'UTF-8') ?>

  <br><br>

  <strong>あなたの回答：</strong>
  <?= $userLabel ?>.
  <?= htmlspecialchars($userText, ENT_QUOTES, 'UTF-8') ?>

  <br>

  <strong>正解：</strong>
  <?= $correctLabel ?>.
  <?= htmlspecialchars($correctText, ENT_QUOTES, 'UTF-8') ?>

  <br>

  <?php if ($isCorrect): ?>
    <span style="color:blue;">正解</span>
  <?php else: ?>
    <span style="color:red;">不正解</span>
  <?php endif; ?>

  <br>

  <strong>和訳：</strong>
  <?= htmlspecialchars($q['japanese'], ENT_QUOTES, 'UTF-8') ?>

  <br><br>

  <strong>解説：</strong><br>
  <?= nl2br(htmlspecialchars($q['explanation'], ENT_QUOTES, 'UTF-8')) ?>

</div>

<?php endforeach; ?>

<a href="user_home.php">ホーム画面へ</a>

</body>
</html>