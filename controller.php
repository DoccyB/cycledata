<?php

class controller
{

	private $modules = array("thefts", "collisions", "api");

	public function __construct ($config)
	{
		# Create an instance of database class
		require_once ("app/helpers/database.php");
		$database = new database ("cycletheft", $config["host"], $config["user"], $config["password"]);

		# Get module query, die if invalid module
		$module = false;
		if (isSet ($_GET['module'])) {
			$module = $_GET['module'];
		}

		if (!in_array($module, $this->modules, true)) {
			echo "There is no page called {$module}";
			die;
		}

		# If API requested load API, else import smarty, load webpage
		if ($module == "api") {
			require_once ("app/api/API.php");
			new api ($database);
		} else {

			# Create an instance of Smarty
			require_once ('libraries/smarty/libs/Smarty.class.php');
			$smarty = new Smarty();
			$smarty->setTemplateDir('app/views/');
			$smarty->setCompileDir('/var/www/html/smarty/templates_c/');
			$smarty->setConfigDir('/var/www/html/smarty/configs/');
			$smarty->setCacheDir('/var/www/html/smarty/cache/');

			# Open web page based on query
			require_once ("app/controllers/{$module}.php");
			new $module ($smarty, $database);
		}
	}
}
?>
