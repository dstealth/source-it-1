<?php

$email = "asd@fasd.com";

$emailHasAt = strpos($email, "@") !== false;
$emailHasDot = strpos($email, ".") !== false;

if ($emailHasAt && $emailHasDot) {
    echo "This email is valid.\n";
} 

if ($emailHasDot) {
    echo 'test';
}
