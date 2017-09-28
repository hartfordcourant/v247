///////////////////////
// 1. DATABASE ////////
///////////////////////

  - Each sport's database consists of 3 tables; Schools, Leagues, Schedule
  - In the sql folder are files to build the procedures and tables for football and boys/girls soccer.
  - First create a db, open the sql files, update "CREATE DEFINER=`your_login`@`localhost`" with your admin address (there are 15 instances)
  - Import the sql into your database and it will create all the tables and populate them with our data. 
  - It's probably easier to use our data during the installation/testing before clearing the tables and importing your data.
    
    TABLE DESCRIPTIONS

    - Schools
      - school_id: varchar, id of the school used in the schedule table (ex: ccc_01, before underscore is league nick, after is num of school)
      - name: varchar, actual name of the school
      - league: int, league_id from the league table
      - division: varchar, division within league if applicable
      - class: varchar, class of school (currently not used for anything)
      - address: varchar, street address (currently not used for anything, needs lat/lng eventually)
      - area: tinyint, whether it's a school we cover or not 1/0 *
    
    - Leagues
      - league_id: int, id of the league
      - league_name: varchar, actual name of the league
      - league_nick: varchar, shortened name of the league
    
    - Schedule (showing football but fields change for different sports, soccer has halves, baseball has innings, etc ...)
      - game_id: varchar, id of game (ex: wk1_01) 
      - away_id: varchar, id of away team
      - home_id: varchar, id of home team
      - week: tinyint, week of the game
      - game_date: date, date of game (ex: 2017-09-08)
      - game_time: time, time of game (ex: 18:30:00)
      - facility: varchar, where the game is being played
      - area: tinyint, is it a game we cover (ex: 1 yes, 0 no) *
      - home_q1: int, quarter score
      - home_q2: int, quarter score
      - home_q3: int, quarter score
      - home_q4: int, quarter score
      - home_ot: int, quarter score
      - away_q1: int, quarter score
      - away_q2: int, quarter score
      - away_q3: int, quarter score
      - away_q4: int, quarter score
      - away_ot: int, quarter score
      - winner: varchar, id of winning team
      - sum_head: varchar, game story / roundup headline
      - sum_body: text, game story
      - sum_scoring: text, qtr by qtr scoring
      - away_abbr: int, 1-3 letter abbreviation for boxscore and scoring plays
      - home_abbr: int, 1-3 letter abbreviation for boxscore and scoring plays
      - away_final: varchar, 
      - home_final: varchar, 
      - video: slug of game video (we are currently working with a vendor that is trying to provide us with live streaming video, the slug of the page goes here)

 * An area game has the ability to input game story, scoring plays. it is also added to the scores barker, newsgate boxscore file and newsgate roundup file

** Below is the format you would input qtr by qtr scoring 
   First Quarter
   SW-Julian Ibes 15 run (Cameron Plourde kick)
   Second Quarter
   SW-Jimmy Tamburro 2 run (Plourde kick)
   SW-Ben Custer 45 pass from Connor Kapisak (Plourde kick)

