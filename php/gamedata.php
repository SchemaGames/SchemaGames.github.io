<?php

require_once 'service.php';

class GameService extends SchemaGamesService
{
    public function run()
    {
        $game = filter_input(INPUT_GET,'game',FILTER_SANITIZE_STRING);

        // For game previews, load only limited information
        if(!isset($game)){

            $num_games_per_page = filter_input(INPUT_GET,'limit',FILTER_VALIDATE_INT);
            $num_games_per_page = isset($num_games_per_page) ?
                $num_games_per_page :
                9;
        
            $newer = filter_input(INPUT_GET,'newer',FILTER_VALIDATE_INT);
            $older = filter_input(INPUT_GET,'older',FILTER_VALIDATE_INT);

            if(isset($older))
            {
                $sql = <<<'SQL'
SELECT
    game_title,
    extract(epoch from post_time) as post_time,
    thumbnail_name,
    game_type
FROM games
WHERE UNIX_TIMESTAMP(post_time) < ?
ORDER BY post_time DESC
LIMIT ?
SQL;
                $inputTypes = array(PDO::PARAM_INT,PDO::PARAM_INT);
                $inputFields = array($older,$num_games_per_page);
            }
            else if(isset($newer))
            {
                $sql = <<<'SQL'
SELECT
    game_title,
    extract(epoch from post_time) as post_time,
    thumbnail_name,
    game_type
FROM games
WHERE UNIX_TIMESTAMP(post_time) > ?
ORDER BY post_time DESC
LIMIT ?
SQL;
                $inputTypes = array(PDO::PARAM_INT,PDO::PARAM_INT);
                $inputFields = array($newer,$num_games_per_page);
            }
            else
            {
                $sql = <<<'SQL'
SELECT
    game_title,
    extract(epoch from post_time) as post_time,
    thumbnail_name,
    game_type
FROM games
ORDER BY post_time DESC
LIMIT ?
SQL;
                $inputTypes = array(PDO::PARAM_INT);
                $inputFields = array($num_games_per_page);
            }
        }
        else
        {
            $sql = <<<'SQL'
SELECT
    COALESCE(group_name,nickname) as creator,
    game_title,
    extract(epoch from post_time) as post_time,
    embedded,
    aspect_height,
    aspect_width,
    game_description,
    game_link
FROM games
    INNER JOIN users
        USING (user_id)
    LEFT JOIN groups
        USING (group_id)
WHERE game_title = ?
SQL;
            $inputTypes = array(PDO::PARAM_STR);
            $inputFields = array($game);
        }

        $resultSet = $this->query($sql,$inputTypes,$inputFields);
        if(isset($game))
        {
            $this->render($resultSet,NULL,true);
        }
        else
        {
            $this->render($resultSet);
        }
    }
}

//
// RUN THE SERVICE 
//
$service = new GameService();
$service->run();