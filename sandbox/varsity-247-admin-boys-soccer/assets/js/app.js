// get the alert message
message = $("#input_message p").text();
// show the alert message
if(message != ''){
	$("#input_message").fadeIn('fast');
}
// all of the games
scores = $(".game_score .score_input");
// check for scores and add them up
$.each(scores,function(){
	test = 'y';
	getWinner(this);
});
// when someone inputs a score, add it up
$(".game_score .qtr input").blur(function(){
	game = $(this).closest('div.score_input');
	test = 'x';
	getWinner(game);
});

////////////////////////////////////////////////////////////////////////////////////////
//////////// FUNCTIONS HERE ////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

/*
 * addScores
 * adds the score of the game as the user inputs it
 * @param game, the game whose score were checking
 */
function addScores(game){
	// declare away and home score vars
	away_score = 0;
	home_score = 0;
	// find the away and home rows
	away = $(game).find(".away .qtr input");
	home = $(game).find(".home .qtr input");
	// get the scores in each box and add them
	$.each(away, function(){
		if($(this).val() != ""){
			away_score += parseInt($(this).val());
		}
	});
	$.each(home, function(){
		if($(this).val() != ""){
			home_score += parseInt($(this).val());
		}
	});
    // set scores into final input box
	$(game).find(".away .qtr input[name='away_final']").val(away_score);
	$(game).find(".home .qtr input[name='home_final']").val(home_score);
	// find the winner of the game
	getWinner(game);
}
/*
 * 
 */
function getWinner(game){
	// get ot val
	$ot = $(game).find(".away .qtr input[name='away_ot']").val();
	// get pk val
	$pk = $(game).find(".away .qtr input[name='away_pk']").val();
	// check if the game went to ot
	if($ot != ''){
		// check if the game went to pk
		if($pk != ''){
			away_score = $(game).find(".away .qtr input[name='away_pk']").val();
			home_score = $(game).find(".home .qtr input[name='home_pk']").val();
		}
		// went to ot but no pks
		else{
			away_score = $(game).find(".away .qtr input[name='away_ot']").val();
			home_score = $(game).find(".home .qtr input[name='home_ot']").val();
		}	
	}
	// no ot, finished in regular time
	else{
		away_score = $(game).find(".away .qtr input[name='away_final']").val();
		home_score = $(game).find(".home .qtr input[name='home_final']").val();
	}
	// figure out who the winner is and mark the correct box
	if(parseInt(away_score) > parseInt(home_score)){
		$(game).find("#rad_away").prop("checked",true);
	}
	else if(parseInt(away_score) < parseInt(home_score)){
		$(game).find("#rad_home").prop("checked",true);
	}
	else{
		$(game).find("#rad_away").prop("checked",false);
		$(game).find("#rad_home").prop("checked",false);
	}
}

////////////////////////////////////////////////////////////////////////////////////////
//////////// EVENTS HERE ///////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

// close message box
$("#input_message span").click(function(){
	$("#input_message").fadeOut('fast');
});
// change button label on toggle
$("#score_nav_button button").click(function(){
	$("#score_nav_wrapper").slideToggle("fast");
	if( $(this).hasClass('active') )
	  $(this).text('Show Filters');
	else
	  $(this).text('Hide Filters');

	$(this).toggleClass('active');
});
// display score nav when med or greater
$( window ).resize(function() {
  if($(window).width() >= 768){
  	$("#score_nav_wrapper").css("display","block");
  }else{
  	$("#score_nav_wrapper").css("display","none");
  }
});
// slide up and down story input boxes
$(".game_story_input p.input_label").click(function(){
	$(this).next().slideToggle();
});
// slide up and down story input boxes
$(".game_note_input p.input_label").click(function(){
	$(this).next().slideToggle();
});
// slide up and down scoring input boxes
$(".game_scoring_input p.input_label").click(function(){
	$(this).next().slideToggle();
});
// update the checked radio button
$("label.top").click(function(){
	$(this).children().prop('checked', true);
	$(this).next().children().prop('checked',false);
});
$("label.bottom").click(function(){
	$(this).children().prop('checked', true);
	$(this).prev().children().prop('checked',false);
});

////////////////////////////////////////////////////////////////////////////////////////
//////////// VALIDATIONS ///////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