///////////////////////
// 2. SPREADSHEETS ////
///////////////////////

  - We use google spreadsheets to build the tables. 
    - Schools: list of schools included in the schedule, same format as above
    - Leagues: list of leagues included in the schedule, same format as above
    - Schedule: working version of schedule, includes school names and school id's, doesn't include quarter scores or summary info
    - Schedule-DB: final version of schedule, no names just id's, includes all quarter scores and summary info

  - Football
    - Schools: https://docs.google.com/spreadsheets/d/1NkPNWvt_5XLA8PQjjdnrXF8pqCnt5feC2cImEpvUpDk/edit?usp=sharing
    - Leagues: https://docs.google.com/spreadsheets/d/1g8JJAbEkx8bYkXKJ_wf3JIkHqqYfAgeKklMumLNdfBA/edit?usp=sharing
    - Schedule: https://docs.google.com/spreadsheets/d/1kPLhJs72-4vma9I3S39dCW0d7k9ZnRTiHvM4yI__bKU/edit?usp=sharing
    - Schedule-DB: https://docs.google.com/spreadsheets/d/1U_fx_7I_hkQP32ZxMru12KxLS-gXnd6tN97Apb-lZAI/edit?usp=sharing 

  - Soccer
    - Schools: https://docs.google.com/spreadsheets/d/1Fi8zuB9qIPVvo3mu0XI1Gzm_qznMERzZSljKKfYiSRw/edit?usp=sharing
    - Leagues: https://docs.google.com/spreadsheets/d/1tmga7QP0SWs8BRvnNrr3VIXViVmF9zGWdd07BFcaMvE/edit?usp=sharing
    - Schedule: https://docs.google.com/spreadsheets/d/1NV60drbss3mKViRC8DoUA1e-Kq-d7RvOmSzYckYxFZs/edit?usp=sharing
    - Schedule-DB: https://docs.google.com/spreadsheets/d/1TH6qtxsQwfRa6y3eaJyAXYyeKm0FMPtY05z3ME5LmgU/edit?usp=sharing

  - Schedule helper
    - Inside the folder v247-skeds is a helper utility to convert school names to school id's for the Schedule-DB folder
    - It also will look at a game and determine wether it's an area game or not.
    - Open the index.php file and it gives instructions on what to do

  - Installation
    - When your spreadsheets are complete exports them as .csv files.
    - Empty the Leagues table from the db and then import the leagues.csv file
    - Empty the Schools table from the db and then import the schools.csv file
    - Empty the Schedule table from the db and then import the schedule.csv file  
    - It's best to do it in that order as the tables are linked together and sometimes the db gets mad if it's looking for something that isn't there.

///////////////////////
// 3. CONFIG FILES ////
///////////////////////
  
  - ADMIN CONFIG FILES

    - /sandbox/varsity-247-admin-sport/config/config.php
      
      - database constants

        - define("DB_HOST", "your_db_localhost");
        - define("DB_NAME", "your_db_name");
        - define("DB_USER", "your_db_user");
        - define("DB_PASS", "your_db_password");

      - p2p constants

        - define("P2P_TOKEN", 'your_p2p_token');
        - define("P2P_STORY_SLUG","your_p2p_story_slug");
        - define("P2P_BARKER_SLUG","your_p2p_barker_slug");

      - v247 constants (full explanations of these constants will be in sections 6-8)

        - define("V247_YEAR",year_of_the_current_season (int));
          - 2017

        - define("V247_SPORT","your_sport");
          - "Boys High School Soccer"
          - "Girls High School Soccer"
          - "High School Football"

        - define("V247_BOX","your_boxscore_text_file_name");
          - "xx-hs-boys-soccer-boxscore.txt"
          - "xx-hs-girls-soccer-boxscore.txt"
          - "xx-hs-football-boxscore.txt"

        - define("V247_ROUND","your_roundup_text_file_name");
          - "xx-hs-boys-soccer-roundup.txt"
          - "xx-hs-girls-soccer-roundup.txt"
          - "xx-hs-football-roundup.txt"

        - define("V247_YEST","your_results_slug");
          - "xx-hs-results"

        - define("V247_TODAY","your_schedule_slug");
          - "xx-hs-schedule"

        - define("V247_FOOT","your_football_slug");
          - "hc-high-school-football-results-test"
          - you need a V247_FOOT slug for soccer barkers

        - define("V247_BSOC","your_boys_soccer_slug");
          - "hc-boys-high-school-soccer-results-test"
          - you need a V247_FOOT slug for football barkers

        - define("V247_GSOC","your_girls_soccer_slug");
          - "hc-girls-high-school-soccer-results-test"
          - you need a V247_GSOC slug for football barkers

  - API CONFIG FILES

    - /api/v247/sport/config/config.php
      
      - database constants

        - define("DB_HOST", "your_db_localhost");
        - define("DB_NAME", "your_db_name");
        - define("DB_USER", "your_db_user");
        - define("DB_PASS", "your_db_password");

      - p2p constants

        - define("P2P_TOKEN", 'your_p2p_token');

        - define("P2P_SLUG","your_p2p_story_slug");
          - xx-boys-high-school-soccer-results-test
          - xx-girls-high-school-soccer-results-test
          - xx-high-school-football-results-test

      - v247 constants

        - define("V247_YEAR",year_of_the_current_season (int));
          - 2017

