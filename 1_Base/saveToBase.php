<?php
/*
 * 
 */
require_once 'config.php';
$typeTask = $_REQUEST["TypeTask"];
$email = $_REQUEST["Email"];
$userName = $_REQUEST["Name"];
$message = $_REQUEST["Message"];
$gender = $_REQUEST["Gender"];

function generateChars ($length=5)
{   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)]; // ��������� � ������ ����� ������ �� $characters
    }
    return $randomString;
}
/*
 * �������� ������ �� ������
 * ��������� �������� �������� �� ��� �����, ��� �������������� ����������
 * ���� ��� ���������� -> ������������
 * $files - ������ ���������� �� ������ ������ � ���� �������, ��� null
 */
$files = null;
if ($_FILES['formFile']['error'] == UPLOAD_ERR_OK) {
    foreach ($_FILES['formFile']["error"] as $key => $error) {
        $file = $_FILES["formFile"]["tmp_name"][$key];
        $filetypeMIME = mime_content_type($file);
        $filetype = explode('/', $filetypeMIME);
        if ($filetype[0] == 'image') {
            echo($filetypeMIME. ' is correct <pre>');
            $filetypeFlag = TRUE;
        }
        else {$filetypeFlag = FALSE; 
        echo($filetypeMIME. ' not correct <pre>');
        }
        if (($error == UPLOAD_ERR_OK) && $filetypeFlag) {
            $name = generateChars(5).$_FILES["formFile"]["name"][$key];
            move_uploaded_file($file, "store/$name");
            echo('Sucess loaded '.$name.'<pre>');
            $files[] = $name;
        }
        else {echo('files not loaded errors:'. $error. '<pre>');}
    }
}
/*
 * ������������ �������� ���������� ������� �������������  �� ������
 * ������ �������� ��������� ��� ������, ���� �������� ������ ��������
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
