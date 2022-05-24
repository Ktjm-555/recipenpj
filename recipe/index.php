<?php
session_start();
require('../library.php');

/**
　　* ログイン確認と変数を使えるようにする
　　*/
if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
	$name    = $_SESSION['name'];
	$user_id = $_SESSION['user_id'];
} else {
	header('Location: ../login.php');
	exit();
}

$form = [
  'recipename'       => '',
  'foodstuffs'       => '',
  'recipe'           => '',
  'recipe_member_id' => '',
];
$error = [];

/**
　　* フォームの値のエラーチェック（空）
　　*/
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == "1") {  
	$form['recipename'] = h($_POST['recipename']);
  if ($form['recipename'] == '') {
    $error['recipename'] = 'blank';
  }
	$form['foodstuffs'] = h($_POST['foodstuffs']);
  if ($form['foodstuffs'] == '') {
    $error['foodstuffs'] = 'blank';
  }
	$form['recipe'] = h($_POST['recipe']);
  if ($form['recipe'] == '') {
    $error['recipe'] = 'blank';
  }
	$form['recipe_member_id'] = filter_input(INPUT_POST, 'recipe_member_id', FILTER_SANITIZE_NUMBER_INT);

	/**
　　	* 画像のチェック
　　	*/
  $image = array();
	// Point 画像が指定されているかどうかを画像の名前があるかないかで確認 
  if ($_FILES['image']['name'] != '') {
    $image = $_FILES['image'];
  } else {
    $error['image'] = 'blank';
  }

  if (!empty($image)) {
    if ($image['error'] == 0) {
      $type = mime_content_type($image['tmp_name']);
      if ($type !== 'image/png' && $type !== 'image/jpeg') {
        $error['image'] = 'type';
      }
    }
  }

	if (empty($error)) {
		$_SESSION['form']  = $form;

		/**
　　		* 画像のアップロード
　　		*/
		$filename = date('YmdHis') . '_' . $image['name'];
		if (!move_uploaded_file($image['tmp_name'], '../recipe_picture/' . $filename)) {
			die('ファイルのアップロードに失敗しました');
		} else {
			$_SESSION['form']['image'] = $filename;
		}
		header('Location: check.php');
		exit();
  }
}
?>



<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>レシピ投稿画面</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="recipe2_style.css">
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
			<div class="join_page">
				<div class=join_page2>
					<div class="joins_form">						
						<div class=hallow><?php echo h($name); ?>さん、今日もレシピ投稿ありがとうございます！</div>
						<div class="recipes_form">
							<form action="" method="post" enctype="multipart/form-data">
								<input type="hidden" name="type" value="1">
								<input type="hidden" name="recipe_member_id" value="<?php echo $user_id; ?>">
								<div class="form_title">
									<p>レシピ名</P>
								</div>							
								<div class="form_contents">
									<label class="ef">
										<input type="text" name="recipename" size="35" maxlength="255" value="<?php echo h($form['recipename']); ?>"/>
									</label>
								</div>									
								<div class="error">
									<!-- Point issetでその変数が存在しているか見る、これがないと $error['recipename']がない時、エラーが出てしまう。　-->
									<?php if (isset($error['recipename']) && $error['recipename'] === 'blank') { ?>
									<p>*レシピ名を入力してください。</p>
									<?php } ?>
								</div>
								<div class="form_title">
									<p>完成写真</P>
								</div>
								<div class="form_contents">
									<input type="file" name="image" size="35" value=""/>
								</div>
								<div class="error">
									<?php if (isset($error['image']) && $error['image'] == 'type') { ?>
									<p>*写真は「.png」または「.jpg」の画像を指定してください。</p>
									<?php } ?>
								</div>
								<div class="error">
									<?php if (isset($error['image']) && $error['image'] == 'blank') { ?>
									<p>*写真を投稿してください。</p>
									<?php } ?>
								</div>
								<div class="form_title">
									<p>材料</P>
								</div>
								<div class="form_contents">
									<label class="ef">
										<textarea name="foodstuffs" cols="50" rows="5"><?php echo h($form['foodstuffs']); ?></textarea>
									</label>
								</div>
								<div class="error">
									<?php if (isset($error['foodstuffs']) && $error['foodstuffs'] == 'blank') { ?>
									<p class="error">*材料を入力してください。</p>
									<?php } ?>
								</div>
								<div class="form_contents">
									<p>作り方</P>
								</div>
								<div class="form_contents">
									<label class="ef">
										<textarea name="recipe" cols="50" rows="5"><?php echo h($form['recipe']); ?></textarea>
									</label>
								</div>
								<div class="error">
									<?php if (isset($error['recipe']) && $error['recipe'] = 'blank') { ?>
									<p class="error">*作り方を入力してください。</p>
									<?php } ?>
								</div>
								<div class="form2">
									<button type="submit">入力内容を確認する</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<footer>2022 @recipenpj</footer>
	</div>
</body>
</html>


