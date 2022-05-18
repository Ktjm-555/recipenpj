<?php 
session_start() ;
if (isset($_SESSION['form'])){
  $form = $_SESSION['form'];
} else {
  header('Location: ../login.php');
  exit();
}
$error = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST'&& $_POST['type'] == "3"){
  $db = new mysqli('localhost:8889', 'root', 'root', 'recipenpj'); 
  if (!$db){
    die($db->error); 
  }
  // $stmt = $db->prepare('select count(buy_u_id) from buy where recipe_d_id=?');
  // if (!$stmt){
  //   die($db->error);
  // }
  // $stmt->bind_param('i', $form['recipe_d_id']);
  // $success = $stmt->execute();
  // if (!$success){
  //   die($db->error);
  // } 
  // $stmt->bind_result($cnt);
  // $stmt->fetch();
  // if($cnt > 0){
  //   $error['recipe_id'] = 'juhuku';
  // }
  
  // if (empty($error)){
    $sql = "INSERT INTO
    buy
    (product, buy_u_id, recipe_d_id)
    VALUES
    ('".$form['product']."', '".$form['buy_u_id']."','".$form['recipe_d_id']."')";
    $res = $db->query($sql);
    // var_dump($res);
    // var_dump($sql);
    // var_dump($db->query($sql));
    // exit();
    if ($res){
      $recipe_d_id = $form['recipe_d_id'];
      header('Location: recipe.php?id=' . $recipe_d_id );
      exit();
    }else{
      // var_dump($form['recipe_d_id']);
      // exit();
     echo 'aaa';
    }   
  // }
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
                <?php if (isset($error['recipe_id'])): ?>
                    <p>*既に登録されています</p>
                  <?php endif; ?>   
              </div>            
            </dl>
            <div class="form2">
              <button type="submit">登録する</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <footer>
      2022 @recipenpj
    </footer>
  </div>
</body>
</html>