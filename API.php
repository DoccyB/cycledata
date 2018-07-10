<?php

$API = new API;

class API
{
	public function __construct ()
	{
		$query = $this->bboxQuery ();
		$this->convertJson ($query);
	}

	private function bboxQuery ()
	{
                if (isSet ($_GET['bbox'])) {
			if (!preg_match ('/^([0-9.]+),([0-9.]+),([0-9.]+),([0-9.]+)$/', $_GET['bbox'], $coords)) {
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
		;";
		return $query;
	}


	private function convertJson ($query)
	{
		// deal with data retrieval in cycletheft later
		include 'database.php';
		$database = new database;
		$data = $database->retrieveData ($query);

		$features = array();
		foreach ($data as $point) {
			
			
			$features[] = array(
				"type" => "Feature",
				"properties" => array(
					'id' => $point['id'],
					'crimeId' => $point['crimeId'],
					'date' => $point['date'],
					'reportedBy' => $point['reportedBy'],
					'location' => $point['location'],
					'status' => $point['status'],
					'latitude' => $point['latitude'],
					'longitude'  => $point['longitude']

				),
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
