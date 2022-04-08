<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レシピ詳細</title>
</head>
<body>
    <?php
    require('library.php');
    $db = dbconnect();

    $stmt = $db->prepare('select * from recipen where id =?');
    if (!$stmt){
        die($db->error);
    }
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $stmt->bind_result($id, $recipename, $image, $foodstuffs, $recipe, $created, $modifind);
    $stmt->fetch();

    ?>
    <div><?php echo h($recipename); ?></div>
    <time><?php echo h($created); ?></time><br>
    <img src="recipe_picture/<?php echo h($image); ?>">
    <div>材料</div>
    <div><?php echo h($foodstuffs); ?></div>
    <div>作り方</div>
    <div><?php echo h($recipe); ?></div>


    
  
  
    

    
</body>
</html>