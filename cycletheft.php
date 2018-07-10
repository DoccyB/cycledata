<?php
$page = new theftPage;

class theftPage
{
	# Constructs webpage
	public function __construct ()
	{
		# Gets data from database based on query
		include 'database.php';
		$database = new database ("cycletheft");

		$query = $this->getQuery ();
		$result = $database->retrieveData ($query);

		if ($result == array()) {
			echo "No matches for your search";
		} else {
			$result = $this->reassignKeys ($result);

			# index.html top
			$html  = "<html>\n";
			$html .= "<head>\n\t";
			$html .= "<title>CycleThefts</title>\n\n\t";
			$html .= "<link href=\"style.css\" rel=\"stylesheet\" type = \"text/css\"/>\n\t";
			$html .= "<!-- Load Leaflet.js and styles from a CDN -->\n\t";
			$html .= "<script src=\"https://unpkg.com/leaflet@1.2.0/dist/leaflet.js\"></script>\n\t";
			$html .= "<link rel=\"stylesheet\" href=\"https://unpkg.com/leaflet@1.2.0/dist/leaflet.css\"/>\n\t";
			$html .= "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/leaflet-ajax/2.1.0/leaflet.ajax.min.js\"></script>\n\t";
			$html .= "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script>\n\t";
			$html .= "<script type=\"text/javascript\" src=\"map.js\"></script>\n\t";
			$html .= "<style type=\"text/css\">#map {width: 100%; height: 600px;}</style>\n";
			$html .= "</head>\n";
			$html .= "<body>";


			$html .= $this->topOfPage ($result);

			include 'html.php';
			$htmlClass = new html;

			$html .= $htmlClass->makeTable ($result);

			# div for map
			$html .= "<div id=\"map\"></div>";

			# index.html bottom
			$html .= "\n</body>\n</html>";


			echo $html;
		}
	}


	private function getQuery ()
	{

		$database = new database ("cycletheft");
		# Gets ID, Checks for a requested item number, and if so, validates and assigns this
		$id = false;
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

		# Get the page number
		$page = false;
		if (isSet ($_GET['page'])) {
			if(!ctype_digit ($_GET['page'])) {
				echo  "Page number must be a NUMBER!";
				die;
			}
			$page = $_GET['page'];
		}

		# Execute form submitted for new entry
		if ($_POST) {
			$database->newRow("crimes", $_POST);
		}

		# Select everything by default
		$query  = "select id, latitude, longitude, location, status from crimes LIMIT 10";



		# If Id, select one result
		if ($id) {
			$query  = "select * from crimes WHERE id = '{$id}'";
		}

		# If search, select relevant results or display error message
		if ($location) {
			$query  = "select * from crimes WHERE location LIKE '%{$location}%'";
		}

		# If page number, select relevant results
		$pageOffset = $page * 10 - 10;
		if ($page) {
			$query  = "select id, latitude, longitude, location, status from crimes LIMIT 10 OFFSET {$pageOffset}";
		}

		return $query;
	}


        # Constructs home button and intro text
	private function topOfPage ($data)
        {
		$database = new database ("cycletheft");

		# Creates main page navigation bar
		$html  = "\n\t<ul class='navbutton'>\n\t\t<li><a href=\"/cycledata/\">Cycle Thefts</a></li>\n\t\t<li><a href=\"/cycledata/collisions.html\">Road Collisions</a></li>\n\t</ul><br>";

		# Creates form for searching locations
		$currentURL = $_SERVER['REQUEST_URI'];
		$html .= "\n\t<form action=\"{$currentURL}\">\n\t\tSearch:<br>\n\t\t<input type=\"text\" name=\"location\" placeholder=\"Search for a location\"<br><br><input type=\"submit\" value=\"Search\">\n\t</form>";


		# Creates form to submit new entries to DB
		$html .= "\n\t<form id=\"newEntryForm\"action=\"{$currentURL}\" method=\"post\">\n\t\tSubmit a New Entry:<br>";
		# Loops through headings and creates input value for form
		$headings = $database->getHeadings ("crimes");
         	foreach ($headings as $heading => $comment) {
			$html .= "\n\t\t$comment: <input type=\"text\" name=\"{$heading}\" placeholder=\"{$heading}\"><br>";
		}
		$html .= "\n\t<input type=\"submit\" value=\"Submit\">\n\t</form>";

		# Creates data page navigation bar
		$countEntries = $database->retrieveOneValue ("SELECT count(*) from crimes");
		$finalPage = ceil ($countEntries / 10);

		if (isSet ($_GET['page'])) {
			$currentPage = $_GET['page'];
		} else {
			$currentPage = 1;
		}
		$previousPage = $currentPage - 1;
		$nextPage = $currentPage + 1;

		$html .= "\n\t<ul class=\"pageBar\">\n\t\t<li><a href=\"/cycledata/?page={$previousPage}\"><</a></li>";
		foreach (range(1, $finalPage) as $pageNumber) {
			$html .= "\n\t\t<li><a href=\"/cycledata/?page={$pageNumber}\">{$pageNumber}</a></li>";
		}
		$html .= "\n\t\t<li><a href=\"/cycledata/?page={$nextPage}\">></a></li>\n\t</ul>";

		# Creates title and intro
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
