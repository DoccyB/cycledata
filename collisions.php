<?php

class collisions
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

                        require_once ('html.php');
                        $htmlClass = new html;

			$table = $htmlClass->makeTable ($result);
			$this->smarty->assign ('table', $table);

			$this->smarty->display ("collisions.tpl");
                }
	}


	private function getData ()
        {

                # Create instance of collisions model
                require_once ("collisionsmodel.php");
                $collisionsModel = new collisionsModel ($this->database);

                # Select everything by default
                $result = $collisionsModel->main ();

		# Gets ID, checks for a requested item number, and if so, validates and assigns this
                $id = false;
		if (isSet ($_GET['id'])) {
			if( !preg_match ('/^[0-9]{6}[-0-9A-Za-z]{2}[0-9]{5}$/', $_GET['id'])) {
				echo "Invalid ID";
                        	die;
                        }
	                $id = $_GET['id'];
			$result = $collisionsModel->id ($id);
		}

                # Get the page number
                $page = false;
                if (isSet ($_GET['page'])) {
                        if(!ctype_digit ($_GET['page'])) {
                                echo  "Page number must be a NUMBER!";
                                die;
                        }
                        $page = $_GET['page'];
			$result = $collisionsModel->page ($page);
                }

		return $result;
	}


	private function assignSmartyVariables ($data)
	{

            # Assign current URL
            $currentUrl = $_SERVER['REQUEST_URI'];
            $this->smarty->assign ('currentUrl', $currentUrl);


            # Assign array of table headings => heading description
            $headings = $this->database->getHeadings ("collisions");
            $this->smarty->assign ('headings', $headings);


            # Get number of data pages needed
            $countEntries = $this->database->retrieveOneValue ("SELECT count(*) from collisions");
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


	private function reassignKeys ($collisionData)
        {
		foreach ($collisionData as $index => $row) {
			$row['id'] = "<a href=\"/cycledata/collisions/{$row['id']}/\">{$row['id']}</a>";
			$row['location'] = "\n\t\t<a href=\"https:/\/www.openstreetmap.org/#map=18/{$row['latitude']}/{$row['longitude']}\" target=\"_blank\">Location</a>";
			$collisionData[$index] = $row;
                }
		return $collisionData;
        }
}
?>
