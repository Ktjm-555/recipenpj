<?php
session_start();

unset($_SESSION['user_id']);
unset($_SESSION['name']);

header('Location: toppage.php');
exit();
?>