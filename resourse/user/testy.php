<?php 
//Подключение к файлу с БД
require_once('../db.php');
//Запросы в БД для вывода тестов
$result1 = mysqli_query($conn, "SELECT * FROM `test` WHERE id_test = '1'");
$t1 = mysqli_fetch_assoc($result1);
$result2 = mysqli_query($conn, "SELECT * FROM `test` WHERE id_test = '2'");
$t2 = mysqli_fetch_assoc($result2);
$result3 = mysqli_query($conn, "SELECT * FROM `test` WHERE id_test = '3'");
$t3 = mysqli_fetch_assoc($result3);
$result4 = mysqli_query($conn, "SELECT * FROM `test` WHERE id_test = '4'");
$t4 = mysqli_fetch_assoc($result4);
$result5 = mysqli_query($conn, "SELECT * FROM `test` WHERE id_test = '5'");
$t5 = mysqli_fetch_assoc($result5);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="\resourse\css\cards.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тесты</title>
</head>
<body>
<? include("menu.php"); ?>
  <div class="card">
    <h2>СПИСОК ТЕСТОВ</h2>
    <div class="cards">
      <!--Вывод наименования и описания теста из запроса по ключу-->
      <h2><?=$t1['name_test']?></h2>
      <p><?=$t1['description']?></p>
      <div class="link">
        <a class="link" href="testy/test_1.php">ОТКРЫТЬ</a>
      </div>
    </div>
    <div class="cards">
      <h2><?=$t2['name_test']?></h2>
      <p><?=$t2['description']?></p>
      <div class="link">
        <a class="link" href="testy/test_2.php">ОТКРЫТЬ</a>
      </div>
    </div>
    <div class="cards">
      <h2><?=$t3['name_test']?></h2>
      <p><?=$t3['description']?></p>
      <div class="link">
        <a class="link"href="testy/test_3.php">ОТКРЫТЬ</a>
      </div>
    </div>
    <div class="cards">
      <h2><?=$t4['name_test']?></h2>
      <p><?=$t4['description']?></p>
      <div class="link">
        <a class="link" href="testy/test_4.php">ОТКРЫТЬ</a>
      </div>
    </div>
    <div class="cards">
      <h2><?=$t5['name_test']?></h2>
      <p><?=$t5['description']?></p>
      <div class="link">
        <a class="link" href="testy/test_5.php">ОТКРЫТЬ</a>
      </div>
    </div>
  </div>
</body>
</html>