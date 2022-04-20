<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="up_delstyle.css">
    <title>Document</title>
</head>
<body>
<div class="delete_form">
    <?php if ($_GET['result'] == 1) { ?>
        <p class="come">削除しました！</p>


    <?php } else { ?>
        <?php header('Location: delete_error.html'); ?>
    <?php } ?>
    <div class="form5" >
        <form action="toppage.php" method="post" >
            <button type="submit"> 
                TOPページに戻る
            </button>
        </form>
    </div>
</div>
</body>
</html>