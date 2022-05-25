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
　　* SQL実行　ユーザーが登録したデータのみを取り出す。
　　*/
$db = dbconnect();
$sql = "
  SELECT 
    * 
  FROM 
    recipen 
  WHERE 
    member_id=? 
  ORDER BY 
    id DESC 
  LIMIT 
    ?, 5
";
$stmt = $db->prepare($sql);
if (!$stmt) {
  die($db->error);
}

/**
　　* SQL実行　　レコードの数取得(ページネーション準備)
　　*/
$recipe_member_id = $_POST['recipe_member_id'];
$sql = "
  SELECT 
    count(*) AS cnt 
  FROM 
    recipen 
  WHERE 
    member_id = ".$recipe_member_id."
";
$counts   = $db->query($sql);
if (!$counts) {
  die($db->error);
}
$count    = $counts->fetch_assoc();
$max_page = floor(($count['cnt']-1)/5+1); //　Point floor 切り捨て

/**
　　* ページネーション
　　*/
if (isset($_POST['page'])) {
  $page  = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
} else {
  $page = 1; // Point $pageに何も入っていない時は、1を入れる
}
$start = ($page - 1) * 5;
$stmt->bind_param('ii', $user_id, $start);
$result = $stmt->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="my_style.css">
  <title>あなたのレシピん</title>
</head>
<body>
  <div class="container"> 
    <header>
      <h1 class="title">Recipen <?php echo $name ?>さんの投稿一覧</h1>
      <nav class="nav"> <div class="button5">
          <form action="./buy_list.php" method="post">
            <input type="hidden" name="type" value="2">
            <input type ="hidden" name="buy_u_id" value="<?php echo $user_id; ?>">
            <button type="submit">買い物リスト</button>
          </form>
        </div>
        <div class="button5">
          <form action="recipe/index.php" method="post">
            <input type="hidden" name="type" value="2">
            <button type="submit">投稿する</button>
          </form>
        </div>
        <div class="button5">
          <form action="toppage.php" method="post">
            <button type="submit">TOPページに戻る</button>
          </form>
        </div>
        <div class="button5">
          <form action="logout.php" method="post">
            <button type="submit">ログアウト</button>
          </form>
        </div>
      </nav>
    </header>
    <div class="main">
      <div class=join_page> 
        <div class=join_page2>
          <div class="join_form">
            <div class="page_title">
              <?php echo h($name) . 'さんのレシピん♪'; ?>
            </div>
            <?php $stmt->bind_result($recipe_id, $recipename, $recipe_member_id, $image, $foodstuffs, $recipe, $created, $modified); ?>
            <?php $count =0; ?>
            <?php while ($stmt->fetch()) { ?>
              <div class="forms">
                <div class="form_title2">
                  <a href="recipe.php?id=<?php echo $recipe_id; ?>"><?php echo h($recipename); ?></a>
                </div>
                <div class="form_title2">
                  <time><?php echo h($created); ?></time><br>
                </div>
                <div class="form_title2">
                  <a href="recipe.php?id=<?php echo $recipe_id; ?>"><img src="recipe_picture/<?php echo h($image); ?>"></a>
                </div>
            <?php $count+=1; ?>
              </div>
            <?php } ?>
            <?php if ($page > 1) { ?>
              <div class="page">
                <div class="button6">
                  <form action="" method="post">
                    <input type ="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
                    <input type ="hidden" name="page" value="<?php echo $page-1; ?>">
                    <button type="submit"> 
                    <?php echo $page-1;?>ページ目へ
                    </button>
                  </form>
                </div>
            <?php } ?>
            <?php if($page < $max_page) { ?>
                <div class="page2">
                  <div class="button6">
                    <form action="" method="post">
                      <input type ="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
                      <input type ="hidden" name="page" value="<?php echo $page+1; ?>">
                      <button type="submit"><?php echo $page+1;?>ページ目へ</button>
                    </form>
                  </div>
                </div>   
              </div>
            <?php } ?>
            <?php if ($count == 0) { ?>
              <p>表示するデータはありません。</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <footer>2022 @recipenpj</footer>
  </div>
</body>
</html>