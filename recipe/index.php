<?php
session_start();
require('../library.php');

if (isset($_SESSION['id']) && isset($_SESSION['name'])){
    $name = $_SESSION['name'];
} else {
    header('Location: ../login.php');
    exit();
}

// フォームが送信されたとき
$form = [
  'recipename' => '',
  'foodstuffs' => '',
  'recipe' => '',
  'member_id'=>'',
  
];
$error = [];

// echo 'ddd';
// echo $_SERVER['REQUEST_METHOD'];

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  // echo 'ccc';
  $form['recipename'] = filter_input(INPUT_POST, 'recipename', FILTER_SANITIZE_STRING);
  if ($form['recipename'] == ''){
    $error['recipename'] = 'blank';
  }
  
  $form['foodstuffs'] = filter_input(INPUT_POST, 'foodstuffs', FILTER_SANITIZE_STRING);
  if ($form['foodstuffs'] == ''){
    $error['foodstuffs'] = 'blank';
  }

  $form['recipe'] = filter_input(INPUT_POST, 'recipe', FILTER_SANITIZE_STRING);
  if ($form['recipe'] == ''){
    $error['recipe'] = 'blank';
  }

  $form['member_id'] = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_STRING);
 

  // var_dump($_FILES['image']['name']);
  
  // がそうのチェック
  $image = array();
  if ($_FILES['image']['name'] != ''){
    // echo 'bbb';

    $image = $_FILES['image'];
  } else {
    // echo 'aaa';
    $error['image'] = 'blank';
  }
  // var_dump($error);
  if(!empty($image)){
    // 画像があるとき からじゃないから　
    if($image['error'] == 0){
      // エラーがなければtype
      $type = mime_content_type($image['tmp_name']);
      
      if ($type !== 'image/png' && $type !== 'image/jpeg'){
        $error['image'] = 'type';
      }
    }
  }
// var_dump($error);
    if (empty($error)){
      // エラーがからの時
      $_SESSION['form']  = $form;

      // 画像のアップロード
      // if (isset($image['image'])){
        $filename = date('YmdHis') . '_' . $image['name'];
       
        // move_uploaded_file($image['tmp_name'], '../recipe_picture/' . $filename);
        // $_SESSION['form']['image'] = $filename;
        // 62,63を追加
        if (!move_uploaded_file($image['tmp_name'], '../recipe_picture/' . $filename)){
          die('ファイルのアップロードに失敗しました');
        } else {
          $_SESSION['form']['image'] = $filename;
        }
      // } 
     
      header('Location: check.php');
      exit();
  }

}

?>



<!doctype html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>レシピ投稿画面</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
<div><h1>レシピ投稿</h1></div>

<div><?php echo h($name); ?>さん、今日もレシピ投稿ありがとうございます！</div>


<form action="" method="post" enctype="multipart/form-data">
  <p class="toukou">レシピ名</P>

  <input type="hidden" name="member_id" value="<?php echo $_SESSION['id']; ?>">

  <input type="text" name="recipename" size="35" maxlength="255" value="<?php echo h($form['recipename']); ?>"/>
  <?php if (isset($error['recipename']) && $error['recipename'] === 'blank'): ?>
  <p class="error">レシピ名を入力してください。</p>
  <?php endif; ?>

  <p class="toukou">完成写真</P>
  <input type="file" name="image" size="35" value=""/>
  <?php if (isset($error['image']) && $error['image'] == 'type'): ?>
  <p class="error">写真は「.png」または「.jpg」の画像を指定してください。</p>
  <?php endif; ?>
  <?php if (isset($error['image']) && $error['image'] == 'blank'): ?>
  <p class="error">写真を投稿してください。</p>
  <?php endif; ?>


  <p class="toukou">材料</P>
  <textarea name="foodstuffs" cols="50" rows="5"><?php echo h($form['foodstuffs']); ?></textarea>
  <?php if (isset($error['foodstuffs']) && $error['foodstuffs'] == 'blank'): ?>
  <p class="error">材料を入力してください。</p>
  <?php endif; ?>

  <p class="toukou">作り方</P>
  <textarea name="recipe" cols="50" rows="5"><?php echo h($form['recipe']); ?></textarea>
  <?php if (isset($error['recipe']) && $error['recipe'] = 'blank'): ?>
  <p class="error">作り方を入力してください。</p>
  <?php endif; ?>

  <div><button type="submit">入力内容を確認する</button></div>

  <div class="gopage">
        <a href="../toppage.php" class="gopage">TOPページに戻る</a>
    </div>

</form>

</body>

</html>
