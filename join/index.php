<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" media="all" type="text/css" href="style.css">
    <title>登録画面</title>
</head>
<body>
    <div class"notification"></div> 
    <!-- なんのため？ -->
    <div class"wrapper select">
        <div class="index-page" id="index-page">
            <!-- どうしてclassとidが一緒に入ってる？ -->
            <div class="index-page__header">
                <p>LOGIN</p>
            </div>
        <div class="index-page__innner--riight user-form">
            <!-- どうしてright？　 -->
            <form class="new_user" id="new_user" action="/users/sign_in" accept-charset="UTF-8" method="post">
                <!-- accept...受け取るもの？　こういう書き方？ -->
                <input name="utf8" type="hidden" value="✓">
                <!-- これは何してる？下も -->
                <input type="hidden" name="authenticity_token" value="u88f4mqo6oqkC0kpG6zCzV2KXXB5LQEbCjY++VqPxu80Fdwb4LTM+cCMJtJS98hvJwEjEWtunIp3vLFS0u5JuA==">
            <div class="field">
                <div class="field-label">
                <label for="user_email">
                    <i class="farfa-envelope" aria-hidden="true">
                        ::before
                        "Email"
                        <!-- なぜ反映されない？ -->
                    </i>
                </label>
            </div>
            <div class="field-input">
                <input autofocus="autofocus" type="email" value="" name="user[email]" id="user_email">
            </div>
            <div class="field">
                <div class="field-label">
                    <label for="user_password">
                        <i class="fas fa-key" aria-hidden="true">
                            <!-- 読み上げない -->
                            ::brfore
                            "Password (英数字8文字以上)"
                        </i>
                    </label>
                </div>
                <div class="field-input">
                    <input autocomplete="off" type="password" name="user[password]" id="user_password">
                    <!-- 自動補完？ -->
                </div>
            </div>
            <div class="actions resingin">
                <input type="submit" name="commit" value="Log in" class="btn login" data-disable-with="Log in">
                <!-- 「data-disable-with」とは？ -->
                <a class="btn another" href="/users/sign_up">Sign up</a>
                <br>
            </div>
        </div>
        </form>
    </div>
</body>
</html>