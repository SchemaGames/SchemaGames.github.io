<?php

include 'mysqlauth.php';

//Begin by connecting to the SQL database
$conn = new mysqli($servername, $username, $password, $dbname);
//Check connection
if ($conn->connect_error) {
    trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
}
//Query the SQL database for a set of users
$result = $conn->query('SELECT user_id, username FROM users;');
//Check query
if ($result === false) {
    trigger_error('Wrong SQL:   Error: ' . $conn->error, E_USER_ERROR);
}
else
{
    $num_users = $result->num_rows;
    //Display each user one by one
    $result->data_seek(0);
    header('Content-Type: application/json');
    echo("{\"total_rows\":" . $num_users . ",\"rows\":[");
    for($i = 0; $i < $num_users -1; $i++)
    {
        $row = $result->fetch_assoc();
        echo json_encode($row) . ',' ;
    }
    $final_row = $result->fetch_assoc();
    echo json_encode($final_row) . "]\n}";
}
$result->free();