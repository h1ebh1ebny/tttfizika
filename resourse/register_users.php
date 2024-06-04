<?php 
//Запуск сессии для передачи сообщений
session_start();
//Подключение к файлу с БД
require_once('db.php');

//Получение внесенных данных пользователем из файла register
$last_name = $_POST['last_name'];
$first_name = $_POST['first_name'];
$pathronymic = $_POST['pathronymic'];
$num_group = $_POST['num_group'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$repeat_pass = $_POST['repeat_pass'];
//Запрос в БД для проверки зарегистрированного пользователя
$check_user = mysqli_query($conn, "SELECT * From `users` WHERE `email` = '$email'");

//Проверка на пустые поля ввода
if(empty($last_name) || empty($first_name) || empty($pathronymic) || empty($num_group) || empty($email) || empty($pass) || empty($repeat_pass)){
    //Вывод сообщения о пустых полях
    $_SESSION['message'] = 'Вы не заполнили поля';
    //Редирект на страницу регистрации
    header('Location: register.php');
    } else {
        //Проверка введенных паролей
        if($pass === $repeat_pass) {
            //Зашифровка пароля для внесения в БД
            $pass = md5($pass);
            //Проверка на зарегистрированного пользователя
            if (mysqli_num_rows($check_user) > 0) {
                $_SESSION['message'] = 'Данный пользователь зарегистрирован';
                header('Location: register.php');
            } else { 
                //Внесение данных с register в БД
                $sql = "INSERT INTO `users` (last_name, first_name, pathronymic, num_group, roles, email, pass) VALUES ('$last_name', '$first_name', '$pathronymic', '$num_group', 'user', '$email', '$pass')";
                $conn -> query($sql);
                //Вывод сообщения об успешной регистрации
                $_SESSION['message'] = 'Регистрация прошла успешно';
                //Редирект на страницу авторизации
                header('Location: authorize.php');
            }
        } else {
            //Если пароли введены неверно вывод сообщения и редирект на страницу регистрации
            $_SESSION['message'] = 'Пароли не совпадают';
            header('Location: register.php');
        }
    }