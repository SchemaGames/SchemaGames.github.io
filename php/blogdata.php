<?php

$num_articles_per_page = filter_input(INPUT_GET,'limit',FILTER_VALIDATE_INT);

if(!isset($num_articles_per_page)){
    $num_articles_per_page=3;
}

$newer = filter_input(INPUT_GET,'newer',FILTER_VALIDATE_INT);
$older = filter_input(INPUT_GET,'older',FILTER_VALIDATE_INT);

include 'mysqlauth.php';

//Begin by connecting to the SQL database
$conn = new mysqli($servername, $username, $password, $dbname);
//Check connection
if ($conn->connect_error) {
	trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
}
//Query the SQL database for a set of articles
if(isset($older))
{
	//Query for up to NUM_ARTICLES_PER_PAGE of the most recent articles older than the referrer's
	$sql = 'SELECT article_title, users.username, portrait_name, UNIX_TIMESTAMP(post_time) as post_time,article_text'
	    . ' FROM articles INNER JOIN users ON articles.user = users.user_id WHERE UNIX_TIMESTAMP(post_time) < ?'
	    . ' ORDER BY post_time DESC LIMIT ?';
	if($stmt = $conn->prepare($sql)){
	    $stmt->bind_param('ii', $older,$num_articles_per_page);
	    $result = $stmt->execute();
	    $stmt->bind_result($article_title, $username,$portrait_name,$post_time,$article_text);
	}
}
else if(isset($newer))
{
	//Query for up to NUM_ARTICLES_PER_PAGE of the most recent articles newer than the referrer's
	$sql = 'SELECT article_title, users.username, portrait_name, UNIX_TIMESTAMP(post_time) as post_time, article_text'
	    . ' FROM articles INNER JOIN users ON articles.user = users.user_id WHERE UNIX_TIMESTAMP(post_time) > ?'
	    . ' ORDER BY post_time DESC LIMIT ?';
	if($stmt = $conn->prepare($sql)){
    	$stmt->bind_param('ii', $newer,$num_articles_per_page);
    	$result = $stmt->execute();
    	$stmt->bind_result($article_title, $username,$portrait_name,$post_time,$article_text);
    }
}
else
{
	//No relevant options - get the NUM_ARTICLES_PER_PAGE most recent articles
	$sql = 'SELECT article_title, users.username, portrait_name, UNIX_TIMESTAMP(post_time) as post_time, article_text'
	    . ' FROM articles INNER JOIN users ON articles.user = users.user_id ORDER BY post_time DESC LIMIT ?';
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('i',$num_articles_per_page);
        $result = $stmt->execute();
        $stmt->bind_result($article_title, $username,$portrait_name,$post_time,$article_text);
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
            .'"article_title":"' . $article_title
            .'","username":"' . $username
            .'","portrait_name":"' . $portrait_name
            .'","post_time":' . $post_time
            .',"article_text":"' . $article_text
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

	//Display each article one by one
	header('Content-Type: application/json');
	$num_articles = count($json_results);
	echo("{\"total_rows\":" . $num_articles . ",\"rows\":[");
	if($num_articles === 0)
	{
	    echo "]\n}";
	}
	else
	{
        for($i = 0; $i < $num_articles -1; $i++)
        {
            echo($json_results[$i] . ',');
        }
        echo($json_results[$num_articles-1] . "]\n}");
    }
}
$stmt->close();