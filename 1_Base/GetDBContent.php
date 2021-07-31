  <head>
    <title>Загруженные данные</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="linkToUs.css" rel="stylesheet" type="text/css"/> 
  </head>
  <body>
<?php
/*
* $rowOnPage - число записей на странице
* $drawedButton - число кнопок выводимых в
* границу диапазона ($currentPage-$drawedButton...$currentPage+$drawedButton)
*/
$rowOnPage = 5;
$drawedButton = 3;

/*
 * определяем в GET запросе указана ли страница
 */
require_once 'config.php';

if(isset($_REQUEST['page'])){
$numPage = $_REQUEST['page'];}
else {
    $numPage = 1;
}
/*
 * Подключаемся к БД -> определяем число записей -> Число выводимых страниц
 * Формируем  запрос и получаем записи для конкретной страницы
 */
$dbh = new PDO($typeDB.':host='.$host.';dbname='.$dbName, $dbLogin, $dbPass);
$querrySelectTable = 'SELECT * FROM '.$tableName;
$resultQ = $dbh->query($querrySelectTable);
$strokes = $resultQ->rowCount();
$pages = ceil($strokes/$rowOnPage);
$startline = $numPage*$rowOnPage-$rowOnPage;
$querrySelectTable = 'SELECT * FROM '.$tableName. ' LIMIT '.$startline." , ".$rowOnPage;
try {
    $resultQ = $dbh->query($querrySelectTable);
    }
catch (PDOException $e) {
        echo 'Ошибка запроса к БД: ' . $e->getMessage();
        $dbh = null;
        die();
    }
/*
* Если ошибок нет отрисовываем таблицу
*/
    echo("<table class='table table-bordered'>
            <thead>
              <tr>
                <th scope='col'>#</th>
                <th scope='col'>Internal Number <br> of Task</th>
                <th scope='col'>TypeTask</th>
                <th scope='col'>Email</th>
                <th scope='col'>Name</th>
                <th scope='col'>Gender</th>
                <th scope='col'>Message</th>
                <th scope='col'>Files</th>
              </tr>
            </thead>
        <tbody>");
    $i=$startline;
    foreach ($resultQ as $row) {
        $i++;
        echo("<tr>
            <th scope=\"row\">$i</th>
            <td>$row[0]</td>
            <td>$row[1]</td>
            <td>$row[2]</td>
            <td>$row[3]</td>
            <td>$row[6]</td>
            <td>$row[4]</td> 
            <td>");
        if (!empty($row[5])) {
            foreach (explode(',',$row[5]) as $value) {
                $line = trim($value);
                echo("<img src = 'store/$line' class='rounded float-left' width='100' height='100'>");
            } 
        }
        echo ('</td></tr>');
    };
    echo('</tbody>
        </table>');
     /*
     * Для отправки списка результата формируем файл с данными таблицы messagetoEmail.csv
     * который будет отправлен на почту
     */
    $handle = fopen('messagetoEmail.csv', "w");
    $columnNames = array();
    $resultQ = $dbh->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$tableName."'");
    foreach ($resultQ->fetchAll(PDO::FETCH_ASSOC ) as $value) { 
       array_push($columnNames, $value['COLUMN_NAME']);
    }
    fputcsv($handle, $columnNames, ';'); 
         
    $resultQ = $dbh->query($querrySelectTable);
        foreach ($resultQ->fetchAll(PDO::FETCH_ASSOC ) as $value) { 
            fputcsv($handle, $value, ';'); 
        }


    fclose($handle); 
    /*
     * рисуем кнопочки для переключаения страниц
     * $drawedButton - число кнопок выводимых в
     * границу диапазона ($currentPage-$drawedButton...$currentPage+$drawedButton)
     * $pages - число страниц
     */
    $drawedButton = 3;
    $startingPositionBut = $numPage - $drawedButton;
    $startingPositionBut = ($startingPositionBut < 1) ? 1 : $startingPositionBut;
    $endingPositionBut = $numPage + $drawedButton;
    $endingPositionBut = ($endingPositionBut > $pages) ? $pages : $endingPositionBut;
    for ($i = $startingPositionBut; $i <= $endingPositionBut; $i++) {
        echo("<a class='link-primary center-block' href=?page=".$i."><button class='btn btn-primary'>".$i."</button></a>");
    }
    
    // Отрисовываем форму отправки  таблицы на почту
    echo(   "<div class = 'row justify-content-md-start'>
                <div class='col-md-4'>
                <form method='POST' class = 'form-group row linkToUs' name = 'sendToEmail' id = 'sendToEmail' action='sendToEmail.php'>
                    <label>Email на который отправлено</label>
                    <input name = 'email' type='email' required></input>
                    <label>Ваш Email</label>
                    <input name = 'emailSender' type='email' required></input>
                    <label>Ваш пароль</label>
                    <input name = 'password' type = 'password' required></input>
                    <button type='submit; class='btn btn-primary mb-2'>Отправить</button>
                </form>
                </div>
            </div>");
    
    $dbh = null;


?>
      
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
  </body>
