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

	private function validateNumeric ($field, &$error = false)
	{
		$result = false;
		if (isSet ($_GET[$field])) {
			if(!ctype_digit ($_GET[$field])) {
				$error = "{$field} must be a number";
				return false;
			}
			$result = $_GET[$field];
		}
		return $result;
	}

	private function validateChars ($field, &$error = false)
	{
		$result = false;
		if (isSet ($_GET[$field])) {
			if (!preg_match ('/^[a-z A-Z]{1,40}$/', $_GET[$field])) {
				$error = "{$field} must consist of letters only";
				return false;
			}
			$result = $_GET[$field];
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


		# Gets theft ID, validates it in validateNumeric function, pulls data in thefts model
		$theft = $this->validateNumeric ("theft", $error);
		if ($error) {
			echo $error;
			die;
		}

		if ($theft) {
			$result = $theftsModel->theft ($theft);
		}


		# Gets page, validates it in validateNumeric function, pulls data in thefts model
		$page = $this->validateNumeric ("page", $error);
		if ($error) {
			echo $error;
			die;
		}

		if ($page) {
			$result = $theftsModel->page ($page);
		}

		# Gets location, validates it in validateChars function, pulls data in thefts model
		$location = $this->validateChars ("location", $error);
		if ($error) {
			echo $error;
			die;
		}

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
