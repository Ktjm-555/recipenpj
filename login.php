<?php
session_start();

$error = [];
$email = '';
$password = '';

require('library.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == "1"){

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if ($email == '' || $password == ''){
        $error['login'] = 'blank';
    } else {
        // ログインチェック
        $db = dbconnect();
        $stmt = $db->prepare('select id, name, password from member where email=? limit 1');
        if (!$stmt){
            die($db->error);
        }
        $stmt->bind_param('s', $email);
        $success = $stmt->execute();
        if (!$success){
            die($db->error);
        }
        $stmt->bind_result($user_id, $name, $hash);
        $stmt->fetch();
        
        if (password_verify($password, $hash)){
            // ログイン成功
            session_regenerate_id();
            // idを再形成
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            header('Location: toppage.php');
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
    <link rel="stylesheet" href="log_style.css">
    <title>ログイン</title>
</head>

<body>
    <div class="login_page">

        <div class=join>
            <h1>ログイン画面</h1>
        </div>

        <div class="form_title">
            <p>会員登録まだの方はこちらへ</p>
        </div>

        <div class="form2">
            <form action="join/index.php" method="post" >
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
            <div>
                <?php if (isset($error['login']) && $error['login'] == 'blank'): ?>
                <p>メールアドレスとパスワードを両方記入してログインしましょう！</p>
                <?php endif; ?>
                <?php if (isset($error['login']) && $error['login'] == 'failed'): ?>
                <p>ログインに失敗しました。正しく入力しまししょう！</p>
                <?php endif; ?>
            </div>

           
            <div class="form2">
                <button type="submit">ログイン</button> 
            </div>
        </form>
            <div class="form2">
                <form action="toppage.php" method="post" >
                    <button type="submit">TOPページに戻る</button>
                </form>
            </div>
    </div>
</body>
</html>