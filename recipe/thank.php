<?php

session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['name'])){
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
    <link rel="stylesheet" href="recipe_style.css">
    <title>投稿完了</title>
</head>
<body>
<header>
    <h1 class="title">タイトル</h1>
    <nav class="nav">
      <ul class="menu-group">
        <li class="menu-item"><a href="#">項目1</a></li>
        <li class="menu-item"><a href="#">項目2</a></li>
        <li class="menu-item"><a href="#">項目3</a></li>
        <li class="menu-item"><a href="#">項目4</a></li>
        <li class="menu-item"><a href="#">項目5</a></li>
      </ul>
    </nav>
  </header>
    <div class="join_thank_page">
        
        <div class="end">
            <div>投稿完了しました</div>
        </div>
        
        <div class="form4" >
            <form action="../toppage.php" method="post" >
                <button type="submit"> 
                TOPページに戻る
                </button>
            </form>
        </div>
    </div>
</body>
</html>