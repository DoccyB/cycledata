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
        {literal}<style type="text/css">#map {width: 100%; height: 400px;}</style>{/literal}
</head>
<body>
{* MAIN PAGE NAVIGATION BAR *}
        <ul class='navbutton'>
                <li><a href="/cycledata/">Cycle Thefts</a></li>
                <li><a href="/cycledata/collisions.html">Road Collisions</a></li>
        </ul><br>

{* PAGE TITLE *}
        <h1>Road Collisions</h1>

{* PAGE INTRO *}
        <h2 class='introText'>Click "ID" for more info or "Location" for a map link</h2>

{* MAP *}
	<div id="map"></div>

{* TABLE *}
	{$table}

</body>
</html>