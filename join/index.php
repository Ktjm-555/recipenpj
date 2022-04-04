
<?php
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING)

$db = new mysqli('localhost:8889', 'root', 'root', 'recipenpj')

$stmt = $db->prepare('insert into member(name, email, ) vale(?, ?, ?)');
if (!$stmt){
    die($db->error);
}


?>


<!-- $passsword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING)
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) -->

<