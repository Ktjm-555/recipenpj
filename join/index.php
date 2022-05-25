<?php
session_start();
require('../library.php');

/**
* rewriteの場合かそうでない場合
*/
if (isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
	$form = [
	'user_id'  => '',
	'name'     => '',
	'email'    => '',
	'password' => ''
	];
}
	$error = [];

 	/**
	　　* フォームの値のエラーチェック
	　　*/
	// Point リクエストはGETかPOSTかをみる、この場合、POSTの場合となる。
	// Point この画面にくるとき、トップページなどから、POSTで飛んでくるので「type」を指定する。
 	if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == "1") {		
		$form['name'] = h($_POST['name']);
		if ($form['name'] === '')	{
			$error['name'] = 'blank';
		}

		$form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$pattern 	     = "/^[a-zA-Z0-9_+-]+(.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/";
		if ($form['email'] === '') {
			$error['email'] = 'blank';
		} else if (!preg_match($pattern, $form['email'])) {
			$error['email'] = 'failed';
		} else {
			$db   = dbconnect();
			$sql = "
				SELECT
					count(*) AS cnt 
				FROM 
					member where email=?	
			";
			$stmt = $db->prepare($sql);	
			if (!$stmt) {
				die($db->error);
			}
			$stmt->bind_param('s', $form['email']);  //　Point　どの値を?に入れるか
			$success = $stmt->execute();	//　Point　実行
			if (!$success) {
				die($db->error);
			} 
			$stmt->bind_result($cnt);  // Point　実行して出てきたものを受け取る変数
			$stmt->fetch();
			if ($cnt > 0) {
				$error['email'] = 'juhuku';
			}
		}

		$form['password'] = h($_POST['password']);
		if ($form['password'] === '') {
			$error['password'] = 'blank';
		} else if (strlen($form['password']) < 4) { 
			$error['password'] = 'length';
		}

	if (empty($error)) {
		$_SESSION['form'] = $form;
		header('Location: check.php');
		exit();
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
	<title>会員登録画面</title>
</head>
<body>
	<div class="container">
		<header>
			<h1 class="title">Recipen 会員登録</h1>
			<nav class="nav">
				<div class="button5">
					<form action="../login.php" method="post">
						<input type="hidden" name="type" value="2">
						<button type="submit">ログイン</button>
					</form>
				</div>
				<div class="button5">
					<form action="../toppage.php" method="post">
						<button type="submit">TOPページに戻る</button>
					</form>
				</div>
			</nav>
		</header>
		<div class="main">
			<div class="join_page">
				<div class=join_page2>
					<div class="join_form">
						<div class=page_title>
							<h1>ようこそ！！</h1>
						</div>
						<form action="" method="post">
							<input type="hidden" name="action" value="submit">
							<input type="hidden" name="type" value="1">
							<div class="form_title">               
								<p>ニックネーム</p>       
							</div>
							<div class="form_contents">
								<label class="ef">
								<input type="text" name="name" size="35" maxlength="255" value="<?php echo h($form['name']); ?>"/>
								</label>
							</div>
							<div class="error">
								<?php if (isset($error['name']) && $error['name'] == 'blank') { ?>
								<p>*ニックネームを入力してくださいね。</p>
								<?php } ?>
							</div>
							<div class="form_title">
								<p>メールアドレス</p>
							</div>
							<div class="form_contents">
								<label class="ef">
									<input type="text" name="email" size="35" maxlength="255" value="<?php echo h($form['email']); ?>"/>
								</label>
							</div>										
							<div class="error">
								<label class="ef">
									<?php if (isset($error['email']) && $error['email'] == 'blank') { ?>
										<p>*メールアドレスを入力してくださいね。</p>
									<?php } ?>        
									<?php if (isset($error['email']) && $error['email'] == 'failed') { ?> 
										<p>*メールアドレスを正しい形式で入力してくださいね。</p>
									<?php } ?>
									<?php if (isset($error['email']) && $error['email'] == 'juhuku') { ?>
										<p>*指定されたメールアドレスは既に登録してあります。</p>
									<?php } ?>         
								</label>     
							</div>
							<div class="form_title">
								<p>パスワード</p>
							</div>
							<div class="form_contents">
								<label class="ef">
									<input type="password" name="password" size="35" maxlength="255" value="<?php echo h($form['password']); ?>"/>
								</label>
							</div>
							<div class="error">
								<?php if (isset($error['password']) && $error['password'] == 'blank') { ?>
									<p">*パスワードを入力してくださいね。</p>
								<?php } ?>											
								<?php if (isset($error['password']) && $error['password'] == 'length') { ?>
									<p>*パスワードは4文字以上で入力してくださいね。</p>
								<?php } ?>
							</div>
							<div class="form2">
								<button type="submit">入力内容を確認する</button> 
							</div>
						</form>
						<div class=login>
							すでに登録済みの方は<a href="../login.php">こちら</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer>2022 @recipenpj</footer>
	</div>
</body>
</html>