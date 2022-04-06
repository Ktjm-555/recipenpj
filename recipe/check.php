<?php
session_start();
require('../library.php');
if (isset($_SESSION['form'])){
    $form = $_SESSION['form'];
} else {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿確認画面</title>
</head>
<body>
<dl>
    <dt>レシピ名</dt>
    <dd><?php echo h($form['recipe']); ?></dd>
    <dt>写真</dt>
    <dd>
        <img src="../recipe_picture/<?php echo h($form['image']); ?>">
    </dd>
    <dt>材料</dt>
    <dd><?php echo h($form['foodstuffs']); ?></dd>
    <dt>作り方</dt>
    <dd><?php echo h($form['recipe']); ?></dd>
    </dl>
    
</body>
</html>