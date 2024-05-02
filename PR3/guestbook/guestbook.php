<?php
// TODO 1: PREPARING ENVIRONMENT: 1) session 2) functions
session_start();

function write_file($arr, $filename) {
    $jsonString = json_encode($arr); // Перетворимо масив в json -рядок
    $fileStream = fopen ($filename , 'a'); // Відкрити (і створити) файл
    fwrite ( $fileStream, $jsonString ."\n"); // записати json- рядок в кінець файлу
    fclose ($fileStream ); // Закрити файл
}

function text_to_html($data) {
    $data = trim($data);    //Удаляем пробелы
    $data = stripslashes($data);    //Удаляем экранирование \
    return htmlspecialchars($data);  //Замена спецсимволов
}

function read_comments($filename) {
    $comments = [];
    if( file_exists ($filename)) { // Перевіряє, що файл існує
        $fileStream = fopen ( $filename , "r"); // відкриває файл

        while (! feof ($fileStream )) { // йде по файлу, поки не буде досягнуто кінця
            $jsonString = fgets ($fileStream); // отримує черговий рядок файлу
            $array = json_decode ( $jsonString , true); // Перетворює рядок в масив
            if ( empty ($array)) break ; // якщо немає даних, то кінець файлу та зупинка
            $comments[$array['name']] = $array['text'];
        }
        fclose ($fileStream ); // Закрити файл
    }
    return $comments;
}

// TODO 2: ROUTING

// TODO 3: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3) transforming data
$guestbook = "guestbook.csv";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $name = $text = "";

    if (!empty($_POST["email"]) && !empty($_POST["name"]) && !empty($_POST["text"])) {
        $email = text_to_html($_POST["email"]);
        $name = text_to_html($_POST["name"]);
        $text = text_to_html($_POST["text"]);
        $record = ['name' => $name, 'email' => $email, 'text' => $text];
        write_file($record, $guestbook);
    }
    else {
        echo "<p style='color: red'>Всі поля мають бути заповнені</p>";
    }
}


//TODO 4: RENDER: 1) view (html) 2) data (from php)

?>

<!DOCTYPE html>
<html lang="">

<?php require_once 'sectionHead.php' ?>

<body>

<div class="container">

    <!-- navbar menu -->
    <?php require_once 'sectionNavbar.php' ?>
    <br>

    <!-- guestbook section -->
    <div class="card card-primary">
        <div class="card-header bg-primary text-light">
            GuestBook form
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-6">

                 <!-- TODO: create guestBook html form   -->
                    <form action="" method="POST">
                        <label for="email">Email:</label><br>
                        <input type="email" id="email" name="email"><br>
                        <label for="name">Name:</label><br>
                        <input type="text" id="name" name="name"><br>
                        <label for="text">Text:</label><br>
                        <textarea id="text" name="text"></textarea><br>
                        <input type="submit" style="background-color: rgba(27,190,73,0.63); border-radius: 12%; border-color: darkgreen" value="Відправити">
                    </form>

                </div>
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

                    <!-- TODO: render guestBook comments   -->
                    <?php
                        $data = read_comments($guestbook);
                        foreach ($data as $name => $comment) {
                            echo "<p>".$name." залишив відгук:"."<br>".$comment."</p>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
