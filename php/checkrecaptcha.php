<?php
header("Cache-Control: no-cache, must-revalidate");
header("Content-Type: application/json");

if(isset($_GET["recaptcha"])){
    $recaptcha = $_GET["recaptcha"];
    $remoteip=$_SERVER["REMOTE_ADDR"];
    $checkResponse = file_get_contents(
    'https://www.google.com/recaptcha/api/siteverify?secret=6LfNnAETAAAAAMpTzTv1kdbR0V66ZMEjiLoU5hfg' .
    '&response=' . $recaptcha .
    '&remoteip=' . $remoteip);
    //Send the result of the check to the user
    header("Cache-Control: no-cache, must-revalidate");
    header("Content-Type: application/json");
    echo($checkResponse);
}
else
{
    echo "{}";
}


