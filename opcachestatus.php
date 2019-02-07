<?php

//////////////////////////////
// OpCache FrontEnd Status  //
// written by dev101        //
// version 0.0.1            //
//////////////////////////////

// RESET CACHE
if (isset($_POST['opcache_reset'])) {
	opcache_reset();
	header('Location: ' . 'opcachestatus.php');
}

?>
<!DOCTYPE html>
<html>
	<head>
<title>PHP OpCache Statistics</title>
<style>
*{
text-align:left;
margin:0;
padding:0;
}
table{
width:100%;
}
table,th,td{
padding:3px;
background-color:#F5F5F5;
}
table td{
border:1px solid #DCDCDC;
}

table.scripts{
white-space:nowrap;
}
table.scripts tr{
}
table.scripts td{
}

table.scripts th.numb{
white-space:nowrap;
}
table.scripts th.path{
max-width:625px;
overflow:auto;
}
table.scripts th.hits{
text-align:right;
white-space:nowrap;
}
table.scripts th.memo{
text-align:right;
white-space:nowrap;
}
table.scripts th.date{
text-align:right;
white-space:nowrap;
}

table.scripts td.numb{
white-space:nowrap;
}
table.scripts td.path{
max-width:625px;
overflow:auto;
}
table.scripts td.hits{
text-align:right;
white-space:nowrap;
}
table.scripts td.memo{
text-align:right;
white-space:nowrap;
}
table.scripts td.date{
text-align:right;
white-space:nowrap;
}

div#fixed-window-wrapper{
position:fixed;
margin:0;
padding:5px;
bottom:5px;
right:5px;
z-index:2;
opacity:0.5;
box-sizing:border-box;
border:1px solid #CCC;
background-color:#EEE;
}
div#fixed-window-wrapper:hover{
opacity:1.0;
}
div#fixed-window-wrapper a{
display:block;
margin:2px 0;
padding:2px;
background-color:#DDD;
text-decoration:none;
}
div#fixed-window-wrapper div#quick-links{
font-size:14px;
margin-bottom:5px;
}
div#fixed-window-wrapper div#reset-cache{
}
</style>
	</head>
	<body>

<?php

##################
# DATA RETRIEVAL #
##################

// get status
$status = opcache_get_status();

// get config
$config = opcache_get_configuration();

#######################
# DATA PRE-PROCESSING #
#######################

