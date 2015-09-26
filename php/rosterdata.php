<?php

require_once 'service.php';

class RosterService extends SchemaGamesService
{
    public function run()
    {
        $sql = 'SELECT username, fullname, title, default_portrait FROM users';
        $resultSet = $this->query($sql);
        $this->render($resultSet);
    }
}

//
// RUN THE SERVICE 
//
$service = new RosterService();
$service->run();