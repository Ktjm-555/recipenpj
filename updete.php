
<?php
require('library.php');
$db = dbconnect();

$stmt = $db->prepare('select * from recipen where id=?');
if (!$stmt){
        die($db->error);
    }
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$stmt->bind_param('i', $id);
$stmt->execute();

$stmt->bind_result($id, $recipename, $image, $foodstuffs, $recipe, $created, $modifind); 
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
<form action="" method="post" enctype="multipart/form-data">
  <div><p class="toukou">レシピ名</P>
  <div><input type="text" name="recipename" size="35" maxlength="255" 
  value="<?php echo h($recipename); ?>"/></div>
  <div><input type="file" name="image" size="35" value=""/></div>
  <div><textarea name="foodstuffs" cols="50" rows="5"><?php echo h($foodstuffs); ?></textarea></div>
  <div><textarea name="recipe" cols="50" rows="5"><?php echo h($recipe); ?></textarea></div>


  <div><button type="submit">編集する</button></div>

</form>

</body>

</html>
