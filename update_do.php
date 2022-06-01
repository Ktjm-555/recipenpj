<?php 
session_start();
require('library.php');

/**
　　* ログインチェック
　　*/
if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
  $user_id = $_SESSION['user_id'];
  $name = $_SESSION['name'];
} else {
  header('Location: login.php');
  exit();
}

/**
　　* 「編集する」を押した際
　　*/
if (isset($_SESSION['form'])) { 
  $form = $_SESSION['form'];
} else {
  header('Location: index.php');
  exit();
}
$db = dbconnect();
$sql = "
  UPDATE 
    recipen 
  SET 
    recipename=?, foodstuffs=?, recipe=?, image=?
  WHERE 
    id=?
";
  
$stmt = $db->prepare($sql);
if (!$stmt) {
  die($db->error);
}
$stmt->bind_param("ssssi", $form['recipename'], $form['foodstuffs'], $form['recipe'], $form['image'], $form['recipe_id']);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
header('Location: recipe.php?id=' . $form['recipe_id']);
exit();
?>
