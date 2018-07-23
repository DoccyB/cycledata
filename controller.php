<?php

$controller = new controller;
class controller
{
	public function __construct ()
	{
		# Create an instance of Smarty
		require_once ('libraries/smarty/libs/Smarty.class.php');
		$smarty = new Smarty();
		$smarty->setTemplateDir('templates/');
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

		# Open web page based on query
		if ($module == "thefts") {
			require_once ("thefts.php");
			new thefts ($smarty, $database);
		} elseif ($module == "collisions") {
			require_once ("collisions.php");
			new collisions ($smarty, $database);
		}

	}
}
?>
