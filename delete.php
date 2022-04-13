<?php
session_start();
require('library.php');

if (isset($_SESSION['id']) && isset($_SESSION['name'])){
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
} else {
    header('Location: ../login.php');
    exit();
}

$db = dbconnect();

$sql = "DELETE FROM recipen WHERE  id=? and member_id=? ";

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


$stmt= $db->prepare($sql);
$stmt->bind_param("ii", $recipen_id, $id);

$success = $stmt->execute();

if (!$success){
    echo '何か問題あったよ！';
    die($db->error);
}

header('Location: deletegoal.php');

?>
