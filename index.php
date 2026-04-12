<?php
// TODO 1: PREPARING ENVIRONMENT
session_start();

// TODO 3: CODE by REQUEST METHODS (Обробник форми)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['text'])) {

        $commentData = [
                'email' => htmlspecialchars($_POST['email']),
                'name'  => htmlspecialchars($_POST['name']),
                'text'  => htmlspecialchars($_POST['text']),
                'date'  => date('Y-m-d H:i:s')
        ];

        $jsonString = json_encode($commentData);

        $fileStream = fopen("comments.csv", "a");
        fwrite($fileStream, $jsonString . "\n");
        fclose($fileStream);

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

            <form action="index.php" method="POST">
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
            $filename = "comments.csv";
            if (file_exists($filename)) {
                $fileStream = fopen($filename, "r");

                while (!feof($fileStream)) {
                    $jsonString = fgets($fileStream);
                    $comment = json_decode($jsonString, true);

                    if (!empty($comment)) {
                        echo "<div class='border-bottom mb-3 pb-2'>";
                        echo "<strong>" . htmlspecialchars($comment['name']) . "</strong> <small class='text-muted'>(" . htmlspecialchars($comment['email']) . ")</small><br>";
                        echo "<span>" . nl2br(htmlspecialchars($comment['text'])) . "</span>";
                        echo "</div>";
                    }
                }
                fclose($fileStream);
            } else {
                echo "Відгуків ще немає.";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>