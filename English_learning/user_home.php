<?php
session_start();
require 'db.php';

// 定数定義
const COUNT_ZERO =0;
const COUNT_TEN =10;
const COUNT_TWENTY =20;
const COUNT_THIRTY =30;
const IS_JAN =1;
const IS_DEC =12;
const MONTH = 1;
const WEEK = 7;
const ONE = 1;
const ZERO = 0;

if (!isset($_SESSION['user_id'])) {
  header('Location: user_login.php');
  exit;
}

/* ▼ 年・月（GET対応） */
$year  = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');

/* 今日 */
$today = date('j');
$todayFull = date('Y年n月j日');

/* ▼ 今日の年月（戻る用） */
$todayYear  = date('Y');
$todayMonth = date('n');

/* ▼ 前月・次月 */
$prevMonth = $month - MONTH;
$prevYear  = $year;
if ($prevMonth < IS_JAN) {
  $prevMonth = IS_DEC;
  $prevYear--;
}

$nextMonth = $month + MONTH;
$nextYear  = $year;
if ($nextMonth > IS_DEC) {
  $nextMonth = IS_JAN;
  $nextYear++;
}

/* カレンダー */
$firstDay    = date('w', strtotime("$year-$month-01"));
$daysInMonth = date('t', strtotime("$year-$month-01"));

/* 科目 */
$stmt = $pdo->query("SELECT id, subject FROM subjects");
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* 問題数取得 */
$stmt = $pdo->prepare("
  SELECT DATE(date) as day, COUNT(*) as count
  FROM answers
  WHERE user_id = ?
    AND YEAR(date) = ?
    AND MONTH(date) = ?
  GROUP BY DATE(date)
");
$stmt->execute([$_SESSION['user_id'], $year, $month]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* 日付 => 問題数 */
$counts = [];
foreach ($results as $row) {
  $day = date('j', strtotime($row['day']));
  $counts[$day] = $row['count'];
}

/* 色 */
function getColorClass($count) {
  if ($count == COUNT_ZERO) return '';
  if ($count < COUNT_TEN) return 'low';
  if ($count < COUNT_TWENTY) return 'medium';
  if ($count < COUNT_THIRTY) return 'high';
  return 'beyond';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ホーム</title>

<style>
body {
  margin: 0;
  background-color: #1f627c;
  font-family: sans-serif;
  color: #fff;
}

.header {
  padding: 20px;
  font-size: 28px;
}

.today-text {
  margin-left: 20px;
  font-size: 18px;
}

/* カレンダー */
.calendar {
  width: 420px;
  margin: 30px;
}

.calendar table {
  width: 100%;
  border-collapse: collapse;
}

.calendar th, .calendar td {
  border: 1px solid #ccc;
  text-align: center;
  padding: 10px;
  background-color: #ddd;
  color: #000;
}

.calendar th {
  background-color: #000;
  color: #fff;
}

.sun { background-color: red !important; color:#fff; }
.sat { background-color: blue !important; color:#fff; }

.today {
  background-color: #ffeb3b !important;
  font-weight: bold;
}

/* 色分け */
.calendar td.low    { background-color:#a7d398; color:#fff; }
.calendar td.medium { background-color:#3eb370; color:#fff; }
.calendar td.high   { background-color:#00a760; color:#fff; }
.calendar td.beyond { background-color:#00582d; color:#fff; }

/* 月ナビ */
.month-nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.month-nav a {
  color: #fff;
  text-decoration: none;
  font-size: 20px;
  padding: 5px 10px;
  background: #333;
  border-radius: 5px;
}

/* 今日ボタン */
.today-btn {
  display: inline-block;
  margin-top: 5px;
  padding: 5px 10px;
  background-color: #ff9800;
  color: #fff;
  text-decoration: none;
  border-radius: 5px;
  font-size: 14px;
}

/* タブ */
.tabs {
  display: flex;
  gap: 10px;
  margin: 20px;
}

.tab {
  padding: 10px 20px;
  background-color: #333;
  color: #fff;
  border-radius: 10px;
  text-decoration: none;
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
</style>
</head>

<body>

<div class="nav">
  <a href="user_home.php">ホーム</a>
  <a href="user_edit.php">各種設定</a>
  <a href="logout.php">ログアウト</a>
</div>

<div class="header">英語学習アプリ(TOEIC、PART5特化)</div>

<p class="today-text">
今日は <?= $todayFull ?> です
</p>

<div class="calendar">

  <!-- 月切り替え + 今日ボタン -->
  <div class="month-nav">
    <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>">←</a>

    <div style="text-align:center;">
      <h2><?= $year ?>年 <?= $month ?>月</h2>

      <?php if ($year != $todayYear || $month != $todayMonth): ?>
        <a href="?year=<?= $todayYear ?>&month=<?= $todayMonth ?>" class="today-btn">
          今月
        </a>
      <?php endif; ?>

    </div>

    <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>">→</a>
  </div>

  <table>
    <tr>
      <th class="sun">日</th>
      <th>月</th>
      <th>火</th>
      <th>水</th>
      <th>木</th>
      <th>金</th>
      <th class="sat">土</th>
    </tr>

<?php
$day = ONE;
echo "<tr>";

for ($i = ZERO; $i < $firstDay; $i++) {
  echo "<td></td>";
}

while ($day <= $daysInMonth) {

  if (($firstDay + $day - ONE) % WEEK == ZERO && $day != ONE) {
    echo "</tr><tr>";
  }

  $count = $counts[$day] ?? ZERO;
  $colorClass = getColorClass($count);

  $class = $colorClass;

  /* 今月のみ今日強調 */
  if ($year == $todayYear && $month == $todayMonth && $day == $today) {
    $class .= " today";
  }

  echo "<td class='$class' title='{$count}問解いた'>";
  echo $day;
  echo "</td>";

  $day++;
}

/* 空白 */
$remaining = (WEEK - (($firstDay + $daysInMonth) % WEEK)) % WEEK;
for ($i = ZERO; $i < $remaining; $i++) {
  echo "<td></td>";
}

echo "</tr>";
?>
  </table>
</div>

<div class="tabs">
<?php foreach ($subjects as $row): ?>
  <a href="learning_custom.php?id=<?= $row['id'] ?>" class="tab">
    <?= htmlspecialchars($row['subject'], ENT_QUOTES, 'UTF-8') ?>
  </a>
<?php endforeach; ?>
</div>


</body>
</html>