<?php
$page = new collisionPage;

class collisionPage
{

	private $smarty;

        # Constructs webpage
        public function __construct ()
        {
		require_once('libraries/smarty/libs/Smarty.class.php');
                $this->smarty = new Smarty();
                $this->smarty->setTemplateDir('templates/');
                $this->smarty->setCompileDir('/var/www/html/smarty/templates_c/');
                $this->smarty->setConfigDir('/var/www/html/smarty/configs/');
                $this->smarty->setCacheDir('/var/www/html/smarty/cache/');

                $query = $this->getQuery ();

	       	include 'database.php';
		$database = new database ("cycletheft");


		$result = $database->retrieveData ($query);
		$result = $this->reassignKeys ($result);

		include 'html.php';
                $htmlClass = new html;

		$html = $htmlClass->makeTable ($result);

		$this->smarty->display("collisions.tpl");
		echo $html;
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


	# Constructs home button and intro text
	private function topOfPage ()
        {
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
