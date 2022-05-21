<?php
require('library.php');

session_start();
$db = dbconnect();

if (isset($_SESSION['user_id']) && isset($_SESSION['name'])){
    $user_id = $_SESSION['user_id'];
    $name = $_SESSION['name'];
    $aisatsu = 'doumo';
} else {
    header('Location: toppage.php');
    exit();
}

$recipe_member_id = filter_input(INPUT_POST, 'recipe_member_id', FILTER_SANITIZE_NUMBER_INT);

$counts = $db->query("select count(*) as cnt from recipen where member_id='".$recipe_member_id."'");
$count = $counts->fetch_assoc();
$max_page = floor(($count['cnt']-1)/5+1);
$stmt = $db->prepare('select * from recipen where member_id=? order by id desc limit ?, 5');
if (!$stmt){
    die($db->error);
}
$page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );
$page = ($page ?: 1);
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
          <form action="./buy_list.php" method="post" >
            <input type="hidden" name="type" value="2">
            <input type ="hidden" name="buy_u_id" value="<?php echo $user_id; ?>">
            <button type="submit"> 
              買い物リスト
            </button>
          </form>
        </div>
        <div class="button5">
          <form action="recipe/index.php" method="post" >
            <input type="hidden" name="type" value="2">
            <button type="submit"> 
              投稿する
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
              <?php echo h($name) . 'さんのレシピん♪'; ?>
            </div>
            <?php $stmt->bind_result($recipe_id, $recipename, $recipe_member_id, $image, $foodstuffs, $recipe, $created, $modified); ?>
            <?php $count =0; ?>
            <?php while ($stmt->fetch()): ?>
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
            <?php endwhile; ?>
            <?php if ($page > 1): ?>
              <div class="page">
                <div class="button6">
                  <form action="" method="post" >
                    <input type ="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
                    <input type ="hidden" name="page" value="<?php echo $page-1; ?>">
                    <button type="submit"> 
                    <?php echo $page-1;?>ページ目へ
                    </button>
                  </form>
                </div>
            <?php endif;?>
            <?php if($page < $max_page): ?>
                <div class="page2">
                  <div class="button6">
                    <form action="" method="post" >
                      <input type ="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
                      <input type ="hidden" name="page" value="<?php echo $page+1; ?>">
                      <button type="submit"> 
                          <?php echo $page+1;?>ページ目へ
                      </button>
                    </form>
                  </div>
                </div>   
              </div>
            <?php endif;?>
            <?php if ($count == 0): ?>
              <p>
                表示するデータはありません。
              </p>
            <?php endif; ?>
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