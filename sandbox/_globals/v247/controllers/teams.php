<?php

if(isset($_GET['team_schedule'])){
	// get school id from form
    $school = $_GET['school'];
	// get school info
	$getInfo = "CALL getInfo('$school')";
    $team_info = $db->getData($getInfo);
    // get school name from id info
    $school_name = $team_info[0]['name'];
    // get correct schedule stuff
	$getScheduleSchool = "CALL adminGetScheduleSchool('$school')";
	$schedule = $db->getData($getScheduleSchool);	
}
/**
 * update area game if 
 * update button is clicked
 */ 
elseif(isset($_POST['update'])){
	// get day of game
	$updated_day = $_POST['game_date'];
    // get the page that sent here
	$referer = $_SERVER['HTTP_REFERER'] . "#" . $_POST['game_id'];
	// set winner to null
	$_win = NULL;
	// if there's a final score
	if($_POST['away_final'] != '' && $_POST['home_final'] != ''){
		// check for a tie
		if((int) $_POST['away_final'] == (int) $_POST['home_final']){
			$_win = 'TIE';
		}
		// if away team wins
		elseif((int) $_POST['away_final'] > (int) $_POST['home_final']){
			$_win = $_POST['update_away_team'];
		}
		// if home team wins
		else{
			$_win = $_POST['update_home_team'];
		}
	}
	// check what sport is updating,post the corect data
	if(V247_SPORT == 'High School Baseball' || V247_SPORT == 'High School Softball'){

	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // BASEBALL / SOFTBALL /////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputData($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],$_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								$_POST['away_team'],$_POST['away_abbr'],$_POST['away_inn1'],$_POST['away_inn2'], $_POST['away_inn3'],$_POST['away_inn4'],$_POST['away_inn5'],$_POST['away_inn6'],$_POST['away_inn7'],$_POST['away_xtra'],$_POST['away_final'],$_POST['away_hits'],$_POST['away_err'],
                                $_POST['home_team'],$_POST['home_abbr'],$_POST['home_inn1'],$_POST['home_inn2'], $_POST['home_inn3'],$_POST['home_inn4'],$_POST['home_inn5'],$_POST['home_inn6'],$_POST['home_inn7'],$_POST['home_xtra'],$_POST['home_final'],$_POST['home_hits'],$_POST['home_err'],
                                $_POST['num_xtra'],$_POST['sum_head'],$_POST['sum_body'],$_POST['sum_wp'],$_POST['sum_lp'],$_POST['sum_hra'],$_POST['sum_hrh'],$_POST['sum_note']);
	}
	elseif(V247_SPORT == 'Girls High School Basketball' || V247_SPORT == 'Boys High School Basketball'){
	
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // BASKETBALL //////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputData($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],
								$_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								$_POST['away_q1'],$_POST['away_q2'], $_POST['away_q3'],$_POST['away_q4'],$_POST['away_ot'],
								$_POST['home_q1'],$_POST['home_q2'],$_POST['home_q3'],$_POST['home_q4'],$_POST['home_ot'],								
								$_POST['sum_head'],$_POST['sum_body'],$_POST['sum_scoring'],
								$_POST['away_abbr'],$_POST['home_abbr'],$_POST['away_team'],
								$_POST['home_team'],$_POST['away_final'],$_POST['home_final']);
	}
	elseif(V247_SPORT == 'High School Football'){
	
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // FOOTBALL ////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputData($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],
								$_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								$_POST['away_q1'],$_POST['away_q2'], $_POST['away_q3'],$_POST['away_q4'],$_POST['away_ot'],
								$_POST['home_q1'],$_POST['home_q2'],$_POST['home_q3'],$_POST['home_q4'],$_POST['home_ot'],								
								$_POST['sum_head'],$_POST['sum_body'],$_POST['sum_scoring'],
								$_POST['away_abbr'],$_POST['home_abbr'],$_POST['away_team'],
								$_POST['home_team'],$_POST['away_final'],$_POST['home_final'],$_POST['video']);
	}
	elseif(V247_SPORT == 'Girls High School Soccer' || V247_SPORT == 'Boys High School Soccer'){																																										
	
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // SOCCER //////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputData($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],
								$_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								$_POST['away_h1'],$_POST['away_h2'],$_POST['away_ot'],
								$_POST['home_h1'],$_POST['home_h2'],$_POST['home_ot'],
								$_POST['sum_head'],$_POST['sum_body'],$_POST['sum_note'],
								$_POST['away_abbr'],$_POST['home_abbr'],$_POST['away_team'],$_POST['home_team'],
								$_POST['away_final'],$_POST['home_final'],$_POST['away_pk'],$_POST['home_pk'],
								$_POST['away_goals'],$_POST['home_goals'],$_POST['away_saves'],$_POST['home_saves']);
	}
	elseif(V247_SPORT == 'High School Hockey'){

	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // HOCKEY //////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputData($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],
								$_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								$_POST['away_p1'],$_POST['away_p2'],$_POST['away_p3'],$_POST['away_ot'],
								$_POST['home_p1'],$_POST['home_p2'],$_POST['home_p3'],$_POST['home_ot'],
								$_POST['sum_head'],$_POST['sum_body'],$_POST['sum_note'],
								$_POST['away_abbr'],$_POST['home_abbr'],$_POST['away_team'],$_POST['home_team'],
								$_POST['away_final'],$_POST['home_final'],$_POST['away_pk'],$_POST['home_pk'],
								$_POST['away_goals'],$_POST['home_goals'],$_POST['away_assists'],$_POST['home_assists'],$_POST['away_saves'],$_POST['home_saves']);
	}
	elseif(V247_SPORT == 'Boys High School Lacrosse' || V247_SPORT == 'Girls High School Lacrosse'){
	
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // LACROSSE ////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputData($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],
								$_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								$_POST['away_p1'],$_POST['away_p2'],$_POST['away_p3'],$_POST['away_p4'],$_POST['away_ot'],
								$_POST['home_p1'],$_POST['home_p2'],$_POST['home_p3'],$_POST['home_p4'],$_POST['home_ot'],
								$_POST['sum_head'],$_POST['sum_body'],$_POST['sum_note'],
								$_POST['away_abbr'],$_POST['home_abbr'],$_POST['away_team'],$_POST['home_team'],
								$_POST['away_final'],$_POST['home_final'],$_POST['away_pk'],$_POST['home_pk'],
								$_POST['away_goals'],$_POST['home_goals'],$_POST['away_assists'],$_POST['home_assists'],$_POST['away_saves'],$_POST['home_saves']);
	}

	// build a newsgate file for the day that was just updated.
	$getResults = "CALL adminGetScheduleCCI('$updated_day')";
	$results = $db->getData($getResults);
	// send results to newsgate
	exportNewsgate($results, $updated_day, $db);
	// update the barker
	$make_barker = $mb->makeScoreboard();
	// send back to page that called update 
	header("Location: $referer");
}
/**
 * update out of area game if 
 * update_away button is clicked
 */ 
