<?php
namespace guestbook\Controllers;

use PDO;

class GuestbookController
{
    public function execute()
    {
        $aConfig = require 'config.php';
        $infoMessage = '';

        if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['text'])) {
            $aComment = $_POST;
            $aComment['date'] = date('m.d.Y');

            $pdo = new PDO("mysql:host=" . $aConfig['host'] . ";dbname=" . $aConfig['name'], $aConfig['user'], $aConfig['pass']);
            $query = "INSERT INTO comments (email, name, text, date) VALUES (:email, :name, :text, :date)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                    'email' => $aComment['email'],
                    'name'  => $aComment['name'],
                    'text'  => $aComment['text'],
                    'date'  => $aComment['date']
            ]);

        } elseif (!empty($_POST)) {
            $infoMessage = 'Заполните поля формы!';
        }

        $pdo = new PDO("mysql:host=" . $aConfig['host'] . ";dbname=" . $aConfig['name'], $aConfig['user'], $aConfig['pass']);
        $query = 'SELECT * FROM comments';
        $stmt = $pdo->query($query);
        $aComments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderView([
                'infoMessage' => $infoMessage,
                'aComments'   => $aComments
        ]);
    }

    public function renderView($arguments = [])
    {
        $infoMessage = $arguments['infoMessage'];
        $aComments = $arguments['aComments'];
        ?>

        <!DOCTYPE html>
        <html>

        <?php require_once 'ViewSections/sectionHead.php' ?>

        <body>

        <div class="container">

            <!-- navbar menu -->
            <?php require_once 'ViewSections/sectionNavbar.php' ?>
            <br>

            <!-- guestbook section -->
            <div class="card card-primary">
                <div class="card-header bg-primary text-light">
                    Guestbook form
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">

                            <!-- form -->
                            <form method="post" name="form" class="fw-bold">
                                <div class="form-group">
                                    <label for="exampleInputEmail">Email address</label>
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter email">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputName">Name</label>
                                    <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Enter name">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputText">Text</label>
                                    <textarea name="text" class="form-control" id="exampleInputText" placeholder="Enter text" required></textarea>
                                </div><br>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Send">
                                </div>
                            </form>
                            <br>
                        </div>

                        <?php
                        if ($infoMessage) {
                            echo "<span style='color:red'>$infoMessage</span>";
                        }
                        ?>

                    </div>
                </div>
            </div>

            <br>

            <div class="card card-primary">
                <div class="card-header bg-body-secondary text-dark">
                    Сomments
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">

                            <?php
                            foreach($aComments as $comment) {
                                echo $comment['name']   . '<br>';
                                echo $comment['email']  . '<br>';
                                echo $comment['text']   . '<br>';
                                echo $comment['date']   . '<br>';

                                echo '<hr>';
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>

        </body>
        </html>

        <?php
    }
}