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
   $db = dbconnect();
   if (!$db){
       die($db->error);
   }
//    echo $form['email'];
//    exit();
   $password = password_hash($form['password'], PASSWORD_DEFAULT);
   $sql = "INSERT INTO 
   member
    (name, email, password) 
    VALUES 
    ('".$form['name']."','".$form['email']."','".$password."')";
    $res = $db->query($sql);

    


    $sql = "SELECT id
    from
    member
    where email='".$form['email']."'";
    // exit($sql);
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    // 結果を配列に入れる　一列ごとに入れるを入れる　一個しかない。　カラム名がキーになる。
    // var_dump($row);
    // exit();

    if ($res){
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['name'] = $form['name'];
        // var_dump($_SESSION['user_id']);
        // exit();
    header('Location: thank.php');
    // exit();
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
    <link rel="stylesheet" href="join_style.css">
    <title>登録確認画面</title>
</head>
<body>
<div class="join_check_page">
    <div class=page_title>
        <h1>確認画面</h1>
    </div>
    <form action="" method="post">
        <dl>
        <div class="form_title">
            <dt>ニックネーム</dt>
        </div>
        <div class="form">
            <dd><?php echo h($form['name']); ?></dd>
            <dd><?php echo h($form['user_id']); ?></dd>
        </div>
        <div class="form_title">
            <dt>アドレス</dt>
        </div>
        <div class="form">
            <dd><?php echo h($form['email']); ?></dd>
        </div>

        <div class="form_title">
        <dt>パスワード</dt>
        </div>
        <div class="form_title">
        <dd>【表示はしないので、ご安心ください】</dd>
        </div>

        </dl>
        <div class="form">
            
            <a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a>
        
        </div>
        <div class="form">
            <button type="submit">登録する</button>
        </div>
    </form>
</div>

</body>
</html>