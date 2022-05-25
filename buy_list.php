<?php
session_start();
require('library.php');

/**
　　* ログイン確認
　　*/
if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
  $user_id = $_SESSION['user_id'];
  $name    = $_SESSION['name'];
  $aisatsu = 'doumo';
} else {
  header('Location: toppage.php');
  exit();
}

/**
　　* 表示するものがないときの表示に備える
　　*/
$buy_u_id = filter_input(INPUT_POST, 'buy_u_id', FILTER_SANITIZE_NUMBER_INT);
$db = dbconnect();
$sql = "
  SELECT 
    count(*) AS cnt 
  FROM 
    buy 
  WHERE 
    buy_u_id = ".$buy_u_id." 
";
$counts = $db->query($sql);
if (!$counts) {
  die($db->error);
}
$count = $counts->fetch_assoc();

/**
　　* SQL実行　買い物リストの表示
　　*/
$sql = "
  SELECT 
    distinct 
    product, recipe_d_id 
  FROM 
    buy 
  WHERE 
    buy_u_id = ".$buy_u_id." 
";
$lists = $db->query($sql);
if (!$lists) {
  die($db->error);
}
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
            <?php $count =0; ?>
            <?php while ($list = $lists->fetch_assoc()) { ?>
              <div class="forms">
                <div class="form_title2">
                  <pre><a href="recipe.php?id=<?php echo $list['recipe_d_id']; ?>"><?php echo h($list['product']); ?></a></pre>
                </div>
                <?php $count+=1; ?>
              </div>
              <?php } ?>
              <div class="page">
                <?php if ($count == 0) { ?>
                  <p>表示するデータはありません</p>
                <?php } ?>
              </div>
          </div>
        </div>
      </div>
    </div>
    <footer>2022 @recipenpj</footer>
  </div> 
</body>
</html>