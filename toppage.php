<?php
require('library.php');
$db = dbconnect();

$recipen = $db->query('select * from recipen order by id desc');
if (!$recipen){
    die($db->error);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
</head>
<body>
    <div><h1>トップページ</h1></div>
    <div><h2>投稿一覧</h2></div>
    <hr>
    <?php while ($recipes = $recipen->fetch_assoc()): ?>
        <!-- 好きな変数とテーブル名？ -->
        <div>
        <a href="recipe.php?id=<?php echo $recipes['id']; ?>"><?php echo h($recipes['recipename']); ?></a>
        <time><?php echo h($recipes['created']); ?></time><br>
        <!-- $recipes（上で設定した変数）['recipenname'（カラム名）] -->
        <a href="recipe.php"><img src="recipe_picture/<?php echo h($recipes['image']); ?>"></a><br>
        </div>
        <hr>
    <?php endwhile; ?>
    
    <div>
        <a href="#">マイページへ</a>
    </div>
    <div>
        <a href="#">ログイン</a>
    </div>
    <div class="re-top">
        <a href="recipe/index.php">投稿する</a>
    </div>
    

</body>
</html>