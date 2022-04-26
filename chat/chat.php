<?php   
require('../library.php');

$J_file = "chatlog.json"; 
date_default_timezone_set('Asia/Tokyo'); 

session_start(); 

$filesize = filesize($J_file); 
if(empty($_SESSION['filesize'])){
	$_SESSION['filesize'] = $filesize ;
}

if(isset($_GET['person'])){
    $_SESSION['person'] = $_GET['person'];
}else{
    if(empty($_SESSION['person'])){
        $_SESSION['person'] = 'person1';
    }elseif ($_SESSION['person'] === "person2"){
        $_SESSION['person']  = 'person2';
    }elseif($_SESSION['person'] === "person1"){
        $_SESSION['person']  = 'person1';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if($_SESSION['person'] === 'person1'){
		$chat = [];
		$chat["person"] = "person1";
		$chat["imgPath"] = "image/animal_cooking_girl_inu.png";

	  }elseif( $_SESSION['person'] === 'person2'){
		$chat = [];
		$chat["person"] = "person2";
		$chat["imgPath"] = "image/animal_cooking_girl_neko.png";

	  }
	  	$chat["time"] = date("H:i");
		$chat["text"] = h($_POST['text']);

    if($file = file_get_contents($J_file)){ 

		$file = str_replace(array(" ","\n","\r"),"",$file);
		$file = mb_substr($file,0,mb_strlen($file)-2);
		$json = json_encode($chat);
		$json = $file.','.$json.']}';
		file_put_contents($J_file,$json,LOCK_EX);

    }else{

		$json = json_encode($chat);
		$json = '{"chatlog":['.$json.']}';
		file_put_contents($J_file,$json,FILE_APPEND | LOCK_EX);
	} 
    header('Location:./chat.php');
    exit;   
} 
if($file = file_get_contents($J_file)){

	$file = json_decode($file);
	$array = $file->chatlog;
	
	foreach($array as $object){
		$result = '<div class="'.$object->person.'"><p class="chat">'.str_replace("\r\n","<br>",$object->text).'<span class="chat-time">'.$object->time.'</span></p><img src="'.$object->imgPath.'"></div>';
	} 

	$result = $result .'<input id="preFilesize" type="hidden" value="'.$_SESSION['filesize'].'"><input id="aftFilesize" type="hidden" value="'.$filesize.'">';

}else{
	$result = '<input id="preFilesize" type="hidden" value="'.$_SESSION['filesize'].'"><input id="aftFilesize" type="hidden" value="'.$filesize.'">';
  }
	


if(isset($_GET['reset']) && $_GET['reset'] == "リセットする" ){

	file_put_contents($J_file,'');
	header('Location:./chat.php');
	exit;   
}
  

if(isset($_GET['back']) && $_GET['back'] == "TOPページに戻る"){
	file_put_contents($J_file,'');

	$_SESSION = [];
	if(isset($_COOKIE[session_name()])){
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params["path"],$params["domain"],$params["secure"],$params['httponly']);
	}
	session_destroy();
	header("Location:../toppage.php");
	exit;   
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
	<title>チャット</title>
	<link rel="stylesheet" href="chat_style.css">
	<script src="https://kit.fontawesome.com/2f5b67bf5b.js" crossorigin="anonymous"></script>
	<script src="js/main.js"></script>
	<script>
		$('#click').click(function () {
    	alert("アラートです");
 		 });
	</script>
</head>
<body>
	<div class="container"> 
		<header>
			<div class="title">
				Recipen チャット
			</div>
			<nav class="nav">
			<form action="chat.php" method="GET">
				<input class="btn back-btn" id="click"  type="submit" name="back" value="TOPページに戻る">
			</form>

			</nav>
		</header>

		<div class="main">
			<div class="chat-system">
				<!-- ⏫ chat-system-->
				<form method="get" action="chat.php">
					<div class="form-box">
						<div class="change-person flex-box">
							<input type="submit" id="person2" name="person" value="person2"><label for="person2"><img  class="<?php if($_SESSION['person'] === 'person2'){echo "on";}?>" src="image/animal_cooking_girl_neko.png"></label>
							<div class="text-center">
								どちらにしますか？
							</div>
							<input type="submit" id="person1" name="person" value="person1"><label for="person1"><img class="<?php if($_SESSION['person'] === 'person1'){echo "on";}?>" src="image/animal_cooking_girl_inu.png"></label>
						</div>
					</div>	
				</form>

				<div class="chat-box">
					<div class="chat-area" id="chat-area">
						<?php echo $result; ?>
					</div>

					<form class="send-box flex-box" action="chat.php#chat-area" method="post">
						<textarea id="textarea" type="text" name="text" rows="1" required placeholder="message.."></textarea>
						<input type="submit" name="submit" value="送信" id="search">
        <label for="search"><i class="far fa-paper-plane"></i></label>
					</form>
				</div>

			</div>
			<div class="form-box2">
				<form action="chat.php" method="GET">
					<input class="btn back-btn" type="submit" name="reset" value="リセットする">
				</form>
			</div>
		</div>
		<footer>
            2022 @recipenpj
        </footer>
	</div>		
</body>
</html>