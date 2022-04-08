<?php

function h($value){
    return htmlspecialchars($value, ENT_QUOTES);
}

function dbconnect(){
    $db = new mysqli('localhost', 'root', 'root', 'recipenpj');
	if (!$db){
		die($db->error);
    }
    return $db;
}


?>