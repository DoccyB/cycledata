<?php

class thefts
{
	private $smarty;
	private $database;
	private $theftsModel;
	private $tableName = "crimes";

	# Constructs webpage
	public function __construct ($smarty, $database)
	{
		# Assign libraries to class variables
		$this->smarty = $smarty;
		$this->database = $database;

		require_once ("app/models/theftsmodel.php");
		$this->theftsModel = new theftsModel ($this->database);


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
		# Select everything by default
		$result = $this->theftsModel->main ();

		# Create instance of APIhelper
		require_once ("app/helpers/apihelper.php");
		$apiHelper = new apiHelper;

		# Array of get values and validation style
		$getVals = array (
			"theft"    => "validateNumeric",
			"page"     => "validateNumeric",
			"location" => "validateChars",
		);

		# Loops through get values, validates them, and pulls data from model
		foreach ($getVals as $field => $validation) {
			$get = $apiHelper->validate ($field, $validation);
			if ($get) {
				$result = $this->theftsModel->$field ($get);
			}
		}

		# Execute form submitted for new entry
		if ($_POST) {
			$this->database->newRow("{$this->tableName}", $_POST);
		}

		return $result;
	}


	private function assignSmartyVariables ($data)
        {

		# Assign current URL
		$currentUrl = $_SERVER['REQUEST_URI'];
		$this->smarty->assign ('currentUrl', $currentUrl);


		# Assign array of table headings => heading description
		$headings = $this->database->getHeadings ("{$this->tableName}");
		$this->smarty->assign ('headings', $headings);


		# Get number of data pages needed
		$countEntries = $this->database->retrieveOneValue ("SELECT count(*) from {$this->tableName}");
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


		# Assign array of distinct values for table headings
		$distinctDates = array_column($this->database->distinctValues ($this->tableName, "date"), "date");
		$distinctReportedBy = array_column($this->database->distinctValues ($this->tableName, "reportedBy"), "reportedBy");
		$distinctStatuses = array_column($this->database->distinctValues ($this->tableName, "status"), "status");

		$distinctValues = array (
			'date'       => $distinctDates,
			'reportedBy' => $distinctReportedBy,
			'status'     => $distinctStatuses,
		);
		$this->smarty->assign ('distinctValues', $distinctValues);

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
