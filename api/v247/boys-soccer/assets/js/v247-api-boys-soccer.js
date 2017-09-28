// init vars
var results;
var d;
var today;

$(document).ready(function(){
  // get the results wrapper
  results = $('#hc-mm-wrapper #search-results');
  // get todays iso date
  d = new Date();
  today = d.toISOString().substring(0, 10);
  // set the init type
  type = 'sked'
  // build query
  buildQuery(today,'','','',type,'');

  periods = $(".period-link").data('period');
});
/*
 * go get new data after click request
 */
function getData(query){
  console.log(query);
    // go get the results
    $.ajax({                                      
      //url: 'http://projects.courant.com/api/v247/boys-soccer/classes/api.php', 
      url: 'http://localhost/your_server/api/v247/boys-soccer/classes/api.php',
      data: query,
      dataType: 'json'              
    })
    // if request successful do this
    .done(function(data){
        // build the results table
        buildHTML(data,type);    
    })
     // if request failed do this
    .fail(function(xhr, status, error){
        // put a message somewhere saying there are no results, check back later.
        console.log('failed');
        console.log(error);
    });
}
/*
 * build the HTML
 */
function buildHTML(data,type){
    //console.log(data.length);
    // empty container   
    results.html('');
    
    ////////////////////////////////////////
    /// Team ///////////////////////////////
    ////////////////////////////////////////

    if(type == 'team'){
      // init ts string
      ts = '';
      ts += "<div class='team_schedule'>";
      ts += "<div id='header'><h2>" + data.info.name + "</h2>";
      ts += "<p class='details'>" + data.info.league + "</p></div>";
      ts += "<table class='results'><thead><tr><th class='date'>Date</th><th class='time'>Time</th><th class='team'>Opponent</th><th class='location'>Location</th><th class='score'>Result</th><th class='record'>Record</th></tr></thead><tbody>";
      $.each(data.games,function(){
        
        ts += "<tr><td class='date'>" + this.gd + "</td><td class='time'>" + this.gt + "</td>";
        ts += "<td class='team' data-type='team' name='" + this.o.i + "'>" + this.o.t + "</td>";
        ts += "<td class='location'>" + this.f + "</td>";
        ts += "<td class='score' data-type='game' name='" + this.i + "'>" + this.s + "</td>";
        ts += "<td class='record'>" + this.r +  "</td></tr>";
      });
      ts += "</tbody></table></div>";
      // append html to div
      results.html(ts);
    }

    ////////////////////////////////////////
    /// Game ///////////////////////////////
    ////////////////////////////////////////

    else if(type == 'game'){
      console.log(data);
      // check for winner
      if(data.wi == data.ai){
        away_win = 'win';
        home_win = '';
        sl = data.at + ' ' + data.af + ', ' + data.ht + ' ' + data.hf;
      }
      else if(data.wi == data.hi){
        away_win = '';
        home_win = 'win';
        sl = data.ht + ' ' + data.hf + ', ' + data.at + ' ' + data.af;
      }
      else if(data.wi == 'TIE'){
        away_win = '';
        home_win = '';
        sl = data.at + ' ' + data.af + ', ' + data.ht + ' ' + data.hf + ' (OT)';
      }
      else{
        away_win = '';
        home_win = '';
        sl = data.at + ' at ' + data.ht;
      }
      // if there's ot, update the label and the thead
      (data.ao != '') ? ot = 'OT' : ot = '&nbsp;';
      
      // init gs string
      gs = '';
      // build summary
      gs += "<div class='sum_wrapper clearfix'><div class='box_score'><div data-game-id='" + data.i + "' class='score_card summary'>"
      gs += "<div id='header'><h2>" + sl + "</h2>";
      gs += "<p class='details'>" + data.gd + ", " + data.gt + ", " + data.f + "</p></div>";
      gs += "<table><thead><tr><th class='team'>&nbsp;</th><th class='inn'>1</th><th class='inn'>2</th><th class='inn'>" + ot + "</th><th class='inn'>F</th></tr></thead>";
      // build the score table
      gs += "<tbody>";
      gs += "<tr data-team-id='" + data.ai + "' class='" + away_win + "'>";
      gs += "<td class='team' data-type='team' name='" + data.ai + "'>" + data.at + "</td>";
      gs += "<td class='inn'>" + data.a1 + "</td>";
      gs += "<td class='inn'>" + data.a2 + "</td>";
      gs += "<td class='inn'>" + data.ao + "</td>";  
      gs += "<td class='inn total'>" + data.af + "</td></tr>";
      gs += "<tr data-team-id='" + data.hi + "' class='" + home_win + "'>";
      gs += "<td class='team' data-type='team' name='" + data.hi + "'>" + data.ht + "</td>";
      gs += "<td class='inn'>" + data.h1 + "</td>";
      gs += "<td class='inn'>" + data.h2 + "</td>";
      gs += "<td class='inn'>" + data.ho + "</td>";      
      gs += "<td class='inn total'>" + data.hf + "</td></tr>";
      gs += "</tbody></table>"
      gs += "</div>";
      // build the scoring plays
      gs += "<div class='scoring_plays'><ul>";
      // display goals 
      if(data.ag != null || data.hg != null){ 

        if(data.ag != null && data.hg != null){
          gs += "<li><span class='make-bold'>Goals: </span>" + data.ab + "&mdash;" + data.ag + "; " + data.hb + "&mdash;" + data.hg + "</li>";
        }
        else if(data.ag != null && data.hg == null){
          gs += "<li><span class='make-bold'>Goals: </span>" + data.ab + "&mdash;" + data.ag + "</li>";
        }
        else{
          gs += "<li><span class='make-bold'>Goals: </span>" + data.hb + "&mdash;" + data.hg + "</li>";
        }
      }
      if(data.as != null || data.hs != null){
        // display saves
        if(data.as != null && data.hs != null){
          gs += "<li><span class='make-bold'>Saves: </span>" + data.ab + "&mdash;" + data.as + "; " + data.hb + "&mdash;" + data.hs + "</li>";
        }
        else if(data.as != null && data.hs == null){
          gs += "<li><span class='make-bold'>Saves: </span>" + data.ab + "&mdash;" + data.as + "</li>";
        }
        else{
          gs += "<li><span class='make-bold'>Saves: </span>" + data.hb + "&mdash;" + data.hs + "</li>";
        }
      }
      if(data.sn != null){
        gs += "<li><span class='make-bold'>Of Note: </span>" + data.sn + "</li>";
      }
      gs += "</ul></div></div>";
      // put game story here
      gs += "<div class='game_story'>";
      // check if there's a game story
      if(data.sb != null){
          // get game story and break paragraphs into array
          story = data.sb.split('\n');
          // build week date time label
          gs += "<p class='section_label'>Period " + data.w + "</p>";
          // add headline
          gs += "<h3>" + data.sh + "</h3>";
          // add each paragraph
          $.each(story,function(k,p){
              gs += "<p class='game_body'>" + p + "</p>"; 
          });
      }   
      gs += "</div>";
      gs += "</div></div>";
      // append html to div
      results.html(gs);
    }

    ////////////////////////////////////////
    /// Standings //////////////////////////
    ////////////////////////////////////////
    
    else if(type == 'stand'){
      // init ls string
      ls = '';
      ls += "<div class='standings'>";
      console.log(data);
      $.each(data,function(key,value){
        //league label
        ls += "<h3>" + key + "</h3>";
        ls += "<div class='division'>";
        ls += "<table class='results'><thead><th class='team_name league'>&nbsp;<br>&nbsp;</th><th class='stand dwl'>Division<br>W - L - T</th><th class='stand dpf'>Division<br>PF - PA</th><th class='stand lwl'>League<br>W - L - T</th><th class='stand lpf'>League<br>GF - GA</th><th class='stand owl'>Overall<br>W - L - T</th><th class='stand opf'>Overall<br>GF - GA</th><th class='stand points'>&nbsp;<br>Pts</th></thead><tbody>";
        //build the standings
        $.each(value,function(key,team){
            ls += "<tr><td class='team_name' data-name='" + team.i + "'>" + team.n + "</td><td class='stand dwl'>&nbsp;</td><td class='stand dpf'>&nbsp;</td><td class='stand lwl'>" + team.l.w + "-" + team.l.l + "-" + team.l.t + "</td><td class='stand lpf'>" + team.l.p + "-" + team.l.a + "</td><td class='stand owl'>" + team.o.w  + "-" +  team.o.l  + "-" +  team.o.t + "</td><td class='stand opf'>" + team.o.p  + "-" +  team.o.a + "</td><td class='stand points'>" + team.p + "</td></tr>";
        });
        ls += "</tbody></table></div>";
      });
      ls += "</div>";
      // append html to div
      results.html(ls);
    }
    ////////////////////////////////////////
    /// Scoreboard /////////////////////////
    ////////////////////////////////////////

    else if(type == 'sked'){

      // init ws string
      ws = '';
      // build game results
      $.each(data, function(key,value){
        // check for video
        if(value.v != null){
          vid = ", <a href='http://www.courant.com/sports/high-schools/" + value.v + "-htmlstory.html' target='_blank'>Game Video</a>"
        }else{
          vid = "";
        }
        // set up all the vars
        // 1. get left/right class
        (key%2 == 1) ? clear = 'right' : clear = 'left';
        // 2. find out if the game is over
        (value.wi == null) ? time = value.gt : time = 'Final';
        // 3. check for winner or tie
        // check if home or away won and add class of win to that row
        (value.af > value.hf) ? aw = 'win' : aw = '';
        (value.af < value.hf) ? hw = 'win' : hw = '';
        // 4. check for summary
        if(value.af != '-' && value.hf != '-'){
          gid = value.i;
          fac = "<span class='game_link' data-game='" + value.i + "'>Summary</span>";
          hov = 'hover';
        }else{
          gid = '';
          fac = "<span class='game_link' data-game='" + value.i + "'>" + value.f + "</span>";
          hov = '';
        }
        // if there's ot, update the label and the thead
        (value.ao != '') ? ot = 'OT' : ot = '&nbsp;';

        ws += "<div class='score_card " + clear + "' id='" + value.i + "'>";
        ws += "<div class='strip'><p class='date'>" + value.gd + "</p><p class='period'>" + time + "</p></div>";
        ws += "<table><thead><tr><th class='team'>&nbsp;</th><th class='inn'>1</th><th class='inn'>2</th><th class='inn'>" + ot + "</th><th class='inn'>F</th></tr></thead>";
        ws += "<tbody>";
        ws += "<tr class='" + aw + "' id='" + value.ai + "'>";
        ws += "<td class='team' data-type='team' name='" + value.ai + "'>" + value.at + "</td>";
        ws += "<td class='inn'>" + value.a1 + "</td>";
        ws += "<td class='inn'>" + value.a2 + "</td>";
        ws += "<td class='inn'>" + value.ao + "</td>";
        ws += "<td class='inn total'>" + value.af + "</td>";
        ws += "</tr></tbody></table>";
        ws += "<table><tbody>";
        ws += "<tr class='" + hw + "' id='" + value.hi + "'>";
        ws += "<td class='team' data-type='team' name='" + value.hi + "'>" + value.ht + "</td>";
        ws += "<td class='inn'>" + value.h1 + "</td>";
        ws += "<td class='inn'>" + value.h2 + "</td>";
        ws += "<td class='inn'>" + value.ho + "</td>"; 
        ws += "<td class='inn total'>" + value.hf + "</td>";
        ws += "</tr></tbody></table>";
        ws += "<div class='facility'><p class='" + hov + "'>" + fac + "</p></div>";
        ws += "</div>";

      });
      // append html to div
      results.html(ws);
    }
    
    
}
/*
 * build query to send to db
 */
 function buildQuery(period,league,team,game,type,stand){
    // set sport
    sport = 'soccer';
    // set season
    year = '2017';
    // check for date, assign today if empty
    if(period == ''){
      period = today;
    }
    // build the query
    query = 'sport='+sport+'&year='+year+'&period='+period+'&league='+league+'&team='+team+'&game='+game+'&stand='+stand;
    // get the results
    getData(query,type);
 }
