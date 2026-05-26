<?php
require 'db.php';
session_start();

// 定数定義
const MODE_NORMAL =2;
const ID_NONE = 0;

if (!isset($_SESSION['user_id'])) {
  header('Location: user_login.php');
  exit;
}

/* id取得 */
$id = (int)($_GET['id'] ?? ID_NONE);

/* モード判定 */
$is_review = ($id !== MODE_NORMAL);

/* DBから科目名取得 */
$stmt = $pdo->prepare("SELECT subject FROM subjects WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

/*  復習対象数 */
$review_count = 0;

if ($is_review) {
  $sql = "
  SELECT COUNT(*)
  FROM questions q
  INNER JOIN answers a
    ON a.id = (
      SELECT id FROM answers
      WHERE question_id = q.id
        AND user_id = :user_id
      ORDER BY id DESC
      LIMIT 1
    )
  WHERE a.is_correct = 0
  ";
  
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
  $stmt->execute();

  $review_count = $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>学習形式選択</title>

<style>
body {
  margin: 0;
  font-family: sans-serif;
  background-color: #1f627c;
  color: #fff;
}

.top-bar {
  padding: 20px;
  font-size: 40px;
}

.nav {
  background-color: #000;
  padding: 15px;
  display: flex;
  justify-content: flex-end;
  gap: 30px;
}

.nav a {
  color: #fff;
  text-decoration: underline;
}

.main {
  text-align: center;
  padding-top: 100px;
}

.title {
  font-size: 32px;
  margin-bottom: 40px;
}

.submit-btn {
  margin-top: 40px;
  padding: 15px 60px;
  font-size: 20px;
  background-color: #0aa64f;
  border: 2px solid #003300;
  border-radius: 10px;
  cursor: pointer;
  color: #fff;
}

.submit-btn:hover {
  opacity: 0.8;
}

/* 復習ボックス */
.review-box {
  background: #ff9800;
  padding: 15px;
  border-radius: 10px;
  margin: 20px auto;
  width: 320px;
  font-size: 18px;
}
</style>
</head>

<body>

<div class="top-bar">
<?= $is_review ? '復習モード' : '通常学習モード' ?>
</div>

<div class="nav">
  <a href="user_home.php">ホーム</a>
  <a href="user_edit.php">各種設定</a>
  <a href="logout.php">ログアウト</a>
</div>

<div class="main">

  <div class="title">
    学習方法を選択してください
  </div>

  <!--  復習対象表示 -->
  <?php if ($is_review): ?>
    <div class="review-box">
      <?php if ($review_count == 0): ?>
        復習対象の問題はありません 。
      <?php else: ?>
        復習対象は <?= $review_count ?> 問あります
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <form action="question.php" method="GET">

    <!-- モード -->
    <input type="hidden" name="review" value="<?= $is_review ? 1 : 0 ?>">
    <input type="hidden" name="id" value="<?= $id ?>">

    <?php if (!$is_review): ?>

      <!-- 通常 -->
      <label>何問出題しますか。(5~20):</label><br>
      <input type="number" name="quantity" min="5" max="20" value="5" ><br><br>

      <label>出題範囲：</label><br>
      <select name="ranges">
        <option value="all">すべての問題</option>
        <option value="unanswered">未回答のみ</option>
      </select>

    <?php else: ?>

      <!-- 復習 -->
      <label>何問出題しますか：</label><br>
      <input type="number" name="quantity" min="1" max=<?= $review_count ?> value=<?= $review_count ?>>

    <?php endif; ?>

    <br>

    <!--  ボタン制御 -->
    <button type="submit" class="submit-btn"
      <?= ($is_review && $review_count == 0) ? 'disabled' : '' ?>>
      学習スタート
    </button>

  </form>

</div>

</body>
</html>