elseif(isset($_POST['update_away'])){
	// get the page that sent here
	$referer = $_SERVER['HTTP_REFERER'] . "#" . $_POST['game_id'];
	// check if there's a winner
	// set winner to null
	$_win = NULL;
	// if there's a final score
	if($_POST['away_final'] != '' && $_POST['home_final'] != ''){
		// check for a tie
		if((int) $_POST['away_final'] == (int) $_POST['home_final']){
			$_win = 'TIE';
		}
		// if away team wins
		elseif((int) $_POST['away_final'] > (int) $_POST['home_final']){
			$_win = $_POST['update_away_team'];
		}
		// if home team wins
		else{
			$_win = $_POST['update_home_team'];
		}
	}
	// check what sport is updating,post the corect data
	if(V247_SPORT == 'High School Baseball' || V247_SPORT == 'High School Softball'){
	
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // BASEBALL / SOFTBALL /////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputDataAway($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],$_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								    $_POST['away_team'],$_POST['away_abbr'],$_POST['away_inn1'],$_POST['away_inn2'], $_POST['away_inn3'],$_POST['away_inn4'],$_POST['away_inn5'],$_POST['away_inn6'],$_POST['away_inn7'],$_POST['away_xtra'],$_POST['away_final'],$_POST['away_hits'],$_POST['away_err'],
                                    $_POST['home_team'],$_POST['home_abbr'],$_POST['home_inn1'],$_POST['home_inn2'], $_POST['home_inn3'],$_POST['home_inn4'],$_POST['home_inn5'],$_POST['home_inn6'],$_POST['home_inn7'],$_POST['home_xtra'],$_POST['home_final'],$_POST['home_hits'],$_POST['home_err'],
                                    $_POST['num_xtra']);
	}
	elseif(V247_SPORT == 'Girls High School Basketball' || V247_SPORT == 'Boys High School Basketball' || V247_SPORT == 'High School Football'){
	
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // BASKETBALL / FOOTBALL ///////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputDataAway($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],
								    $_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								    $_POST['away_q1'],$_POST['away_q2'],$_POST['away_q3'],$_POST['away_q4'],$_POST['away_ot'],
								    $_POST['home_q1'],$_POST['home_q2'],$_POST['home_q3'],$_POST['home_q4'],$_POST['home_ot'],
								    $_POST['away_team'],$_POST['home_team'],$_POST['away_final'],$_POST['home_final']);
	}
	elseif(V247_SPORT == 'Girls High School Soccer' || V247_SPORT == 'Boys High School Soccer'){																																										
	
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // SOCCER //////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputDataAway($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],
								    $_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								    $_POST['away_h1'],$_POST['away_h2'],$_POST['away_ot'],
								    $_POST['home_h1'],$_POST['home_h2'],$_POST['home_ot'],
								    $_POST['away_team'],$_POST['home_team'],$_POST['away_final'],$_POST['home_final'],
								    $_POST['away_pk'],$_POST['home_pk']);
	}
	elseif(V247_SPORT == 'High School Hockey'){

	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // HOCKEY //////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputDataAway($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],
								    $_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								    $_POST['away_p1'],$_POST['away_p2'],$_POST['away_p3'],$_POST['away_ot'],
								    $_POST['home_p1'],$_POST['home_p2'],$_POST['home_p3'],$_POST['home_ot'],
								    $_POST['away_team'],$_POST['home_team'],$_POST['away_final'],$_POST['home_final'],
								    $_POST['away_pk'],$_POST['home_pk']);
	}
	elseif(V247_SPORT == 'Boys High School Lacrosse' || V247_SPORT == 'Girls High School Lacrosse'){

	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
    // LACROSSE //////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////

	$gameScore = $db->inputDataAway($_POST['game_id'],$_POST['area'],$_POST['game_date'],$_POST['game_time'],
								    $_POST['update_away_team'],$_POST['update_home_team'],$_POST['facility'],$_win,
								    $_POST['away_p1'],$_POST['away_p2'],$_POST['away_p3'],$_POST['away_p4'],$_POST['away_ot'],
								    $_POST['home_p1'],$_POST['home_p2'],$_POST['home_p3'],$_POST['home_p4'],$_POST['home_ot'],
								    $_POST['away_team'],$_POST['home_team'],$_POST['away_final'],$_POST['home_final'],
								    $_POST['away_pk'],$_POST['home_pk']);
	}

	// send back to page that called update
	header("Location: $referer");
}
/**
 * Add new game if 
 * add_game button is clicked
 */ 
