<?php

// links to spreadsheets
// go to google spreadsheet, file / publish to the web / publish as a csv 
// copy link into '' below
$schools_url = 'csv_link_to your_schools_spreadsheet';
$sked_url = 'csv_link_to your_schedule_spreadsheet';

// get spreadsheets and convert to array
$schools = array_map('str_getcsv', file($schools_url));
$sked = array_map('str_getcsv', file($sked_url));

// get rid of first row (column labels)
array_splice($schools, 0, 1);
array_splice($sked, 0, 1);

// make a csv file with a list of all home team names in your schedule
// see away.csv as example
$home = array_map('str_getcsv', file('home.csv'));
// make a csv file with a list of all home team names in your schedule
// see home.csv as example
$away = array_map('str_getcsv', file('away.csv'));

// get size of of schools array 
$size = sizeof($schools);

// get team_id from team name
// to print out away team id's use foreach($away AS ... )
// to print out hoem team id's use foreach($home AS ... )
// after printing out id's, copy them directly into spreadsheet into the appropriate column
foreach($away AS $key=>$team){
	$game = $key + 1;
	foreach($schools AS $i=>$school){
		if($team[0] == $school[1]){
			echo $school[0] . "<br>";
			break;		
		}
		elseif($i == ($size - 1)){
			echo $team[0] . "<br>";		
		}
	}
}
// check if a team is an area team
// comment out the team_id script and uncomment this when you're ready
/*foreach($sked AS $key=>$game){
	$away_team = $game[2];
	$home_team = $game[4];
	$away_area = NULL;
	$home_area = NULL;
	foreach($schools AS $school){
		if($away_team == $school[0]){
			if($school[6] == '1'){
				$away_area = TRUE; 
			}else{
				$away_area = FALSE;
			}
		}
		if($home_team == $school[0]){
			if($school[6] == '1'){
				$home_area = TRUE; 
			}else{
				$home_area = FALSE;
			}
		}
	}
	if($away_area == TRUE || $home_area == TRUE){
		echo "1<br>";
	}else{
		echo "0<br>";
	}
}*/

?>