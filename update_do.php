<?php 
session_start();
require('library.php');

if (isset($_SESSION['id']) && isset($_SESSION['name'])){
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
} else {
    header('Location: login.php');
    exit();
}

$db = dbconnect();

$clear = '';
if (isset($_SESSION['id']) && isset($_SESSION['name']) && $_SESSION['id'] == $member_id){
    $clear = 'clear'; 
}  

if ($clear == 'clear'){

$sql = "UPDATE 
recipen 
SET 
recipename=?, foodstuffs=?, recipe=?
WHERE 
id=?";

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
// $member_id = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);
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

} else {
    header('Location: toppage.php');
}

?>




