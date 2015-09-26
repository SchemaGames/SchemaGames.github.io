<?php

require_once 'service.php';

class ThingService extends SchemaGamesService
{
    protected static $thingtypes = array(
        "writings" => 0,
        "music" => 1,
        "comics" => 2,
        "art" => 3,
        "tutorials" => 4
    );

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
                $sql = 'SELECT thing_id, thing_name, thing_type, UNIX_TIMESTAMP(post_time) as post_time, username, default_portrait as portrait'
                    . ' FROM things INNER JOIN users ON users.user_id = things.user'
                    . ' ORDER BY post_time DESC LIMIT ?';
                $inputTypes = array(PDO::PARAM_INT);
                $inputFields = array($num_things_per_page);
            }
            else
            {
                //A type is set, get a list of things of that type
                $sql = 'SELECT thing_id, thing_name, thing_type, UNIX_TIMESTAMP(post_time) as post_time, username, default_portrait as portrait'
                    . ' FROM things INNER JOIN users ON users.user_id = things.user'
                    . ' WHERE thing_type = ?'
                    . ' ORDER BY post_time DESC LIMIT ?';
                $inputTypes = array(PDO::PARAM_INT,PDO::PARAM_INT);
                $inputFields = array(self::$thingtypes[$type],$num_things_per_page);
            }
        }
        else
        {
            //A thing has been chosen by setting an id - obtain information to display it
            $sql = 'SELECT username, UNIX_TIMESTAMP(post_time) as post_time, thing_name, thing_type, content_url'
                . ' FROM things'
                . ' INNER JOIN users on users.user_id=things.user'
                . ' WHERE thing_id = ?';
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