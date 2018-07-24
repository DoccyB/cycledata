<?php

$controller = new controller;
class controller
{

	private $modules = array("thefts", "collisions");

	public function __construct ()
	{
		# Create an instance of Smarty
		require_once ('libraries/smarty/libs/Smarty.class.php');
		$smarty = new Smarty();
		$smarty->setTemplateDir('app/views/');
		$smarty->setCompileDir('/var/www/html/smarty/templates_c/');
		$smarty->setConfigDir('/var/www/html/smarty/configs/');
		$smarty->setCacheDir('/var/www/html/smarty/cache/');

		# Create an instance of database class
		require_once ("database.php");
		$database = new database ("cycletheft");

		# Get module query
		$module = false;
                if (isSet ($_GET['module'])) {
                        $module = $_GET['module'];
                }

		if (!in_array($module, $this->modules, true)) {
			echo "There is no page called {$module}";
			die;
		}

		# Open web page based on query
		require_once ("app/controllers/{$module}.php");
		new $module ($smarty, $database);

	}
}
?>
