<?php

$subject = 'LB 1 EMAIL';
$firstName = 'Vlad';
$secondName = 'Vodolazhskiy';
$email = 'v.v.vodolazhskyi@student.khai.edu';

echo $subject . "\n";
echo $firstName . "\n";
echo $secondName . "\n";
echo $email . "\n";

$text1 = "Hello, men" . "\n";
$text2 = "Full Name : {$firstName} ";
$text3 = "{$secondName}" . "\n";
$text4 = "email : {$email}" . "\n";
$message = $text1 . $text2 . $text3. $text4;
echo "message: \n" . $message;
$headers = 'From: v.v.vodolazhskyi@student.khai.edu';

mail($email, $subject, $message, $headers);