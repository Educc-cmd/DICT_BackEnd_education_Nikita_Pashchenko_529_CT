<?php
// TODO 1: PREPARING ENVIRONMENT
session_start();
// Подключаем настройки базы данных
$aConfig = require_once 'config.php';

// TODO 3: CODE by REQUEST METHODS (Обробник форми)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['text'])) {

        // Подключаемся к БД для записи
        $db = mysqli_connect($aConfig['host'], $aConfig['user'], $aConfig['pass'], $aConfig['name']);

        // Очищаем данные перед вставкой в БД (защита от кавычек и инъекций)
        $email = mysqli_real_escape_string($db, htmlspecialchars($_POST['email']));
        $name = mysqli_real_escape_string($db, htmlspecialchars($_POST['name']));
        $text = mysqli_real_escape_string($db, htmlspecialchars($_POST['text']));
        $date = date('Y-m-d H:i:s');

        // Формируем и выполняем SQL-запрос
        $query = "INSERT INTO comments (email, name, text, date) VALUES ('$email', '$name', '$text', '$date')";
        mysqli_query($db, $query);
        mysqli_close($db);

        $status = "success";
    } else {
        $status = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<?php require_once 'sectionHead.php'; ?>
<body>

<div class="container">
    <?php require_once 'sectionNavbar.php'; ?>
    <br>

    <div class="card card-primary mb-4">
        <div class="card-header bg-primary text-light">
            Залишити запис у Гостьовій книзі
        </div>
        <div class="card-body">
            <?php if (isset($status) && $status == 'success'): ?>
                <div class="alert alert-success">Повідомлення збережено!</div>
            <?php elseif (isset($status) && $status == 'error'): ?>
                <div class="alert alert-danger">Будь ласка, заповніть усі поля!</div>
            <?php endif; ?>

            <form action="guestbook.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ім'я:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Повідомлення:</label>
                    <textarea name="text" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Відправити</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-body-secondary text-dark">
            Коментарі відвідувачів
        </div>
        <div class="card-body">
            <?php
            // Подключаемся к БД для чтения
            if (!isset($aConfig)) {
                $aConfig = require_once 'config.php';
            }
            $db = mysqli_connect($aConfig['host'], $aConfig['user'], $aConfig['pass'], $aConfig['name']);

            // Выбираем все комментарии (сортируем по дате, чтобы новые были сверху)
            $query = "SELECT * FROM comments ORDER BY date DESC";
            $dbResponse = mysqli_query($db, $query);
            $aComments = mysqli_fetch_all($dbResponse, MYSQLI_ASSOC);
            mysqli_close($db);

            // Выводим комментарии, если они есть
            if (!empty($aComments)) {
                foreach ($aComments as $comment) {
                    echo "<div class='border-bottom mb-3 pb-2'>";
                    echo "<strong>" . htmlspecialchars($comment['name']) . "</strong> <small class='text-muted'>(" . htmlspecialchars($comment['email']) . ")</small> <small class='text-secondary float-end'>" . $comment['date'] . "</small><br>";
                    echo "<span>" . nl2br(htmlspecialchars($comment['text'])) . "</span>";
                    echo "</div>";
                }
            } else {
                echo "Відгуків ще немає.";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>