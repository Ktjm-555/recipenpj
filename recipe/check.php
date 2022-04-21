<?php
session_start();
require('../library.php');


if (isset($_SESSION['form'])){
    $form = $_SESSION['form'];
} else {
    header('Location: ../login.php');
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
    ('".$form['recipename']."', '".$form['recipe_member_id']."','".$form['foodstuffs']."','".$form['recipe']."','".$form['image']."')";

    $res = $db->query($sql);
    if ($res){
        // unset($_SESSION['form']);
        header('Location: thank.php');
        exit();
        }else{
        echo 'できていませんよ！何かがおかしいよ！'; 
        // var_dump($form['recipename']);
        // exit();
    }
    

}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="recipe2_style.css">
    <title>投稿確認画面</title>
</head>
<body>
<div class="container">
<!-- <div class="recipe_check_page"> -->
    
    <div class="recipe_form">
        <div class=page_title>
            <h1>確認画面</h1>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form_title">
                <dt>レシピ名</dt>
            </div>

        <dl>
            <div class="form">
                <dd><?php echo h($form['recipe']); ?></dd>
            </div>

            <div class="form_title">
                <dt>写真</dt>
            </div>  
            
            <div class="form">
                <dd>
                    <img src="../recipe_picture/<?php echo h($form['image']); ?>">
                </dd>
            </div>  

            <div class="form_title">
                <dt>材料</dt>
            </div>

            <div class="form">
                <dd><?php echo h($form['foodstuffs']); ?></dd>
            </div>

            <div class="form_title">
                <dt>作り方</dt>
            </div>

            <div class="form">
                <dd><?php echo h($form['recipe']); ?></dd>
            </div>
            </dl>
            <div class="form3">
            <div><button type="submit">投稿する</button></div>
            </div>
        </form>
    </div>
<!-- </div> -->
</div>

</body>
</html>