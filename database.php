<?php

# Class which handles database interactions
class database
{
	# Constructs a table from dataset with limited info
	public function retrieveData ($query)
	{
		# connect to DB
		include '.config.php';
		$dbName = 'cycletheft';
		$pdo = new PDO ("mysql:host=localhost;dbname={$dbName}", "root", $password);

		# retrieve data
		$data = $pdo->query ($query, PDO::FETCH_ASSOC);

		$result = array();

		if ($data) {
			foreach ($data as $row)
			{
				$result[] = $row;
			}
		}
		return $result;
	}
}

?>
