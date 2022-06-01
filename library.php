<?php
function h($value) {
  return htmlspecialchars($value, ENT_QUOTES);
}

function dbconnect() {
  // @re
  $db = new mysqli('localhost', 'root', 'root', 'recipenpj');
  // $db = mysqli_connect('us-cdbr-east-05.cleardb.net', 'b9fd27154c00f7', 'aa790497', 'heroku_e2cf5f30a7f3ca5');
	if (!$db) {
    die($db->error);
  }
  return $db;
}
?>