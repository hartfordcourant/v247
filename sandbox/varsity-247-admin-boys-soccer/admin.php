<?php
// turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
// set timezone
date_default_timezone_set('America/New_York');
// get the stuff we need
require_once('config/config.php');
require_once('classes/Database.php');
require_once('../../../_globals/p2p/p2p-api.php');
require_once('../_globals/v247/classes/Utils.php');
require_once('classes/Schedule.php');
require_once('classes/ExportNewsgate.php');
require_once('classes/MakeBarker.php');
// instantiate classes
$db = new Database();
$mb = new MakeBarker(V247_YEAR);
$sked = new Schedule();
// get page setup and page controller
require_once('../_globals/v247/layouts/setup.htm');
require_once('../_globals/v247/controllers/admin.php');
?>

<html>

<?php include('layouts/header.htm'); ?>

<body>

<div id="wrapper" class="container">
	<div id="admin_cci_docs" class="row">
		<div class="col-md-12">
			<p><a href="teams.php">Teams Page</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo V247_ROUND;?>" download>Today's Roundup</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo V247_BOX;?>" download>Today's Boxscores</a></p>
		</div>
	</div>
	<div id='input_message' class='bg-success border-all'>
		<div class='row'>
			<div class='col-md-12 clearfix'>
				<p><?php echo $db->messages . $mb->barker_message; ?></p>
				<span class='glyphicon glyphicon-remove-circle'></span>
			</div>
		</div>
	</div>
	
	<?php
		// check if add game is selected
		if(isset($_POST['add']) || isset($_POST['add_game'])){ $addUp = 'add'; }
		// check if update game is selected
		else{ $addUp = 'up'; }
		// include navigation
		include('../_globals/v247/layouts/nav.htm'); 
	?>

	<div class="mod_bg border-all dropshadow">

		<?php 		
			// include the correct view view
			if(isset($_POST['add']) || isset($_POST['add_game'])){ 
				include('../_globals/v247/views/input.php'); 
			}
			else{ 
				include('views/update.php'); 
			}
		?>		
		
	</div>
</div>

<?php include('../_globals/v247/layouts/scripts.htm'); ?>

</body>
</html>