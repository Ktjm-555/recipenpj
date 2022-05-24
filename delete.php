<?php
session_start();
require('library.php');

if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
	$user_id = $_SESSION['user_id'];
	$name = $_SESSION['name'];
} else {
	header('Location: login.php');
	exit();
}

$db = dbconnect();

/**
　　* SQL実行　削除
　　*/
$sql = "
	DELETE 
	FROM 
		recipen 
	WHERE  
		id=? and member_id=? 
";
$recipe_id        = filter_input(INPUT_POST, 'recipe_id', FILTER_SANITIZE_NUMBER_INT);
$recipe_member_id = filter_input(INPUT_POST, 'recipe_member_id', FILTER_SANITIZE_NUMBER_INT);


// Point 削除するユーザーがレシピを投稿したユーザーと同じだった時
if ($user_id == $recipe_member_id) {
  $stmt = $db->prepare($sql);	
	if (!$stmt) {
		header('Location: delete_error.html');
		exit();
	}
	$stmt->bind_param("ii", $recipe_id, $recipe_member_id);	
	$success = $stmt->execute();	
	if (!$success) {
		header('Location: delete_error.html');
		die($db->error);
	}
	// Point GETのパラメーターによって、出す文言やページを変える。
	header('Location: deletegoal.php?result=1');
} else {    
  header('Location: deletegoal.php?result=2');
}
?>
