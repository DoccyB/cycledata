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

	# Validates Get
	private function validate ($field, $eval)
	{
		# Create instance of APIhelper
		require_once ("app/helpers/apihelper.php");
		$apiHelper = new apiHelper;

		$result = $apiHelper->$eval ($field, $error);
		if ($error) {
			echo $error;
			die;
		}
		return $result;
	}


	private function getData ()
	{

		# Create instance of thefts model
		require_once ("app/models/theftsmodel.php");
		$theftsModel = new theftsModel ($this->database);

		# Select everything by default
		$result = $theftsModel->main ();

		# Create instance of APIhelper
		require_once ("app/helpers/apihelper.php");
		$apiHelper = new apiHelper;

		# Gets theft ID, validates it in validateNumeric function, pulls data in thefts model
		$theft = $this->validate ("theft", "validateNumeric");

		if ($theft) {
			$result = $theftsModel->theft ($theft);
		}


		# Gets page, validates it in validateNumeric function, pulls data in thefts model
		$page = $this->validate ("page", "validateNumeric");

		if ($page) {
			$result = $theftsModel->page ($page);
		}

		# Gets location, validates it in validateChars function, pulls data in thefts model
		$location = $this->validate ("location", "validateChars");

		if ($location) {
			$result = $theftsModel->location ($location);
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
