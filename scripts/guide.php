<?php
declare(strict_types=1);

$pdo = Db::getConn();

if (! checkEmptyness()) {
    $error = 'Не все поля были заполнены';
} else if (! checkFile()) {
    $error = 'Недопустимый формат файла';
} else if (! checkRowsAmount()) {
    $error = 'Файл должен содержать >100000 строк';
} else if (file_exists('public/uploads/' . $_FILES['file']['name'])) {
    $error = 'Такой файл уже существует';
}

if (empty($error)) {
    $guide = new Guide($_FILES['file']['name']);
    $id = (int) strip_tags($_POST['id']);
    $name = strip_tags($_POST['name']);

    $guide->upload();
    $guide->addErrorColumn();

    if (! checkNameForWrongSymbols($guide)) {
        $guide->importToDb($pdo);
        $guide->updateString($pdo, $id, $name);
        $updatedName = $guide->getUpdatedString($pdo, $id);

        $guide->setUpdatedString($id, $updatedName);
    }

    $guide->download();
}

function checkEmptyness(): bool
{
    return ! empty($_POST['id']) && ! empty($_POST['name']) 
    && $_FILES['file']['error'] === UPLOAD_ERR_OK;
}

function checkFile(): bool 
{
    return in_array(explode('/', $_FILES['file']['type'])[1], 
    ['plain', 'vnd.ms-excel', 'x-csv']);
}

function checkRowsAmount(): bool
{
    return count(file($_FILES['file']['tmp_name'])) > 100000;
}

function checkNameForWrongSymbols(object $guide): bool
{
    foreach(str_split($_POST['name']) as $char) {
        if (! preg_match('/^[\sа-яА-Яa-zA-Z0-9.-]*$/', $char)) {
            $guide->addErrorValue($char);
            return true;
        }
    }

    return false;
}

