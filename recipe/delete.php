<?php
session_start();
require('../library.php');

/**
　　* ログイン確認
　　*/
if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
	$user_id = $_SESSION['user_id'];
	$name = $_SESSION['name'];
} else {
	header('Location: ../login.php');
	exit();
}

/**
　　* SQL実行　削除
　　*/
$db = dbconnect();
$sql = "
	DELETE 
	FROM 
		recipen
	WHERE  
		id=? and member_id=? 
";
$recipe_id        = filter_input(INPUT_POST, 'recipe_id', FILTER_SANITIZE_NUMBER_INT);
$recipe_member_id = filter_input(INPUT_POST, 'recipe_member_id', FILTER_SANITIZE_NUMBER_INT);

//　削除するユーザーがレシピを投稿したユーザーと同じだった時
if ($user_id == $recipe_member_id) {
  $stmt = $db->prepare($sql);	
	if (!$stmt) {
    die($db->error);
  }
	$stmt->bind_param("ii", $recipe_id, $recipe_member_id);	
	$success = $stmt->execute();	
	if (!$success) {
		header('Location: delete_error.html');
		exit();
	}
	header('Location: deletegoal.php');
	exit();
} else {    
	header('Location: delete_error.html');
	exit();
}
?>
