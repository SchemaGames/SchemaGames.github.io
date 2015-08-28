<?php

$game = filter_input(INPUT_GET,'game',FILTER_SANITIZE_STRING);

$servername="localhost";
$username="root";
$password="lacarana7";
$dbname="SCHEMADB";

// For game previews, load only limited information
if(!isset($game)){

    $num_games_per_page = filter_input(INPUT_GET,'limit',FILTER_VALIDATE_INT);

    $newer = filter_input(INPUT_GET,'newer',FILTER_VALIDATE_INT);
    $older = filter_input(INPUT_GET,'older',FILTER_VALIDATE_INT);

    if(!isset($num_games_per_page)){
        $num_games_per_page = 9;
    }

    //Begin by connecting to the SQL database
    $conn = new mysqli($servername, $username, $password, $dbname);
    //Check connection
    if ($conn->connect_error) {
        trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
    }
    //Query the SQL database for a set of games
    if(isset($older))
    {
        //Query for up to NUM_GAMES_PER_PAGE of the most recent articles older than the referrer's
        $sql = 'SELECT game_title, UNIX_TIMESTAMP(post_time) as post_time, thumbnail_name, game_type FROM games'
            . ' WHERE UNIX_TIMESTAMP(post_time) < ? ORDER BY post_time DESC LIMIT ?';
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('ii', $older,$num_games_per_page);
            $result = $stmt->execute();
            $stmt->bind_result($game_title,$post_time,$thumbnail_name,$game_type);
        }
    }
    else if(isset($newer))
    {
        //Query for up to NUM_GAMES_PER_PAGE of the most recent articles newer than the referrer's
        $sql = 'SELECT game_title, UNIX_TIMESTAMP(post_time) as post_time, thumbnail_name, game_type FROM games'
            . ' WHERE UNIX_TIMESTAMP(post_time) > ? ORDER BY post_time DESC LIMIT ?';
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('ii', $newer,$num_games_per_page);
            $result = $stmt->execute();
            $stmt->bind_result($game_title,$post_time,$thumbnail_name,$game_type);
        }
    }
    else
    {
        //No relevant options - get the NUM_GAMES_PER_PAGE most recent games
        $sql = 'SELECT game_title, UNIX_TIMESTAMP(post_time) as post_time, thumbnail_name, game_type FROM games'
            . ' ORDER BY post_time DESC LIMIT ?';
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('i',$num_games_per_page);
            $result = $stmt->execute();
            $stmt->bind_result($game_title,$post_time,$thumbnail_name,$game_type);
        }
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
                .'"game_title":"' . $game_title
                .'","post_time":' . $post_time
                .',"thumbnail_name":"' . $thumbnail_name
                .'","game_type":"' . $game_type
                .'"}');
            //Determine the time of the newest and oldest articles on the page
            if(!isset($newest) || $post_time > $newest){
                $newest = $post_time;
            }
            if(!isset($oldest) || $post_time < $oldest){
                $oldest = $post_time;
            }
        }
        //Get the newest and oldest times as unix strings
        if(isset($newest))
        {
            $newunix = strtotime($newest);
            $oldunix = strtotime($oldest);
        }

        //Display each game one by one
        header('Content-Type: application/json');
        $num_games = count($json_results);
        echo("{\"total_rows\":" . $num_games . ",\"rows\":[");
        if($num_games === 0)
        {
            echo "]\n}";
        }
        else
        {
            for($i = 0; $i < $num_games -1; $i++)
            {
                echo($json_results[$i] . ',');
            }
            echo($json_results[$num_games-1] . "]\n}");
        }
    }
    $stmt->close();
}
else
{
    //Begin by connecting to the SQL database
    $conn = new mysqli($servername, $username, $password, $dbname);
    //Check connection
    if ($conn->connect_error) {
        trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
    }
    //Query the SQL database for a set of articles
    $sql = 'SELECT GROUP_CONCAT(username SEPARATOR \'","\') as usernames, UNIX_TIMESTAMP(post_time) as post_time, game_title, embedded, aspect_height, aspect_width, '
         . 'game_description, game_link FROM users INNER JOIN (SELECT g.*,IFNULL(u.user_id,g.user) as user_ids FROM games AS g LEFT JOIN usergroups '
         . 'AS ug ON g.usergroup = ug.group_id LEFT JOIN users AS u ON ug.user_id = u.user_id) as uids ON user_id = uids.user_ids WHERE game_title = ? GROUP BY game_id;';
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('s',$game);
        $result = $stmt->execute();
        $stmt->bind_result($usernames, $post_time, $game_title, $embedded, $aspect_height, $aspect_width, $game_description, $game_link);
    }
    else
    {
        trigger_error('error number: ' . $conn->errno . ', error is:' . $conn->error,E_USER_ERROR);
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
        $json_result = '{"usernames":["' . $usernames . '"],'
            . '"post_time":' . $post_time . ','
            . '"game_title":"' . $game_title . '",'
            . '"embedded":' . $embedded . ','
            . '"aspect_height":' . $aspect_height . ','
            . '"aspect_width":' . $aspect_width . ','
            . '"game_description":"' . $game_description . '",'
            . '"game_link":"' . $game_link . '"}';
        echo $json_result;
    }
    $stmt->close();
}