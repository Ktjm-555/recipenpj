
<?php
session_start();
$form = [
    // 'name' =>''
    // 'email' =>''
    // 'password' =>''
];


$form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

$form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$db = new mysqli('localhost:8889', 'root', 'root', 'recipenpj');

// $db->query("INSERT INTO member(name, email, password) VALUES ('aa', 'bb', 'cc') ");

$sql = "INSERT INTO 
member
(name, email, password) 
VALUES 
('".$name."','".$email."','".$password."')";

$res = $db->query($sql);
if ($res){
    $_SESSION['form'] = $form;
    header('Location: check.php');
    exit();
}else{
    echo 'できていませんよ！何かがおかしいよ！';
}



// $stmt = $db->prepare('insert into member(name, email, password) value(?, ?, ?)');
// if (!$stmt){
//     die($db->error);
// }

// $stmt->bind_param('sss', $name, $email, $password);
// $ret = $stmt->execute();

// if ($ret):
// echo "登録されました";
// else:
//     $db->error;
// endif;
?>


    






