<?php

class API
{
	public function convertJson () {
		include 'database.php';
		$database = new database;
// Basic Query for Now
		$data = $database->retrieveData ("select * from crimes LIMIT 10");

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
			"features" => $features
		);

		header('Content-Type: application/json');
		echo json_encode ($geojson, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
	}
}
$API = new API;
$API->convertJson ();

?>
