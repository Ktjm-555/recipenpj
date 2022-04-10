<?php

$error = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if ($email == '' || $password == ''){
        $error['login'] = 'blank';
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
  
    <form action="" method="post">
        <dl>
            <dt>メールアドレス</dt>
            <dd> 
            <input type="text" name="email" size="35" maxlength="255" value=""/>
            </dd>
            <dt>パスワード</dt>
            <dd> 
            <input type="password" name="password" size="10" maxlength="20" value=""/>
            </dd> 
        </dl>
        <div>
            <?php if (isset($error['login']) && $error['login'] == 'blank'): ?>
            <p>メールアドレスとパスワードを両方記入してログインしましょう！</p>
            <?php endif; ?>
            <p>ログインに失敗しました。正しく入力しまししょう！</p>
            <p>会員登録まだの方はこちらからどうぞ</p>
        </div>
        <div>
            <input type="submit" value="ログイン">
        </div>

</body>
</html>