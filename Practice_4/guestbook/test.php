<?php
$host = 'localhost'; // имя хоста
$user = 'root'; // Имя пользователя
$pass = ''; // пароль
$name = 'guestbook'; // имя базы данных

$db = mysqli_connect($host, $user, $pass, $name);
$query = 'SELECT * FROM users';
$dbResponse = mysqli_query($db, $query);
$aUsers = mysqli_fetch_all($dbResponse, MYSQLI_ASSOC);

mysqli_close($db);
var_dump($aUsers);
?>