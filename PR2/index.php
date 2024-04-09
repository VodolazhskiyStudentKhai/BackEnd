<?php
// !!! TODO 1: ваш код обробки GET запиту; виконання запиту через cURL у пошукову систему; підготовка даних для малювання
if(isset($_GET["search"])) {
    $search = htmlspecialchars($_GET["search"]);
    $apiKey = "AIzaSyBogcfQoq-E1oLIFs-p1-vappmjvn5VDW8";
    $cx = "a020f95a47709428a";
    $url = "https://www.googleapis.com/customsearch/v1?key=$apiKey&cx=$cx&q=$search";
    //$url = "https://www.googleapis.com/customsearch/v1?key=AIzaSyBogcfQoq-E1oLIFs-p1-vappmjvn5VDW8&cx=a020f95a47709428a&q=blablabla";
    // Инициализация нового сеанса cURL
    $ch = curl_init(); // открыть сеанс cURL
    curl_setopt($ch, CURLOPT_URL, $url); // установить параметр $url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // вернуть ответ в строку
    $resultJson = curl_exec($ch); // выполнить запрос
    curl_close($ch); // закрыть сеанс cURL
//    var_dump(json_decode($resultJson, true));
    $items = json_decode($resultJson, true)["items"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<h2>My Browser</h2>
<form method="GET" action="/index.php">
    <label for="search">Search:</label>
    <input type="text" id="search" name="search" value=""><br><br>
    <input type="submit" value="Submit">
</ form >
<?php
// !!! TODO 2: код відображення відповіді
if(!empty($items)) {
    foreach ($items as $item) {
        echo "<p>".$item['title']."</p>";
        echo "<a href='".$item['link']."'>".$item['link']."</a>";
        echo "<br>";
        echo $item['htmlTitle'];
    }
}
?>
</body>
</html>