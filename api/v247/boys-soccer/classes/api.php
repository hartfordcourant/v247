<?php
// show all erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
// set timezone
date_default_timezone_set('UTC');
// include all your stuff
include('../config/config.php');
include('../classes/Database.php');
include('../classes/Utils.php');
include('../classes/Schedule.php');
include('../../../../_globals/p2p/p2p-api.php');

// instantiate classes
$db = new Database();
$sked = new Schedule();
$p2p = new P2PAPI(P2P_TOKEN, "json"); 

// get params from request
// what sport is it, which db are we selecting?
isset($_GET['sport']) ? $sport = $_GET['sport'] : $sport = "boys-soccer";
// what year is it which db are we selecting?
isset($_GET['year']) ? $year = $_GET['year'] : $year = date("Y");
// what week(period) of the season is it?
isset($_GET['period']) ? $period = $_GET['period'] : $period = "";
// are we asking for a specific league?
isset($_GET['league']) ? $league = $_GET['league'] : $league = "";
// are we asking for a specific team
isset($_GET['team']) ? $team = $_GET['team'] : $team = "";
// are we asking for a specific game
isset($_GET['game']) ? $game = $_GET['game'] : $game = "";
// are we asking for the standings
isset($_GET['stand']) ? $stand = $_GET['stand'] : $stand = "";
// assign params to array
$params = ['period'=>$period,'league'=>$league,'team'=>$team];

////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////

// get the selected league, if there isn't one set it to nothing
if($params['league'] != ''){
    $league = strtoupper($params['league']);
    $league_out = strtolower($league) . '_%';
    $league_key = $params['league'];
}else{
    $league = '';
}

////////////////////////////////////////
////////////////////////////////////////
////////////////////////////////////////

