<?php 
require('library.php');
$db = dbconnect();

$sql = 
"UPDATE 
recipen 
SET 
recipename=?, foodstuffs=?, recipe=?
WHERE 
id=? ";



$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$recipename = filter_input(INPUT_POST, 'recipename', FILTER_SANITIZE_STRING);
$foodstuffs = filter_input(INPUT_POST, 'foodstuffs', FILTER_SANITIZE_STRING);
$recipe = filter_input(INPUT_POST, 'recipe', FILTER_SANITIZE_STRING);

$stmt= $db->prepare($sql);
$stmt->bind_param("sssi", $recipename, $foodstuffs, $recipe, $id);

$success = $stmt->execute();

if (!$success){
    echo '何か問題あったよ！';
    die($db->error);
}

header('Location: recipe.php?id=' . $id);

?>




