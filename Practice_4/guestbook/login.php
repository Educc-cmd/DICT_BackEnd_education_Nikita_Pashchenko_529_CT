<?php
// TODO 1: PREPARING ENVIRONMENT: 1) session 2) functions
session_start();
$aConfig = require_once 'config.php';

// TODO 2: ROUTING
if (!empty($_SESSION['auth'])) {
    header('Location: /admin.php');
    die;
}

// 1. Create empty $infoMessage
$infoMessage = '';

// 2. handle form data
if (!empty($_POST['email']) && !empty($_POST['password'])) {

    // Подключаемся к БД
    $db = mysqli_connect($aConfig['host'], $aConfig['user'], $aConfig['pass'], $aConfig['name']);

    // Экранируем данные
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // 3. Ищем пользователя с таким email и паролем в БД
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $dbResponse = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($dbResponse);

    mysqli_close($db);

    if ($user) {
        // Пользователь найден
        $_SESSION['auth'] = true;
        // $_SESSION['email'] = $user['email'];

        header("Location: admin.php");
        die;
    } else {
        // Пользователь не найден
        $infoMessage = "Такого пользователя не существует или пароль неверный. Перейдите на страницу регистрации. ";
        $infoMessage .= "<a href='register.php'>Страница регистрации</a>";
    }

} elseif (!empty($_POST)) {
    $infoMessage = 'Заполните форму авторизации!';
}
?>

<!DOCTYPE html>
<html>
<?php require_once 'sectionHead.php' ?>
<body>
<div class="container">
    <?php require_once 'sectionNavbar.php' ?>
    <br>
    <div class="card card-primary">
        <div class="card-header bg-primary text-light">
            Login form
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email"/>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password"/>
                </div>
                <br>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="form"/>
                </div>
            </form>

            <?php
            if ($infoMessage) {
                echo '<hr/>';
                echo "<span style='color:red'>$infoMessage</span>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>