<?php
session_start();
require('library.php');

/**
　　* ログイン確認
　　*/
if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
	$name = $_SESSION['name'];
	$user_id = $_SESSION['user_id'];
} else {
	header('Location: ./login.php');
	exit();
}

/**
　　* SQL実行 一つ取得
　　*/
$db = dbconnect();
$sql = "
	SELECT 
		r.*, 
		m.name 
	FROM 
		recipen r        
	LEFT JOIN 
		member m ON r.member_id = m.id
	WHERE 
		r.id=?
";
$stmt = $db->prepare($sql);
if (!$stmt) {
die($db->error);
}					
$recipe_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$stmt->bind_param('i', $recipe_id);
$stmt->execute();
$stmt->bind_result($recipe_id, $recipename, $recipe_member_id, $image, $foodstuffs, $recipe, $created, $modified, $name);
$stmt->fetch();

/**
　　* 買い物リストに登録するため、チェック画面へ
　　*/
$form = [
  'product' 	  => '',
  'buy_u_id'    => '',
	'recipe_d_id' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == "2") {
  $form['product']     = h($_POST['product']);
	$form['buy_u_id']    = filter_input(INPUT_POST, 'buy_u_id', FILTER_SANITIZE_NUMBER_INT);
	$form['recipe_d_id'] = filter_input(INPUT_POST, 'recipe_d_id', FILTER_SANITIZE_NUMBER_INT);
	$_SESSION['form']    = $form;
	header('Location: check.php');
	exit();
} 
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="reci_style.css">
	<title>レシピ詳細</title>
</head>
<body>
	<div class="container"> 
		<header>
			<h1 class="title">Recipen レシピん詳細</h1>
			<nav class="nav">
			<div class="button5">
					<form action="buy_list.php" method="post">
						<input type ="hidden" name="buy_u_id" value="<?php echo $user_id; ?>">
						<button type="submit">買うものリスト</button>
					</form>
				</div>
				<div class="button5">
					<form action="recipe/index.php" method="post">
						<input type="hidden" name="type" value="2">
						<button type="submit">投稿する</button>
					</form>
				</div>
				<div class="button5">
					<form action="toppage.php" method="post">
						<button type="submit">TOPページに戻る</button>
					</form>
				</div>
			</nav>
		</header>
		<div class="main">
			<div class=join_page> 
				<div class=join_page2>
					<div class="join_form">
						<div class=page_title>
							<?php echo h($name) . 'さんのレシピん♪' ; ?>
						</div>
						<div class="form_title2">
							<?php echo h($recipename); ?>
						</div>
						<div class="form_title2">
							<time><?php echo h($created); ?></time>
						</div>						
						<div class="form_title2">
							<img src="recipe_picture/<?php echo h($image); ?>">
						</div>
						<div class="form_title2">
							<p>材料</p>
						</div>
						<form action="" method="post">
							<input type="hidden" name="type" value="2">
							<input type="hidden" name="product" value="<?php echo h($foodstuffs); ?>">
							<input type="hidden" name="buy_u_id" value="<?php echo $user_id; ?>">
							<input type="hidden" name="recipe_d_id" value="<?php echo $recipe_id; ?>">
							<div class="form_title2">
								<pre><?php echo h($foodstuffs); ?></pre>
							</div>
							<div class="form3">
								<button type="submit">作る予定</button>
							</div>
						</form>
						<div class="form_title2">
							<p>作り方</p>
						</div>
						<div class="form_title2">
							<pre><?php echo h($recipe); ?></pre>
						</div>
						<?php 
						$clear = '';
						if (isset($_SESSION['user_id']) && isset($_SESSION['name']) && $_SESSION['user_id'] == $recipe_member_id) {
							$clear = 'clear'; 
						}  
						?>						
					<?php if ($clear == 'clear') { ?>
						<div class="page">
							<div class="button6">
								<form action="update.php" method="post" enctype="multipart/form-data">
									<input type="hidden" name="type" value="2">
									<input type="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
									<input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
									<button type="submit">編集する</button>
								</form>
							</div>
							<div class="button6">
								<form action="delete.php" method="post" enctype="multipart/form-data">
									<input type="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
									<input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
									<button type="submit">削除</button>
								</form>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<footer>2022 @recipenpj</footer>
	</div>
</body>
</html>