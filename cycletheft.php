<?php

$page = new theftPage;
class theftPage
{
	private $smarty;
	private $database;

	# Constructs webpage
	public function __construct ()
	{
		require_once('libraries/smarty/libs/Smarty.class.php');
		$this->smarty = new Smarty();
		$this->smarty->setTemplateDir('templates/');
		$this->smarty->setCompileDir('/var/www/html/smarty/templates_c/');
		$this->smarty->setConfigDir('/var/www/html/smarty/configs/');
		$this->smarty->setCacheDir('/var/www/html/smarty/cache/');

		require_once ('database.php');
		$this->database = new database ("cycletheft");

		$query = $this->getQuery ();
		$result = $this->database->retrieveData ($query);

		if ($result == array()) {
			echo "No matches for your search";
		} else {
			$result = $this->reassignKeys ($result);

			$this->topOfPage ($result);

			require_once('html.php');
			$htmlClass = new html;

			$table = $htmlClass->makeTable ($result);
			$this->smarty->assign ('table', $table);

			$this->smarty->display("cycletheft.tpl");
		}
	}


	private function getQuery ()
	{

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
			$this->database->newRow("crimes", $_POST);
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
		# Creates main page navigation bar
		$navBar  = "<ul class='navbutton'>\n\t\t<li><a href=\"/cycledata/\">Cycle Thefts</a></li>\n\t\t<li><a href=\"/cycledata/collisions.html\">Road Collisions</a></li>\n\t</ul><br>";
		$this->smarty->assign ('navBar', $navBar);

		# Creates form for searching locations
		$currentURL = $_SERVER['REQUEST_URI'];
		$locationSearch  = "<form action=\"{$currentURL}\">\n\t\tSearch:<br>\n\t\t<input type=\"text\" name=\"location\" placeholder=\"Search for a location\"<br><br><input type=\"submit\" value=\"Search\">\n\t</form>";
		$this->smarty->assign ('locationSearch', $locationSearch);


		# Creates form to submit new entries to DB
		$newEntry = "<form action=\"{$currentURL}\" method=\"post\">\n\t<table id=\"newEntryForm\" style='width:80%'>\n\t<p>Submit a New Entry:</p>\n\t</tr>\n\t";

		# Loops through headings and creates input value for form
		$headings = $this->database->getHeadings ("crimes");
         	foreach ($headings as $heading => $comment) {
			$newEntry .= "<tr>\n\t\t<td>$comment: </td><td><input type=\"text\" name=\"{$heading}\" placeholder=\"{$heading}\"></td>\n\t</tr>\n\t";
		}
		$newEntry .= "\n\t</table>\n\t<p><input type=\"submit\" value=\"Submit\"></p>\n\t</form>";
		$this->smarty->assign ('newEntry', $newEntry);


		# Creates data page navigation bar
		$countEntries = $this->database->retrieveOneValue ("SELECT count(*) from crimes");
		$finalPage = ceil ($countEntries / 10);
		if (isSet ($_GET['page'])) {
			$currentPage = $_GET['page'];
		} else {
			$currentPage = 1;
		}
		$previousPage = $currentPage - 1;
		$nextPage = $currentPage + 1;

		$pagination = array (
			'finalPage' => $finalPage,
			'nextPage' => $nextPage,
			'previousPage' => $previousPage,
		);
		$this->smarty->assign ('pagination', $pagination);


		# Creates title and intro
 		$pageTitle = "<h1>Cycle Thefts In Cambridge</h1>";
		$this->smarty->assign ('pageTitle', $pageTitle);
		$pageIntro = "<h2 class='introText'>Click \"ID\" for more info or \"Location\" for a map link</h2>";
		$this->smarty->assign ('pageIntro', $pageIntro);
        }


	private function reassignKeys ($crimeData)
	{
		foreach ($crimeData as $index => $row) {
			$row['id'] = "<a href=\"/cycledata/{$row['id']}/\">{$row['id']}</a>";
			$row['location'] = "\n\t\t<a href=\"https:/\/www.openstreetmap.org/#map=18/{$row['latitude']}/{$row['longitude']}\" target=\"_blank\">{$row['location']}</a>";
			$crimeData[$index] = $row;
		}
		return $crimeData;
	}
}

?>
