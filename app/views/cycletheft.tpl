<html>
<head>
	<title>CycleThefts</title>

	<!-- Load Leaflet.js and styles from a CDN -->
	<script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-ajax/2.1.0/leaflet.ajax.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="/cycledata/js/map.js"></script>
	<link href="/cycledata/css/style.css" rel="stylesheet" type = "text/css"/>
	{literal}<style type="text/css">#map {width: 100%; height: 400px;}</style>{/literal}
</head>
<body>


{* MAIN PAGE NAVIGATION BAR *}
	<ul class='navbutton'>
		<li><a href="/cycledata/thefts/">Cycle Theft</a></li>
		<li><a href="/cycledata/collisions/">Road Collisions</a></li>
	</ul><br>


{* LOCATION SEARCH *}
	<form action="{$currentUrl}">
		Search for a location:
		<input type="text" name="location" placeholder="e.g. mill road">
		<input type="submit" value="Go!">
	</form>


{* PAGE TITLE *}
	<h1>Cycle Thefts In Cambridge</h1>

{* PAGE INTRO *}
	<h2 class='introText'>Click "ID" for more info or "Location" for a map link</h2>

{* DATA PAGE NAVIGATION BAR *}
	<ul class="pageBar">
	{* previous page button *}
	{if $pagination.currentPage != 1}
	<li><a href="/cycledata/page/{$pagination.previousPage}/"><</a></li>
	{/if}
	{* only display pages +/- 5 to current page *}
	{if $pagination.currentPage < 7}}
	{for $pageNumber = 1 to 11}
	<li><a href="/cycledata/page/{$pageNumber}/">{$pageNumber}</a></li>
	{/for}
	{elseif $pagination.currentPage > $pagination.finalPage - 6}
	{for $pageNumber = $pagination.finalPage - 10 to $pagination.finalPage}
	<li><a href="/cycledata/page/{$pageNumber}/">{$pageNumber}</a></li>
	{/for}
	{else}
	{for $pageNumber = $pagination.currentPage - 5 to $pagination.currentPage + 5}
	<li><a href="/cycledata/page/{$pageNumber}/">{$pageNumber}</a></li>
	{/for}
	{/if}
	{* next page button *}
	{if $pagination.currentPage != $pagination.finalPage}
	<li><a href="/cycledata/page/{$pagination.nextPage}/">></a></li>
	{/if}
	</ul>

{* DATA TABLE *}
	{$table}

{* MAP *}
	<div id="map"></div>

</body>
</html>
