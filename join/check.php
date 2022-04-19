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

    // var_dump($form['email']);
    // exit();

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
    <title>登録確認画面</title>
</head>
<body>

<form action="" method="post">
    <dl>
    <dt>ニックネーム</dt>
    <dd><?php echo h($form['name']); ?></dd>
    <dd><?php echo h($form['user_id']); ?></dd>
    <dt>アドレス</dt>
    <dd><?php echo h($form['email']); ?></dd>
    <dt>パスワード</dt>
    <dd>【表示はしないので、ご安心ください】</dd>
    </dl>
 <div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a>
 <div><button type="submit">登録する</button> </div>
</form>


</body>
</html>