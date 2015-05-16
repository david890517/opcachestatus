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
			}
			table,th,td{
				padding:3px;
				background-color:#F5F5F5;
			}
			table td{
				border:1px solid #DCDCDC;
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
	$opcacheScripts = $status['scripts'];

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

// Wrapper
echo '<div style="width:100%; max-width:1000px; margin:0 auto;">';

// Main Title
echo '<table width="100%">';
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
	echo '<table width="100%">';
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
	echo '<table width="100%">';
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
	echo '<table width="100%">';
		echo '<thead>';
			echo '<tr>';
				echo '<th colspan="3"><span style="color:#F40;">' . 'OPCACHE STATS' . '</span></th>';
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
	echo '<table width="100%">';
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
	echo '<table width="100%">';
		echo '<thead>';
			echo '<tr>';
				echo '<th colspan="6"><span style="color:#F40;">' . 'OPCACHE SCRIPTS' . '</span></th>';
			echo '</tr>';
			echo '<tr>';
				echo '<th>Full Path</th>';
				echo '<th>Hits</th>';
				echo '<th>Memory [KB]</th>';
				echo '<th>Last Used</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
			foreach ($opcacheScripts as $key) {
				echo '<tr>';
					echo '<td>' . $key['full_path'] . '</td>';
					echo '<td>' . $key['hits'] . '</td>';
					echo '<td>' . round(($key['memory_consumption'] / 1024), 2) . '</td>';
					echo '<td>' . $key['last_used'] . '</td>';
				echo '</tr>';
			}
		echo '</tbody>';
	echo '</table>';

	if ($opcacheBlacklist) {
		echo '<br/><br/>';

		// OpCache Blacklist
		echo '<table width="100%">';
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

<form name="opcache_reset" id="opcache_reset" action="opcachestatus.php" method="post">
	<input type="hidden" name="opcache_reset" value="opcache_reset"/>
	<button type="submit" style="padding:8px 10px;">RESET CACHE</button>
</form>

	</body>
</html>

<?php

// Close Wrapper
echo '</div>';

}

?>
