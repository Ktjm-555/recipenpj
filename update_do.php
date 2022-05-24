<?php 
session_start();
require('library.php');

if (isset($_SESSION['user_id']) && isset($_SESSION['name'])){
  $user_id = $_SESSION['user_id'];
  $name = $_SESSION['name'];
} else {
  header('Location: login.php');
  exit();
}
if (isset($_SESSION['form'])){
  $form = $_SESSION['form'];
} else {
  header('Location: login.php');
  exit();
}

$db = dbconnect();
  $sql = "UPDATE 
  recipen 
  SET 
  recipename=?, foodstuffs=?, recipe=?, image=?
  WHERE 
  id=?";
  
  $recipe_id = $form['recipe_id'];
  $recipename = $form['recipename'];
  $foodstuffs = $form['foodstuffs'];
  $recipe = $form['recipe'];
  $image = $form['image'];
  $stmt= $db->prepare($sql);
  $stmt->bind_param("ssssi", $recipename, $foodstuffs, $recipe, $image, $recipe_id);
  $success = $stmt->execute();
  if (!$success){
    echo '何か問題あったよ！';
    die($db->error);
  }
  header('Location: recipe.php?id=' . $recipe_id);
?>
