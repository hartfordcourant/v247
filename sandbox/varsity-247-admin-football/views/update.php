<?php
	// initialize $conf
	$conf = NULL;

	foreach($schedule as $game){
	($game['away_q1'] >= 0) ? $q1a = $game['away_q1'] : $q1a = 0;
	($game['away_q2'] >= 0) ? $q2a = $game['away_q2'] : $q2a = 0;
	($game['away_q3'] >= 0) ? $q3a = $game['away_q3'] : $q3a = 0;
	($game['away_q4'] >= 0) ? $q4a = $game['away_q4'] : $q4a = 0;
	($game['away_ot'] >= 0) ? $ota = $game['away_ot'] : $ota = 0;
	($game['away_final']  >= 0) ? $af = $game['away_final'] : $af = 0;

	($game['home_q1'] >= 0) ? $q1h = $game['home_q1'] : $q1h = 0;
	($game['home_q2'] >= 0) ? $q2h = $game['home_q2'] : $q2h = 0;
	($game['home_q3'] >= 0) ? $q3h = $game['home_q3'] : $q3h = 0;
	($game['home_q4'] >= 0) ? $q4h = $game['home_q4'] : $q4h = 0;
	($game['home_ot'] >= 0) ? $oth = $game['home_ot'] : $oth = 0;
	($game['home_final']  >= 0) ? $hf = $game['home_final'] : $hf = 0;

	$cover=NULL;
	//
	($game['area'] == 1) ? $cover = 'area_game' : $cover = 'non_area_game';
	//
	if($game['area'] == 1){
		$yes_active = 'active';
		$no_active = '';
		$yes_check = 'checked';
		$no_check = '';  
	}else{
		$yes_active = '';
		$no_active = 'active';
		$yes_check = '';
		$no_check = 'checked';
	}
	//get the conferenece of the home team
	//check if the conference is already stored
	//put a label with hash if it's new
	$new_conf = substr($game['home_id'],0,strpos($game['home_id'], "_"));
	
	if($new_conf != $conf){
		$conf = $new_conf;
		echo "<div id='$new_conf'><p class='game_label label'name='{$new_conf}'>{$new_conf}</p></div>";
	}
	
	echo "<div class='game_score {$cover}'>
			<form role='form' name='game_score_input_form' method='post' action='{$action}#{$game['game_id']}'>
			<div class='row game_score_input'>
		      <div class='col-md-9 score_input'>
					  <table id='{$game['game_id']}'>
						<thead>
							<tr>
								<th class='winner'>&nbsp;W</th>
								<th class='area_toggle'>Area</th>
								<th class='abbr'>Abbr</th>
								<th class='team_name'>Team</th>
								<th class='qtr'>1</th>
								<th class='qtr'>2</th>
								<th class='qtr'>3</th>
								<th class='qtr'>4</th>
								<th class='qtr'>OT</th>
								<th class='qtr'>F</th>
							</tr>
						</thead>
						<tbody>
							<tr class='away' id='{$game['away_id']}'>
								<td class='winner'><input type='radio' id='rad_away' name='winner' value='{$game['away_id']}'></td>
								<td class='area' rowspan='2'>
								<div class='' data-toggle='buttons'>
								  <label class='form-control btn btn-default top " . $yes_active . "'>
								    <input type='radio' name='area' id='rad_true' value='1' " . $yes_check . "> Y
								  </label>
								  <label class='form-control btn btn-default bottom " . $no_active . "'>
								    <input type='radio' name='area' id='rad_false' value='0' " . $no_check . "> N
								  </label>
								</div>
							</td>";

						($game['area'] == 1) ? $hide_this = "dont_hide_this" : $hide_this = "hide_this";	

						echo "<td class='abbr {$hide_this}'><input class='form-control' type='text' name='away_abbr' maxlength='3' value='{$game['away_abbr']}' /></td>";
								
						echo "<td class='team_name'><select name='update_away_team' class='form-control'>";
						    
						foreach($updateSchools AS $school){
							if($school['name'] == $game['away_team']){
								echo "<option value='" . $school['school_id'] . "' selected>" . $school['name'] . "</option>";
							}else{
								echo "<option value='" . $school['school_id'] . "'>" . $school['name'] . "</option>";
							}
						}      
						echo "</select></td>";

						echo   "<td class='qtr'><input class='form-control' type='text' name='away_q1' maxlength='2' value='{$q1a}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='away_q2' maxlength='2' value='{$q2a}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='away_q3' maxlength='2' value='{$q3a}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='away_q4' maxlength='2' value='{$q4a}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='away_ot' maxlength='2' value='{$ota}' /></td>
								<td class='override'><input class='form-control' type='text' name='away_final' maxlength='2' value='{$af}' /></td>
							</tr>
							<tr class='home' id='{$game['home_id']}'>
								<td class='winner'><input type='radio' id='rad_home' name='winner' value='{$game['home_id']}'></td>";
					
						echo "<td class='abbr {$hide_this}'><input class='form-control' type='text' name='home_abbr' maxlength='3' value='{$game['home_abbr']}' /></td>";
						
						echo "<td class='team_name'><select name='update_home_team' class='form-control'>";

						foreach($updateSchools AS $school){
							if($school['name'] == $game['home_team']){
								echo "<option value='" . $school['school_id'] . "' selected>" . $school['name'] . "</option>";
							}else{
								echo "<option value='" . $school['school_id'] . "'>" . $school['name'] . "</option>";
							}
						} 

						echo "</select></td>";

						echo   "<td class='qtr'><input class='form-control' type='text' name='home_q1' maxlength='2' value='{$q1h}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='home_q2' maxlength='2' value='{$q2h}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='home_q3' maxlength='2' value='{$q3h}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='home_q4' maxlength='2' value='{$q4h}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='home_ot' maxlength='2' value='{$oth}' /></td>
								<td class='override'><input class='form-control' type='text' name='home_final' maxlength='2' value='{$hf}' /></td>
							</tr>
						</tbody>
					  </table>
			  </div>
			  <div class='col-md-3 game_input'>
				<table>
					<thead>
						<tr><th class='date'>Date</th><th class='time'>Time</th></tr>
					</thead>
					<tbody>
						<tr>
							<td class='date'><input class='form-control' type='text' name='game_date' maxlength='10' value='{$game['game_date']}' /></td>
							<td class='time'><input class='form-control' type='text' name='game_time' maxlength='8' value='{$game['game_time']}' /></td>
						</tr>
						<tr>
							<td class='location' colspan=2><input class='form-control' type='text' name='facility' value='{$game['facility']}' /></td>
						</tr>
					</tbody>
				</table>
			  </div>
		  </div>
		  <div class='row error_message'><div class='col-md-12'><p class='border-all'></p></div></div>
		  <div class='row'>
		  <div class='col-md-9'>";

		  /////////////////////////////////
		  ///// GAME SUMMARY
		  /////////////////////////////////
		  
		  if($game['area'] == 1){
		  	echo "<div class='game_scoring_input'>
				     <p class='input_label'><span class='glyphicon glyphicon-plus-sign'></span> Scoring Plays</p>
					 <div class='scoring_input'>
						 <textarea class='form-control' name='sum_scoring' value=''>{$game['sum_scoring']}</textarea>
					 </div>
				   </div>
				   <div class='game_story_input'>
				     <p class='input_label'><span class='glyphicon glyphicon-plus-sign'></span> Game Story</p>
					 <div class='story_input'>
						 <input class='form-control' type='text' name='sum_head' value='{$game['sum_head']}' />
						 <textarea class='form-control' name='sum_body' value=''>{$game['sum_body']}</textarea>
					 </div>
				   </div>
				   <div class='game_video_input'>
				   		<p class='input_label'><span class='glyphicon glyphicon-plus-sign'></span> Video Slug</p>
						<div class='video_input'>
							<input class='form-control' type='text' name='video' value='{$game['video']}' />
						</div>
				   </div>";
		  }
		  if($game['area'] == 1){
		    echo "</div>
		          <div class='col-md-3 update_button'><button type='submit' name='update' class='btn btn-primary'>Update</button>
		          	<div class='delete_button'>
						<input type='submit' class='submit_link' name='delete_game' value='[x] Delete'>
					</div>
		          </div>
		          </div>";
		  }else{
		  	echo "</div>
		  	      <div class='col-md-3 update_button'><button type='submit' name='update_away' class='btn btn-primary'>Update</button>
					<div class='delete_button'> 
						<input type='submit' class='submit_link' name='delete_game' value='[x] Delete'>
					</div>
		  	      </div>
		  	      </div>";
		  }
		  echo "<input type='hidden' class='hidden_away' name='away_team' value='{$game['away_team']}'/>
		  	    <input type='hidden' class='hidden_home' name='home_team' value='{$game['home_team']}'/>
		  	    <input type='hidden' name='game_id' value='{$game['game_id']}'/>
		        </form></div>";
	}			    
?>