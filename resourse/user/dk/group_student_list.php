<?php 
//Подключение к файлу с БД
require_once('../../db.php');
//Запуск сессии для приема данных пользователя с файла signin
session_start();
$res = mysqli_query($conn, "SELECT DISTINCT num_group FROM `users` WHERE num_group IS NOT NULL ORDER BY num_group ");
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/test.css">
    <title>Курсанты</title>
</head>
<body> 

<div class="card" >
    <div class="cards">
      <!--Вывод информации с ранее полученного массива по ключам-->
        <form action="../../student_list.php" method="POST">
            <div class="cards">
                <h2>Выберите номер групы, чтобы посмотреть список курсантов. </h2>
                <label>
                    <select name="num_group">
                        <?php
                            while ($result = mysqli_fetch_assoc($res)) {?>
                                <option><?=$result['num_group']?></option>
                        <?php } ?>
                    </select>
                </label>
            </div>
            <div class="btn">
                <button class="glow-on-hover" type="submit" >ОТПРАВИТЬ</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>