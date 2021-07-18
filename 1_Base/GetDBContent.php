  <head>
    <title>Связаться с нами</title>
    <meta name="description" content="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="linkToUs.css" rel="stylesheet" type="text/css"/> 
  </head>
  <body>
<?php
/*
 * 
 */
require_once 'config.php';

if(isset($_REQUEST['page'])){
$numPage = $_REQUEST['page'];}
else {
    $numPage = 1;
}
/*
 * Определим число записей, в завсимости от числа, создадим соответсвующее
 * число страниц
 */
$dbh = new PDO($typeDB.':host='.$host.';dbname='.$dbName, $dbLogin, $dbPass);
$querrySelectTable = 'SELECT * FROM '.$tableName;
$resultQ = $dbh->query($querrySelectTable);
$strokes = $resultQ->rowCount();
$pages = ceil($strokes/5);
$startline = $numPage*5-5;
$strokes = 5;
//сделаем выборку в зависимости от текущей страницы для 1 - с 1 по 5 запись
$querrySelectTable = 'SELECT * FROM '.$tableName. ' LIMIT '.$startline." , ".$strokes;
try {
    $resultQ = $dbh->query($querrySelectTable);

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
        foreach (explode(',',$row[5]) as $value) {
        $line = trim($value);
        echo("<img src = 'store/$line' class='rounded float-left' width='100' height='100'>");}
        
            echo ('</td></tr>');
        };
    echo('</tbody>
        </table>');
     /*
     * Запишем результаты запроса в Csv файл
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


    fclose($handle); //Закрываем= 
    /*
     * отрисовка ссылок на страницы
     */
    for ($i = 1; $i <= $pages; $i++) {
        echo("<a class='link-primary center-block' href=?page=".$i."><button class='btn btn-primary'>".$i."</button></a>");
    }
    
    // отрисовка формы для отправки данных на почту
    echo(   "<form method='POST' class = 'form-group row' name = 'sendToEmail' id = 'sendToEmail' action='sendToEmail.php'>
            <label>Email</label>
            <input name = 'email' type='email' required></input>
            <label>Для теста введите от чьего имени будет отправлено MyEmail@yandex.ru</label>
            <input name = 'emailSender' type='email' required></input>
            <label>Пароль для почты</label>
            <input name = 'password' required></input>
            <button type='submit; class='btn btn-primary mb-2'>Send to email</button>
            </form>");
    
    $dbh = null;
} catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
}

?>
      
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
  </body>
