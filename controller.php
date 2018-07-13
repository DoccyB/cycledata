<?php

$controller = new controller;
class controller
{
	public function __construct ()
	{

		require_once("cycletheft.php");
		require_once("roadcollisions.php");

		$module = false;
                if (isSet ($_GET['module'])) {
                        $module = $_GET['module'];
                }



		if ($module == "cycletheft") {
			new theftPage;
		} elseif ($module == "collisions") {
			new collisionPage;
		}

	}
}
?>
