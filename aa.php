<?php
session_start();
require('library.php');
if (isset($_SESSION['id']) && isset($_SESSION['name'])){
    $name = $_SESSION['name'];
} else {
    header('Location: login.php');
    exit();
}
$db = dbconnect();
$stmt = $db->prepare('select * from recipen where id=?');
if (!$stmt){
        die($db->error);
    }
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($id, $recipename, $member_id, $image, $foodstuffs, $recipe, $created, $modifind); 
$res = $stmt->fetch();
if (!$res){
  die('正しい値を指定してください。');
}
// header()
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
<?php 
  $clear = '';
   if (isset($_SESSION['id']) && isset($_SESSION['name']) && $_SESSION['id'] == $member_id){
    $clear = 'clear'; 
  }  
?>
<?php if ($clear == 'clear'){ ?>
<form action="update_do.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?php echo $id; ?>">
  <input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
  <div><p class="toukou">レシピ名</P>
  <div><input type="text" name="recipename" size="35" maxlength="255" 
  value="<?php echo h($recipename); ?>"/></div>
  <div><input type="file" name="image" size="35" value=""/></div>
  <div><textarea name="foodstuffs" cols="50" rows="5"><?php echo h($foodstuffs); ?></textarea></div>
  <div><textarea name="recipe" cols="50" rows="5"><?php echo h($recipe); ?></textarea></div>
  <div><button type="submit">編集する</button></div>
</form>
<?php } else { ?>
<div>自身の投稿したものでないと変えれませんよ！</div>
<div><a href="toppage.php">TOPページに戻る</a></div>
<?php } ?>
</body>
</html>