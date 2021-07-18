<?php
/*
 * Выполняется миграция таблицы необходимой для работы проекта
 */
require_once 'config.php';

$querryCreateDB = 'CREATE DATABASE IF NOT EXISTS '.$dbName;
$querryCreateTable = 'CREATE TABLE IF NOT EXISTS '.$tableName.'  
(
    NumTask INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    TypeTask VARCHAR(30) NOT NULL,
    Email VARCHAR(30) NOT NULL,
    Name VARCHAR(20) NOT NULL,
    Message TEXT,
    Filename VARCHAR(255),
    Sex VARCHAR(1)
)';

try {
    $dbh = new PDO($typeDB.':host = '.$host, $dbLogin, $dbPass);
    $dbh->exec($querryCreateDB);
    $dbh = null;
    $dbh = new PDO($typeDB.':host='.$host.';dbname='.$dbName, $dbLogin, $dbPass);
    $dbh->exec($querryCreateTable);
    $dbh = null;
    echo('Таблица успешно создана');
} catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
}
