<?php
// TODO 1: PREPARING ENVIRONMENT: 1) session 2) functions
session_start();

$aConfig = require_once __DIR__.'/config.php';


// TODO 2: ROUTING
if (!empty($_SESSION['auth'])) {
    header('Location: /admin.php');
    die;
}

// TODO 3: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3) transforming data

// 1. Create empty $infoMessage
$infoMessage = '';

// 2. handle form data
if (!empty($_POST['email']) && !empty($_POST['password'])) {

    // 3. Check that user has already existed
    $isAlreadyRegistered = false;

    global $aConfig;
    $db = mysqli_connect (
        $aConfig ['host'],
        $aConfig ['user'],
        $aConfig ['pass'],
        $aConfig ['name']
    );
    $query = "SELECT * FROM users WHERE email LIKE '".$_POST['email']."'";
    $dbResponse = mysqli_query ( $db , $query);
    $result = mysqli_fetch_all ( $dbResponse);
    mysqli_close ($db);

    if (!empty($result)) {
        $isAlreadyRegistered = true;

        $infoMessage = "Такой пользователь уже существует! Перейдите на страницу входа. ";
        $infoMessage .= "<a href='/login.php'>Страница входа</a>";
    }

    if (!$isAlreadyRegistered) {
        // 4. Create new user
        $aNewUser = [$_POST['email'] => $_POST['password']];
        global $aConfig;
        $db = mysqli_connect (
            $aConfig ['host'],
            $aConfig ['user'],
            $aConfig ['pass'],
            $aConfig ['name']
        );
        $query = "INSERT INTO users VALUES (DEFAULT,'".$_POST['nickname']."',
        '".$_POST['email']."',
        '".$_POST['password']."'
        ,NOW())";

        mysqli_query( $db, $query);
        mysqli_close ($db);
        header('Location: /login.php');
        die;
    }

} elseif (!empty($_POST)) {
    $infoMessage = 'Заполните форму регистрации!';
}

// TODO 4: RENDER: 1) view (html) 2) data (from php)

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
                    <label>Nickname</label>
                    <input class="form-control" type="text" name="nickname"/>
                </div>
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

            <!-- TODO: render php data   -->
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