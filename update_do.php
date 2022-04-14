<?php 
session_start();

// var_dump($_SESSION['form']['image']);
// exit();

require('library.php');
if (isset($_SESSION['id']) && isset($_SESSION['name'])){
    $id = $_SESSION['id'];
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
// $id = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);
$clear = '';
if ($id == $_SESSION['id']){
    $clear = 'clear'; 
}  
// ここおかしい　削除のボタンのやつ

if ($clear == 'clear'){
    $sql = "UPDATE 
    recipen 
    SET 
    recipename=?, foodstuffs=?, recipe=?, image=?
    WHERE 
    id=?";

    // $recipename = '';
    
    $id = $form['id'];
    $recipename = $form['recipename'];
    $foodstuffs = $form['foodstuffs'];
    $recipe = $form['recipe'];
    $image = $form['image'];

    // var_dump($image);
    // exit();

    $stmt= $db->prepare($sql);

    $stmt->bind_param("ssssi", $recipename, $foodstuffs, $recipe, $image, $id);
    $success = $stmt->execute();
        if (!$success){
            echo '何か問題あったよ！';
            die($db->error);
        }
        // echo $recipename;
    // exit();
        header('Location: recipe.php?id=' . $id);
} 
    // echo $id;
    // exit();

?>
