<?php
	// initialize $conf
	$conf = NULL;

	foreach($schedule as $game){
	
	($game['away_h1'] >= 0) ? $h1a = $game['away_h1'] : $h1a = 0;
	($game['away_h2'] >= 0) ? $h2a = $game['away_h2'] : $h2a = 0;
	($game['away_ot'] >= 0) ? $ota = $game['away_ot'] : $ota = 0;
	($game['away_final']  >= 0) ? $af = $game['away_final'] : $af = 0;
	($game['away_pk'] >= 0) ? $pka = $game['away_pk'] : $pka = 0;
	
	($game['home_h1'] >= 0) ? $h1h = $game['home_h1'] : $h1h = 0;
	($game['home_h2'] >= 0) ? $h2h = $game['home_h2'] : $h2h = 0;
	($game['home_ot'] >= 0) ? $oth = $game['home_ot'] : $oth = 0;
	($game['home_final']  >= 0) ? $hf = $game['home_final'] : $hf = 0;
	($game['home_pk'] >= 0) ? $pkh = $game['home_pk'] : $pkh = 0;

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
								<th class='qtr abbr'>Abbr</th>
								<th class='team_name'>Team</th>
								<th class='qtr'>1</th>
								<th class='qtr'>2</th>
								<th class='qtr'>OT</th>
								<th class='qtr'>F</th>
								<th class='qtr'>PK</th>
							</tr>
						</thead>
						<tbody>
							<tr class='away' id='{$game['away_id']}'>
								<td class='winner'><input type='radio' id='rad_away' name='winner' value='{$game['away_id']}'></td>
								<td rowspan='2'>
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

						echo   "<td class='qtr'><input class='form-control' type='text' name='away_h1' maxlength='2' value='{$h1a}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='away_h2' maxlength='2' value='{$h2a}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='away_ot' maxlength='2' value='{$ota}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='away_final' maxlength='3' value='{$af}' /></td>
								<td class='qtr'><input class='form-control' type='text' name='away_pk' maxlength='2' value='{$pka}' /></td>
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

						echo "<td class='qtr'><input class='form-control' type='text' name='home_h1' maxlength='2' value='{$h1h}' /></td>
							  <td class='qtr'><input class='form-control' type='text' name='home_h2' maxlength='2' value='{$h2h}' /></td>
							  <td class='qtr'><input class='form-control' type='text' name='home_ot' maxlength='2' value='{$oth}' /></td>
							  <td class='qtr'><input class='form-control' type='text' name='home_final' maxlength='3' value='{$hf}' /></td>
							  <td class='qtr'><input class='form-control' type='text' name='home_pk' maxlength='2' value='{$pkh}' /></td>
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
		  if($game['area'] == 1){
		  	echo "<div class='game_scoring_input'>
				     <p class='input_label'><span class='glyphicon glyphicon-plus-sign'></span> Scoring Plays</p>
					 <div class='scoring_input'>
					   <label>{$game['away_team']} Goals</label>
					   <input class='form-control' type='text' name='away_goals' value='{$game['away_goals']}' />
					   <label>{$game['away_team']} Saves</label>
					   <input class='form-control' type='text' name='away_saves' value='{$game['away_saves']}' />
					   <label>{$game['home_team']} Goals</label>
					   <input class='form-control' type='text' name='home_goals' value='{$game['home_goals']}' />
					   <label>{$game['home_team']} Saves</label>
					   <input class='form-control' type='text' name='home_saves' value='{$game['home_saves']}' />
					 </div>
				   </div>
				   <div class='game_note_input'>
				     <p class='input_label'><span class='glyphicon glyphicon-plus-sign'></span> Of Note</p>
					 <div class='note_input'>
						 <textarea class='form-control' name='sum_note' value=''>{$game['sum_note']}</textarea>
					 </div>
				   </div>
				   <div class='game_story_input'>
				     <p class='input_label'><span class='glyphicon glyphicon-plus-sign'></span> Game Story</p>
					 <div class='story_input'>
						 <input class='form-control' type='text' name='sum_head' value='{$game['sum_head']}' />
						 <textarea class='form-control' name='sum_body' value=''>{$game['sum_body']}</textarea>
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
