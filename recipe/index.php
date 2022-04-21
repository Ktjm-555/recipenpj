<?php
session_start();
require('../library.php');

if (isset($_SESSION['user_id']) && isset($_SESSION['name'])){
    $name = $_SESSION['name'];
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: ../login.php');
    exit();
}

// フォームが送信されたとき
$form = [
  'recipename' => '',
  'foodstuffs' => '',
  'recipe' => '',
  'recipe_member_id'=>'',
  
];
$error = [];

// echo 'ddd';
// echo $_SERVER['REQUEST_METHOD'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == "1"){
  // echo 'ccc';
  $form['recipename'] = filter_input(INPUT_POST, 'recipename', FILTER_SANITIZE_STRING);
  if ($form['recipename'] == ''){
    $error['recipename'] = 'blank';
  }
  
  $form['foodstuffs'] = filter_input(INPUT_POST, 'foodstuffs', FILTER_SANITIZE_STRING);
  if ($form['foodstuffs'] == ''){
    $error['foodstuffs'] = 'blank';
  }

  $form['recipe'] = filter_input(INPUT_POST, 'recipe', FILTER_SANITIZE_STRING);
  if ($form['recipe'] == ''){
    $error['recipe'] = 'blank';
  }

  $form['recipe_member_id'] = filter_input(INPUT_POST, 'recipe_member_id', FILTER_SANITIZE_STRING);
 

  // var_dump($_FILES['image']['name']);
  // exit();

  // がそうのチェック
  $image = array();
  if ($_FILES['image']['name'] != ''){
    // echo 'bbb';
    $image = $_FILES['image'];
  } else {
    // echo 'aaa';
    $error['image'] = 'blank';
  }
  // var_dump($error);
  if(!empty($image)){
    // 画像があるとき からじゃないから　
    if($image['error'] == 0){
      // エラーがなければtype
      $type = mime_content_type($image['tmp_name']);
      
      if ($type !== 'image/png' && $type !== 'image/jpeg'){
        $error['image'] = 'type';
      }
    }
  }
// var_dump($error);
    if (empty($error)){
      // エラーがからの時
      $_SESSION['form']  = $form;

      // 画像のアップロード
      // if (isset($image['image'])){
        $filename = date('YmdHis') . '_' . $image['name'];
       
        // move_uploaded_file($image['tmp_name'], '../recipe_picture/' . $filename);
        // $_SESSION['form']['image'] = $filename;
        // 62,63を追加
        if (!move_uploaded_file($image['tmp_name'], '../recipe_picture/' . $filename)){
          die('ファイルのアップロードに失敗しました');
        } else {
          $_SESSION['form']['image'] = $filename;
        }
      // } 
     
      // var_dump($user_id);
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
						<button type="submit"> 
							TOPページに戻る
						</button>
					</form>
				</div>
			</nav>
		</header>
		<div class="main">
			<div class="join_page">
				<div class=join_page2>
					<div class="joins_form">
						
						<div class=hallow>
							<?php echo h($name); ?>さん、今日もレシピ投稿ありがとうございます！
						</div>

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
									<?php if (isset($error['recipename']) && $error['recipename'] === 'blank'): ?>
									<p>*レシピ名を入力してください。</p>
									<?php endif; ?>
								</div>

								<div class="form_title">
									<p>完成写真</P>
								</div>

								<div class="form_contents">
									<input type="file" name="image" size="35" value=""/>
								</div>

								<div class="error">
									<?php if (isset($error['image']) && $error['image'] == 'type'): ?>
									<p>*写真は「.png」または「.jpg」の画像を指定してください。</p>
									<?php endif; ?>
								</div>

								<div class="error">
									<?php if (isset($error['image']) && $error['image'] == 'blank'): ?>
									<p>*写真を投稿してください。</p>
									<?php endif; ?>
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
									<?php if (isset($error['foodstuffs']) && $error['foodstuffs'] == 'blank'): ?>
									<p class="error">*材料を入力してください。</p>
									<?php endif; ?>
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
									<?php if (isset($error['recipe']) && $error['recipe'] = 'blank'): ?>
									<p class="error">*作り方を入力してください。</p>
									<?php endif; ?>
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
		<footer>
            2022 @recipenpj
        </footer>
	</div>
</body>
</html>


