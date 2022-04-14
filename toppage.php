<?php
require('library.php');

session_start();
$db = dbconnect();

// 最大ページを求める
$counts = $db->query('select count(*) as cnt from recipen');
$count = $counts->fetch_assoc();
$max_page = floor(($count['cnt']-1)/5+1);


$stmt = $db->prepare('select r.id, r.recipename, r.member_id, r.image, r.foodstuffs, r.recipe, r.created,  
r.modified, m.name from recipen r, member m where m.id=r.member_id order by id desc limit ?, 5');
if (!$stmt){
    die($db->error);
}
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT );
$page = ($page ?: 1);
$start = ($page - 1) * 5;
$stmt->bind_param('i', $start);
$result = $stmt->execute();

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
    <?php 
        $error = '';
        if (isset($_SESSION['id']) && isset($_SESSION['name'])){
            $name = $_SESSION['name'];
            echo $name .'さん、ようこそ';
        } else {
            $error = 'blank';
            echo 'ログインすれば、あなたもレシピを公開できます！';
            echo '会員登録まだの方は会員登録をお願いします！';
        }
    ?>
    
    <?php if ($error == 'blank'): ?>
        <div>
        <a href="login.php">ログイン</a>
        </div>
        <div>
        <a href="join/index.php">会員登録する</a>
        </div>
    <?php endif; ?>
    <?php if (!$error == 'blank'): ?>
        <div class="re-top">
        <a href="recipe/index.php">投稿する</a>
        </div>
    <?php endif; ?>
<hr>
    <?php $stmt->bind_result($id, $recipename, $member_id, $image, $foodstuffs, $recipe, $created, $modified, $name); ?>
    <?php $count =0; ?>
    <?php while ($stmt->fetch()): ?>
  
    <?php if (!$error == 'blank'): ?>
        <a href="myrecipen.php?id=<?php echo $member_id; ?>">マイページへ</a>
    <?php endif; ?>
    
 
        <div>
        <div><?php echo h($name) . 'さんのレシピん♪'; ?></div>
        <a href="recipe.php?id=<?php echo $id; ?>"><?php echo h($recipename); ?></a>
        <time><?php echo h($created); ?></time><br>
        <a href="recipe.php?id=<?php echo $id; ?>"><img src="recipe_picture/<?php echo h($image); ?>"></a>
<hr>

        <?php $count+=1; ?>
         </div>
        <?php endwhile; ?>
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page-1;?>"><?php echo $page-1;?>ページ目へ</a>|
        <?php endif;?>
        <?php if($page < $max_page): ?>
            <a href="?page=<?php echo $page+1;?>"><?php echo $page+1;?>ページ目へ</a>
        <?php endif;?>

        

        <?php if ($count == 0): ?>
            <p>
                表示するデータはありません。
            </p>
        <?php endif; ?>
       
       

        
    <!-- <div>
        <a href="#">マイページへ</a>
    </div> -->
    <?php if (!$error == 'blank'): ?>
    <div>
        <a href="logout.php">ログアウト</a>
    </div>
    <?php endif; ?>

   
    <?php if ($error == 'blank'): ?>
        <div>
        <a href="login.php">ログイン</a>
        </div>
        <div>
        <a href="join/index.php">会員登録する</a>
        </div>
    <?php endif; ?>


    

</body>
</html>