<?php

$page = new collisionPage;
class collisionPage
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

		# Get data
                $query = $this->getQuery ();
		$result = $this->database->retrieveData ($query);

                # Only run page if data retrieved
                if ($result == array()) {
                        echo "No matches for your search";
                } else {
                        $result = $this->reassignKeys ($result);

                        require_once('html.php');
                        $htmlClass = new html;

			$table = $htmlClass->makeTable ($result);
			$this->smarty->assign('table', $table);

			$this->smarty->display("collisions.tpl");
                }
	}


	private function getQuery ()
        {
		# Get the ID
                $id = false;

		# Checks for a requested item number, and if so, validates and assigns this
		if (isSet ($_GET['id'])) {
			if( !preg_match ('/^[0-9]{6}[-0-9A-Za-z]{2}[0-9]{5}$/', $_GET['id'])) {
				echo "Invalid ID";
                        	die;
                        }
	                $id = $_GET['id'];
		}


		# Assemble the query
		if ($id == FALSE) {
                	$query  = "select id, longitude, latitude, severity, vehiclesInvolved, casualties FROM collisions LIMIT 9300, 300";
                } else {
                      $query  = "select * from collisions WHERE id = '{$id}'";
               }
		return $query;
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
			$row['id'] = "<a href=\"/cycledata/collisions.html?id={$row['id']}\">{$row['id']}</a>";
			$row['location'] = "\n\t\t<a href=\"https:/\/www.openstreetmap.org/#map=18/{$row['latitude']}/{$row['longitude']}\" target=\"_blank\">Location</a>";
			$collisionData[$index] = $row;
                }
		return $collisionData;
        }
}
?>
