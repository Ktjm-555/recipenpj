<?php

session_start();
require('../library.php');

if (isset($_SESSION['form'])){
    $form = $_SESSION['form'];
} else {
 header('Location: index.php');
 exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
   $db = new mysqli('localhost:8889', 'root', 'root', 'recipenpj'); 
   if (!$db){
       die($db->error);
   }
   $password = password_hash($form['password'], PASSWORD_DEFAULT);
   $sql = "INSERT INTO 
   member
    (name, email, password) 
    VALUES 
    ('".$form['name']."','".$form['email']."','".$password."')";
    $res = $db->query($sql);
    // パスワードを表示しないようにするためには？
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
    <title>登録確認画面</title>
</head>
<body>

<form action="" method="post">
    <dl>
    <dt>ニックネーム</dt>
    <dd><?php echo h($form['name']); ?></dd>
    <dt>アドレス</dt>
    <dd><?php echo h($form['email']); ?></dd>
    <dt>パスワード</dt>
    <dd>【表示はしないので、ご安心ください】</dd>
    </dl>

 <br><button type="submit">登録する</button> 
</form>


</body>
</html>