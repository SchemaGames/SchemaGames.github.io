<?php

$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

include 'mysqlauth.php';

$thingtypes = array(
    "writings" => 0,
    "music" => 1,
    "comics" => 2,
    "art" => 3,
    "tutorials" => 4
);

// For thing previews, load only limited information
if(!isset($id)){

    $type = filter_input(INPUT_GET,'type',FILTER_SANITIZE_STRING);
    $num_things_per_page = filter_input(INPUT_GET,'limit',FILTER_VALIDATE_INT);

    if(!isset($num_things_per_page)){
        $num_things_per_page = 10;
    }

    if(!isset($type)){
        //No type is set, so all of the things are being queried.
        //Begin by connecting to the SQL database
        $conn = new mysqli($servername, $username, $password, $dbname);
        //Check connection
        if ($conn->connect_error) {
            trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
        }
        //Query the SQL database for a set of things
        $sql = 'SELECT thing_id, thing_name, thing_type, UNIX_TIMESTAMP(post_time) as post_time, username, default_portrait'
            . ' FROM things INNER JOIN users ON users.user_id = things.user'
            . ' ORDER BY post_time DESC LIMIT ?';
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('i',$num_things_per_page);
            $result = $stmt->execute();
            $stmt->bind_result($thing_id,$thing_name,$thing_type,$post_time,$username,$portrait);
        }
        //Check query
        if ($result === false) {
            trigger_error('Wrong SQL:   Error: ' . $conn->error, E_USER_ERROR);
        }
        else
        {
            //Put each result row into an array of json strings
            $json_results = array();
            while($stmt->fetch()){
                array_push($json_results,'{'
                    .'"thing_id":' . $thing_id . ','
                    .'"thing_name":"' . $thing_name . '",'
                    .'"thing_type":' . $thing_type . ','
                    .'"post_time":' . $post_time . ','
                    .'"username":"' . $username . '",'
                    .'"portrait":"' . $portrait .'"}'
                );
            }
            //Display each thing one by one
            header('Content-Type: application/json');
            $num_things = count($json_results);
            echo("{\"total_rows\":" . $num_things . ",\"rows\":[");
            if($num_things === 0)
            {
                echo "]\n}";
            }
            else
            {
                for($i = 0; $i < $num_things -1; $i++)
                {
                    echo($json_results[$i] . ',');
                }
                echo($json_results[$num_things-1] . "]\n}");
            }
        }
        $stmt->close();
    }
    else
    {
        //A type is set, get a list of things of that type
        //Begin by connecting to the SQL database
        $conn = new mysqli($servername, $username, $password, $dbname);
        //Check connection
        if ($conn->connect_error) {
            trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
        }
        //Query the SQL database for a set of things
        $sql = 'SELECT thing_id, thing_name, thing_type, UNIX_TIMESTAMP(post_time) as post_time, username, default_portrait'
            . ' FROM things INNER JOIN users ON users.user_id = things.user'
            . ' WHERE thing_type = ?'
            . ' ORDER BY post_time DESC LIMIT ?';
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('ii',$thingtypes[$type],$num_things_per_page);
            $result = $stmt->execute();
            $stmt->bind_result($thing_id,$thing_name,$thing_type,$post_time,$username,$portrait);
        }
        //Check query
        if ($result === false) {
            trigger_error('Wrong SQL:   Error: ' . $conn->error, E_USER_ERROR);
        }
        else
        {
            //Put each result row into an array of json strings
            $json_results = array();
            while($stmt->fetch()){
                array_push($json_results,'{'
                    .'"thing_id":' . $thing_id . ','
                    .'"thing_name":"' . $thing_name . '",'
                    .'"thing_type":' . $thing_type . ','
                    .'"post_time":' . $post_time . ','
                    .'"username":"' . $username . '",'
                    .'"portrait":"' . $portrait .'"}'
                );
            }
            //Display each thing one by one
            header('Content-Type: application/json');
            $num_things = count($json_results);
            echo("{\"total_rows\":" . $num_things . ",\"rows\":[");
            if($num_things === 0)
            {
                echo "]\n}";
            }
            else
            {
                for($i = 0; $i < $num_things -1; $i++)
                {
                    echo($json_results[$i] . ',');
                }
                echo($json_results[$num_things-1] . "]\n}");
            }
        }
        $stmt->close();
    }
}
else
{
    //A thing has been chosen by setting an id - obtain information to display it
    //Begin by connecting to the SQL database
    $conn = new mysqli($servername, $username, $password, $dbname);
    //Check connection
    if ($conn->connect_error) {
        trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
    }
    //Query the SQL database for a set of things
    $sql = 'SELECT username, UNIX_TIMESTAMP(post_time) as post_time, thing_name, thing_type, content_url'
        . ' FROM things'
        . ' INNER JOIN users on users.user_id=things.user'
        . ' WHERE thing_id = ?';
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('i',$id);
        $result = $stmt->execute();
        $stmt->bind_result($username, $post_time, $thing_name, $thing_type, $content_url);
    }
    //Check query
    if ($result === false) {
        trigger_error('Wrong SQL:   Error: ' . $conn->error, E_USER_ERROR);
    }
    else
    {
        //Display the game data
        header('Content-Type: application/json');
        $stmt->fetch();
        $json_result = '{'
            . '"username":"' . $username . '",'
            . '"post_time":' . $post_time . ','
            . '"thing_name":"' . $thing_name . '",'
            . '"thing_type":' . $thing_type . ','
            . '"content_url":"' . $content_url . '"}';
        echo $json_result;
    }
    $stmt->close();
}