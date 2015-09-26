<?php

require_once 'service.php';

class UserService extends SchemaGamesService
{
    public function run()
    {
        $sql = 'SELECT user_id, username FROM users';
        $resultSet = $this->query($sql);
        $this->render($resultSet);
    }
}

//
// RUN THE SERVICE 
//
$service = new UserService();
$service->run();