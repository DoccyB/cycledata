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

{* FILTERS *}
	<form action="{$currentUrl}" class='filter'>
		<h3>Filter:</h2>
		<p>Month:</p>
		<select name="month">
			<option value="january">January</option>
			<option value="february">February</option>
			<option value="april">April</option>
		</select> <br>
		<p>Police Force:</p>
		<select name="force">
			<option value="avonAndSomerset">Avon and Somerset Constabulary</option>
			<option value="bedfordshire">Bedfordshire Police</option>
			<option value="britishTransportPolice">British Transport Police</option>
			<option value="cabridgeshire">Cambridgeshire Constabulary</option>
			<option value="cheshire">Cheshire Constabulary</option>
			<option value="cityOfLondon">City of London Police</option>
			<option value="cleveland">Cleveland Police</option>
			<option value="cumbria">Cumbria Constabulary</option>
			<option value="derbyshire">Derbyshire Constabulary</option>
			<option value="devonAndCornwall">Devon & Cornwall Police</option>
			<option value="dorset">Dorset Police</option>
			<option value="durham">Durham Constabulary</option>
			<option value="dyfedPowys">Dyfed-Powys Police</option>
			<option value="essex">Essex Police</option>
			<option value="gloucestershire">Gloucestershire Constabulary</option>
			<option value="manchester">Greater Manchester Police</option>
			<option value="gwent">Gwent Police</option>
			<option value="hampshire">Hampshire Constabulary</option>
			<option value="hertfordshire">Hertfordshire Constabulary</option>
			<option value="humberside">Humberside Police</option>
			<option value="kent">Kent Police</option>
			<option value="lancashire">Lancashire Constabulary</option>
			<option value="leicestershire">Leicestershire Police</option>
			<option value="lincolnshire">Lincolnshire Police</option>
			<option value="merseyside">Merseyside Police</option>
			<option value="metropolitan">Metropolitan Police Service</option>
			<option value="norfolk">Norfolk Constabulary</option>
			<option value="northWales">North Wales Police</option>
			<option value="northYorkshire">North Yorkshire Police</option>
			<option value="northamptonshire">Northamptonshire Police</option>
			<option value="northumbria">Northumbria Police</option>
			<option value="nottinghamshire">Nottinghamshire Police</option>
			<option value="northernIreland">Police Service of Northern Ireland</option>
			<option value="southWales">South Wales Police</option>
			<option value="southYorkshire">South Yorkshire Police</option>
			<option value="staffordshire">Staffordshire Police</option>
			<option value="suffolk">Suffolk Constabulary</option>
			<option value="surrey">Surrey Police</option>
			<option value="sussex">Sussex Police</option>
			<option value="thamesValley">Thames Valley Police</option>
			<option value="warwickshire">Warwickshire Police</option>
			<option value="westMercia">West Mercia Police</option>
			<option value="westMidlands">West Midlands Police</option>
			<option value="westYorkshire">West Yorkshire Police</option>
			<option value="wiltshire">Wiltshire Police</option>

		</select><br>
		<p>Crime Status</p>
		<select name="status">
			<option value="underInvestigation">Under investigation</option>
			<option value="IdFailed">Investigation complete; no suspect identified</option>
			<option value="unableToProsecute">Unable to prosecute suspect</option>
			<option value="localResolution">Local resolution</option>
			<option value="awaitingOutcome">Awaiting court outcome</option>
			<option value="notGuilty">Defendant found not guilty</option>
			<option value="fined">Offender fined</option>
			<option value="communitySentence">Offender given community sentence</option>
			<option value="noFormalAction">Formal action is not in the public interest</option>
		</select><br><br>
		<input type="submit" value="Go!">
	</form>


{* MAP *}
	<div id="map"></div>

</body>
</html>
