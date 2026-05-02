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

    $db = mysqli_connect($aConfig['host'], $aConfig['user'], $aConfig['pass'], $aConfig['name']);

    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // 3. Проверяем, существует ли уже пользователь с таким email
    $checkQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkResponse = mysqli_query($db, $checkQuery);

    if (mysqli_num_rows($checkResponse) > 0) {
        $infoMessage = "Такой пользователь уже существует! Перейдите на страницу входа. ";
        $infoMessage .= "<a href='login.php'>Страница входа</a>";
        mysqli_close($db);
    } else {
        // 4. Если пользователя нет, создаем нового
        $date = date('Y-m-d H:i:s');

        // ВАЖНО: Если колонка с датой у тебя называется 'date', замени 'created_at' на 'date'
        $insertQuery = "INSERT INTO users (email, password, date) VALUES ('$email', '$password', '$date')";
        mysqli_query($db, $insertQuery);
        mysqli_close($db);

        header('Location: login.php');
        die;
    }

} elseif (!empty($_POST)) {
    $infoMessage = 'Заполните форму регистрации!';
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
        <div class="card-header bg-success text-light">
            Register form
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
                    <input type="submit" class="btn btn-primary" name="formRegister"/>
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