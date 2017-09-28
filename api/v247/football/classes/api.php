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
$footsked = new Schedule();
$p2p = new P2PAPI(P2P_TOKEN, "json"); 

// get params from request
// what sport is it, which db are we selecting?
isset($_GET['sport']) ? $sport = $_GET['sport'] : $sport = "football";
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
  ($game_info[0]['away_q1'] == null) ? $aq1 = '' : $aq1 = $game_info[0]['away_q1'];
  ($game_info[0]['away_q2'] == null) ? $aq2 = '' : $aq2 = $game_info[0]['away_q2'];
  ($game_info[0]['away_q3'] == null) ? $aq3 = '' : $aq3 = $game_info[0]['away_q3'];
  ($game_info[0]['away_q4'] == null) ? $aq4 = '' : $aq4 = $game_info[0]['away_q4'];
  ($game_info[0]['away_ot'] == null) ? $aot = '' : $aot = $game_info[0]['away_ot'];
  ($game_info[0]['away_final'] == null) ? $af = '-' : $af = $game_info[0]['away_final'];
  // get home scores
  ($game_info[0]['home_q1'] == null) ? $hq1 = '' : $hq1 = $game_info[0]['home_q1'];
  ($game_info[0]['home_q2'] == null) ? $hq2 = '' : $hq2 = $game_info[0]['home_q2'];
  ($game_info[0]['home_q3'] == null) ? $hq3 = '' : $hq3 = $game_info[0]['home_q3'];
  ($game_info[0]['home_q4'] == null) ? $hq4 = '' : $hq4 = $game_info[0]['home_q4'];
  ($game_info[0]['home_ot'] == null) ? $hot = '' : $hot = $game_info[0]['home_ot'];
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
              'a1'=>$aq1,  
              'a2'=>$aq2, 
              'a3'=>$aq3,  
              'a4'=>$aq4,  
              'ao'=>$aot,
              'af'=>$af,   
              'h1'=>$hq1,  
              'h2'=>$hq2,
              'h3'=>$hq3,  
              'h4'=>$hq4,
              'ho'=>$hot,  
              'hf'=>$hf,
              'sh'=>$game_info[0]['sum_head'],
              'sb'=>$game_info[0]['sum_body'],
              'ss'=>$game_info[0]['sum_scoring'] 
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
    
    if($i != 'constitution state'){
      $league_up = ucwords($i);
      $standings[$league_up] = [];
      foreach ($league as $j => $division) {
        //echo $division[0][2] . "<br>";
        $standings[$league_up][$division[0][2]] = [];
        foreach ($division as $k => $team) {
          //echo $team[0] . "<br>";
          array_push($standings[$league_up][$division[0][2]],
            array("n"=>$team[0],
                  "i"=>$team[19],
                  "d"=>array(
                    "w"=>$team[4],
                    "l"=>$team[5],
                    "t"=>$team[6],
                    "p"=>$team[7],
                    "a"=>$team[8]),
                  "l"=>array(
                    "w"=>$team[9],
                    "l"=>$team[10],
                    "t"=>$team[11],
                    "p"=>$team[12],
                    "a"=>$team[13]),
                  "o"=>array(
                    "w"=>$team[14],
                    "l"=>$team[15],
                    "t"=>$team[16],
                    "p"=>$team[17],
                    "a"=>$team[18])
          ));
        }
      }
    }else{
      $league_up = ucwords($i);
      $standings[$league_up] = [];
      foreach ($league as $team) {
        array_push($standings[$league_up],
            array("n"=>$team[0],
                  "i"=>$team[19],
                  "l"=>array(
                    "w"=>$team[9],
                    "l"=>$team[10],
                    "t"=>$team[11],
                    "p"=>$team[12],
                    "a"=>$team[13]),
                  "o"=>array(
                    "w"=>$team[14],
                    "l"=>$team[15],
                    "t"=>$team[16],
                    "p"=>$team[17],
                    "a"=>$team[18])
          ));
      }
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
    $period = $footsked->getDisplayPeriod($year,$period);
    $getSchedule = "Call getSchedule('$period[0]','$period[1]','$league_out')";
    $scores = $db->getData($getSchedule);
  }
  // if period is selected and league is not
  elseif($params['period'] != '' && $params['league'] == ''){
    $period = $footsked->getDisplayPeriod($year,$period);
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
    $period = $footsked->getDisplayPeriod($year,$today);
    $getSchedulePeriod = "Call getSchedulePeriod('$period[0]','$period[1]')";
    $scores = $db->getData($getSchedulePeriod);
    $league = "All";
  }

  ////////////////////////////////////////
  ////////////////////////////////////////
  ////////////////////////////////////////

  foreach ($scores as $key =>$value) {
      ($value['away_q1'] == null) ? $aq1 = '' : $aq1 = $value['away_q1'];
      ($value['away_q2'] == null) ? $aq2 = '' : $aq2 = $value['away_q2'];
      ($value['away_q3'] == null) ? $aq3 = '' : $aq3 = $value['away_q3'];
      ($value['away_q4'] == null) ? $aq4 = '' : $aq4 = $value['away_q4'];
      ($value['away_ot'] == null) ? $aot = '' : $aot = $value['away_ot'];
      ($value['away_final'] == null) ? $af = '-' : $af = $value['away_final'];

      ($value['home_q1'] == null) ? $hq1 = '' : $hq1 = $value['home_q1'];
      ($value['home_q2'] == null) ? $hq2 = '' : $hq2 = $value['home_q2'];
      ($value['home_q3'] == null) ? $hq3 = '' : $hq3 = $value['home_q3'];
      ($value['home_q4'] == null) ? $hq4 = '' : $hq4 = $value['home_q4'];
      ($value['home_ot'] == null) ? $hot = '' : $hot = $value['home_ot'];
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
                              'a1'=>$aq1,  
                              'a2'=>$aq2, 
                              'a3'=>$aq3,  
                              'a4'=>$aq4,  
                              'ao'=>$aot,
                              'af'=>$af,   
                              'h1'=>$hq1,  
                              'h2'=>$hq2,
                              'h3'=>$hq3,  
                              'h4'=>$hq4,
                              'ho'=>$hot,  
                              'hf'=>$hf,
                              'v'=>$value['video'] 
                              ));
  }
  // encode array as json
  echo json_encode($games);
}

?>