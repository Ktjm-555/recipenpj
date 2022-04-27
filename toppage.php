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
$page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );
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
    <link rel="stylesheet" href="top_style.css">
    <title>トップページ</title>
</head>
<body>
    <div class="container"> 
        <?php 
            $error = '';
            if (isset($_SESSION['user_id']) && isset($_SESSION['name'])){
                $user_id = $_SESSION['user_id'];
                $name = $_SESSION['name'];
            } else {
                $error = 'blank';
            }
        ?>         
        <header>
            <div class="title">
                Recipen
            </div>
            <nav class="nav">
                <?php if ($error == 'blank'): ?>
                    <div class="button5 login1">
                        ログインすれば、あなたもレシピを公開できます！
                        会員登録まだの方は会員登録をお願いします！
                    </div>
                    <div class="button5 login1">
                        <form action="login.php" method="post" >
                            <input type="hidden" name="type" value="2">
                            <button type="submit"> 
                                ログイン
                            </button>
                        </form>
                    </div>
                    <div class="button5 join1">
                        <form action="join/index.php" method="post" >
                            <input type="hidden" name="type" value="2">
                            <button type="submit"> 
                                会員登録する
                            </button>
                        </form>
                    </div>
                    <div class="button5 join1">
                        <form action="chat/chat.php" >
                            <button type="submit"> 
                                チャットページ
                            </button>
                        </form>
                    </div>
                    
                <?php endif; ?>
                <?php if (!$error == 'blank'): ?>
                    <div class="button5 join1">
                        <?php echo h($name); ?>さん、ようこそ！
                    </div>
                    
                    <div class="button5 join1">
                        <form action="recipe/index.php" method="post" >
                            <input type="hidden" name="type" value="2">
                            <button type="submit"> 
                                投稿する
                            </button>
                        </form>
                    </div>
                    
                    <div class="button5">
                        <form action="myrecipen.php" method="post" >
                            <input type ="hidden" name="recipe_member_id" value="<?php echo $user_id; ?>">
                            <button type="submit"> 
                                マイページ
                            </button>
                        </form>
                    </div>
                    <div class="button5 join1">
                        <form action="chat/chat.php" method="post" >
                            <button type="submit"> 
                                チャットページ
                            </button>
                        </form>
                    </div>
                    <div class="button5">
                        <form action="logout.php" method="post" >
                            <button type="submit"> 
                                ログアウト
                            </button>
                        </form>
                    </div>
                <?php endif; ?>       
            </nav>
        </header>
        <div class="main">
        <script>
            var today = new Date();
            var todayHtml = today.getFullYear() + '年' + (today.getMonth()+1) + '月' + today.getDate() + '日';
            document.write('<p class="date">本日の日付は' + todayHtml + 'です</p>');
        </script>
            <div class=join_page> 
                <div class=join_page2>
                    <div class="join_form">
                    <?php $stmt->bind_result($recipe_id, $recipename, $recipe_member_id, $image, $foodstuffs, $recipe, $created, $modified, $name); ?>
                    <?php $count =0; ?>
                    <?php while ($stmt->fetch()): ?>
                        <div class="form_title2 form_title1">
                            <?php echo h($name) . 'さんのレシピん♪'; ?>
                        </div>
                    
                        <div class="form_title2">
                            <a href="recipe.php?id=<?php echo $recipe_id; ?>"><?php echo h($recipename); ?></a>
                        </div>

                        <div class="form_title2">
                            <time><?php echo h($created); ?></time>
                        </div>

                        <div class="form_title2">
                            <a href="recipe.php?id=<?php echo $recipe_id; ?>"><img src="recipe_picture/<?php echo h($image); ?>"></a>
                        </div>

                        <?php $count+=1; ?>
                        <?php endwhile; ?>  
                        
                        <?php if ($page > 1){ ?>
                            <div class="page">
                                <div class="button6">
                                    <form action="" method="post" >
                                        <input type ="hidden" name="page" value="<?php echo $page-1; ?>">
                                        <button type="submit"> 
                                            <?php echo $page-1;?>ページ目へ
                                        </button>
                                    </form>
                                </div>
                        <?php } ?>

                        <?php if($page < $max_page) { ?>
                                <div class="page2">
                                    <div class="button6">
                                        <form action="" method="post" >
                                            <input type ="hidden" name="page" value="<?php echo $page+1; ?>">
                                            <button type="submit"> 
                                                <?php echo $page+1;?>ページ目へ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($count == 0){ ?>
                            <p>
                            表示するデータはありません。
                            </p>
                        <?php } ?>
                    </div>
                </div>               
            </div>
        </div>
        <footer>
            2022 @recipenpj
        </footer>
    </div>
</body>
</html>