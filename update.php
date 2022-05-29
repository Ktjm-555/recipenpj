<?php
session_start();
require('library.php');

/**
　　* ログインチェック
　　*/
if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
  $user_id = $_SESSION['user_id'];
  $name = $_SESSION['name'];
} else {
  header('Location: login.php');
  exit();
}

/**
　　* SQL実行　フォームのvalueに表示するため。
　　*/
//　ここは前ページからのPOSTで送られてきた値を受け取っている。
$recipe_member_id = filter_input(INPUT_POST, 'recipe_member_id', FILTER_SANITIZE_NUMBER_INT);
$recipe_id        = filter_input(INPUT_POST, 'recipe_id', FILTER_SANITIZE_NUMBER_INT);

//　編集するユーザーがレシピを投稿したユーザーと同じだった時
if ($user_id == $recipe_member_id) {
  $db = dbconnect();
  $sql = "
    SELECT 
      *
    FROM 
      recipen 
    WHERE 
      id=?
  ";
  $stmt = $db->prepare($sql);
  if (!$stmt) {
    die($db->error);
  }
  $stmt->bind_param('i', $recipe_id);
  $stmt->execute();
  $stmt->bind_result($recipe_id, $recipename, $recipe_member_id, $image, $foodstuffs, $recipe, $created, $modifind); 
  $res = $stmt->fetch();
  if (!$res) {
    die('正しい値を指定してください。');
  }

  /**
　  　* 配列の初期化
　　  */
  $form = [
    'recipe_id'        => '',
    'recipename'       => '',
    'foodstuffs'       => '',
    'recipe'           => '',
    'recipe_member_id' =>'',
  ];
  $error = []; 

  /**
　  　* フォームの値のエラーチェック（空）
　　  */
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == "1") {
    //　ここは「編集する」を押したときに、POSTで送られてきた値を受け取っている。
    $form['recipe_id']  = filter_input(INPUT_POST, 'recipe_id', FILTER_SANITIZE_NUMBER_INT);
    $form['recipename'] = filter_input(INPUT_POST, 'recipename', FILTER_SANITIZE_STRING);
    if ($form['recipename'] == '') {
      $error['recipename'] = 'blank';
    }      
    $form['foodstuffs'] = h($_POST['foodstuffs']);
    if ($form['foodstuffs'] == '') {
      $error['foodstuffs'] = 'blank';
    }    
    $form['recipe'] = h($_POST['recipe']);
    if ($form['recipe'] == '') {
      $error['recipe'] = 'blank';
    }    
    $form['recipe_member_id'] = filter_input(INPUT_POST, 'recipe_member_id', FILTER_SANITIZE_NUMBER_INT);

    /**
  　  　* 画像のチェック
  　　  */
    $image = array();
    if ($_FILES['image']['name'] != '') {
      $image = $_FILES['image'];
    } else {
      $error['image'] = 'blank';
    }
    if (!empty($image)) {
      if ($image['error'] == 0) {
        $type = mime_content_type($image['tmp_name']);
        if ($type !== 'image/png' && $type !== 'image/jpeg') {
          $error['image'] = 'type';
        }
      }
    }
    if (empty($error)) {
      $_SESSION['form'] = $form;  
      $filename = date('YmdHis') . '_' . $image['name'];        
      if (!move_uploaded_file($image['tmp_name'], 'recipe_picture/' . $filename)) {
        die('ファイルのアップロードに失敗しました');
      } else {
        $_SESSION['form']['image'] = $filename;
      }
      header('Location: update_do.php');
      exit();
    }
  }
} else {
  header('Location: login.php');
  exit();
}
?>

<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>レシピ編集</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="upd_del_style.css">
</head>
<body>
  <div class="container">
    <header>
      <h1 class="title">Recipen 投稿画面</h1>
      <nav class="nav">
        <div class="button5">
          <form action="toppage.php" method="post">
            <button type="submit">TOPページに戻る</button>
          </form>
        </div>
      </nav>
    </header>
    <div class="main">
      <div class="join_page">
        <div class=join_page2>
          <div class="joins_form">
            <div class=page_title>
              <p>レシピ編集</p>
            </div>
            <div class="update_form">
              <form action="" method="post" enctype="multipart/form-data">                
                <input type="hidden" name="type" value="1">
                <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                <input type="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">                
                <div class="form_title">
                  <p>レシピ名</P>
                </div>
                <div class="form_contents">
                  <input type="text" name="recipename" size="35" maxlength="255" value="<?php echo h($recipename); ?>"/>
                </div>
                <div class="error">
                  <?php if (isset($error['recipename']) && $error['recipename'] === 'blank') { ?>
                    <p>レシピ名を入力してください。</p>
                  <?php } ?>
                </div>
                <div class="form_title">
                  <p>完成写真</P>
                </div>
                <div class="form_contents">
                  <input type="file" name="image" size="35" value=""/>
                </div>
                <div class="error">
                  <?php if (isset($error['image']) && $error['image'] == 'type') { ?>
                    <p>写真は「.png」または「.jpg」の画像を指定してください。</p>
                  <?php } ?>
                  <?php if (isset($error['image']) && $error['image'] == 'blank') { ?>
                    <p>写真を投稿してください。</p>
                  <?php } ?>
                </div>
                <div class="form_title">
                  <p>材料</P>
                </div>
                <div class="form_contents">
                  <textarea name="foodstuffs" cols="50" rows="5"><?php echo h($foodstuffs); ?></textarea>
                </div>
                <div class="error">
                  <?php if (isset($error['foodstuffs']) && $error['foodstuffs'] == 'blank') { ?>
                    <p>材料を入力してください。</p>
                  <?php } ?>
                </div>
                <div class="form_title">
                  <p>作り方</P>
                </div>
                <div class="form_contents">
                  <textarea name="recipe" cols="50" rows="5"><?php echo h($recipe); ?></textarea>
                </div>
                <div class="error">
                  <?php if (isset($error['recipe']) && $error['recipe'] = 'blank') { ?>
                    <p>作り方を入力してください。</p>
                  <?php } ?>
                </div>
                <div class="form2">
                  <button type="submit">編集する</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footer>2022 @recipenpj</footer>
  </div>
</body>
</html>