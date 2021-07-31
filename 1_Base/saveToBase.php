<?php
/*
 * 
 */
require_once 'config.php';
/*
* checkFolderonDublicateName проверяет папку $folderName на содержание
* имени файла $fileName
* Return true - дубликат имени есть
* false - дубликата имени нет
*/
function checkFolderonDublicateName (string $folderName, string $fileName) {
    $arrayFiles = scandir($folderName);
    foreach ($arrayFiles as $fileInFolder) {
        if (trim($fileInFolder) === trim($fileName)) {
            return true;
        }
    }
    return false;
}

/*
* generateChars(n)
* генерирует n переменных
* RETURN string
*/
function generateChars ($length=5)
{   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)]; 
        }
    return $randomString;
}
/*
* checkInsertData проверяет переменную $data
* На следующие соотвествия NOT NULL,
* очистка от спецсимволов
* RETURN string $data
*/
function checkInsertData (string $data) {
    if ($data == null) {
        print('denied');
        die();
    }
    return $data = htmlspecialchars($data);
}
/*
* Получаем очищенные данные запроса
*/
$typeTask = checkInsertData($_REQUEST["TypeTask"]);
$email = checkInsertData($_REQUEST["Email"]);
$userName = checkInsertData($_REQUEST["Name"]);
$message = checkInsertData($_REQUEST["Message"]);
$gender = checkInsertData($_REQUEST["Gender"]);

/*
 * Проверяем загружены ли файлы, затем проверяем каждый на соответсвие формату изображения
 * $files - содержит файлы прикреплённые пользоваталем 
 */
$files = null;
if ($_FILES['formFile']) {
    foreach ($_FILES['formFile']["error"] as $key => $error) {
        if ($error !== UPLOAD_ERR_OK) {
            echo ('ошибка загрузки файла' . $_FILES["formFile"]["tmp_name"][$key]);
            continue;
        }
        $file = $_FILES["formFile"]["tmp_name"][$key];
        $filetypeMIME = mime_content_type($file);
        $filetype = explode('/', $filetypeMIME);
        if ($filetype[0] == 'image') {
            echo($filetypeMIME. ' is correct <pre>');
            $filetypeFlag = true;
         }else {$filetypeFlag = false; 
            echo($filetypeMIME. ' not correct <pre>');
            }
        if ($filetypeFlag) {
            $name = generateChars(5).$_FILES["formFile"]["name"][$key];
            $dublicateFlag = checkFolderonDublicateName('store/', $name);
            $nCycle = 0;
            while ($dublicateFlag && ($nCycle<100)) {
                $nCycle++;
                $name = generateChars(5).$_FILES["formFile"]["name"][$key];
            }
            move_uploaded_file($file, "store/$name");
            echo('Sucess loaded '.$name.'<pre>');
            $files[] = $name;
        }else {echo('files not loaded errors:'. $error. '<pre>');}
    }
} else {
    echo('files not attached by user <pre>');
}
/*
 * В зависимости от наличия или отсутсвия файлов формируем запрос
 */
if ($files === null) {
    $querryInsert = 'INSERT INTO '.$tableName.'(TypeTask, Email, Name, Message, Sex) VALUES '
            . "('$typeTask', '$email', '$userName', '$message', '$gender')";
} else {
    $filesString = implode(', ',$files);
    $querryInsert = 'INSERT INTO '.$tableName.'(TypeTask, Email, Name, Message, Sex, filename) VALUES '
            . "('$typeTask', '$email', '$userName', '$message', '$gender', '$filesString')";
}
try {
    $dbh = new PDO($typeDB.':host='.$host.';dbname='.$dbName, $dbLogin, $dbPass);
    $dbh->exec($querryInsert);
    $dbh = null;
    echo 'all data has loaded <pre>';
}
catch (PDOException $e) {
    echo ('error with load data on server ' . $e->getMessage());
}

echo('<a href = "index.html">RETURN TO INDEX</a>');
