<?php   
require('library.php');

$J_file = "chatlog.json"; // ファイルパス格納
// "chatlog.json"
date_default_timezone_set('Asia/Tokyo'); // タイムゾーンを日本にセット

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $chat = [];
    
  $chat["person"] = "person1";
  $chat["image"] = "image/person1.png"; //画像ファイル名は任意
  // ⏫imgPath
  $chat["time"] = date("H:i");
  $chat["text"] = h($_POST['text']);
}

// チャットログをJSONファイルに格納するから！！
    
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
        <!-- ⏫chat-system -->
      <form class="chat_form cform" action="chat.php#chat-area" method="post">
          <!-- ＃とは？ / send-box flex-box -->
        <textarea id="textarea" type="text" name="text" rows="1" required placeholder="message.."></textarea>
        <input type="submit" name="submit" value="送信" id="search">
        <label for="search"><i class="fa-solid fa-message-lines"></i></label>
        <!-- <i class="far fa-paper-plane"></i> -->
      </form>
    </div>
  </main>
</body>
</html>
