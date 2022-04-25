if(isset($_POST['submit']) && $_POST['submit'] === "送信"){ // #1

if($_SESSION['person'] === 'person1'){
    $chat = [];
    $chat["person"] = "person1";
    $chat["imgPath"] = "image/person1.png";
  
}else if( $_SESSION['person'] === 'person2'){
    $chat = [];
    $chat["person"] = "person2";
    $chat["imgPath"] = "image/person2.png";
}
    $chat["time"] = date("H:i");
    $chat["text"] = h($_POST['text']);