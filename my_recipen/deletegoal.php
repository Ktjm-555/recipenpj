<?php
session_start();
require('../library.php');

/**
　　* ログイン確認
　　*/
if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
  $user_id = $_SESSION['user_id'];
  $name    = $_SESSION['name'];
  $aisatsu = 'doumo';
} else {
  header('Location: ../index.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="upd_del_style.css">
	<title>Document</title>
</head>
<body>
	<div class="container">
		<header>
			<h1 class="title">Recipen 買うものリスト削除</h1>
		</header>
		<div class="main">
			<div class="join_thank_page">
					<div class="end">
						<p>削除完了しました！！</p>
					</div>	
        	<div class="form5" >
					<form action="buy_list.php" method="post">
            <input type="hidden" name="type" value="2">
            <input type ="hidden" name="buy_u_id" value="<?php echo $user_id; ?>">
            <button type="submit">買い物リスト</button>
          </form>
				</div>			
				<div class="form5" >
					<form action="../index.php" method="post">
						<button type="submit">TOPページに戻る</button>
					</form>
				</div>
			</div>
		</div>
		<footer>2022 @recipenpj</footer>
	</div>
</body>
</html>