/* 
 * Get url parameters
 * @param sParam 
 * @return param the value of the param
 */
function getSearchParams(k){
   var p={};
   location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
   return k?p[k]:p;
}

///////////////////////////////////
/// EVENTS ////////////////////////
///////////////////////////////////

/*
 * standings/sked buttons
 */
$('.stand_link').click(function(e){
  // highlight correct week
  $('button.sked_link','a.sked_link').removeClass('active');
  $('button.stand_link','a.stand_link').addClass('active');
  $('a.sked_link').removeClass('active');
  $('a.stand_link').addClass('active');
  // set period to empty
  period = '';
  // set league to empty
  league = '';
  // set the team
  team = '';
  // set the game
  game = '';
  // set the type
  type = 'stand';
  // set the stand
  stand = 'stand';
  // build query
  buildQuery(period,league,team,game,type,stand);
});
$('.sked_link').click(function(e){
  // highlight correct week
  $('button.stand_link','a.stand_link').removeClass('active');
  $('button.sked_link','a.sked_link').addClass('active');
  $('a.stand_link').removeClass('active');
  $('a.sked_link').addClass('active');
  // set period to empty
  period = today;
  // set league to empty
  league = '';
  // set the team
  team = '';
  // set the game
  game = '';
  // set the type
  type = 'sked';
  // set the stand
  stand = '';
  // build query
  buildQuery(period,league,team,game,type,stand);
});
/*
 * get teams season results
 */
