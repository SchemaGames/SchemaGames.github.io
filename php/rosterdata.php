<?php

require_once 'service.php';

class RosterService extends SchemaGamesService
{
    public function run()
    {
        $sql = <<<'SQL'
SELECT
	nickname,
	fullname,
	title,
	ports.portrait_file
FROM users
	INNER JOIN portraits AS ports
		ON users.default_portrait = ports.portrait_id
SQL;
        $resultSet = $this->query($sql);
        $this->render($resultSet);
    }
}

//
// RUN THE SERVICE 
//
$service = new RosterService();
$service->run();