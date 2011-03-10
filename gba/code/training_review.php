<?php require_once('Connections/drws.php'); ?>
<?php
session_start();

if($_SESSION['family_id'] == 1433){$debug = 1; }else{ $debug = 0; }
$debug = 0;
if($debug){
	if(isset($_POST)){
		foreach($_POST as $key => $value){
			if($key == "player_select"){
				foreach($_POST['player_select'] as $key => $value){
					$player_select_list[$key] = $value;
					
					if($debug==1){echo "Key: ".$key." - ".$value."<br>";}
				}
				$total_registering_today = count($player_select_list);
			}else{
				if($debug==1){echo "Key: ".$key." - ".$value."<br>";}
				}
		}
	}
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

function testRegistrationStatus($l_id)
	{	if($debug==1){echo "League ID: ".$l_id."<br>";}
		global $database_drws, $drws;
		$query_closedreg = sprintf("SELECT * FROM league WHERE league_id = '%s'",$l_id);
		$closedreg = mysql_query($query_closedreg, $drws) or die(mysql_error());
		$row_closedreg = mysql_fetch_assoc($closedreg);
		if($debug==1){echo "Status: ".$row_closedreg['status']."<br>";}
		
		$query_numplayers = sprintf("SELECT * FROM registration WHERE league_id = '%s' AND season_id=%s",$l_id, $_SESSION['reg_season']);
		$numplayers = mysql_query($query_numplayers, $drws);
		$row_numplayers = mysql_fetch_assoc($numplayers);
		$totalRows_numplayers = mysql_num_rows($numplayers);

		if($row_closedreg['status'] == 0){
			return 0; // Returns league is closed.
		}else{
			if($totalRows_numplayers >= $row_closedreg['maxreg'])
				{
					return 0; // Returns league is at max registratiion and is closed.
				}else{
					return 1; // Returns league is open for registration.
				}
		}
	
	}

function getLeagueInfo($l_id, $field)
	{	// This function returns league information based on the league id and the field name passed into it
		global $database_drws, $drws;
		$query = sprintf("SELECT %s FROM league WHERE league_id = '%s'", $field, $l_id);
		mysql_select_db($database_drws, $drws);
		$league = mysql_query($query, $drws) or die(mysql_error());
		$row_league = mysql_fetch_assoc($league);
		$totalRows_league = mysql_num_rows($league);
		
		if($league == -2)
			return "No league for the selected birth date.";
		else
			return $row_league[$field];
	}

function findLeague($birthdate)
	{
		global $database_drws, $drws;
		// Added check to make sure league was open with a status of 1, not just the birthdate range. //
		$query3 = sprintf("SELECT league_id FROM league WHERE  mindate <= '%s'  AND '%s' <= maxdate AND status = 1 AND travel = 0 ORDER BY league_id ASC LIMIT 1", $birthdate, $birthdate);
		//echo $query3."<br/>";
		mysql_select_db($database_drws, $drws);
		$league = mysql_query($query3, $drws) or die(mysql_error());
		$row_league = mysql_fetch_assoc($league);
		$totalRows_league = mysql_num_rows($league);
		
		if($totalRows_league >= 1)
			return $row_league['league_id'];
		else 
			return -2;
	}

// Setup Seasons information //
mysql_select_db($database_drws, $drws);
$query_season = "SELECT * FROM season WHERE season_start <= CURDATE() AND CURDATE() <= season_end LIMIT 0,1";
$season = mysql_query($query_season, $drws) or die(mysql_error());
$row_season = mysql_fetch_assoc($season);
$totalRows_season = mysql_num_rows($season);

$query_family = sprintf("SELECT * FROM family WHERE family_id = '%s'", $_SESSION['family_id']);
if($debug ==1){echo $query_family."<br>";}
$family = mysql_query($query_family, $drws) or die(mysql_error());
$row_family = mysql_fetch_assoc($family);
$totalRows_family = mysql_num_rows($family);

$query = sprintf("SELECT * FROM players WHERE family_id = '%s' ORDER BY birth_date DESC", $_SESSION['family_id']);
if($debug ==1){echo $query."<br>";}
$players = mysql_query($query, $drws) or die(mysql_error());
$row_players = mysql_fetch_assoc($players);
$totalRows_players = mysql_num_rows($players);

$query_discount = "SELECT * FROM organization WHERE org_id = '1'";
if($debug ==1){echo $query_discount."<br>";}
$discount = mysql_query($query_discount, $drws) or die(mysql_error());
$row_discount = mysql_fetch_assoc($discount);

	

// Test to see if any are already registered
do{ 
	$query_registered = sprintf("SELECT season_id, player_id FROM registration_training WHERE player_id = '%s' AND season_id=%s", $row_players['player_id'], $_SESSION['reg_season']);
	if($debug ==1){echo $query_registered."<br>";}
	$registered = mysql_query($query_registered, $drws) or die(mysql_error());
	$row_registered = mysql_fetch_assoc($registered);
	$totalRows_registered = mysql_num_rows($registered);

	// Assigns players based on whether they are registered or not to the following variables used below
	if($totalRows_registered == 1){
		$already_registered[$x] = $row_registered['player_id'];
		++$x;
	}else{
		$not_registered[$y] = $row_players['player_id'];
		++$y;
	}

}while($row_players = mysql_fetch_assoc($players));
if($debug==1){echo "Already Registered: ".count($already_registered)."<br>";}
if($debug==1){echo "Not Registered: ".count($not_registered)."<br>";}


$late_start = $row_season['late_start'];
//$late_start = "2007-03-01";
$registration_closed = $row_season['late_end'];
if($debug==1){echo date('Y-m-d');}
if($debug==1){ echo "Late Start: ".$late_start."<br>"; }


	
	


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/geneva_main.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Step 3 : Select Players to Registration</title>
<!-- InstanceEndEditable -->
<link href="styles.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<script type="text/javascript" src="jquery-1.2.6.js"></script>
<script type="text/javascript">
     $(document).ready(function() {
          $("#reviewForm").submit(function() {
               var valid = true;
               $(".travelSelection").each(function() {
                    if($(this).val() == '-1') {
                         $(this).parent().parent().addClass("required");
                         if (valid) {
                              valid = false;
                         }
                    } else {
                         $(this).parent().parent().removeClass("required");
                    }
               })
$(".player_fundraiser_select").each(function() {
                    if($(this).val() == '-1') {
                         $(this).addClass("required");
                         if (valid) {
                              valid = false;
                         }
                    } else {
                         $(this).removeClass("required");
                    }
               })
               return valid;
          })
			
			$(".select_league").change(function() {
				var session = $(this)
				$.ajax({
					url: "training_dropdown.php",
					type: "GET",
					data: "league_id=" + $(this).val(),
					success: function(e) {
						session.parent().children().filter(".training_dropdown").html(e);
					}
				})
			})
     })
</script>
<style>
     .required {
          background-color: #FF0000;
     }
</style>
<!-- InstanceEndEditable -->
</head>

<body>
<div id="top_nav"><a href="index.php">home</a>&nbsp;|&nbsp;<a href="contact.php">contact us</a>&nbsp;|&nbsp;<a href="sitemap.php">sitemap</a> </div>  
<table border="0" cellspacing="0" cellpadding="0" id="main_table" align="center">
  <tr>
    <td align="left" valign="top"><a href="index.php"><img src="images/geneva_logo_1.jpg" alt="Geneva Baseball Home Page" width="152" height="110" hspace="0" vspace="0" border="0" id="geneva_logo_1" /></a><a href="index.php"><img src="images/header_1.jpg" alt="Geneva Baseball Home Page" width="627" height="110" hspace="0" vspace="0" border="0" id="header_1" /></a></td>
  </tr>
  <tr>
    <td height="20" valign="middle" bgcolor="#CCC070"  id="nav-menu"><img src="images/geneva_logo_2.jpg" alt="GBAA Logo Piece" width="152" height="20" hspace="0" vspace="0" border="0" align="left" id="geneva_logo_2" />
	 
<ul>
<li><a href="about_gba.php">About GBA</a></li>
<li><a href="leagues.php">Leagues</a></li>
<li><a href="standings.php">Standings</a></li>
<li><a href="schedules.php">Schedules</a></li>
<li><a href="news.php">News & Events</a></li>
<li><a href="fields.php">Fields</a></li>
<li><a href="documents.php">Documents</a></li>
</ul></td>
  </tr>
  <tr>
    <td id="under_nav"><img id="geneva_logo_3" src="images/geneva_logo_3.jpg" width="152" height="9" alt="GBA Logo Piece" /></td>
  </tr>
  <tr>
    <td valign="middle" height="39" style="border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #060021;"><table width="779" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle">
    <td width="152"><img src="images/new_bottom_logo.jpg" alt="GBA Logo Bottom" width="152" height="39" hspace="0" vspace="0" border="0" align="absmiddle" id="geneva_logo_4" /></td>
    <td id="header"><!-- InstanceBeginEditable name="header" -->Spring Training Academy Registration<!-- InstanceEndEditable --></td>
    <td align="right" class="date"><?php echo date('F j, Y'); ?></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td valign="top"><table width="779" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="152" id="left_side">&nbsp;</td>
        <td align="left" valign="top" id="content"><!-- InstanceBeginEditable name="content" -->
    <!-- <table cellpadding="0" cellspacing="0" border="0" width="300" align="right">
				<tr>
					<td><img src="images/register_progress_3.jpg" width="300" height="100" /></td>
				</tr>
			</table> -->
			<h2>Step 3: Select Players to Register </h2>			
			<p>Please review all of your information below.  To make any changes, please press the 'Edit' button next to the corresponding information.</p>
			<p>Select the players you wish to register and press 'Continue' to complete your registration process.</p>
			<p>*Be sure to respond to Volunteer/Coaching questions at bottom of page</p>
			<table cellpadding="5" cellspacing="0" border="0" width="675" id="register_table">
				<tr>
					<th width="260" align="left"> Family Information - ID <?php echo $row_family['family_id']; ?></th>
					<th width="10">&nbsp;</th>
					<th align="right"><input type="button" onclick="window.location.href='training_family.php?<?php echo $row_family['family_id']; ?>'" value="Edit" /></th>
				</tr>
				<tr valign="top">
					<td valign="top" align="left">
						<strong>Primary Contact</strong><br /><br /><?php echo $row_family['firstname1']." ".$row_family['lastname1']."<br />";
						echo $row_family['address1']."<br />";
						if(!empty($row_family['address2'])){ 
							echo $row_family['address2']."<br />"; 
						}
						echo $row_family['city'].", ".$row_family['state']." ".$row_family['zip']."<br /><br />"; ?>
						<strong>Email: </strong><?php echo $row_family['email1']."<br />"; ?>
						<strong>Home: </strong><?php echo $row_family['home_phone']."<br />"; ?>
						<strong>Emergency: </strong><?php echo $row_family['emergency_phone']."<br />"; ?></td>
						<td width="10" id="line">&nbsp;</td>
						<td align="left" valign="top">
						<strong>Secondary Contact<br /><br /></strong>
						<?php echo $row_family['firstname2']." ".$row_family['lastname2']."<br />"; ?>
						<?php 
						if(!empty( $row_family['email2'])){ ?>
							<strong>Email: </strong><?php echo $row_family['email2']."<br />"; ?>
						<?php } ?>
						<?php 
						if(!empty( $row_family['home_phone2'])){ ?>
							<strong>Home: </strong><?php echo $row_family['home_phone2']."<br />"; ?>
						<?php 
						} ?>
						<?php 
						if(!empty( $row_family['emergency_phone2'])){ ?>
							<strong>Emergency: </strong><?php echo $row_family['emergency_phone2']."<br />"; ?>
						<?php 
						} ?>
					</td>
				</tr>
			</table><br  />

<?php // Players in the family and registration table who are registered are handled below
			if(isset($already_registered)&&(count($already_registered) >= 1)){ ?>
				<table cellpadding="5" cellspacing="0" border="0" width="675" id="register_table">
					<tr>
						<th align="left" colspan="3">These players are already registered for this season.</th>
					</tr>
					<?php 	
					$flip = 1;
					foreach($already_registered as $registered_player){ 
						$reg_player_query = sprintf("SELECT * FROM players, registration_training, training WHERE players.player_id = '%s' AND registration_training.player_id = '%s' AND registration_training.training_id = training.id", $registered_player, $registered_player);
						$reg_player = mysql_query($reg_player_query, $drws) or die(mysql_error());
						$row_reg_player = mysql_fetch_assoc($reg_player);
// This assignment sets league id to the league id in the registration table for this registered player
						$league_id = $row_reg_player['league_id']; ?>
						<tr>
							<td width="260" valign="top" bgcolor="#<?php if($flip%2 == 0){ echo "cccccc"; }else{ echo "ffffff";} ?>"><strong>Name:</strong> <?php echo $row_reg_player['fname']." ".$row_reg_player['lname']."<br>"; ?>
								<strong>Date of Birth:</strong> <?php $bday = explode('-',$row_reg_player['birth_date']); echo date("F j, Y",mktime(0,0,0,$bday[1],$bday[2],$bday[0])); ?><br />
								<?php 
								if(isset($row_reg_player['medical'])){ ?>
									<strong>Medical Condition:</strong> <?php echo $row_reg_player['medical']; ?><br /> 
								<?php 
								} ?>
							</td>
							<td width="10" id="line"  bgcolor="#<?php if($flip%2 == 0){ echo "cccccc"; }else{ echo "ffffff";} ?>">&nbsp;</td>
							<td valign="top"  bgcolor="#<?php if($flip%2 == 0){ echo "cccccc"; }else{ echo "ffffff";} ?>">
								<?php 
								echo "This player has already been registered for the <strong>".$row_reg_player['day']." ".$row_reg_player['start_time']." - ".$row_reg_player['end_time']."</strong> session.<br />"; ?>

							</td>
						</tr>
						<?php 
						$flip++; 
					} ?>
				</table>
			<?php 
			} ?>
			<p><input name="button2" type="button" onclick="window.location.href='training_player.php'" value="Add Player" /> Click here to add players that you would like to register.</p>
<?php // Players in the family table who are not registered are handled below
			if(isset($not_registered)&&(count($not_registered) >= 1)){ ?>
				<form method="post" action="training_invoice.php" name="reviewForm" id="reviewForm">
					<p class="requiredtext">Note: If your player is returning from a previous season, please click the "Edit Player" button and review their information.</p>
					<table cellpadding="5" cellspacing="0" border="1" width="675" id="register_table">
						<tr>
							<th align="left" colspan="5">These players have not been registered for this season.</th>
						
						<?php ?>
							<th width="80" align="center">Register<br />Player?</th>
						</tr>
						<?php 	
						$flip = 1;
						foreach($not_registered as $not_registered_player){ 
							$not_reg_player_query = sprintf("SELECT * FROM players WHERE player_id = '%s'", $not_registered_player, $not_registered_player);
							$not_reg_player = mysql_query($not_reg_player_query, $drws) or die(mysql_error());
							$row_not_reg_player = mysql_fetch_assoc($not_reg_player);
// This call finds the league id based on the player's birthdate 
							$league_id = findLeague($row_not_reg_player['birth_date']); //echo $league_id."<br>";?>
							<tr>
							  <td width="44" align="left" valign="middle" bgcolor="#99CCFF"><input name="button" type="button" onclick="window.location.href='training_edit.php?id=<?php echo $row_not_reg_player['player_id']."&mod=review"; ?>'" value="Edit Player" /></td>
							  <td colspan="4" width="235" valign="top" bgcolor="#99CCFF"><strong>Name:</strong> <?php echo $row_not_reg_player['fname']." ".$row_not_reg_player['lname']."<br>"; 
							  
							$query_training = sprintf("SELECT * FROM training WHERE league_id = '%s'", $league_id);
							$training = mysql_query($query_training, $drws) or die(mysql_error());
							$row_training = mysql_fetch_assoc($training);
							  ?>
									<strong>Date of Birth:</strong> <?php $bday = explode('-',$row_not_reg_player['birth_date']); echo date("F j, Y",mktime(0,0,0,$bday[1],$bday[2],$bday[0])); ?><br />
									<?php 
									if(isset($row_not_reg_player['medical'])){ ?>
										<strong>Medical Condition:</strong> <?php echo $row_not_reg_player['medical']; ?><br />
							  <?php 
									} ?></td>
									<td align="center" valign="middle" bgcolor="#99CCFF">
										<?php 
										if(!empty($row_training['league_id'])){
											$valid = false;
											
											$query_sessionLimit = "SELECT `session_limit` FROM `organization`";
											$sessionLimit = mysql_query($query_sessionLimit, $drws) or die(mysql_error());
											$row_sessionLimit = mysql_fetch_assoc($sessionLimit);
											
											do {
												$query_limit = sprintf("SELECT * FROM registration_training WHERE training_id='%s' AND season_id = %s", $row_training['id'], $_SESSION['reg_season']);
												$limit = mysql_query($query_limit, $drws) or die(mysql_error());
												$totalRows_limit = mysql_num_rows($limit);
												
												if($totalRows_limit < $row_sessionLimit['session_limit']) {
													$valid = true;
												}
											} while($row_training = mysql_fetch_assoc($training));
											
											if($valid) { ?>
												<select name="player_select[]">
	                                				<option value="<?php echo $flip."_Y_".$row_not_reg_player['player_id'];//."_".$league_id; ?>" selected="selected">Yes</option>
	                                				<option value="<?php echo $flip."_N_".$row_not_reg_player['player_id'];//."_".$league_id; ?>">No</option>
	                              				</select>
											<?php
											} 
										} ?>
									</td>
								</tr>
								<?php
								/***************** League and Training session selection ****************/
								$query_list_league = sprintf("SELECT * FROM league WHERE travel = 0 ORDER BY position ASC");
								$list_league = mysql_query($query_list_league, $drws) or die(mysql_error());
								$row_list_league = mysql_fetch_assoc($list_league); ?>
								<tr>
									<td bgcolor="#<?php if($flip%2 == 0){ echo "cccccc"; }else{ echo "ffffff";} ?>"><strong>Training Session Selection</strong></td>
								  	<td colspan="4" width="230" valign="top" bgcolor="#<?php if($flip%2 == 0){ echo "cccccc"; }else{ echo "ffffff";} ?>">
								  		Based on this player's birth date, training sessions from the <strong><?php echo getLeagueInfo($league_id, "league_name"); ?></strong> league will be displayed.<br />
								  		To place this player in a different league, select that league from the menu below.<br />
								  		<select name="select_league[]" class="select_league">
											<?php
											do {
												if(testRegistrationStatus($row_list_league['league_id'])) {	?>											
													<option value="<?php echo $row_list_league['league_id']."_".$row_not_reg_player['player_id']; ?>" <?php if($league_id == $row_list_league['league_id']) { echo "SELECTED"; } ?>><?php echo $row_list_league['league_name']; ?></option>
												<?php									
												}
											} while($row_list_league = mysql_fetch_assoc($list_league)); ?>
										</select><br />
										
										
										
										<div class="training_dropdown">
											<?php readfile('http://'.$_SERVER['SERVER_NAME'].'/training_dropdown.php?league_id='.$league_id.'_'.$row_not_reg_player['player_id']); ?>
										</div>
								  	</td>
									<td>&nbsp;</td>
								</tr>
								<?php
								/***************** League and Training session selection ****************/ ?>
							<?php 
							$flip++; 
						} ?>
				</table>
				<p>Would you be interested in being a Volunteer Assistant during your player's session?</p>
				<input name="volunteer" type="radio" value="Y_">Yes &nbsp;<input name="volunteer" type="radio" value="N_" CHECKED>No <br /><br />
				If yes, please provide contact information below so we can contact you for planning purposes.<br /><br />
				<textarea name="volunteerContact" rows="7" cols="50"></textarea><br /><br />
				
				<p>There is a GBA Coaches Clinic covering the STA topics prior to the first STA, dates times TBD. Do you want to Manage/Coach any GBA League teams in 2010?</p>
				<input name="clinic" type="radio" value="Y_">Yes &nbsp;<input name="clinic" type="radio" value="N_" CHECKED>No <br /><br />
				If yes, please provide your best contact information so you can be notified of Clinic date and time options.<br /><br />
				<textarea name="clinicContact" rows="7" cols="50"></textarea><br /><br />
				
				<p align="right">Click here to continue the registration process  &gt; <input name="Continue" type="submit" value="continue" /></p>				
			</form>
		<?php 
		} ?>
    
	  <!-- InstanceEndEditable -->
          	<p>&nbsp;</p></td>
      </tr>
      <tr>
        <td id="left_side">&nbsp;</td>
        <td height="81" align="center" valign="bottom" id="bottom_nav"><img src="images/spacer.gif" width="132" height="81" align="right" /><table width="460" cellpadding="0" cellspacing="0" border="0">
				<tr> 
                <!-- 09.05.08 Ralph De Stefano - Changed the main Player Registration link from register.php to returning.php -->
					<td align="left" valign="middle"><a href="http://www.surveymonkey.com/s.aspx?sm=MYTz2JttpcUP9tz4qQcIKg_3d_3d"><img src="images/manager_registration.jpg" alt="manager registration" width="150" height="94" border="0" /></a></td>
					<!--<td align="right" valign="middle"><a href="../support_gba.php"><img src="../images/support_geneva.jpg" alt="support geneva baseball" width="150" height="94" border="0" /></a></td>-->
				</tr>
			</table><p>&nbsp;</p></td>
      </tr>
    </table></td>
  </tr>
</table>
<div id="footer">
<p align="center"><a href="index.php">Home</a> &nbsp;|&nbsp;<a href="about_gba.php">About GBA</a>&nbsp;|&nbsp;<a href="leagues.php">Leagues</a>&nbsp;|&nbsp;<a href="schedules.php">Schedules</a>&nbsp;|&nbsp;<a href="news.php">News &amp; Events</a>&nbsp;|&nbsp;<a href="fields.php">Fields</a>&nbsp;|&nbsp;<a href="documents.php">Documents</a>&nbsp;|&nbsp;<a href="contact.php">Contact Us</a></p>
  <p>&copy; <?php if(date('Y') == "2006"){ echo date('Y'); }else{ echo "2006 - ".date('Y');} ?>, Geneva Baseball Association. All Rights Reserved.<br />&nbsp;<br />
  This Baseball League Website designed and developed by <br />
  <a href="http://www.drwebsolutions.com" target="_blank"><img src="http://www.drws.com/assets/chicago-web-design-drweb.gif" alt="This Baseball League Website designed and developed by Chicago web design firm Direct Response Web Solutions, Inc." border="0" /></a></p>
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-2607830-9");
pageTracker._initData();
pageTracker._trackPageview();
</script>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($season);
?>
