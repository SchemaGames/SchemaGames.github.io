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
            $sql = 'SELECT article_title, users.username, portrait_name, UNIX_TIMESTAMP(post_time) as post_time,article_text'
                . ' FROM articles INNER JOIN users ON articles.user = users.user_id WHERE UNIX_TIMESTAMP(post_time) < ?'
                . ' ORDER BY post_time DESC LIMIT ?';
            $inputTypes = array(PDO::PARAM_INT,PDO::PARAM_INT);
            $inputFields = array($older,$num_articles_per_page);
        }
        else if(isset($newer))
        {
            $sql = 'SELECT article_title, users.username, portrait_name, UNIX_TIMESTAMP(post_time) as post_time, article_text'
                . ' FROM articles INNER JOIN users ON articles.user = users.user_id WHERE UNIX_TIMESTAMP(post_time) > ?'
                . ' ORDER BY post_time DESC LIMIT ?';
            $inputTypes = array(PDO::PARAM_INT,PDO::PARAM_INT);
            $inputFields = array($newer,$num_articles_per_page);
        }
        else
        {
            $sql = 'SELECT article_title, users.username, portrait_name, UNIX_TIMESTAMP(post_time) as post_time, article_text'
                . ' FROM articles INNER JOIN users ON articles.user = users.user_id ORDER BY post_time DESC LIMIT ?';
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