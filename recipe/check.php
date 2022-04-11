<?php
session_start();
require('../library.php');


if (isset($_SESSION['form'])){
    $form = $_SESSION['form'];
} else {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $db = new mysqli('localhost:8889', 'root', 'root', 'recipenpj'); 
    if (!$db){
        die($db->error);   
    }
    $sql = "INSERT INTO
    recipen
    (recipename, member_id, foodstuffs, recipe, image)
    VALUES
    ('".$form['recipename']."', '".$form['member_id']."','".$form['foodstuffs']."','".$form['recipe']."','".$form['image']."')";



    $res = $db->query($sql);
    if ($res){
        unset($_SESSION['form']);
        header('Location: thank.php');
        exit();
        }else{
        echo 'できていませんよ！何かがおかしいよ！'; 
    }
    

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
<form action="" method="post" enctype="multipart/form-data">
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

    <div><button type="submit">投稿する</button></div>

</form>


</body>
</html>