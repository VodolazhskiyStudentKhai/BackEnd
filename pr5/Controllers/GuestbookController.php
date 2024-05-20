<?php

namespace Controllers;

class GuestbookController {

    // TODO 3.raw: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3.raw) transforming data
    public function execute() {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $aConfig = require_once 'config.php';

// TODO 2: ROUTING

// TODO 3.raw: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3.raw) transforming data

// 1. Create empty $infoMessage
        $infoMessage = '';

// 2. handle form data
        if (!empty($_POST['name']) && !empty($_POST['email']) &&!empty($_POST['text'])) {

            // 3. Prepare data
            $aComment = $_POST;

            // create new comment
            $db = mysqli_connect($aConfig['host'], $aConfig['user'], $aConfig['pass'], $aConfig['name']);
            $query = "INSERT INTO comments VALUES (DEFAULT,'". $aComment['name']."','". $aComment['email']."','". $aComment['text']."')";
            mysqli_query($db, $query);
            mysqli_close($db);

        } elseif (!empty($_POST)) {
            $infoMessage = 'Заполните поля формы!';
        }
        $arguments = [
            'infoMessage' => $infoMessage
        ];
        $arguments = $arguments + $aConfig;
        $this->renderView($arguments);
    }

    // TODO 4: RENDER: 1) view (html) 2) data (from php)
    public function renderView($arguments = []) {
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
                                    <input type="submit" style="background-color: rgba(27,190,73,0.63); border-radius: 12%; border-color: darkgreen" value="Send">
                                </div>
                            </form>
                            <br>
                        </div>

                        <!-- TODO: render php data   -->

                        <?php
                        if ($arguments['infoMessage']) {
                            echo "<span style='color:red'>{$arguments['infoMessage']}</span>";
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

                            <!-- TODO: render php data   -->

                            <?php

                            // select from database
                            $db = mysqli_connect($arguments['host'], $arguments['user'], $arguments['pass'], $arguments['name']);
                            $query = 'SELECT * FROM comments';
                            $dbResponse = mysqli_query($db, $query);
                            $aComments = mysqli_fetch_all($dbResponse, MYSQLI_ASSOC);
                            mysqli_close($db);

                            // render data
                            foreach($aComments as $comment) {
                                echo "<p>"."<span style='color: darkorange'>".$comment['name']."</span>"." залишив відгук:"."<br>".$comment['text']."</p>";
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