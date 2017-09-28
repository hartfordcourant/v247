<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// set timezone
date_default_timezone_set('UTC');
// include all your stuff
include('config/config.php');
include('classes/Database.php');
include('classes/Schedule.php');
include('../../../_globals/p2p/p2p-api.php');

class V247Football{

  public function __construct()
  {
    $this->makeGraphic();
  }
  /*
   *
   */
  private function makeGraphic(){
    // instantiate classes
    $db = new Database();
    $p2p = new P2PAPI(P2P_TOKEN, "json");
    $sked = new Schedule();
    // get the schedule
    $weeks = $sked->getSeason(V247_YEAR);
    $wk = $sked->getWeek(V247_YEAR);
    // get all teams 
    $getAllLeagues = "SELECT * FROM leagues";
    $leagues = $db->getData($getAllLeagues);
    // build the page
    $content = $this->buildPage($leagues,$weeks,$wk);
    // update body of array 
    $data = array( 'content_item' => array(
      'body' => $content
      )
    );
    // send the content to p2p
    $page = $p2p->P2PAPI_UpdateContentItem(P2P_SLUG,$data);

    echo $content;
  }
  /*
   * Build the scoreboard barker
   * @param $data the array of results from the spreadsheet
   */
  private function buildPage($leagues,$weeks,$wk){
    //$graphic .= "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    //$graphic =  "<link rel='stylesheet' type='text/css' href='https://hc-assets.s3.amazonaws.com/css/v247-general.min.css'>";
    $graphic =  "<link rel='stylesheet' type='text/css' href='../../_globals/assets/css/v247-general.min.css'>";
    $graphic .= "<style>.score_card table tbody td.team{max-width:185px!important;padding-left:5px;text-align:left;overflow:hidden}.score_card table tbody td.qtr{width:28px;border-left:1px solid #ccc;text-align:center}.score_card table tbody td.qtr.total{font-weight:700}.central_connecticut.standings{display:block}.standings table.results .team_name{width:60%;overflow:hidden}.standings table.results th.team_name.division{color:#428bca;text-transform:uppercase;font-size:14px}.standings table.results .stand{width:20%}.standings table.results td.dpf,.standings table.results td.dwl,.standings table.results td.lpf,.standings table.results td.opf,.standings table.results th.dpf,.standings table.results th.dwl,.standings table.results th.lpf,.standings table.results th.opf{display:none}@media only screen and (min-width:768px){.standings table.results .team_name{width:55%}.standings table.results .stand{width:15%}.standings table.results td.dwl,.standings table.results th.dwl{display:table-cell}}@media only screen and (min-width:992px){.score_card table tbody td.team{width:52%}.score_card table tbody td.qtr{width:8%}.standings table.results .team_name{width:34%}.standings table.results .stand{width:11%}.standings table.results td.dpf,.standings table.results td.dwl,.standings table.results td.lpf,.standings table.results td.opf,.standings table.results th.dpf,.standings table.results th.dwl,.standings table.results th.lpf,.standings table.results th.opf{display:table-cell}}</style>";
    $graphic .= "<div id='hc-mm-wrapper'>";
    $graphic .= "<div id='wrapper' class='container'>";
    $graphic .= "<div id='branding'>
                  <div id='nav-wrapper' class='clearfix'> 
                  <img src='https://hc-assets.s3.amazonaws.com/logos/varsity_24_7_logo.png' alt='Varsity 24/7'/>
                  <nav class='clearfix'>
                  <a href='#' id='menu-icon'></a>
                  <ul>
                    <li class='active'><a href='hc-high-school-football-results-htmlstory.html'>Football</a></li>
                    <li class=''><a href='hc-girls-high-school-soccer-results-htmlstory.html'>Girls Soccer</a></li>
                    <li class=''><a href='hc-boys-high-school-soccer-results-htmlstory.html'>Boys Soccer</a></li>
                  </ul>
                  </nav></div></div>
                  <div id='mobile'>
                  <nav>
                  <ul>
                    <li class='inactive'><a href='/high-school-baseball'>Baseball</a></li>
                    <li class='inactive'><a href='/boys-high-school-basketball'>Boys Basketball</a></li>
                    <li class='inactive'><a href='/girls-high-school-basketball'>Girls Basketball</a></li>
                    <li><a href='hc-high-school-football-results-htmlstory.html'>Football</a></li>
                    <li class='inactive'><a href='/high-school-hockey'>Hockey</a></li>
                    <li class='inactive'><a href='/boys-high-school-lacrosse'>Boys Lacrosse</a></li>
                    <li class='inactive'><a href='/girls-high-school-lacrosse'>Girls Lacrosse</a></li>
                    <li><a href='hc-boys-high-school-soccer-results-htmlstory.html'>Boys Soccer</a></li>
                    <li><a href='hc-girls-high-school-soccer-results-htmlstory.html'>Girls Soccer</a></li>
                    <li class='inactive'><a href='/high-school-softball'>Softball</a></li>
                  </ul></nav></div>";
    $graphic .= "<div id='period-nav'><ul class='clearfix desktop'><li class='label'>Week</li>";
    foreach ($weeks as $key=>$value) {
      $period = $key + 1;
      ($period == $wk) ? $class = 'active' : $class = '';
      $graphic .= "<li class='period-link {$class}' data-period='{$value[0]}'>{$period}</li>";
    }
    $graphic .= "<li id='league'><select class='form-control desktop' name='league_desk'>";
    $graphic .= "<option value=''>Select League</option>";
    foreach ($leagues as $value){
      $graphic .= "<option value='{$value[2]}'>{$value[1]}</option>";
    }
    $graphic .= "</select></li><li><a class='stand_link'>Standings</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class='sked_link active'>Scoreboard</a></li></ul><select class='form-control mobile' name='period'>";
    $graphic .= "<option value=''>Select Week</option>";
    foreach ($weeks as $key=>$value) {
      $period = $key + 1;
      $graphic .= "<option value='{$value[0]}'>{$period}</option>";
    }
    $graphic .= "</select><select class='form-control mobile' name='league_mobile'>";
    $graphic .= "<option value=''>Select League</option>";
    foreach ($leagues as $value) {
      $graphic .= "<option value='{$value[2]}'>{$value[1]}</option>";
    }
    $graphic .= "</select><div class='buttons mobile'><button class='btn btn-primary stand_link'>Standings</button><button class='btn btn-primary sked_link active'>Scoreboard</button></div></div>";
    $graphic .= "<div id='search-results' class='results_wrapper clearfix'></div>";
    $graphic .= "</div></div>";
    $graphic .= "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>";
    //$graphic .= "<script src='http://hc-assets.s3.amazonaws.com/js/v247-api-football.js'></script>";
    $graphic .= "<script src='assets/js/v247-api-football.js'></script>";

    return $graphic;
  }
}
$vfoot = new V247Football();

?>
