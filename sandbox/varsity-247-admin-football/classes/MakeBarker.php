<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// set timezone
date_default_timezone_set('America/New_York');

class MakeBarker{
	/*
     * 
     */
	public function __construct(){}
	/*
     * 
     */
	public function makeScoreboard(){
		$bdb = new Database();
		$bp2p = new P2PAPI(P2P_TOKEN, "json");
		$bsked = new Schedule();
		// get the week of the season
		$week = $bsked->getWeek(V247_YEAR);
		// get all the area games from that week
		$getGames = "SELECT
		            (SELECT name FROM schools WHERE schedule.away_id = schools.school_id) AS away_team, 
		            (SELECT name FROM schools WHERE schedule.home_id = schools.school_id) AS home_team,
		             game_date,
		             game_time,
		             winner,
		             facility,
		             week,
		             away_final,
		             home_final
		             FROM schedule 
		             WHERE week = {$week} AND area = 1
		             ORDER BY winner DESC, game_date, game_time";

		$games = $bdb->getData($getGames);

		/* take array and build scoreboard */
		$scores = $this->buildScoreboard($games);

		/* p2p api location of item to update */
		$P2Purl = 'https://content-api.p2p.tribuneinteractive.com/content_items/' . P2P_BARKER_SLUG . '.json';

		/* update body of array */
		$data = array( 'content_item' => array(
			'body' => $scores
			)
		);
	    // send the content to p2p
	    $barker = $bp2p->P2PAPI_UpdateContentItem(P2P_BARKER_SLUG,$data);
	}
	/*
	 * Build the scoreboard
	 * @param $data the array of results from the spreadsheet
	 */
	private function buildScoreboard($games){
		/* html string for the scoreboard */
		$scoreboard = "<link href='http://hc-assets.s3.amazonaws.com/css/hs-scores-barker.css' rel='stylesheet' type='text/css'>
					   <style>div#barker_container{height:165px;max-width:1280px}div#barker_container div#hed_dropdown{width:100%;margin-bottom:4px}div#barker_container div#hed_dropdown h1,div#barker_container div#hed_dropdown img{display:none}div#barker_container div#buttons{left:20px;top:7px}div#barker_container div#buttons a img{width:30px;height:30px}div#barker_container div#barker{height:auto}div#barker_container .score_card{min-width:200px;min-height:0}div#barker_container table{line-height:150%}div#barker_container .info{min-height:0;font-family:Arial,Helvetica,sans-serif;font-size:12px}div#barker_container table tr.links td{width:150px}div#barker_container .links{color:#386b8d}div#barker_container .links a{text-decoration:none;font-family:Arial,Helvetica,sans-serif;font-weight:700;color:#386b8d;text-transform:uppercase;font-size:11px}div#barker_container .links a:hover{color:#ccc}@media only screen and (min-width:480px){div#barker_container div#hed_dropdown{margin-bottom:0;padding-bottom:8px;padding-top:10px}div#barker_container div#hed_dropdown h1{display:block}div#barker_container div#buttons{left:220px;top:7px}}@media only screen and (min-width:768px){div#barker_container div#hed_dropdown img{display:inline-block}div#barker_container div#buttons{left:350px}}@media only screen and (-webkit-min-device-pixel-ratio:2),only screen and (min-resolution:192dpi){div#barker_container div#hed_dropdown h1{display:block}div#barker_container div#hed_dropdown img{display:none}div#barker_container div#buttons{left:220px;top:10px}}</style>
		               <div class='group' id='barker_container'>
	                   <div id='hed_dropdown'><a href='http://www.courant.com/sports/high-schools/" . P2P_STORY_SLUG . "-htmlstory.html' target='_blank'><img src='http://hc-assets.s3.amazonaws.com/logos/varsity_24_7_logo.png' alt='varsity 24/7' /><h1>Football Week {$this->week}</h1></a><select id='leagues'></select></div>
	                   <div id='buttons'>
	                   <a href='#' id='left'><img src='http://www.trbimg.com/img-53fce391/turbine/os-left-arrow-sports-scores-barker/600.png'></a>
	                   <a href='#' id='right'><img src='http://www.trbimg.com/img-53fce404/turbine/os-right-arrow-sports-scores-barker/600.png'></a>
	                   </div>
	                   <div id='barker' class='clearfix'>";

		foreach($games AS $game){
	    	
		    // check game status 
		    if($game['winner'] != NULL){
		      	$game_status = "Final";
		    }else{
		      	$game_status = date('g:i A', strtotime($game['game_time']));
		    }
		    //convert game date
		    $game_date = date('D, M d', strtotime($game['game_date']));

		    //get total scores
		    if($game['away_final'] != NULL && $game['home_final'] != NULL){
		    	$away_score = $game['away_final'];
		    	$home_score = $game['home_final'];
		    }
		    else{
				$away_score = '-';
		    	$home_score = '-';
		    }
		    //abbreviate long names
		    if(strpos($game['away_team'], '/') !== FALSE ){ 
		    	$away_team = str_replace(" ","",$game['away_team']); 
		    }
		    else{
		    	$away_team = $game['away_team'];
		    }
		    if(strpos($game['home_team'], '/') !== FALSE){ 
		    	$home_team = str_replace(" ","",$game['home_team']); 
		    }
		    else{
		    	$home_team = $game['home_team'];
		    }

		    $scoreboard .= "<div class='score_card'>
		                    <div class='card_strip'>
		                    <p class='date'>{$game_date}</p><p class='period'>{$game_status}</p>
		                    </div>
		                    <div class='info'>
		                    <table><tbody>
		                    <tr class='away_team'>
		                    <td class='away_team_name'>{$away_team}</td>
		                    <td class='away_team_score'>{$away_score}</td>
		                    </tr>
		                    <tr class='home_team'>
		                    <td class='home_team_name'>{$home_team}</td>
		                    <td class='home_team_score'>{$home_score}</td>
		                    </tr>
		                    <tr class='links'><td class='story_link'><a target='_blank' href='http://www.courant.com/sports/high-schools/" . P2P_STORY_SLUG . "-htmlstory.html?period={$game['game_date']}'>Full Scoreboard</a></td></tr>
		                    </tbody></table>
		                    </div>
		                    </div>";
	    }
	    $scoreboard .= "</div>
	    				<div id='score_links'>
	    				  <p>Varsity 24/7: 
						    <a target='_parent' href='http://www.courant.com/sports/high-schools/" . V247_BSOC . "-htmlstory.html'>Boys Soccer</a>
						    &nbsp;|&nbsp; 
						    <a target='_parent' href='http://www.courant.com/sports/high-schools/" . V247_GSOC . "-htmlstory.html'>Girls Soccer</a>
						  	<span class='hide_show'>&nbsp;&nbsp;More Sports:
							  <a target='_parent' href='http://www.courant.com/sports/high-schools/" . V247_YEST . "-story.html'>Yesterday&rsquo;s Results</a>
								&nbsp;|&nbsp; 
							  <a target='_parent' href='http://www.courant.com/sports/high-schools/" . V247_TODAY . "-story.html'>Today&rsquo;s Schedule</a>
							</span>
						  </p>
						</div>
	                    </div> 
	                    <script async='' src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
		                <script src='http://hc-assets.s3.amazonaws.com/js/hs-scores-barker.js'></script>";

	    return $scoreboard;
	}
}

?>