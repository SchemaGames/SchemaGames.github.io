<?php

require_once 'service.php';

class ThingService extends SchemaGamesService
{

    public function run()
    {
        $id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

        // For thing previews, load only limited information
        if(!isset($id))
        {
            $type = filter_input(INPUT_GET,'type',FILTER_SANITIZE_STRING);
            $num_things_per_page = filter_input(INPUT_GET,'limit',FILTER_VALIDATE_INT);
            $num_things_per_page = isset($num_things_per_page) ?
                $num_things_per_page :
                10;

            if(!isset($type))
            {
                //No type is set, so all of the things are being queried.
                $sql = <<<'SQL'
SELECT 
    thing_id,
    thing_name,
    thing_type,
    extract(epoch from post_time) as post_time,
    nickname,
    portrait_file as portrait
FROM things
    INNER JOIN users
        USING (user_id)
    INNER JOIN portraits AS ports
        ON users.default_portrait = ports.portrait_id
ORDER BY post_time DESC
LIMIT ?
SQL;
                $inputTypes = array(PDO::PARAM_INT);
                $inputFields = array($num_things_per_page);
            }
            else
            {
                //A type is set, get a list of things of that type
                $sql = <<<'SQL'
SELECT
    thing_id,
    thing_name,
    thing_type,
    extract(epoch from post_time) as post_time,
    nickname,
    portrait_file as portrait
FROM things
    INNER JOIN users
        USING (user_id)
    INNER JOIN portraits AS ports
        ON users.default_portrait = ports.portrait_id
WHERE thing_type = ?
ORDER BY post_time DESC
LIMIT ?
SQL;
                $inputTypes = array(PDO::PARAM_INT,PDO::PARAM_INT);
                $inputFields = array($type,$num_things_per_page);
            }
        }
        else
        {
            //A thing has been chosen by setting an id - obtain information to display it
            $sql = <<<'SQL'
SELECT
    thing_name,
    thing_type,
    extract(epoch from post_time) as post_time,
    nickname,
    content_url
FROM things
    INNER JOIN users
        USING (user_id)
WHERE thing_id = ?
SQL;
            $inputTypes = array(PDO::PARAM_INT);
            $inputFields = array($id);
        }

        $resultSet = $this->query($sql,$inputTypes,$inputFields);
        
        if(isset($id))
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
$service = new ThingService();
$service->run();