elseif(isset($_POST['add_game'])){
	// get day of game
	$updated_day = $_POST['game_date'];
	// get the week of game
    $gameWeek = $sked->getGameWeek($season,$updated_day);
	// get the new game id request
	$getID = "SELECT CONVERT(SUBSTRING_INDEX(game_id,'_',-1),UNSIGNED INTEGER) AS num
				FROM schedule
				WHERE week = '$gameWeek'
				ORDER BY num DESC
				LIMIT 1;";
    // request the id
	$id = $db->getData($getID);
    // make new unique id
    $game_id = "wk" . $gameWeek . "_" . ($id[0]['num'] + 1);
    // send data for new game to database
	$newGame = $db->addData($game_id,
							$_POST['away_id'],
							$_POST['home_id'],
							$gameWeek,
							$_POST['game_date'],
							$_POST['game_time'],
							$_POST['facility'],
							$_POST['area']);
	// set schools to all
	$school_name = 'All';
}
// delete game if delete button is clicked
elseif(isset($_POST['delete_game'])){
	// get the page that sent here
	$referer = $_SERVER['HTTP_REFERER'];
	//get game id
	$game_id = $_POST['game_id'];
	// get day of game
	$updated_day = $_POST['game_date'];
	// get away team name
	$away_team = $_POST['away_team'];
	// get home team name
	$home_team = $_POST['home_team'];
	//delete game with that id
	$deleteGame = $db->deleteData($game_id,$away_team,$home_team);
	// send the page back where it came from
	header("Location: $referer");
}
// on page load return the games for ccc_01
else{
	// get school id from form
    $school = 'ccc_01';
	// get school info
	$getInfo = "CALL getInfo('$school')";
    $team_info = $db->getData($getInfo);
    // check if add button was pushed
    // set school name based
    if(isset($_POST['add'])){
    	// set school name to all
    	$school_name = 'All';
	}else{
		// get school name from id info
    	$school_name = $team_info[0]['name'];
	}
    // get correct schedule stuff
	$getScheduleSchool = "CALL adminGetScheduleSchool('$school')";
	$schedule = $db->getData($getScheduleSchool);
}
?>