// make sure abbr and q1 -> q4 is filled out
$(".update_button button").click(function(e){
	error = 0;
	// get the game table
	game = $(this).closest(".game_score");
	// get the error message
	message = $(game).find(".error_message");
	console.log(message);
	// get all the home/away inn scores
	away = $(game).find(".away .qtr input");
	home = $(game).find(".home .qtr input");
	// make sure the scores are numbers
	$.each(away, function(){
		if($(this).val() != ""){
			num = parseInt($(this).val());
			if(isNaN(num)){
				error++;	
			}
		}
	});
	$.each(home, function(){
		if($(this).val() != ""){
			num = parseInt($(this).val());
			if(isNaN(num)){
				error++;
			}
		}
	});
	if(error > 0){
		message.css("display","block");
		message.find("p").text("Only input numbers into the quarter scores.");
		e.preventDefault();
	}else{
		// get team names
		ateam = $(game).find(".away .team_name select option:checked").text();
		hteam = $(game).find(".home .team_name select option:checked").text();
		// get overtime
		otaway = $(game).find(".away .qtr input[name='away_ot']").val();
		othome = $(game).find(".home .qtr input[name='home_ot']").val();
		console.log($(game).find(".home .qtr input[name='home_ot']").val());
		// get final scores
		afinal = $(game).find(".away .qtr input[name='away_final']").val();
		hfinal = $(game).find(".home .qtr input[name='home_final']").val();
		console.log($(game).find(".home .qtr input[name='home_final']").val());
		// get penalty kicks
		pkaway = $(game).find(".away .qtr input[name='away_pk']").val();
		pkhome = $(game).find(".home .qtr input[name='home_pk']").val();

		// get saves goals
		agoals = $(game).find(".scoring_input input[name='away_goals']").val();
		hgoals = $(game).find(".scoring_input input[name='home_goals']").val();
		asaves = $(game).find(".scoring_input input[name='away_saves']").val();
		hsaves = $(game).find(".scoring_input input[name='home_saves']").val();
		// get scoring plays
		note = $(game).find(".note_input textarea").val();
		// get game head
		head = $(game).find(".story_input input").val();
		// get game story
		story = $(game).find(".story_input textarea").val();
		// pop up a confirmation message
		myconfirm = window.confirm("Are you sure you want to submit:\r\r" +  
								    ateam + ": " + afinal + "\r" + 
			                        hteam + ": " + hfinal + "\r\r" +
			                        "OT Away" + ": " + otaway + "\r" + 
			                        "OT Home" + ": " + othome + "\r\r" +
			                        "PK Away" + ": " + pkaway + "\r" + 
			                        "PK Home" + ": " + pkhome + "\r\r" +
			                        "Away Goals: " + agoals + "\r\r" +
			                        "Home Goals: " + hgoals + "\r\r" +
			                        "Away Saves: " + asaves + "\r\r" +
			                        "Home Saves: " + hsaves + "\r\r" +
			                        "Of Note: " + note + "\r\r" +
			                        "Game Head: " + head + "\r\r" + 
			                        "Game Story: " + story
			                       );
		// if yes is selected, submit the form
		if(myconfirm == true){
			return true;
		}else{
			return false;
		}
	}
}); 
// confirm you want to add the game
$(".add_button input").click(function(e){
	console.log("add button was clicked");
	// declare error/success messages
	err_message = "";
	suc_message = "";
	// get the game info
	game = $(this).closest(".game_score");
	// check if area has a value
	area = [$(game).find("input:radio[name=area]:checked").val(),"Area is not checked. "];
	// check if away team has a value
	away = [$(game).find("select[name='away_id'] option:selected").text(),"A away team was not selected. "];
	// check if home team has a value
	home = [$(game).find("select[name='home_id'] option:selected").text(),"A home team was not selected. "];
	// check if date has a value
	date = [$(game).find("input:text[name='game_date']").val(),"A game date was not submitted. "];
	// check if time has a value
	time = [$(game).find("input:text[name='game_time']").val(),"A game time was not submitted. "];
	// check if facility name has a value
	fac = [$(game).find("input:text[name='facility']").val(),"A facility was not submitted. "];
	// assign all values and messages to array
	validate = [area,away,home,date,time,fac];
	// build the error message
    $.each(validate, function(){
    	if(this[0] == "" || this[0] == undefined){
    		err_message += this[1];
    	}
    });
    //check if the message has anything in it
    if(err_message != ""){
        // turn on the error message
    	themessage = $(".error_message");
    	themessage.css("display","block");
		themessage.find("p").text(err_message);
		// prevent form from being submitted
		e.preventDefault();
    }
    if(err_message == ""){
    	// build success message
    	suc_message = "Are you sure you want to add:" + validate[1][0] + " at " + validate[2][0];
    	// send confirmation pop up
    	myconfirm = window.confirm(suc_message);
    	if(myconfirm == true){
			return true;
		}else{
			return false;
		}
    }

});
// confirm you want to delete the game
$(".delete_button input").click(function(e){
	// get the game info to delete
	game = $(this).closest(".game_score");
	// get home team and away team names
	ateam = $(game).find("input.hidden_away").val();
	hteam = $(game).find("input.hidden_home").val();
	// build the delete message
	myconfirm = window.confirm("Are you sure you want to delete: " + ateam + " at " + hteam);
	// check for confirmation
	if(myconfirm == true){
		return true;
	}else{
		return false;
	}
}); 