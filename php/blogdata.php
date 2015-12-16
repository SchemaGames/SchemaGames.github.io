<?php

require_once 'service.php';

class BlogService extends SchemaGamesService
{
    public function run()
    {
        $num_articles_per_page = filter_input(INPUT_GET,'limit',FILTER_VALIDATE_INT);
        $num_articles_per_page = isset($num_articles_per_page) ?
            $num_articles_per_page :
            3;
        
        $newer = filter_input(INPUT_GET,'newer',FILTER_VALIDATE_INT);
        $older = filter_input(INPUT_GET,'older',FILTER_VALIDATE_INT);
        
        if(isset($older))
        {
            $sql = <<<'SQL'
SELECT
    article_title,
    users.nickname,
    ports.portrait_file,
    extract(epoch FROM post_time) as post_time,
    article_text
FROM articles
    INNER JOIN portraits AS ports
        USING (user_id,portrait_id)
    INNER JOIN users
        USING (user_id)
WHERE extract(epoch FROM post_time) < ?
ORDER BY post_time DESC
LIMIT ?
SQL;
            $inputTypes = array(PDO::PARAM_INT,PDO::PARAM_INT);
            $inputFields = array($older,$num_articles_per_page);
        }
        else if(isset($newer))
        {
            $sql = <<<'SQL'
SELECT
    article_title,
    users.nickname,
    ports.portrait_file,
    extract(epoch FROM post_time) as post_time,
    article_text
FROM articles
    INNER JOIN portraits AS ports
        USING (user_id,portrait_id)
    INNER JOIN users
        USING (user_id)
WHERE extract(epoch FROM post_time) > ?
ORDER BY post_time DESC
LIMIT ?
SQL;
            $inputTypes = array(PDO::PARAM_INT,PDO::PARAM_INT);
            $inputFields = array($newer,$num_articles_per_page);
        }
        else
        {
            $sql = <<<'SQL'
SELECT
    article_title,
    users.nickname,
    ports.portrait_file,
    extract(epoch FROM post_time) as post_time,
    article_text
FROM articles
    INNER JOIN portraits AS ports
        USING (user_id,portrait_id)
    INNER JOIN users
        USING (user_id)
ORDER BY post_time DESC
LIMIT ?
SQL;
            $inputTypes = array(PDO::PARAM_INT);
            $inputFields = array($num_articles_per_page);
        }

        $resultSet = $this->query($sql,$inputTypes,$inputFields);
        $this->render($resultSet);
    }
}

//
// RUN THE SERVICE 
//
$blogService = new BlogService();
$blogService->run();