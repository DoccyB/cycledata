<?php

# Class which handles database interactions
class database
{
	private $pdo;

	public function __construct ($dbName)
	{
		# connect to DB
		include '.config.php';
		$this->pdo = new PDO ("mysql:host=localhost;dbname={$dbName}", "root", $password);

	}

	# Constructs a table from dataset with limited info
	public function retrieveData ($query)
	{


		# retrieve data
		$data = $this->pdo->query ($query, PDO::FETCH_ASSOC);

		$result = array();

		if ($data) {
			foreach ($data as $row)
			{
				$result[] = $row;
			}
		}
		return $result;
	}

	public function retrieveOneValue ($query)
	{
		$data = $this->retrieveData ($query);

		foreach ($data[0] as $key => $value) {
                        return $value;
                }
	}

	 public function retrieveFirst ($query)
        {
                $data = $this->retrieveData ($query);
		return $data[0];
	}

	public function getHeadings ($table)
	{
		$fullFields = $this->retrieveData("SHOW full fields FROM {$table}");

		$headings = array();
		foreach ($fullFields as $column) {
			$field = $column["Field"];
			$comment = $column["Comment"];
                	$headings[$field] = $comment;
   	        }
		return $headings;

	}

	public function newRow ($table, $values)
	{
/*
		$stmt = $this->pdo->prepare("INSERT INTO {$table} (heading, value) VALUES (:heading, :value)");
		foreach($values as $heading => $value) {
			$stmt->bindparam('heading', $h);
			$stmt->bindparam('value', $v);

			$h = $heading;
			$v = $value;
			$stmt->execute();
		}
*/


		foreach ($values as $key => $value) {
			$values[$key] = str_replace ("'", "\'", $value);
		}

		$newCols = "(";
		$newCols .= implode(", ", array_keys($values));
		$newCols .= ")";
		$newVals = "('";
		$newVals .= implode("', '", $values);
		$newVals .= "')";

		$query = "INSERT INTO {$table} {$newCols} VALUES {$newVals}";
		echo $query;
		$this->pdo->query ($query);

	}

}

?>
