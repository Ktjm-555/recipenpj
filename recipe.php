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
    session_start();

    $stmt = $db->prepare('select r.id, r.recipename, r.member_id, r.image, r.foodstuffs, r.recipe, r.created,  
    r.modified, m.name from recipen r, member m where r.id=? and m.id=r.member_id');
    if (!$stmt){
    die($db->error);
    }
   
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $stmt->bind_result($id, $recipename, $member_id, $image, $foodstuffs, $recipe, $created, $modified, $name);
    $stmt->fetch();

    ?>
    <div><?php echo h($name) . 'さんのレシピん♪' ; ?></div>

    <div><?php echo h($recipename); ?></div>
    <time><?php echo h($created); ?></time><br>
    <img src="recipe_picture/<?php echo h($image); ?>">
    <div>材料</div>
    <div><pre><?php echo h($foodstuffs); ?></pre></div>
    <div>作り方</div>
    <div><pre><?php echo h($recipe); ?><pre></div>

    <div>
      <a href="toppage.php">TOPページに戻る</a>
    </div>

    <?php 
        $clear = '';
        if (isset($_SESSION['id']) && isset($_SESSION['name']) && $_SESSION['id'] == $member_id){
            $clear = 'clear'; 
        }  
    ?>
    
  <?php if ($clear == 'clear'): ?>
    <a href="update.php?id=<?php echo $id; ?>">編集する</a>|

    <a href="delete.php?id=<?php echo $id; ?>">削除する</a>|

    <form action="delete.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="recipe_member_id" value="<?php echo $member_id; ?>">
      <input type="hidden" name="recipe_id" value="<?php echo $id; ?>">
      <button type="submit"> 
          削除する
        </button>
      </form>

  <?php endif; ?>
</body>
</html>