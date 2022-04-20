<?php 
session_start();

// var_dump($_SESSION['form']['image']);
// exit();

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
// $id = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);


    $sql = "UPDATE 
    recipen 
    SET 
    recipename=?, foodstuffs=?, recipe=?, image=?
    WHERE 
    id=?";

    // $recipename = '';
    
    $recipe_id = $form['recipe_id'];
    $recipename = $form['recipename'];
    $foodstuffs = $form['foodstuffs'];
    $recipe = $form['recipe'];
    $image = $form['image'];


    // var_dump($image);
    // exit();

    $stmt= $db->prepare($sql);

    $stmt->bind_param("ssssi", $recipename, $foodstuffs, $recipe, $image, $recipe_id);
    $success = $stmt->execute();
        if (!$success){
            echo '何か問題あったよ！';
            die($db->error);
        }
    //     echo $recipe_id;
    // exit();
        header('Location: recipe.php?id=' . $recipe_id);
 
    // echo $id;
    // exit();

?>
