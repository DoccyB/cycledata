<?php

class collisionsModel
{
        private $database;
        public function __construct ($database)
        {
                $this->database = $database;
        }


	public function main ()
	{
		$query  = "select id, longitude, latitude, severity, vehiclesInvolved, casualties FROM collisions LIMIT 9300, 300";
                $result = $this->database->retrieveData($query);
                return $result;
	}


	public function id ($id)
	{
		$query  = "select * from collisions WHERE id = '{$id}'";
                $result = $this->database->retrieveData($query);
                return $result;
	}


	public function page ($page)
	{
		$pageOffset = $page * 10 - 10;
		$query  = "select id, severity, vehiclesInvolved, casualties, longitude, latitude from collisions LIMIT 10 OFFSET {$pageOffset}";
                $result = $this->database->retrieveData($query);
		return $result;
	}


}
?>
