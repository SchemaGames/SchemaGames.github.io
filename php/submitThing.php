<?php
$thing_type  = filter_input(INPUT_POST,'thing_type',FILTER_VALIDATE_INT);
$user        = filter_input(INPUT_POST,'user',FILTER_VALIDATE_INT);
$thing_name  = filter_input(INPUT_POST,'thing_name',FILTER_SANITIZE_STRING);
$content_url = filter_input(INPUT_POST,'content_url',FILTER_VALIDATE_URL);

include 'mysqlauth.php';

//Begin by connecting to the SQL database
$conn = new mysqli($servername, $username, $password, $dbname);
//Check connection
if ($conn->connect_error) {
    trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
}
//Query the SQL database to insert the new thing
$sql = 'INSERT INTO things (thing_type,user,post_time,thing_name,content_url) VALUES (?, ?,NOW(), ?, ?)';
if($stmt = $conn->prepare($sql)){
    $stmt->bind_param('iiss', $thing_type,$user,$thing_name,$content_url);
    $result = $stmt->execute();
    //Check query
    if ($result === false) {
        trigger_error('Wrong SQL:   Error: ' . $conn->error, E_USER_ERROR);
    }

    echo "Success";
}
else{
    echo "Failure";
}
$stmt->close();