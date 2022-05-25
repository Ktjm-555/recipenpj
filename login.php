<?php
session_start();
require('library.php');

$error = [];
$email = '';
$password = '';

if (isset($_SESSION['user_id']) && isset($_SESSION['name'])) {
  header('Location: toppage.php');
  exit();
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == "1") {
  $email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = h($_POST['password']);

  /**
　　  * フォームの値のエラーチェック（空）
　　  */
  if ($email == '' || $password == '') {
    $error['login'] = 'blank';
  } else {
    $db = dbconnect();

    /**
　　    * 該当するレコードを取得
　　    */
    $sql = "
      SELECT 
        id, name, password 
      FROM 
        member 
      WHERE 
        email=? limit 1
    ";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bind_param('s', $email);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $stmt->bind_result($user_id, $name, $hash);
    $stmt->fetch();
    
     /**
　　    *  ユーザーのパスワードがハッシュ化されたものと一致しているか
　　    */
    if (password_verify($password, $hash)) {
      session_regenerate_id(); // Point セッションidを再発行して、乗っ取りを防ぐ。
      $_SESSION['user_id'] = $user_id;
      $_SESSION['name'] = $name;
      header('Location: toppage.php');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login_style.css">
  <title>ログイン</title>
</head>
<body>
  <div class="container">
    <header>
      <h1 class="title">Recipen ログイン</h1>
      <nav class="nav">
        <div class="button5">
          <form action="toppage.php" method="post">
            <button type="submit">TOPページに戻る</button>
          </form>
        </div>
      </nav>
    </header>    
    <div class="main">
      <div class="join_page">
        <div class=join_page2>
          <div class="join_form">
            <div class=page_title>
              <h1>ようこそ！！</h1>
            </div>
            <div class="form_title">
              <p>会員登録まだの方はこちらへ</p>
            </div>
            <div class="form2">
              <form action="join/index.php" method="post">
              <input type="hidden" name="type" value="2">
                <button type="submit"> 
                  会員登録する
                </button>
              </form>
            </div>
            <form action="" method="post">
              <input type="hidden" name="type" value="1">
              <dl>
                <div class="form_title">
                  <dt>メールアドレス</dt>
                </div>
                <div class="form_contents">
                  <label class="ef">
                    <dd> 
                      <input type="text" name="email" size="35" maxlength="255" value="<?php echo h($email); ?>"/>
                    </dd>
                  </label>
                </div>
                <div class="form_title">
                  <dt>パスワード</dt>
                </div>
                <div class="form_contents">
                  <label class="ef">
                    <dd> 
                      <input type="password" name="password" size="35" maxlength="255" value="<?php echo h($password); ?>"/>
                    </dd> 
                  </label>
                </div>
              </dl>
              <div class="error">
                <?php if (isset($error['login']) && $error['login'] == 'blank') { ?>
                  <p>*メールアドレスとパスワードを両方記入してログインしましょう！</p>
                <?php } ?>
              </div>                
              <div class="error">
                <?php if (isset($error['login']) && $error['login'] == 'failed') { ?>
                  <p>*ログインに失敗しました。正しく入力しまししょう！*</p>
                <?php } ?>
              </div>            
              <div class="form2">
                <button type="submit">ログイン</button> 
              </div>
            </form>
          </div>
        </div>        
      </div>
    </div>
    <footer>2022 @recipenpj</footer>
  </div>
</body>
</html>