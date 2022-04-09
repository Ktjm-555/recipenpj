<?php
require('library.php');
$db = dbconnect();

$sql = "DELETE FROM recipen WHERE  id=? ";

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


$stmt= $db->prepare($sql);
$stmt->bind_param("i", $id);

$success = $stmt->execute();

if (!$success){
    echo '何か問題あったよ！';
    die($db->error);
}

header('Location: deletegoal.php');

?>
