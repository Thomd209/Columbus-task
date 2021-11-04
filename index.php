<?php 
spl_autoload_register(function($class) {
    require_once($_SERVER['DOCUMENT_ROOT'] . 'classes/' . $class . '.php');
});

require_once($_SERVER['DOCUMENT_ROOT'] . 'scripts/guide.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML form</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <form enctype="multipart/form-data" method="post">
        <div class="form-group">
            <label for="id">Код:</label>
            <input type="text" name="id" id="id">
        </div>
        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" name="name" id="name">
        </div>
        <div class="form-group">
            <label for="file">Загрузить .csv файл:</label>
            <input type="file" name="file" id="file">
        </div>
        <button class="submit" type="submit">Отправить</button>
        <?php if (! empty($_POST) && ! empty($error)): ?>
            <span class="error"><?= $error ?></span>
        <?php endif; ?>
    </form>
</body>
</html>