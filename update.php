
<?php
session_start();

require('library.php');

if (isset($_SESSION['user_id']) && isset($_SESSION['name'])){
  $user_id = $_SESSION['user_id'];
  $name = $_SESSION['name'];

} else {
    header('Location: login.php');
    exit();
}

$recipe_member_id = filter_input(INPUT_POST, 'recipe_member_id', FILTER_SANITIZE_NUMBER_INT);

if ($user_id == $recipe_member_id){

  $db = dbconnect();

  $stmt = $db->prepare('select * from recipen where id=?');
    if (!$stmt){
          die($db->error);
    }

  $recipe_id = filter_input(INPUT_POST, 'recipe_id', FILTER_SANITIZE_NUMBER_INT);

  $stmt->bind_param('i', $recipe_id);
  $stmt->execute();
  $stmt->bind_result($recipe_id, $recipename, $recipe_member_id, $image, $foodstuffs, $recipe, $created, $modifind); 
  $res = $stmt->fetch();
    if (!$res){
      die('正しい値を指定してください。');
    }

    // echo $image;
    // exit();

    $form = [
      'recipe_id' => '',
      'recipename' => '',
      'foodstuffs' => '',
      'recipe' => '',
      'recipe_member_id'=>'',
    ];
    $error = [];
    
    // echo 'ddd';
    // echo $_SERVER['REQUEST_METHOD'];
    // exit();
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == "1"){
      // echo 'ccc';

      $form['recipe_id'] = filter_input(INPUT_POST, 'recipe_id', FILTER_SANITIZE_STRING);

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
    
      $form['recipe_member_id'] = filter_input(INPUT_POST, 'recipe_member_id', FILTER_SANITIZE_STRING);
    
    
      // var_dump($_FILES['image']['name']);
      // exit();
      
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
            if (!move_uploaded_file($image['tmp_name'], 'recipe_picture/' . $filename)){
              die('ファイルのアップロードに失敗しました');
            } else {
              $_SESSION['form']['image'] = $filename;
            }
          // } 
        //  var_dump($_SESSION['form']['image']);
        //  exit();
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
</head>
<body>
<div><h1>レシピ編集</h1></div>

<form action="" method="post" enctype="multipart/form-data">
  
  <input type="hidden" name="type" value="1">
  <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
  <input type="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
  
  <div><p class="toukou">レシピ名</P>
  <div><input type="text" name="recipename" size="35" maxlength="255" 
  value="<?php echo h($recipename); ?>"/></div>
  <?php if (isset($error['recipename']) && $error['recipename'] === 'blank'): ?>
  <p class="error">レシピ名を入力してください。</p>
  <?php endif; ?>
  
  <p class="toukou">完成写真</P>
  <div><input type="file" name="image" size="35" value=""/></div>
  <?php if (isset($error['image']) && $error['image'] == 'type'): ?>
  <p class="error">写真は「.png」または「.jpg」の画像を指定してください。</p>
  <?php endif; ?>
  <?php if (isset($error['image']) && $error['image'] == 'blank'): ?>
  <p class="error">写真を投稿してください。</p>
  <?php endif; ?>

  <p class="toukou">材料</P>
  <div><textarea name="foodstuffs" cols="50" rows="5"><?php echo h($foodstuffs); ?></textarea></div>
  <?php if (isset($error['foodstuffs']) && $error['foodstuffs'] == 'blank'): ?>
  <p class="error">材料を入力してください。</p>
  <?php endif; ?>

  <p class="toukou">作り方</P>
  <div><textarea name="recipe" cols="50" rows="5"><?php echo h($recipe); ?></textarea></div>
  <?php if (isset($error['recipe']) && $error['recipe'] = 'blank'): ?>
  <p class="error">作り方を入力してください。</p>
  <?php endif; ?>

  <div><button type="submit">編集する</button></div>

</form>



</body>
</html>