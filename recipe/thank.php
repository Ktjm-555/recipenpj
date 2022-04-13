<?php

session_start();

if (isset($_SESSION['id']) && isset($_SESSION['name'])){
    $name = $_SESSION['name'];
} else {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿完了</title>
</head>
<body>
    <div>投稿完了しました</div>
    <div class="gopage">
        <a href="../toppage.php" class="gopage">TOPページへ</a>
    </div>
</body>
</html>