///////////////////////
// 4. SCHEDULE ////////
///////////////////////

  - There is a helper class called Schedule.php. This lets the system know what week of the season it is so it can show the correct games at the correct time. There are several locations for this file:
    
    - Admin
      - your_server/sandbox/varsity-247-admin-boys-soccer/classes/Schedule.php
      - your_server/sandbox/varsity-247-admin-girls-soccer/classes/Schedule.php
      - your_server/sandbox/varsity-247-admin-football/classes/Schedule.php
    
    - Api
      - your_server/api/v247/boys-soccer/classes/Schedule.php
      - your_server/api/v247/girls-soccer/classes/Schedule.php
      - your_server/api/v247/football/classes/Schedule.php
  
  - Line 11, adjust the schedule for your market
    - This is a multidimensional array containing first and last day of each period
    - [['2017-09-03','2017-09-09'],['2017-09-10','2017-09-16'] ...]
    - Our periods start on Sunday end on Saturday
    - If seasons are longer or shorter, adjust switch statements in getWeek() and getDisplayPeriod()
    - You also may need to adjust to account for playoff games
   
///////////////////////
// 5. STANDINGS ///////
///////////////////////

  - Configuring the standings requires a little more work. Most football conferences also have divisions but not all of them. For soccer there are conferences but no divisions. Each sport has a function called getStandings that puts the right teams in the right conferences and divisions.
    
    - Api

      - api/sport/classes/Utils.php

        - Line 85, create arrays named by conf_div
          - $ccc_d1e = [], central connecticut / div 1 east
          - $nvl_brass = [], naugatuck valley / brass

        - Line 115, update $record[1] and $record[2] to reflect your conference / division names
          - $record[1] = 'Central Connecticut', $record[2] = 'Div 1 East'
          - $record[1] = 'Naugatuck Valley', $record[2] = 'Brass'
        
        - Lines 91, 133, example of a conference without divisions
          - 91, $csc = [], constitution state
          - 133, $record[1] = 'Constitution State'

        - Line 181, out of state or private schools 
          - have no local conference / division
          - You don't need this but it's there if you want to use it for something

        - Line 186, sort teams in the correct order
          - Sorted by overall, conf, div
          - Update with the new conf_div array name
        
        - Line 216 the conf_div arrays get put into the conf arrays
          - $ccc = [$ccc_d1e,$ccc_d1w,$ccc_d2e,$ccc_d2w,$ccc_d3e,$ccc_d3w]
          - $nvl = [$nvl_brass,$nvl_copper,$nvl_iron]
          - you don't have to do anything if there aren't division, ex: $csc can just stay $csc
        
        - Line 224 all conf arrays are put into an associative array
          - $all_leagues = ['central connecticut'=>$ccc, ... ]

      - /api/sport/assets/js/v247-api-sport.js

        - Adjust layout based on whether conference has divisions or not
          - Line 186, standings are built here
          - Line 195, shows the conferences without divisions 
            - If conference is not "Connecticut Tech" and not "Independent" then use the division layout. Else, use the no division layout
          - Update line 195 with your conferences without divisions

        IMPORTANT
        
        - The js file lives in the above directory for development. 
          - For production it needs to be moved to an external server.
          - All hc external js files live on an amazon s3 server.
          - Need to update /api/v247/sport/index.php line 106 / 107 with the correct path 

