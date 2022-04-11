<?php
session_start();

$error = [];
$email = '';
$password = '';

require('library.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
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
        $stmt->bind_result($id, $name, $hash);
        $stmt->fetch();
        
        if (password_verify($password, $hash)){
            // ログイン成功
            session_regenerate_id();
            // idを再形成
            $_SESSION['id'] = $id;
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
    <title>ログイン</title>
</head>
<body>
    <div>
        <h1>ログイン画面</h1>
    </div>
    <div>
        <p>会員登録まだの方はこちらへ</p>
        <a href="join/index.php">会員登録へ</a>
    </div>

    <form action="" method="post">
        <dl>
            <dt>メールアドレス</dt>
            <dd> 
            <input type="text" name="email" size="35" maxlength="255" value="<?php echo h($email); ?>"/>
            </dd>
            <dt>パスワード</dt>
            <dd> 
            <input type="password" name="password" size="10" maxlength="20" value="<?php echo h($password); ?>"/>
            </dd> 
        </dl>
        <div>
            <?php if (isset($error['login']) && $error['login'] == 'blank'): ?>
            <p>メールアドレスとパスワードを両方記入してログインしましょう！</p>
            <?php endif; ?>
            <?php if (isset($error['login']) && $error['login'] == 'failed'): ?>
            <p>ログインに失敗しました。正しく入力しまししょう！</p>
            <?php endif; ?>
        </div>
        <div>
            <input type="submit" value="ログイン">
        </div>

</body>
</html>