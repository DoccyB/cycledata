<?php
# Class which handles database interactions
class database
{
	private $pdo;

	public function __construct ($dbName, $host, $user, $password)
	{
		# connect to DB
		$this->pdo = new PDO ("mysql:host={$host};dbname={$dbName}", $user, $password);

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


	public function distinctValues ($tableName, $field)
	{
		$query = "SELECT DISTINCT {$field} FROM {$tableName}";
		$result = $this->retrieveData ($query);
		return $result;
	}

}

?>
