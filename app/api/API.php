<?php

class API
{
	public function __construct ($database)
	{
		# retrieve data
		$query = $this->bboxQuery ();
		$data = $database->retrieveData ($query);
		$this->convertJson ($data);
	}

	private function bboxQuery ()
	{
                if (isSet ($_GET['bbox'])) {
			if (!preg_match ('/^([-0-9.]+),([-0-9.]+),([-0-9.]+),([-0-9.]+)$/', $_GET['bbox'], $coords)) {
				echo "Invalid bbox";
				die;
			}
                }

		$query = "SELECT *
			FROM crimes WHERE
			longitude > {$coords[1]} AND
			longitude < {$coords[3]} AND
			latitude  > {$coords[2]} AND
			latitude  < {$coords[4]}
			LIMIT 50
		;";
		return $query;
	}


	private function convertJson ($data)
	{
		$features = array();
		foreach ($data as $point) {

			# add each feature
			$features[] = array(
				"type" => "Feature",
				"properties" => $point,
				"geometry" => array(
					"type" => "Point",
					"coordinates" => array(floatval($point['longitude']), floatval($point['latitude']))
				)
			);
		}

		$geojson = array(
			"type" => "FeatureCollection",
			"count" => count ($features),
			"features" => $features
		);

		header('Content-Type: application/json');
		echo json_encode ($geojson, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
	}
}

?>
