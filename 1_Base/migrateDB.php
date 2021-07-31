<?php
/*
 * Миграция БД
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
    echo('Таблицы Успешно созданы');
} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
}
