<?php
require('library.php');

session_start();
$db = dbconnect();

if (isset($_SESSION['id']) && isset($_SESSION['name'])){
    $name = $_SESSION['name'];
    $member_id = $_SESSION['id'];
    $aisatsu = 'doumo';
} else {
    echo 'あれ？';
    exit();
}
$counts = $db->query('select count(*) as cnt from recipen');

// 最大ページを求める
// $counts = $db->prepare('select count(*) as cnt from recipen where id=?');
// // $count->bind_param('i', $id);
// // $result = $stmt->execute();

// 自身の投稿だけの数を取り出す
$count = $counts->fetch_assoc();
$max_page = floor(($count['cnt']-1)/5+1);



$stmt = $db->prepare('select * from recipen where member_id=? order by id desc limit ?, 5');
if (!$stmt){
    die($db->error);
}
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT );
$page = ($page ?: 1);
// 上のはページはURLでid指定されなかった時1頁目を開くということ
$page = ($page ?: 1);
$start = ($page - 1) * 5;
$stmt->bind_param('ii', $member_id, $start);
$result = $stmt->execute();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>あなたのレシピん</title>
</head>
<body>
    <div><h1>あなたのレシピん一覧</h1></div>
    <?php if ($aisatsu == 'doumo') { ?>
    <div><?php $name .'さん、ようこそ'; ?></div>
    <?php } ?>

<hr>
    <?php $stmt->bind_result($id, $recipename, $member_id, $image, $foodstuffs, $recipe, $created, $modified); ?>
    <?php $count =0; ?>
    <?php while ($stmt->fetch()): ?>
  

    
 
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
        <?php if($page < $max_page && $page != 1): ?>
            <a href="?page=<?php echo $page+1;?>"><?php echo $page+1;?>ページ目へ</a>
        <?php endif;?>

        

        <?php if ($count == 0): ?>
            <p>
                表示するデータはありません。
            </p>
        <?php endif; ?>
       
       

        
    

    

</body>
</html>