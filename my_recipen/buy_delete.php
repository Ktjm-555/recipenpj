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
		buy
	WHERE  
		id=? and buy_u_id=? 
";
$buy_id   = filter_input(INPUT_POST, 'buy_id', FILTER_SANITIZE_NUMBER_INT);
$buy_u_id = filter_input(INPUT_POST, 'buy_u_id', FILTER_SANITIZE_NUMBER_INT);

//　削除するユーザーが買い物リストに入力したユーザーと同じだった時
if ($user_id == $buy_u_id) {
  $stmt = $db->prepare($sql);	
	if (!$stmt) {
    die($db->error);
  }
	$stmt->bind_param("ii", $buy_id, $buy_u_id);	
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