$('#search-results').on('click', '.team', function (e) {
  // set period to empty
  period = '';
  // set league to empty
  league = '';
  // set the team
  team = $(this).attr('name');
  // set the game
  game = '';
  // set the type
  type = 'team';
  // set the stand
  stand = '';
  // build query
  buildQuery(period,league,team,game,type,stand);
});
/*
 * get game summary
 */
$('#search-results').on('click', '.facility p span.game_link', function (e) {
  period = '';
  // set league to empty
  league = '';
  // set the team
  team = '';
  // set the game
  game = $(this).data('game');
  // set the type
  type = 'game';
  // set the stand
  stand = '';
  // build query
  buildQuery(period,league,team,game,type,stand);
});
/*
 * get selected league/week results
 */
$('.period-link').click(function(e){
  // highlight correct week
  $('.period-link').removeClass('active');
  $(this).addClass('active');
  // get selected period
  period = $(this).data('period');
  // get selected league
  league = $('#period-nav select[name="league_desk"]').val();
  // set team to empty
  team = '';
  // set the game
  game = '';
  // set the type
  type = 'sked';
  // set the stand
  stand = '';
  // build query
  buildQuery(period,league,team,game,type,stand);
});
/*
 * get selected league/week results
 */
$('#period-nav select[name="period"]').change(function(e){
  // get selected period
  period = $(this).val();
  // get selected league
  league = $('#period-nav select[name="league_mobile"]').val();
  // set team to empty
  team = '';
  // set the game
  game = '';
  // set the type
  type = 'sked';
  // set the stand
  stand = '';
  // build query
  buildQuery(period,league,team,game,type,stand);
});
/*
 * get selected league/week results
 */
$('#period-nav select[name="league_desk"]').change(function(e){
  // get selected week
  period = $('.period-link.active').data('period');
  // get selected league
  league = $(this).val();
  // set team to empty
  team = '';
  // set the game
  game = '';
  // set the type
  type = 'sked';
  // set the stand
  stand = '';
  // build query
  buildQuery(period,league,team,game,type,stand);
});
/*
 * get selected league/week results
 */
$('#period-nav select[name="league_mobile"]').change(function(e){
  // get selected week
  period = $('#period-nav select[name="period"]').val();
  // get selected league
  league = $(this).val();
  // set team to empty
  team = '';
  // set the game
  game = '';
  // set the type
  type = 'sked';
  // set the stand
  stand = '';
  // build query
  buildQuery(period,league,team,game,type,stand);
});
/*
 * open/close mobile menu
 */
$("#menu-icon").click(function(){
  $("#mobile").slideToggle();
});