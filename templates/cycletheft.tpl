{* Smarty *}


<html>	
<head>
	<title>CycleThefts</title>

	<!-- Load Leaflet.js and styles from a CDN -->
	<script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-ajax/2.1.0/leaflet.ajax.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="/cycledata/map.js"></script>
	<link href="/cycledata/style.css" rel="stylesheet" type = "text/css"/>
	{literal}<style type="text/css">#map {width: 50%; height: 400px;}</style>{/literal}
</head>
<body>


{* MAIN PAGE NAVIGATION BAR *}
	<ul class='navbutton'>
		<li><a href="/cycledata/">Cycle Thefts</a></li>
		<li><a href="/cycledata/collisions.html">Road Collisions</a></li>
	</ul><br>


{* LOCATION SEARCH BAR *}
	<form action="{$currentUrl}">
		Search:<br>
		<input type="text" name="location" placeholder="Search for a location"><br>
		<input type="submit" value="Search">
	</form>


{* DATA PAGE NAVIGATION BAR *}
	<ul class="pageBar">
		<li><a href="/cycledata/page/{$pagination.previousPage}/"><</a></li>
	{for $pageNumber = 1 to $pagination.finalPage}
		<li><a href="/cycledata/page/{$pageNumber}/">{$pageNumber}</a></li>
        {/for}
		<li><a href="/cycledata/page/{$pagination.nextPage}/">></a></li>
	</ul>



	{$pageTitle}

	{$pageIntro}

	{$table}

	<div id="map"></div>

	{$newEntry}
</body>
</html>
