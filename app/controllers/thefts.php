<?php

class thefts
{
	private $smarty;
	private $database;

	# Constructs webpage
	public function __construct ($smarty, $database)
	{
		# Assign libraries to class variables
		$this->smarty = $smarty;
		$this->database = $database;

		# Get data
		$result = $this->getData ();

		# Only run page if data retrieved
		if ($result == array()) {
			echo "No matches for your search";
		} else {
			$result = $this->reassignKeys ($result);

			$this->assignSmartyVariables ($result);

			require_once ('app/helpers/html.php');
			$htmlClass = new html;

			$table = $htmlClass->makeTable ($result);
			$this->smarty->assign ('table', $table);

			$this->smarty->display ("cycletheft.tpl");
		}
	}


	private function getData ()
	{

		# Create instance of thefts model
		require_once ("app/models/theftsmodel.php");
		$theftsModel = new theftsModel ($this->database);

		# Select everything by default
		$result = $theftsModel->main ();

		# Gets ID, checks for a requested item number, and if so, validates and assigns this
		$theft = false;
		if (isSet ($_GET['theft'])) {
			if(!ctype_digit ($_GET['theft'])) {
				echo "Invalid ID";
				die;
			}
			$theft = $_GET['theft'];
			$result = $theftsModel->theft ($theft);
		}

		# Get the Location
		$location = false;
		if (isSet ($_GET['location'])) {
			if (!preg_match ('/^[a-z A-Z]{1,40}$/', $_GET['location'])) {
				echo "Use letters only for location search";
				die;
			}
			$location = $_GET['location'];
			$result = $theftsModel->location ($location);
		}

		# Get the page number
		$page = false;
		if (isSet ($_GET['page'])) {
			if(!ctype_digit ($_GET['page'])) {
				echo  "Page number must be a NUMBER!";
				die;
			}
			$page = $_GET['page'];
			$result = $theftsModel->page ($page);
		}

		# Execute form submitted for new entry
		if ($_POST) {
			$this->database->newRow("crimes", $_POST);
		}

		return $result;
	}


	private function assignSmartyVariables ($data)
        {

		# Assign current URL
		$currentUrl = $_SERVER['REQUEST_URI'];
		$this->smarty->assign ('currentUrl', $currentUrl);


		# Assign array of table headings => heading description
		$headings = $this->database->getHeadings ("crimes");
		$this->smarty->assign ('headings', $headings);


		# Get number of data pages needed
		$countEntries = $this->database->retrieveOneValue ("SELECT count(*) from crimes");
		$finalPage = ceil ($countEntries / 10);

		# If page number requested, assign values to current, next, previous page
		if (isSet ($_GET['page'])) {
			$currentPage = $_GET['page'];
		} else {
			$currentPage = 1;
		}
		$previousPage = $currentPage - 1;
		$nextPage = $currentPage + 1;

		$pagination = array (
			'currentPage' => $currentPage,
			'finalPage' => $finalPage,
			'nextPage' => $nextPage,
			'previousPage' => $previousPage,
		);
		$this->smarty->assign ('pagination', $pagination);

        }


	private function reassignKeys ($crimeData)
	{
		foreach ($crimeData as $index => $row) {
			$row['id'] = "<a href=\"/cycledata/{$row['id']}/\">{$row['id']}</a>";
			$row['location'] = "<a href=\"https:/\/www.openstreetmap.org/#map=18/{$row['latitude']}/{$row['longitude']}\" target=\"_blank\">{$row['location']}</a>";
			$crimeData[$index] = $row;
		}
		return $crimeData;
	}
}

?>
