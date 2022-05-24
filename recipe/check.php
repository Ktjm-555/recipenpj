<?php
session_start();
require('../library.php');

if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
	header('Location: ../login.php');
	exit();
}

/**
　　* SQLの実行
　　*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$db = new mysqli('localhost:8889', 'root', 'root', 'recipenpj'); 
	if (!$db) {
		die($db->error); 
	}
	$sql = "
		INSERT INTO
			recipen
			(recipename, member_id, foodstuffs, recipe, image)
		VALUES
			(?, ?, ?, ?, ?)
		";
	$stmt = $db->prepare($sql);	
	if (!$stmt){
		header('Location: index.php');
		exit();
	}
	$stmt->bind_param("sisss", $form['recipename'], $form['recipe_member_id'], $form['foodstuffs'],$form['recipe'],$form['image']);	
	$success = $stmt->execute();	
	if (!$success){
		header('Location: index.php');
		die($db->error);
	}
	header('Location: thank.php');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="recipe2_style.css">
	<title>投稿確認画面</title>
</head>
<body>
	<div class="container"> 
		<header>
			<h1 class="title">Recipen 投稿画面</h1>
			<nav class="nav">
				<div class="button5">
					<form action="../toppage.php" method="post" >
						<button type="submit">TOPページに戻る</button>
					</form>
				</div>
			</nav>
		</header>
		<div class="main">
			<div class=join_page2>  
				<div class="recipe_form">
					<div class=page_title>
						<h1>確認画面</h1>
					</div>						
					<form action="" method="post" enctype="multipart/form-data">
						<div class="form_title2">
							<dt>レシピ名</dt>
						</div>
						<dl>
							<div class="form_title2">
								<dd><?php echo h($form['recipe']); ?></dd>
							</div>
							<div class="form_title2">
								<dt>写真</dt>
							</div>  									
							<div class="form_title2">
								<dd>
									<img src="../recipe_picture/<?php echo h($form['image']); ?>">
								</dd>
							</div>  
							<div class="form_title2">
								<dt>材料</dt>
							</div>
							<div class="form_title2">
								<dd><?php echo h($form['foodstuffs']); ?></dd>
							</div>
							<div class="form_title2">
								<dt>作り方</dt>
							</div>
							<div class="form_title2">
								<dd><?php echo h($form['recipe']); ?></dd>
							</div>
						</dl>
						<div class="form2">
							<div><button type="submit">投稿する</button></div>
						</div>
					</form>
				</div>
			</div>
		</div>   
		<footer>@2022 recipenpj</footer>
	</div>
</body>
</html>