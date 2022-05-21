<?php
require('library.php');

session_start();
$db = dbconnect();

if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
  $user_id = $_SESSION['user_id'];
  $name    = $_SESSION['name'];
  $aisatsu = 'doumo';
} else {
  header('Location: toppage.php');
  exit();
}

$buy_u_id = filter_input(INPUT_POST, 'buy_u_id', FILTER_SANITIZE_NUMBER_INT);
$counts   = $db->query("select count(*) as cnt from buy where buy_u_id='".$buy_u_id."'");
$coun     = $counts->fetch_assoc();

$stmt = $db->prepare('select distinct product, recipe_d_id from buy where buy_u_id=?');
if (!$stmt) {
  die($db->error);
}
$stmt->bind_param('i', $buy_u_id);
$result = $stmt->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="my_style.css">
  <title>あなたの買い物リスト</title>
</head>
<body>
  <div class="container"> 
    <header>
      <h1 class="title">Recipen <?php echo $name ?>さんの買い物リスト</h1>
      <nav class="nav">
        <div class="button5">
          <form action="recipe/index.php" method="post" >
            <input type="hidden" name="type" value="2">
            <button type="submit"> 
              投稿する
            </button>
          </form>
        </div>
        <div class="button5">
          <form action="myrecipen.php" method="post" >
            <input type ="hidden" name="recipe_member_id" value="<?php echo $user_id; ?>">
            <button type="submit"> 
              マイページ
            </button>
          </form>
        </div>
        <div class="button5">
          <form action="toppage.php" method="post" >
            <button type="submit"> 
              TOPページに戻る
            </button>
          </form>
        </div>
        <div class="button5">
          <form action="logout.php" method="post" >
            <button type="submit"> 
              ログアウト
            </button>
          </form>
        </div>
      </nav>
    </header>
    <div class="main">
      <div class=join_page> 
        <div class=join_page2>
          <div class="join_form">
            <div class="page_title">
              <?php echo h($name) . 'さんの買い物リスト♪'; ?>
            </div>
            <?php $stmt->bind_result($product, $recipe_d_id); ?>
            <?php $count =0; ?>
            <?php while ($stmt->fetch()): ?>
              <div class="forms">
                <div class="form_title2">
                  <pre><a href="recipe.php?id=<?php echo $recipe_d_id; ?>"><?php echo h($product); ?></a></pre>
                </div>
                <?php $count+=1; ?>
              </div>
              <?php endwhile; ?>
              <div class="page">
                <?php if ($count == 0): ?>
                  <p>
                    表示するデータはありません。
                  </p>
                <?php endif; ?>
              </div>
          </div>
        </div>
      </div>
    </div>
    <footer>
      2022 @recipenpj
    </footer>
  </div> 
</body>
</html>