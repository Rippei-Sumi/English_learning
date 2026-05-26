<?php
require 'db.php';
session_start();

// 定数定義
const ONE = 1;
const ZERO = 0;
const CHOICE = 65;

if (!isset($_SESSION['user_id'])) {
  header('Location: user_login.php');
  exit;
}

/* パラメータ */
$quantity  = $_GET['quantity'] ?? ONE;
$ranges    = $_GET['ranges'] ?? 'unanswered';
$review = $_GET['review'] ?? '';
$user_id = $_SESSION['user_id'];

/* ===== 問題取得 ===== */
if ($review == ONE) {

    /* 復習チェック：最後が不正解の問題 */
    $sql = "SELECT q.*
            FROM questions q
            INNER JOIN answers a
              ON a.id = (
                  SELECT id FROM answers
                  WHERE question_id = q.id
                    AND user_id = :user_id
                  ORDER BY id DESC
                  LIMIT 1
              )
            WHERE a.is_correct = 0";

} else {

    /* 通常モード */
    $sql = "SELECT DISTINCT q.*
            FROM questions q
            LEFT JOIN answers a
              ON q.id = a.question_id
              AND a.user_id = :user_id
            WHERE 1=1";

    /* 未回答のみ */
    if ($ranges === 'unanswered') {
        $sql .= " AND a.id IS NULL";
    }
}

$sql .= " ORDER BY RAND() LIMIT :quantity";

/* ===== 実行 ===== */
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

$stmt->bindValue(':quantity', (int)$quantity, PDO::PARAM_INT);
$stmt->execute();
$questions = $stmt->fetchAll();

/* ===== 選択肢取得 ===== */
$choices_by_question = [];

if (!empty($questions)) {
    $question_ids = array_column($questions, 'id');
    $placeholders = implode(',', array_fill(ZERO, count($question_ids), '?'));

    $stmt = $pdo->prepare("SELECT * FROM choices WHERE question_id IN ($placeholders)");
    $stmt->execute($question_ids);
    $all_choices = $stmt->fetchAll();

    foreach ($all_choices as $choice) {
        $choices_by_question[$choice['question_id']][] = $choice;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>問題</title>
</head>
<body>

<h1>問題一覧</h1>

<form action="check.php" method="POST">

<?php foreach ($questions as $index => $q): ?>

<div style="margin-bottom:30px;">

  <strong>Q<?= $index + ONE ?>.</strong>
  <?= htmlspecialchars($q['question_statement'], ENT_QUOTES, 'UTF-8') ?>

  <br><br>

  <?php if (!empty($choices_by_question[$q['id']])): ?>
    <?php foreach ($choices_by_question[$q['id']] as $i => $choice): ?>
      <label>
        <input type="radio"
               name="answers[<?= $q['id'] ?>]"
               value="<?= $choice['id'] ?>"
               required>
        <?= chr(CHOICE + $i) ?>.
        <?= htmlspecialchars($choice['choice_text'], ENT_QUOTES, 'UTF-8') ?>
      </label>
      <br>
    <?php endforeach; ?>
  <?php endif; ?>

  <input type="hidden" name="indexes[<?= $q['id'] ?>]" value="<?= $index + ONE ?>">

</div>

<?php endforeach; ?>

<br>
<button type="submit">まとめて回答</button>

</form>

</body>
</html>