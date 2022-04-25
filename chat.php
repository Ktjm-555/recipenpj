<?php   
$J_file = "chatlog.json"; // ファイルパス格納
date_default_timezone_set('Asia/Tokyo'); // タイムゾーンを日本にセット
session_start(); 

// ユーザーアイコンが押された時
if(isset($_GET['person'])){
  $_SESSION['person'] = $_GET['person'];

}else{

  // ユーザーアイコンも押されず、切り替えアイコンも押されていない時(=初めてページに訪れた時)
  if(empty($_GET['change']) && empty($_SESSION['person'])){
    $_SESSION['person'] = 'person1';
    // 以下切り替えアイコンが押された時にアクティブのユーザーではない方に切り替える処理
  }elseif (isset($_GET['change']) && $_GET['change'] === 'change' && $_SESSION['person'] === "person2"){
    $_SESSION['person']  = 'person1';//現在2なら1をアクティブに
  }elseif(isset($_GET['change']) && $_GET['change'] === 'change' && $_SESSION['person'] === "person1"){
    $_SESSION['person']  = 'person2';//現在1なら2をアクティブに
  }
}

if(isset($_POST['submit']) && $_POST['submit'] === "送信"){ // #1
	if($_SESSION['person'] === 'person1'){
		$chat = [];
		$chat["person"] = "person1";
		$chat["imgPath"] = "image/animal_cooking_girl_inu.png";
		$chat["time"] = date("H:i");
		$chat["text"] = htmlspecialchars($_POST['text'],ENT_QUOTES);
	  }elseif( $_SESSION['person'] === 'person2'){
		$chat = [];
		$chat["person"] = "person2";
		$chat["imgPath"] = "image/animal_cooking_girl_neko.png";
		$chat["time"] = date("H:i");
		$chat["text"] = htmlspecialchars($_POST['text'],ENT_QUOTES);
	  }

    // 入力値格納処理
    if($file = file_get_contents($J_file)){ // #2
      // ファイルがある場合 追記処理
      $file = str_replace(array(" ","\n","\r"),"",$file);
      $file = mb_substr($file,0,mb_strlen($file)-2);
      $json = json_encode($chat);
      $json = $file.','.$json.']}';
      file_put_contents($J_file,$json,LOCK_EX);
    }else{ // #2
      // ファイルがない場合 新規作成処理
      $json = json_encode($chat);
      $json = '{"chatlog":['.$json.']}';
      file_put_contents($J_file,$json,FILE_APPEND | LOCK_EX);
	} // #2
    // header('Location:https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/chat.php');
    header('Location:./chat.php');
    exit;   
} // #1
if($file = file_get_contents($J_file)){
  $file = json_decode($file);
  $array = $file->chatlog;
  foreach($array as $object){
  $result =  $result.'<div class="'.$object->person.'"><p class="chat">'.str_replace("\r\n","<br>",$object->text).'<span class="chat-time">'.$object->time.'</span></p><img src="'.$object->imgPath.'"></div>';
} 
}

if(isset($_GET['reset']) && $_GET['reset'] == "チャット履歴をリセット" ){
	file_put_contents($J_file,'');
	// header('Location:https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/chat.php');
	header('Location:./chat.php');
	exit;   
}
  

if(isset($_GET['back']) && $_GET['back'] == "Topページに戻る"){
	$_SESSION = [];
	if(isset($_COOKIE[session_name()])){
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, $params["path"],$params["domain"],$params["secure"],$params['httponly']);
	}
	session_destroy();
	header("Location:../index.php");
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
  <link rel="stylesheet" href="css/fontawesome-free-5.15.3-web/css/all.min.css">
  <script src="js/main.js"></script>
</head>
<body>
  <main class="main">
    <div class="chat-system">
	<h2 class="text-center"><i class="far fa-comments"></i></h2>
  <p class="text-center">1対1のチャット機能です。下のユーザーを切り替えることで会話が楽しめます。</p>
  <form method="get" action="chat.php">
        <div class="change-person flex-box">
          <input type="submit" id="person2" name="person" value="person2"><label for="person2"><img  class="<?php if($_SESSION['person'] === 'person2'){echo "on";}?>" src="image/person2.png"></label>
          <input type="submit" id="change" name="change" value="change"><label for="change"><i class="fas fa-people-arrows"></i></label>
          <input type="submit" id="person1" name="person" value="person1"><label for="person1"><img class="<?php if($_SESSION['person'] === 'person1'){echo "on";}?>" src="image/person1.png"></label>
      </div>
  </form>
	<div class="chat-box">
    <div class="chat-area" id="chat-area">
      <?php echo $result; ?>
    </div>
      <form class="send-box flex-box" action="chat.php#chat-area" method="post">
        <textarea id="textarea" type="text" name="text" rows="1" required placeholder="message.."></textarea>
        <input type="submit" name="submit" value="送信">
      </form>
	  </div>
    </div>
	<form action="chat.php" method="GET">
    <input class="btn back-btn" type="submit" name="back" value="Topページに戻る">
    <input class="btn back-btn" type="submit" name="reset" value="チャット履歴をリセット">
  </form>
  </main>
</body>
</html>