/////////////////////////
// 6. P2P HTML STORIES //
/////////////////////////

  - You're going to need to create an html story for each sport in p2p
  - Example file
    - Name: hc-high-school-football-results
    - Path: /sports/high-schools
    - Custom Parameters
      - Disable Dateline: Yes
      - Disable Publication Date: Yes
      - HTML Story: Show Ads on the Right Hand Side: False
      - HTML Story: Display Byline: False
      - HTML Story: Show Headline: False
      - Lead Art (article): Suppress Lead Art Automation logic: On
   
  IMPORTANT

  - /api/sport/assets/js/v247-api-sport.js     
    - The js file lives in the above directory for development. 
    - For production it needs to be moved to an external server.
    - All hc external js files live on an amazon s3 server.
    - Need to update /api/v247/sport/index.php line 106 / 107 with the correct path 
  
  - /api/_globals/assets/css/v247-general.min.css
    - This is the general css file for all v247 api files
    - The ccc file lives in the above directory for development.
    - All hc external css files live on an amazon s3 server.
    - Need to update /api/v247/sport/index.php line 50 / 51 with the correct path

  - /api/sport/assets/css/overwrite.css
    - This is the css file that contains the overwrites for a specific sport
    - It's located in the above location but is by default baked into the html on line 52 in a <style> tag

//////////////////////////
// 7. P2P BLURB BARKERS //
//////////////////////////

  - Everytime an area score is updated, a blurb barker is updated with all of that weeks area games.
  
  - P2P Blurb
    - Create a blurb (for each sport) in p2p and set it in the /sandbox/varsity-247-admin-sport/config/config.php file
    - Blurbs consist of a scrolling scoreboard of the current weeks area games and links to other sports underneath
    - The function that creates the blurbs is located in /sandbox/varsity-247-admin-sport/classes/MakeBarker.php
    
    - If you keep the links below the scoreboard:
      - Set links in the config file
        - If it's a football barker, set the girls and boys soccer slugs (ex in section 3)
        - If it's a soccer barker, set the football slugs (ex in section 3)
        - Set the yesterdays results and todays games slugs (ex in section 3)
        - Adjust the links starting at line 140 in MakeBarker.php
      - The barker should be set to 165px high
    
    - If you don't want the links below the scoreboard:
      - Remove or comment out the links starting at line 140 in MakeBarker.php
      - The barker should be set to 140px high

    - The scoreboard is controlled by /sandbox/_globals/v247/assets/js/hs-scores-barker.js
    - It's currently linked to https://hc-assets.s3.amazonaws.com/js/hs-scores-barker.js
    - You can keep it linked to that or put it on your own server, just make sure you update the path

//////////////////////////
// 8. NEWSGATE FILES /////
//////////////////////////

  - Everytime an area score is updated, two text files get updated:
    - /sandbox/varsity-247-admin-sport/hc-hs-sport-boxscore.txt
    - /sandbox/varsity-247-admin-sport/hc-hs-sport-roundup.txt

  - The names of those files are set in the admin config files for each sport located at /sandbox/varsity-247-admin-sport/config/config.php
  - The function that builds these files is located in /sandbox/varsity-247-admin-sport/classes/ExportNewsgate.php
  - There are links to these files on the top right corner of the admin.php and teams.php page 

  - The boxscore file is text file that formats each of the area games into a cci/newsgate friendly boxscore.
    - The most efficient way we've found to get the boxscores into cci/newsgate is:
      - Put a pre-styled agate shape onto a page
      - Open the article shape in codefixer
      - Download the "Today's Boxscores" file and open in text edit or any raw text editor (don't use word or any program that adds hidden tags.)
      - Copy all text from the boxscores file and paste it over everything in the codefixer file. 
      - Save and it should appear properly styled. (The way we style our boxscores may be different that yours so you may have to make some adjustments)
  
  - The roundup is just a game score with a few paragraphs of text that were input into the game story textbox. You can follow the same procedure for boxscores or just cut and paste it into a cci word file.

  IMPORTANT

  - Once you copy these files to your server, check the permissions on them to make sure they are writable. Often the server will automatically default these files to read only.