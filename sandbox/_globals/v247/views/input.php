<div class='game_score'>
	<form role="form" name="add_game_input_form" method="post" action="admin.php">
			<div class="row add_game_input">
		      <div class="col-md-7 col-lg-8 score_input">
				  <table id="wk1_1">
					<thead>
						<tr>
							<th class="winner">Area Game</th>
							<th class="team_name">Team</th>
						</tr>
					</thead>
					<tbody>
						<tr class="area_toggle">
							<td rowspan="2">
								<div class="" data-toggle="buttons">
								  <label class="form-control btn btn-default top">
								    <input type="radio" name="area" id="rad_true" value="1" autocomplete="off"> Y
								  </label>
								  <label class="form-control btn btn-default bottom">
								    <input type="radio" name="area" id="rad_false" value="0" autocomplete="off"> N
								  </label>
								</div>
							</td>
							<td class="team_name">
								<select id="away_id" name="away_id" class="form-control">
									<option value="">Select Away team</option>
									<?php 
										foreach($updateSchools AS $school){
											echo "<option value='" . $school['school_id'] . "'>" . $school['name'] . "</option>";
										} 
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="team_name">
								<select id="home_id" name="home_id" class="form-control">
									<option value="">Select Home team</option>
									<?php 
										foreach($updateSchools AS $school){
											echo "<option value='" . $school['school_id'] . "'>" . $school['name'] . "</option>";
										} 
									?>
								</select>
							</td>
						</tr>
					</tbody>
				  </table>
			  </div>
			  <div class="col-md-5 col-lg-4 game_input">
				<table>
					<thead>
						<tr><th class="date">Date (2015-12-14)</th><th class="time">Time (18:30:00)</th></tr>
					</thead>
					<tbody>
						<tr>
							<td class="date"><input class="form-control" type="text" name="game_date" maxlength="10" placeholder="0000-00-00 Y-M-D" value=""></td>
							<td class="time"><input class="form-control" type="text" name="game_time" maxlength="8" placeholder="00:00:00 H:M:S" value=""></td>
						</tr>
						<tr>
							<td class="location" colspan="3"><input class="form-control" type="text" placeholder="Facility Name" name="facility" value=""></td>
						</tr>
					</tbody>
				</table>
			  </div>
		  </div>
		  <div class="row error_message"><div class="col-md-12"><p class="border-all"></p></div></div>
		  <div class="row">
		  	  <div class="col-md-7 col-lg-8">
		  		
		  	  </div>
			  <div class="game_scoring_input">     
			      <div class="col-md-5 col-lg-4 add_button">
			        <input type="submit" name="add_game" class="btn btn-primary" value="Add Game" />
			      </div>
			  </div>
		  </div>
	</form>
</div>