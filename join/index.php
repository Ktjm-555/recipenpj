
<?php
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$db = new mysqli('localhost:8889', 'root', 'root', 'recipenpj');

// $db->query("INSERT INTO member(name, email, password) VALUES ('aa', 'bb', 'cc') ");

$sql = "INSERT INTO 
member
(name, email, password) 
VALUES 
('".$name."','".$email."','".$password."')";

$res = $db->query($sql);
if ($res){
    echo '登録できました';
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


    