if($team != ''){
  // init arrays
  $info = [];
  $games = [];
  $team_results = [];
  $team_record = ['w'=>0,'l'=>0,'t'=>0];

  // get school info
  $getInfo = "CALL getInfo('$team')";
  $team_info = $db->getData($getInfo);
  // get schedule info
  $getTeam = "CALL getTeam('$team')";
  $schedule = $db->getData($getTeam);
  // get team record
  $getRecord = "CALL getTeamRecord('$team')";
  $record = $db->getData($getRecord);

  $info['name'] = $team_info[0]['name'];
  $info['league'] = $team_info[0]['league'];

  foreach ($schedule as $key => $value) {
  
    ///////////////////////////////
    //// Get Final Score //////////
    ///////////////////////////////
  
    // get home and away team names
    $away_team = $value['away_team'];
    $home_team = $value['home_team'];
    // check if the final score is filled out and assign values to vars 
    if($value['away_final'] != NULL && $value['home_final'] != NULL){
      $away_score = $value['away_final'];
      $home_score = $value['home_final'];
    }
    else{
      $away_score = '';
      $home_score = '';
    }
    // check if team matches winner of game
    if($value['winner'] != null){
      if($value['school_id'] == $value['winner']){ $result = 'W, '; $team_record['w']++;}
      // check if game is a tie
      elseif($value['winner'] == 'TIE'){ $result = 'T, '; $team_record['t']++;}
      // must have lost
      else{ $result = 'L, '; $team_record['l']++;}
      // put score in the right order, add it to the result
      if($away_score > $home_score){ $result .= ($away_score . '-' . $home_score); }
      else{ $result .= ($home_score . '-' . $away_score); }
      // print the team record for this week if the game is over
      $team_wlt = $team_record['w'] . '-' . $team_record['l'] . '-' . $team_record['t'];
    }else{
      $result = '';
      // don't print the team record if the game isn't over
      $team_wlt = '';
    }

    ///////////////////////////////
    //// Get Home/Away ////////////
    ///////////////////////////////

    if($team == $value['away_id']){
      $opp = ['i'=>$value['home_id'],'t'=>('at ' . $value['home_team'])];
    }else{
      $opp = ['i'=>$value['away_id'],'t'=>('vs ' . $value['away_team'])];
    }

    ///////////////////////////////
    //// Get Record ///////////////
    ///////////////////////////////

    array_push($games,array('i'=>$value['game_id'],
                            'w'=>$value['week'],
                            'gd'=>date('D, M d', strtotime($value['game_date'])),
                            'gt'=>date('g:i A', strtotime($value['game_time'])),
                            'f'=>$value['facility'],
                            's'=>$result,
                            'o'=>$opp,
                            'r'=>$team_wlt
                            ));
  }

  $team_results['info']=$info;
  $team_results['games']=$games;
  // encode array as json
  echo json_encode($team_results);
}
elseif($game != ''){
  // init sum arrays
  $summary = [];
  // get game info
  $getGame = "CALL getGame('$game')";
  $game_info = $db->getData($getGame);

  // get away scores
  ($game_info[0]['away_h1'] == null) ? $ah1 = '' : $ah1 = $game_info[0]['away_h1'];
  ($game_info[0]['away_h2'] == null) ? $ah2 = '' : $ah2 = $game_info[0]['away_h2'];
  ($game_info[0]['away_ot'] == null) ? $aot = '' : $aot = $game_info[0]['away_ot'];
  ($game_info[0]['away_pk'] == null) ? $apk = '' : $apk = $game_info[0]['away_pk'];
  ($game_info[0]['away_final'] == null) ? $af = '-' : $af = $game_info[0]['away_final'];
  // get home scores
  ($game_info[0]['home_h1'] == null) ? $hh1 = '' : $hh1 = $game_info[0]['home_h1'];
  ($game_info[0]['home_h2'] == null) ? $hh2 = '' : $hh2 = $game_info[0]['home_h2'];
  ($game_info[0]['home_ot'] == null) ? $hot = '' : $hot = $game_info[0]['home_ot'];
  ($game_info[0]['home_pk'] == null) ? $hpk = '' : $hpk = $game_info[0]['home_pk'];
  ($game_info[0]['home_final'] == null) ? $hf = '-' : $hf = $game_info[0]['home_final'];

  // build new array for json
  $summary = ['i'=>$game,
              'at'=>$game_info[0]['away_team'],
              'ai'=>$game_info[0]['away_id'],
              'ht'=>$game_info[0]['home_team'],
              'hi'=>$game_info[0]['home_id'],
              'w'=>$game_info[0]['week'],
              'gd'=>date('D, M d', strtotime($game_info[0]['game_date'])),
              'gt'=>date('g:i A', strtotime($game_info[0]['game_time'])),
              'f'=>$game_info[0]['facility'],
              'wi'=>$game_info[0]['winner'],
              'a1'=>$ah1,  
              'a2'=>$ah2,   
              'ao'=>$aot,
              'ap'=>$apk,
              'af'=>$af,   
              'h1'=>$hh1,  
              'h2'=>$hh2,
              'ho'=>$hot,
              'hp'=>$hpk,  
              'hf'=>$hf,
              'sh'=>$game_info[0]['sum_head'],
              'sb'=>$game_info[0]['sum_body'],
              'sn'=>$game_info[0]['sum_note'],
              'ag'=>$game_info[0]['away_goals'],
              'hg'=>$game_info[0]['home_goals'],
              'as'=>$game_info[0]['away_saves'],
              'hs'=>$game_info[0]['home_saves'],
              'ab'=>$game_info[0]['away_abbr'],
              'hb'=>$game_info[0]['home_abbr']
              ];
  // encode array as json
  echo json_encode($summary);
}
elseif($stand != ''){
  $standings = [];
  // get all schools
  $getAllSchools = "SELECT school_id, name, (SELECT league_name FROM leagues WHERE league_id = league) AS league, division, class FROM schools WHERE league != 0 ORDER BY league, division";
  $allSchools = $db->getData($getAllSchools);
  // get all of the standings
  $all_leagues = getStandings($allSchools);
  foreach ($all_leagues as $i => $league) {
    $league_up = ucwords($i);
    $standings[$league_up] = [];
    foreach ($league as $team) {
      array_push($standings[$league_up],
          array("n"=>$team["team"],
                "i"=>$team["team_id"],
                "l"=>array(
                  "w"=>$team["lwin"],
                  "l"=>$team["lloss"],
                  "t"=>$team["ltie"],
                  "p"=>$team["lgf"],
                  "a"=>$team["lga"]),
                "o"=>array(
                  "w"=>$team["owin"],
                  "l"=>$team["oloss"],
                  "t"=>$team["otie"],
                  "p"=>$team["ogf"],
                  "a"=>$team["oga"]),
                "p"=>$team["points"]
        ));
    }
  }
  // encode array as json
  echo json_encode($standings); 
}
else{
  // init games results array
  $games = [];
  // if period and league are selected
  if($params['period'] != '' && $params['league'] != ''){
    $period = $sked->getDisplayPeriod($year,$period);
    $getSchedule = "Call getSchedule('$period[0]','$period[1]','$league_out')";
    $scores = $db->getData($getSchedule);
  }
  // if period is selected and league is not
  elseif($params['period'] != '' && $params['league'] == ''){
    $period = $sked->getDisplayPeriod($year,$period);
    $getSchedulePeriod = "Call getSchedulePeriod('$period[0]','$period[1]')";
    $scores = $db->getData($getSchedulePeriod);
    $league = "All";
  }
  // if period is not selected and league is
  elseif($params['period'] == '' && $params['league'] != ''){
    $getScheduleLeague = "Call getScheduleLeague('$league_out')";
    $scores = $db->getData($getScheduleLeague);
    $period = "All";
  }
  // if period and league are not selected
  else{
    $today = date("Y-m-d");
    $period = $sked->getDisplayPeriod($year,$today);
    $getSchedulePeriod = "Call getSchedulePeriod('$period[0]','$period[1]')";
    $scores = $db->getData($getSchedulePeriod);
    $league = "All";
  }

  ////////////////////////////////////////
  ////////////////////////////////////////
  ////////////////////////////////////////

  foreach ($scores as $key =>$value) {
      ($value['away_h1'] == null) ? $ah1 = '' : $ah1 = $value['away_h1'];
      ($value['away_h2'] == null) ? $ah2 = '' : $ah2 = $value['away_h2'];
      ($value['away_ot'] == null) ? $aot = '' : $aot = $value['away_ot'];
      ($value['away_pk'] == null) ? $apk = '' : $apk = $value['away_pk'];
      ($value['away_final'] == null) ? $af = '-' : $af = $value['away_final'];

      ($value['home_h1'] == null) ? $hh1 = '' : $hh1 = $value['home_h1'];
      ($value['home_h2'] == null) ? $hh2 = '' : $hh2 = $value['home_h2'];
      ($value['home_ot'] == null) ? $hot = '' : $hot = $value['home_ot'];
      ($value['home_pk'] == null) ? $hpk = '' : $hpk = $value['home_pk'];
      ($value['home_final'] == null) ? $hf = '-' : $hf = $value['home_final'];

      array_push($games,array('i'=>$value['game_id'],
                              'at'=>$value['away_team'],
                              'ai'=>$value['away_id'],
                              'ht'=>$value['home_team'],
                              'hi'=>$value['home_id'],
                              'w'=>$value['week'],
                              'gd'=>date('D, M d', strtotime($value['game_date'])),
                              'gt'=>date('g:i A', strtotime($value['game_time'])),
                              'f'=>$value['facility'],
                              'wi'=>$value['winner'],
                              'a'=>$value['area'],
                              'a1'=>$ah1,  
                              'a2'=>$ah2,  
                              'ao'=>$aot,
                              'ap'=>$apk,
                              'af'=>$af,   
                              'h1'=>$hh1,  
                              'h2'=>$hh2,
                              'hp'=>$hpk,
                              'ho'=>$hot,  
                              'hf'=>$hf
                              ));
  }
  // encode array as json
  echo json_encode($games);
}

?>