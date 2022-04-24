<?php   
require('library.php');

$J_file = "chatlog.json"; // ファイルパス格納
// "chatlog.json"
date_default_timezone_set('Asia/Tokyo'); 

session_start(); 

// アイコンが押された時
if($_SERVER['REQUEST_METHOD'] == 'GET'){
  $_SESSION['person'] = $_GET['person'];
}else{
  // アイコンも押されず、切り替えアイコンも押されていない時(=初めてページに訪れた時)
  if(empty($_GET['change']) && empty($_SESSION['person'])){
    $_SESSION['person'] = 'person1';
    // 以下切り替えアイコンが押された時にアクティブのユーザーではない方に切り替える処理
//   }elseif ($_GET['change'] === 'change' && $_SESSION['person'] === "person2"){
//     $_SESSION['person']  = 'person1';//現在2なら1をアクティブに
//   }elseif($_GET['change'] === 'change' && $_SESSION['person'] === "person1"){
//     $_SESSION['person']  = 'person2';//現在1なら2をアクティブに
  }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$chat = [];
	$chat["person"] = "person1";
	$chat["image"] = "image/animal_cooking_girl_inu.png"; 
	// ⏫imgPath
	$chat["time"] = date("H:i");
	$chat["text"] = h($_POST['text']);		// 入力値格納処理

		if($file = file_get_contents($J_file)){
			// ファイルがある場合 追記処理
			$file = str_replace(array(" ","\n","\r"),"",$file);
			// replace 置き換える　改行コードを解除する　　$ファイルの
			$file = mb_substr($file,0,mb_strlen($file)-2);
			// mb_substr　取り出す０番の文字　　括弧が反映されてしまうから解除する。
			$json = json_encode($chat);
			$json = $file.','.$json.']}';
			file_put_contents($J_file,$json,LOCK_EX);
			// ファイルに文字を書き込む　（ファイル名、内容、排他的ロック※同時に書き込めない）
		}else{
			// ファイルがない場合 新規作成処理
			$json = json_encode($chat);
			$json = '{"chatlog":['.$json.']}';
			file_put_contents($J_file,$json,FILE_APPEND | LOCK_EX);
			// 追記　FILE_APPEND
		} 
		
		header('Location:chat.php');
		exit; 
}			
$result = '';
// データがあれば取り出して表示する準備
if($file = file_get_contents($J_file)){
    $file = json_decode($file);
	// エンコードの逆
    $array = $file->chatlog;
    foreach($array as $object){
    $result = $result.'<div class="'.$object->person.'"><p class="chat">'.str_replace("\r\n","<br>",$object->text).'<span class="chat-time">'.$object->time.'</span></p><img src="'.$object->image.'"></div>';
	// ⭐️構文？何やっているかわ分かるけど。規則性というか書こうと思ったら書けない。　phpの中にhtml？？
  } 
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
  <div class="main">
      <!--   <main class="main"> -->
		<div class="chat_corner">
			<!-- ⏫　chat-system -->
			<h2 class="text-center"><i class="far fa-comments"></i></h2>
  			<p class="text-center">1対1のチャット機能です。</p>
 			<form method="get" action="chat.php">
        		<div class="choose_person cform">
					<!-- ⏫ change-person flex-box-->
					<input type="submit" id="person2" name="person" value="person2"><label for="person2"><img  class="<?php if($_SESSION['person'] === 'person2'){echo "on";}?>" src="image/person2.png"></label>
					<!-- <input type="submit" id="change" name="change" value="change"><label for="change"><i class="fas fa-people-arrows"></i></label> -->
					<input type="submit" id="person1" name="person" value="person1"><label for="person1"><img class="<?php if($_SESSION['person'] === 'person1'){echo "on";}?>" src="image/person1.png"></label>
      			</div>
  			</form>

			<div class="chat-box">
				<div class="chat_show" id="chat_show">
					<!-- ⏫　chat-area -->
					<?php echo $result; ?>
				</div>
				<form class="chat_form cform" action="chat.php#chat_show" method="post">
					<!-- ＃とは？ / ⏫send-box flex-box #chat-area -->
					<textarea id="textarea" type="text" name="text" rows="1" required placeholder="message.."></textarea>
					<input type="submit" name="submit" value="送信">
					<!-- id="search">　を消してる　id と　以下のforで結びつけてsubmitを消してる -->
					<!-- <label for="search"><i class="fa-solid fa-message-lines"></i></label> -->
					<!-- ⏫<i class="far fa-paper-plane"></i> -->
				</form>
			</div>
		</div>
	</div>
</body>
</html>