if(!$status) {
	echo 'OPCode Caching is not enabled on this system.';
} else {

	// array debug
	//echo '<pre>';
	//print_r($status);
	//echo '</pre>';

	// array debug
	//echo '<pre>';
	//print_r($config);
	//echo '</pre>';

# from $status

	// get status
	if($status['opcache_enabled'] == 1) {
		$opcacheStatus = 'OPCACHE IS FULLY OPERATIONAL';
	}

	// get memory info
	$usedMemory    = round(($status['memory_usage']['used_memory'] / (1024 * 1024) ), 3);
	$freeMemory    = round(($status['memory_usage']['free_memory'] / (1024 * 1024) ), 3);
	$wastedMemory  = round(($status['memory_usage']['wasted_memory'] / (1024 * 1024) ), 3);
	$wastedMemoryP = round(($status['memory_usage']['current_wasted_percentage']), 3);

	// get statistics
	$opcacheStatistics = $status['opcache_statistics'];

	// get scripts
	$opcacheScripts = array();
	if (isset($status['scripts']) && $status['scripts'] != '') {
		$opcacheScripts = $status['scripts'];
	}

# from $config

	// get configuration directives
	$opcacheDirectives = $config['directives'];

	// get product
	$opcacheProduct = $config['version']['opcache_product_name'];

	// get version
	$opcacheVersion = $config['version']['version'];

	// get blacklist
	$opcacheBlacklist = $config['blacklist'];

################
# DATA DISPLAY #
################

// Open Wrapper
echo '<div style="width:100%; max-width:1000px; margin:0 auto;">';

// Main Title
echo '<table id="main">';
	echo '<thead>';
		echo '<tr>';
			echo '<th colspan="3"><span style="color:#F40;">' . $opcacheStatus . '</span></th>';
		echo '</tr>';
			echo '<tr>';
				echo '<th>Product</th>';
				echo '<th>Version</th>';
			echo '</tr>';
		echo '<tr>';
			echo '<td width="50%">' . $opcacheProduct . '</span></td>';
			echo '<td width="50%">' . 'version:' . ' ' . $opcacheVersion . '</span></td>';
		echo '</tr>';
	echo '</thead>';
echo '</table>';

if($status) {

	echo '<br/><br/>';

	// Memory
	echo '<table id="memory">';
		echo '<thead>';
			echo '<tr>';
				echo '<th colspan="3"><span style="color:#F40;">' . 'MEMORY USAGE' . '</span></th>';
			echo '</tr>';
			echo '<tr>';
				echo '<th>Parameter</th>';
				echo '<th>Value</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			echo '<tr>';
				echo '<td width="50%">Used Memory</th>';
				echo '<td width="50%">' . $usedMemory . ' ' . 'MB' . '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%">Free Memory</th>';
				echo '<td width="50%">' . $freeMemory . ' ' . 'MB' . '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%">Wasted Memory</th>';
				echo '<td width="50%">' . $wastedMemory . ' ' . 'MB' . '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%">Wasted Memory [%]</td>';
				echo '<td width="50%">' . $wastedMemoryP . ' ' . '%' . '</td>';
			echo '</tr>';
		echo '</tbody>';
	echo '</table>';

	echo '<br/><br/>';

	// OpCache Hit/Miss Ratio
	echo '<table id="hit-miss">';
		echo '<thead>';
			echo '<tr>';
				echo '<th colspan="3"><span style="color:#F40;">' . 'OPCACHE HIT/MISS RATIO' . '</span></th>';
			echo '</tr>';
			echo '<tr>';
				echo '<th>Parameter</th>';
				echo '<th>Value</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			echo '<tr>';
				echo '<td width="50%">Hit/Miss ratio</td>';
				echo '<td width="50%">' . round(($opcacheStatistics['hits']/($opcacheStatistics['hits']+$opcacheStatistics['misses'])), 3)*100 . ' ' . '%' . '</td>';
			echo '</tr>';
		echo '</tbody>';
	echo '</table>';

	echo '<br/><br/>';

	// OpCache Stats
	echo '<table id="statistics">';
		echo '<thead>';
			echo '<tr>';
				echo '<th colspan="3"><span style="color:#F40;">' . 'OPCACHE STATISTICS' . '</span></th>';
			echo '</tr>';
			echo '<tr>';
				echo '<th>Parameter</th>';
				echo '<th>Value</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			foreach ($opcacheStatistics as $k => $v) {
				echo '<tr>';
					echo '<td width="50%">' . $k . '</td>';
					echo '<td width="50%">' . $v . '</td>';
				echo '</tr>';
			}
		echo '</tbody>';
	echo '</table>';

	echo '<br/><br/>';

	// OpCache Directives
	echo '<table id="directives">';
		echo '<thead>';
			echo '<tr>';
				echo '<th colspan="3"><span style="color:#F40;">' . 'OPCACHE DIRECTIVES' . '</span></th>';
			echo '</tr>';
			echo '<tr>';
				echo '<th>Parameter</th>';
				echo '<th>Value</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			foreach ($opcacheDirectives as $k => $v) {
				echo '<tr>';
					echo '<td width="50%">' . $k . '</td>';
					echo '<td width="50%">' . $v . '</td>';
				echo '</tr>';
			}
		echo '</tbody>';
	echo '</table>';

	echo '<br/><br/>';

	// OpCache Scripts
	echo '<table id="scripts" class="scripts">';
		echo '<thead>';
			echo '<tr>';
				echo '<th colspan="6"><span style="color:#F40;">' . 'OPCACHE SCRIPTS' . '</span></th>';
			echo '</tr>';
			echo '<tr>';
				echo '<th class="numb">N</th>';
				echo '<th class="path">Full Path</th>';
				echo '<th class="hits">Hits</th>';
				echo '<th class="memo">Memory [KB]</th>';
				echo '<th class="date">Last Used</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			$i = 1;
			foreach ($opcacheScripts as $key) {
				echo '<tr>';
					echo '<td class="numb">' . $i . '</td>';
					echo '<td class="path">' . $key['full_path'] . '</td>';
					echo '<td class="hits">' . $key['hits'] . '</td>';
					echo '<td class="memo">' . round(($key['memory_consumption'] / 1024), 2) . '</td>';
					echo '<td class="date">' . $key['last_used'] . '</td>';
				echo '</tr>';
				$i++;
			}
		echo '</tbody>';
	echo '</table>';

	if ($opcacheBlacklist) {
		echo '<br/><br/>';

		// OpCache Blacklist
		echo '<table id="blacklist">';
			echo '<thead>';
				echo '<tr>';
					echo '<th colspan="3"><span style="color:#F40;">' . 'OPCACHE BLACKLIST' . '</span></th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
				foreach ($opcacheBlacklist as $b) {
					echo '<tr>';
						echo '<td>' . $b . '</td>';
					echo '</tr>';
				}
			echo '</tbody>';
		echo '</table>';
	}

}

echo '<br/>';
?>
<div id="fixed-window-wrapper">
	<!-- Quick Jump Links -->
	<div id="quick-links">
	<p><a href="#main">OpCache Main</a></p>
	<p><a href="#memory">OpCache Memory</a></p>
	<p><a href="#hit-miss">OpCache Hit&#47;Miss</a></p>
	<p><a href="#statistics">OpCache Statistics</a></p>
	<p><a href="#directives">OpCache Directives</a></p>
	<p><a href="#scripts">OpCache Scripts</a></p>
	<?php if ($opcacheBlacklist) { ?>
	<p><a href="#blacklist">OpCache Blacklist</a></p>
	<?php } ?>
	</div>
	<!-- Reset Cache Button -->
	<div id="reset-cache">
	<form name="opcache_reset" id="opcache_reset" action="opcachestatus.php" method="post">
		<input type="hidden" name="opcache_reset" value="opcache_reset"/>
		<button type="submit" style="padding:8px 10px;">RESET CACHE</button>
	</form>
	</div>
</div>
<?php
	// Close Wrapper
	echo '</div>';
}
?>
	</body>
</html>
