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
                    <form action="recipe/index.php" method="post" >
                        <input type="hidden" name="type" value="2">
                        <button type="submit"> 
                            投稿する
                        </button>
                    </form>
                </div>
                <div class="button5">
                    <form action="toppage.php" method="post" >
                        <button type="submit"> 
                            TOPページに戻る
                        </button>
                    </form>
                </div>
			</nav>
		</header>
		<div class="main">
			<div class=join_page> 
				<div class=join_page2>
					<div class="join_form">
						<?php
						require('library.php');
						$db = dbconnect();
						session_start();

						$stmt = $db->prepare('select r.id, r.recipename, r.member_id, r.image, r.foodstuffs, r.recipe, r.created,  
						r.modified, m.name from recipen r, member m where r.id=? and m.id=r.member_id');
						if (!$stmt){
						die($db->error);
						}
					
						// URLパラメータで指定されたidを受け取る。
						$recipe_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
						// なぜここ’id’なんだ？カラム名が入る？　hiddenだとnameだけど。。。
						$stmt->bind_param('i', $recipe_id);
						$stmt->execute();

						$stmt->bind_result($recipe_id, $recipename, $recipe_member_id, $image, $foodstuffs, $recipe, $created, $modified, $name);
						$stmt->fetch();

						?>

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

						<div class="form_title2">
							<pre><?php echo h($foodstuffs); ?></pre>
						</div>

						<div class="form_title2">
							<p>作り方</p>
						</div>

						<div class="form_title2">
							<pre><?php echo h($recipe); ?></pre>
						</div>
						
						<?php 
							$clear = '';
							if (isset($_SESSION['user_id']) && isset($_SESSION['name']) && $_SESSION['user_id'] == $recipe_member_id){
								$clear = 'clear'; 
							}  
						?>
						
					<?php if ($clear == 'clear'): ?>
						<div class="page">
							<div class="button6">
								<form action="update.php" method="post" enctype="multipart/form-data">
									<input type="hidden" name="type" value="2">
									<input type="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
									<input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
									<button type="submit"> 
									編集する
									</button>
								</form>
							</div>

							<div class="button6">
								<form action="delete.php" method="post" enctype="multipart/form-data">
									<input type="hidden" name="recipe_member_id" value="<?php echo $recipe_member_id; ?>">
									<input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
									<button type="submit"> 
									削除する
									</button>
								</form>
							</div>
						</div>
					<?php endif; ?>
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