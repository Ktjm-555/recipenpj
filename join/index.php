
<?php
session_start();

require('../library.php');

$form = [
     'name' =>'',
     'email' =>'',
     'password' =>''
];

$error = [];
// フォームの内容をチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    if ($form['name'] === ''){
         $error['name'] = 'blank';
    }

    $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if ($form['email'] === ''){
         $error['email'] = 'blank';
    }

    $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_EMAIL);
    if ($form['password'] === ''){
         $error['password'] = 'blank';
    } else if (strlen($form['password']) < 4) { 
        $error['password'] = 'length';
    }

    if (empty($error)){
        $_SESSION['form'] = $form;
        header('Location: check.php');
        exit();
    }
} 


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録画面</title>
</head>
<header>
    
</header>
<body>
    <div><h1>会員登録</h1></div>
    <form action="" method="post">
        <p class="input">ニックネーム</p>
        
        <input type="text" name="name" size="35" maxlength="255" value="<?php echo h($form['name']); ?>"/>
        <?php if (isset($error['name']) && $error['name'] === 'blank'): ?>
            <p class="error">*ニックネームを入力してくださいね。</p>
        <?php endif; ?>

        <p class="input">メールアドレス</p>
        <input type="text" name="email" size="35" maxlength="255" value="<?php echo h($form['email']); ?>"/>
        <?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
            <p class="error">*メールアドレスを入力してくださいね。</p>
        <?php endif; ?>

        <p class="input">パスワード</p>
        <input type="password" name="password" size="10" maxlength="20" value="<?php echo h($form['password']); ?>"/>
        <?php if (isset($error['password']) && $error['password'] === 'blank'): ?>
        <p class="error">*パスワードを入力してくださいね。</p>
        <?php endif; ?>
        
        <?php if (isset($error['password']) && $error['password'] === 'length'): ?>
        <p class="error">*パスワードは4文字以上で入力してくださいね。</p>
        <?php endif; ?>


        <br><button type="submit">入力内容を確認する</button> 
    </form>
</body>
</html>


