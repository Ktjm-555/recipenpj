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

	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_assoc($result);

	if ($res){
		$_SESSION['user_id'] = $row['id'];
		$_SESSION['name'] = $form['name'];
		header('Location: thank.php');
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
<div class="container">
	<header>
		<h1 class="title">Recipen 会員登録</h1>
			<nav class="nav">
				<div class="button5">
					<form action="login.php" method="post" >
						<input type="hidden" name="type" value="2">
						<button type="submit"> 
							ログイン
						</button>
					</form>
				</div>
				<div class="button5">
					<form action="../toppage.php" method="post" >
						<button type="submit"> 
							TOPページに戻る
						</button>
					</form>
				</div>
			</nav>
	</header>
	<div class="main">  
		<div class="join_check_page">
			<div class=join_page2>
			<div class=join_form>

				<div class=page_title>
					<h1>確認画面</h1>
				</div>

				<form action="" method="post">
					<dl>
					<div class="form_title2">
						<dt>ニックネーム</dt>
					</div>
					<div class="form_title2">
						<dd><?php echo h($form['name']); ?></dd>
						<dd><?php echo h($form['user_id']); ?></dd>
					</div>
					<div class="form_title2">
						<dt>アドレス</dt>
					</div>
					<div class="form_title2">
						<dd><?php echo h($form['email']); ?></dd>
					</div>

					<div class="form_title2">
						<dt>パスワード</dt>
					</div>
					<div class="form_title2">
						<dd>【表示はしないので、ご安心ください】</dd>
					</div>
					</dl>
					<div class="form_title2">   
						<a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a>
					</div>
					<div class="form2">
						<button type="submit">登録する</button>
					</div>
				</form>
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