<?php

$typeDB = 'mysql'; //тип БД,подключение осуществляется через PDO обьект -> при изменении необходимо наличие PDO драйвера http://php.adamharvey.name/manual/ru/pdo.drivers.php
$host = "127.0.0.1"; // адрес хоста
$dbLogin = "root"; // логин для подключения к хосту
$dbPass = '';  // пароль для подлючения к хосту
$dbName = 'linktous'; // Наименование БД которая будет создана при  запуске migration.php
$tableName = 'tasks';  // Имя таблицы которая будет создана при  запуске migration.php
