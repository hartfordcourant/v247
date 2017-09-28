<?php

/**
 * Get the current seasons schedule dates
 */
class Schedule
{
    /**
     * @var array $periods_2017
     */                    
    public $periods_2017 = [['2017-09-03','2017-09-09'],['2017-09-10','2017-09-16'],['2017-09-17','2017-09-23'],['2017-09-24','2017-09-30'],['2017-10-01','2017-10-07'],['2017-10-08','2017-10-14'],['2017-10-15','2017-10-21'],['2017-10-22','2017-10-28'],['2017-10-29','2017-11-04'],['2017-11-05','2017-11-11'],['2017-11-12','2017-11-18']];                      
        
    public function __construct(){}
    
    /*
     * Get the current season schedule 
     * @param $season the year of the season
     * @return $periods_ array with that seasons dates
     */
    public function getSeason($season){
        switch ($season) { 
          case "2017": return $this->periods_2017; break;
          default: return $this->periods_2017;
        }
    }
    /*
     * Get the week of the season
     * @param $season which season it is
     * @return int the week fo the season
     */
    public function getWeek($season){
        // include the schedule date data
        $weeks = $this->getSeason($season);
        //get todays date
        $today = date("Y-m-d");
        
        switch ($today) {
            case $today < $weeks[0][0]: return 1;  break;
            case $today >= $weeks[0][0] && $today < $weeks[1][0]: return 1;  break;
            case $today >= $weeks[1][0] && $today < $weeks[2][0]: return 2;  break;
            case $today >= $weeks[2][0] && $today < $weeks[3][0]: return 3;  break;
            case $today >= $weeks[3][0] && $today < $weeks[4][0]: return 4;  break;
            case $today >= $weeks[4][0] && $today < $weeks[5][0]: return 5;  break;
            case $today >= $weeks[5][0] && $today < $weeks[6][0]: return 6;  break;
            case $today >= $weeks[6][0] && $today < $weeks[7][0]: return 7;  break;
            case $today >= $weeks[7][0] && $today < $weeks[8][0]: return 8;  break;
            case $today >= $weeks[8][0] && $today < $weeks[9][0]: return 9; break;
            case $today >= $weeks[9][0] && $today < $weeks[10][0]: return 10; break;
            case $today > $weeks[10][0]: return 11; break;
            default: return 1;
        }
    }
    /*
     * Get the period of games to display 
     * @return the dates to display
     */
    function getDisplayPeriod($season, $period){
        // include the schedule date data
        $periods = $this->getSeason($season);

        if(strlen($period) == 10){
            // check where the date falls
            switch ($period) {
                case $period >= $periods[0][0] && $period <= $periods[0][1]: return $periods[0];  break;
                case $period >= $periods[1][0] && $period <= $periods[1][1]: return $periods[1];  break;
                case $period >= $periods[2][0] && $period <= $periods[2][1]: return $periods[2];  break;
                case $period >= $periods[3][0] && $period <= $periods[3][1]: return $periods[3];  break;
                case $period >= $periods[4][0] && $period <= $periods[4][1]: return $periods[4];  break;
                case $period >= $periods[5][0] && $period <= $periods[5][1]: return $periods[5];  break;
                case $period >= $periods[6][0] && $period <= $periods[6][1]: return $periods[6];  break;
                case $period >= $periods[7][0] && $period <= $periods[7][1]: return $periods[7];  break;
                case $period >= $periods[8][0] && $period <= $periods[8][1]: return $periods[8];  break;
                case $period >= $periods[9][0] && $period <= $periods[9][1]: return $periods[9];  break;
                case $period >= $periods[10][0] && $period <= $periods[10][1]: return $periods[10];  break;
                default: return $periods[0];
            }
        }else{
            return $periods[$period];
        }
    }
}
?>