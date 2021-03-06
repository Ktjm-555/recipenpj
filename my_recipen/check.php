<?php 
session_start();
require('../library.php');

if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: ../login.php');
  exit();
}

$error = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST'&& $_POST['type'] == "3") {
  $db = dbconnect();
  /**
　　  * SQL実行　 買うものリスト登録
　　  */
  $sql = "
    INSERT INTO
      buy
      (product, buy_u_id, recipe_d_id)
    VALUES
      (?, ?, ?)
  ";
  $stmt = $db->prepare($sql);
  if (!$stmt) {
    die($db->error);
  }
  $stmt->bind_param('sii', $form['product'], $form['buy_u_id'], $form['recipe_d_id']);
  $success = $stmt->execute();
  
  if (!$success) {
		header('Location: check.php');
    exit();
  } else {
    header('Location: ../recipe/recipe.php?id=' . $form['recipe_d_id'] );
    exit();
  }   
  if (!$success) {
	}
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="reci_style.css">
  <title>購入予定のもの確認</title>
</head>
<body>
  <div class="container"> 
    <header>
      <h1 class="title">Recipen レシピん詳細</h1>
        <nav class="nav">
          <div class="button5">
            <form action="recipe/index.php" method="post" >
              <input type="hidden" name="type" value="2">
              <button type="submit">投稿する</button>
            </form>
          </div>
          <div class="button5">
            <form action="../index.php" method="post" >
              <button type="submit">TOPページに戻る</button>
            </form>
          </div>
        </nav>
    </header>
    <div class="main">
      <div class=join_page3> 
        <div class=join_form>
          <div class=page_title>
            <h1>確認画面</h1>
          </div>
          <form action="" method="post">
            <input type="hidden" name="type" value="3">
            <dl>          
              <div class="form_title2">
                <dt>買うもの</dt>
              </div>
              <div class="form_title2">
                <dd><pre><?php echo $form['product']; ?></pre></dd>   
              </div>            
            </dl>
            <div class="form2">
              <button type="submit">登録する</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <footer>2022 @recipenpj</footer>
  </div>
</body>
</html>