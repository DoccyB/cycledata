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



	{$navBar}

	{$locationSearch}


{* PAGE BAR *}
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

	{$map}

	{$newEntry}
</body>
</html>
