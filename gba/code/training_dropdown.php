<?php
require_once('Connections/drws.php');
session_start();

$playerArr = explode("_", $_GET['league_id']);
$league_id = $playerArr[0];
$player_id = $playerArr[1];

mysql_select_db($database_drws, $drws);
$query_training = sprintf("SELECT * FROM training WHERE league_id = '%s' AND season_id = %s", $league_id, '20101');
$training = mysql_query($query_training, $drws) or die(mysql_error());
$row_training = mysql_fetch_assoc($training);

$query_config = "SELECT session_limit FROM organization";
$config = mysql_query($query_config, $drws) or die(mysql_error());
$row_config = mysql_fetch_assoc($config);
								
if(!empty($row_training['league_id'])) {
	$option = "";
	do {
		$query_limit = sprintf("SELECT * FROM registration_training WHERE training_id='%s' AND `season_id` = %s", $row_training['id'], '20101');
		$limit = mysql_query($query_limit, $drws) or die(mysql_error());
		$row_limit = mysql_fetch_assoc($limit);
		$totalRows_limit = mysql_num_rows($limit);

		if($totalRows_limit < $row_config['session_limit']) {
			$spots = $row_config['session_limit'] - $totalRows_limit;
			$option .= "<option value=\"".$row_training['league_id']."_".$player_id."_".$row_training['id']."\">".$row_training['day']." ".$row_training['start_time']." - ".$row_training['end_time']." (".$spots." spots left in this session)</option>\n";
		}
	} while($row_training = mysql_fetch_assoc($training));

	if(!empty($option)) {
		echo "Please select a training session from below.<br />";
		echo "<select name =\"select_training[]\">\n";
			echo $option;
		echo "</select>\n";
	} else {
		echo "We apologize, but all training sessions for this player's league are currently full.";
	}
} else {
	echo "There are no training sessions available for this child at this time.";
}
?>