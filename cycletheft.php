<?php
$page = new theftPage;

class theftPage
{
	# Constructs webpage
	public function __construct ()
	{
		$query = $this->getQuery ();

		include 'database.php';
		$database = new database;

		# Gets data from database based on query
		$result = $database->retrieveData ($query);

		if ($result == array()) {
			echo "No matches for your search";
		} else {
			$result = $this->reassignKeys ($result);

			$html = $this->topOfPage ($result);

			include 'html.php';
			$htmlClass = new html;

			$html .= $htmlClass->makeTable ($result);
			echo $html;
		}
	}


	private function getQuery ()
	{
		# Get the ID
		$id = false;

		# Checks for a requested item number, and if so, validates and assigns this
		if (isSet ($_GET['id'])) {
			if(!ctype_digit ($_GET['id'])) {
				echo "Invalid ID";
				die;
			}
			$id = $_GET['id'];
		}

		# Get the Location
		$location = false;
		if (isSet ($_GET['location'])) {
			if (!preg_match ('/^[a-z A-Z]{1,40}$/', $_GET['location'])) {
				echo "Use letters only for location search";
				die;
			}
			$location = $_GET['location'];
		}

		# Select everything by default
		$query  = "select id, latitude, longitude, location, status from crimes";


		# If Id, select one result
		if ($id) {
			$query  = "select * from crimes WHERE id = '{$id}'";
		}
		
		# If search, select relevant results or display error message
		if ($location) {
			$query  = "select * from crimes WHERE location LIKE '%{$location}%'";
		}
		
		return $query;

	}


        # Constructs home button and intro text
	private function topOfPage ($data)
        {
		$html  = "\n\t<ul class='navbutton'>\n\t\t<li><a href=\"/cycledata/\">Cycle Thefts</a></li>\n\t\t<li><a href=\"/cycledata/collisions.html\">Road Collisions</a></li>\n\t</ul>";

		# Creates form for searching locations
		$currentPage = $_SERVER['REQUEST_URI'];
		$html .= "\n\t<form action=\"{$currentPage}\">\n\t\tSearch:<br>\n\t\t<input type=\"text\" name=\"location\" placeholder=\"Search for a location\"<br><br><input type=\"submit\" value=\"Submit\">\n\t</form>";

		# Creates form to submit new entries to DB
		$html .= "\n\t<form action=\"/cycletheft.php\">\n\t\tSubmit a New Entry:<br>";

/*
		# New data entry form
		foreach($data[0] as $tableHeading=>$value) {
                        $html .= "\n\t\t<input type=\"text\" name=\"{$tableHeading}\" placeholder=\"{$tableHeading}\"<br>";
                }
		$html .= "<input type=\"submit\" value=\"Submit\">\n\t</form>";
*/
 		$html .= "\n\t<h1>Cycle Thefts In Cambridge</h1>";
		$html .= "\n\t<h2 class='introText'>Click \"ID\" for more info or \"Location\" for a map link</h2>\n";
                return $html;
        }


	private function reassignKeys ($crimeData)
	{
		foreach ($crimeData as $index => $row) {
			$row['id'] = "<a href=\"/cycledata/?id={$row['id']}\">{$row['id']}</a>";
			$row['location'] = "\n\t\t<a href=\"https:/\/www.openstreetmap.org/#map=18/{$row['latitude']}/{$row['longitude']}\" target=\"_blank\">{$row['location']}</a>";
			$crimeData[$index] = $row;
		}
		return $crimeData;
	}
}

?>
