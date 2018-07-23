<?php

class theftsModel
{
	private $database;
	public function __construct ($database)
	{
		$this->database = $database;
	}


	public function main ()
	{
		$query  = "select id, latitude, longitude, location, status from crimes LIMIT 10";
		$result = $this->database->retrieveData($query);
		return $result;
	}


	public function id ($id)
	{
        	$query  = "select * from crimes WHERE id = '{$id}'";
		$result = $this->database->retrieveData($query);
		return $result;
	}


	public function location ($location)
	{
	        $query  = "select * from crimes WHERE location LIKE '%{$location}%'";
		$result = $this->database->retrieveData($query);
		return $result;
	}


	public function page ($page)
	{
                $pageOffset = $page * 10 - 10;
                $query  = "select id, latitude, longitude, location, status from crimes LIMIT 10 OFFSET {$pageOffset}";
		$result = $this->database->retrieveData($query);
		return $result;
	}
}
?>
