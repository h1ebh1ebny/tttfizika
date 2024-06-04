<?php 
//Подключение к файлу с БД
require_once('db.php');
//Запуск сессии для приема данных пользователя с файла signin
session_start();
//$id_user = $_SESSION['user']['id_user'];
$g=$_POST['num_group']; //получение номера группы из group_student_result
$res = mysqli_query($conn, "SELECT DISTINCT u.last_name, u.first_name, u.pathronymic, u.num_group, r.correct_answers/*,  t.name_test*/ FROM `users` u, `results` r /*`, test` t*/ WHERE u.id_user=r.id_user/* AND r.id_test=t.id_test */AND u.num_group=$g ");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/testy.css">
    <title>Результаты</title>
</head>
<body>
<div class="card_list" >
  <div class="back">
    <a href="psycho/menu.html">Назад</a>
  </div>
  <!--Вывод информации с ранее полученного массива по ключам-->
  <?php
    if (mysqli_num_rows($res) > 0) { //проверка на наличие записей в бд
      while ($result = mysqli_fetch_assoc($res)) {?>
        <div class="card_1">
          <h2>Фамилия:  <?= $result['last_name']?></h2>
          <h2>Имя:  <?= $result['first_name']?></h2>
          <h2>Отчество: <?= $result['pathronymic']?></h2>
          <h2>Группа:  <?= $result['num_group']?></h2>
          <h2>Название теста: <?= $result['name_test']?></h2>
          <h2>Результат: <?= $result['result']?></h2>
        </div> 
      <?php } } else { ?>
        <h2>Курсанты данной группы не проходили тестирование.</h2>
      <?php } ?>
</div>
</body>
</html>