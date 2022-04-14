<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if ($_GET['result'] == 1) { ?>
    <div>削除しました！</div>
    <?php } else { ?>
    <div>他の人は削除できないよ！</div>
    <?php } ?>

    <div><a href="toppage.php">TOPページに戻る</a></div>
</body>
</html>