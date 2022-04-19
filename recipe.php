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
   
    // URLパラメータで指定されたidを受け取る。
    $recipe_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
    // なぜここ’id’なんだ？カラム名が入る？　hiddenだとnameだけど。。。
    $stmt->bind_param('i', $recipe_id);
    $stmt->execute();

    $stmt->bind_result($recipe_id, $recipename, $recipe_member_id, $image, $foodstuffs, $recipe, $created, $modified, $name);
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

    <form action="toppage.php" method="post" >
        <button type="submit"> 
        TOPページに戻る
        </button>
    </form>
    

    <?php 
        $clear = '';
        if (isset($_SESSION['user_id']) && isset($_SESSION['name']) && $_SESSION['user_id'] == $recipe_member_id){
            $clear = 'clear'; 
        }  
    ?>
    
  <?php if ($clear == 'clear'): ?>

    <form action="update.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="2">
      <input type="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
      <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
      <button type="submit"> 
          編集する
        </button>

    <form action="delete.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
      <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
      <button type="submit"> 
          削除する
        </button>
      </form>

  <?php endif; ?>
</body>
</html>