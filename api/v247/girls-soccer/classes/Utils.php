<?php
/*
 * Get closest game day to current day
 * @param dates the list of dates games are played
 * @return the closest game day
 */
function getClosestDay($dates){
    
    $referenceDate = strtotime(date('Y-m-d'));
    foreach($dates as $key=>$date){
        $diff = strtotime($date['game_date']) - $referenceDate;
        if($diff >= 0){
            $resultArrFuture[$key][0] = $diff;
            $resultArrFuture[$key][1] = $date;
        }
    }       
    usort($resultArrFuture,make_comparer([0,SORT_ASC]));
    return $resultArrFuture[0][1];
}
/*
 * Get the record for a team for a particular week
 * @param $record
 * @param $school
 * @param $week
 */
function teamRecordWeek($record, $school, $week){
    $win = 0;
    $loss = 0;
    $tie = 0;

    foreach ($record as $key=>$result) {
        if($result[1] != NULL && $key <= $week){
            if($result[1] == $school){
                $win++;
            }
            elseif($result[1] == "TIE"){
                $tie++;
            }
            else{
                $loss++;
            }
        }
    }
    return $win . " - " . $loss . " - " . $tie;
}
/*
 * Get the overall record for a team
 * @param $record array of all of the games a team has played
 * @param $school
 */
function teamRecordOverall($record, $school){
    $win = 0;
    $loss = 0;
    $tie = 0;

    foreach ($record as $key=>$result) {

        if($result[1] != NULL){
            if($result[1] == $school){
                $win++;
            }
            elseif($result[1] == "TIE"){
                $tie++;
            }
            else{
                $loss++;
            }
        }
    }
    return $win . " - " . $loss . " - " . $tie;
}
/*
 * Build the standings for all the divisions and leagues
 * @param $allSchools
 */
function getStandings($allSchools){
    
    $all_records = [];
    $all_leagues = [];
    $brk = [];
    $ccc = [];
    $cra = [];
    $csc = [];
    $ecc = [];
    $fciac = [];
    $nvl = [];
    $nccc = [];
    $scc = [];
    $shr = [];
    $swc = [];
    
    $db_standings = new Database();
    //
    foreach($allSchools as $team){
        $getTeamRecord = "CALL getRecord('$team[0]')";
        $record = $db_standings->getData($getTeamRecord);
        $record = getRecords($record, $team);

        if($record['league'] == 'Berkshire'){ array_push($brk, $record); }
        elseif($record['league']=='Central Connecticut'){ array_push($ccc, $record); }
        elseif($record['league']=='Capital Region Athletic'){ array_push($cra,$record); }
        elseif($record['league']=='Constitution State'){ array_push($csc, $record); }
        elseif($record['league']=='Eastern Connecticut'){ array_push($ecc, $record);  }
        elseif($record['league']=='Fairfield County Interscholastic Athletic'){ array_push($fciac, $record); }
        elseif($record['league']=='Naugatuck Valley'){ array_push($nvl, $record); }
        elseif($record['league']=='North Central Connecticut'){ array_push($nccc, $record); }
        elseif($record['league']=='Southern Connecticut'){ array_push($scc, $record); }
        elseif($record['league']=='Shoreline'){ array_push($shr, $record); }
        elseif($record['league']=='South West'){ array_push($swc, $record); }
        else{ array_push($all_records, $record); }

        // Sort brk arrays by overall w,l
        usort($brk,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        // Sort ccc arrays by overall w,l
        usort($ccc,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        // Sort cra arrays by overall w,l
        usort($cra,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        // Sort csc arrays by overall w,l
        usort($csc,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        // Sort ecc arrays by overall w,l
        usort($ecc,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        // Sort fcica arrays by overall w,l
        usort($fciac,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        // Sort nccc arrays by overall w,l
        usort($nccc,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        // Sort nvl arrays by overall w,l
        usort($nvl,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        // Sort scc arrays by overall w,l
        usort($scc,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        // Sort shr arrays by overall w,l
        usort($shr,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        // Sort swc arrays by overall w,l
        usort($swc,make_comparer(['points',SORT_DESC],['lwin',SORT_DESC],['lloss',SORT_ASC],['lgf',SORT_DESC],['owin',SORT_DESC],['oloss',SORT_ASC],['ogf',SORT_DESC],['team',SORT_ASC]));
        
        // Combine sorted divisions into all leagues array
        $all_leagues = [
                        'berkshire'=>$brk,
                        'capital region athletic'=>$cra,
                        'central connecticut'=>$ccc,
                        'constitution state'=>$csc,
                        'eastern connecticut'=>$ecc,
                        'fairfield county interscholastic athletic'=>$fciac,
                        'north central connecticut'=>$nccc,
                        'naugatuck valley'=>$nvl,
                        'shoreline'=>$shr,
                        'southern connecticut'=>$scc,
                        'south west'=>$swc
                       ];
    }
    
    return $all_leagues;
}
/*
 * Get the record for a team
 * @param $record array of all teams games
 * @param $team array the basic info about the team
 */
function getRecords($record, $team){
    // league standings
    $l_win = 0;
    $l_loss = 0;
    $l_tie = 0;
    // overall standings
    $o_win = 0;
    $o_loss = 0;
    $o_tie = 0;
    // league goals
    $l_pf = 0;
    $l_pa = 0;
    // overall goals
    $o_pf = 0;
    $o_pa = 0;
    // total points
    $points = 0;

    foreach($record as $result){
        if($result['winner'] != NULL){
            //get the final scores for both teams
            if($result['away_final'] != NULL && $result['home_final'] != NULL){
               $away_score = $result['away_final'];
               $home_score = $result['home_final']; 
            }
            //get pf/pa and w/l for league
            if($result['away_league'] == $result['home_league']){
                //if school won, +1 to win, else +1 to loss
                if($result['team_id'] == $result['winner']){
                    $l_win++;
                }
                elseif($result['winner'] == "TIE"){
                    $l_tie++;
                }
                else{
                    $l_loss++;
                }
                //is school home or away, add points to appropriate total
                if($result['team_id'] == $result['away_id']){
                    // if away, add score to points for and opponent to points against 
                    $l_pf += $away_score;
                    $l_pa += $home_score;
                }else{
                    // if home, add score to points for and opponent to points against
                    $l_pf += $home_score;
                    $l_pa += $away_score;
                }
            }
            //get pf/pa and w/l for overall
            //if school won, +1 to win, else +1 to loss
            if($result['team_id'] == $result['winner']){
                $o_win++;
                $points += 3;
            }
            elseif($result['winner'] == "TIE"){
                $o_tie++;
                $points += 1;
            }
            else{
                $o_loss++;
            }
            //is school home or away, add points to appropriate total
            if($result['team_id'] == $result['away_id']){
                // if away, add score to points for and opponent to points against 
                $o_pf += $away_score;
                $o_pa += $home_score;
            }else{
                // if home, add score to points for and opponent to points against
                $o_pf += $home_score;
                $o_pa += $away_score;
            }
        }
    }
    // push team record info into array
    $team_record = ['team'=>$team['name'],'team_id'=>$team['school_id'],'league'=>$team['league'],'class'=>$team['class'],'lwin'=>$l_win,'lloss'=>$l_loss,'ltie'=>$l_tie,'lgf'=>$l_pf,'lga'=>$l_pa,'owin'=>$o_win,'oloss'=>$o_loss,'otie'=>$o_tie,'ogf'=>$o_pf,'oga'=>$o_pa,'points'=>$points];

    return $team_record;
}
/*
 * make comparer
 * sorts columns of array
 */
function make_comparer() {
    // Normalize criteria up front so that the comparer finds everything tidy
    $criteria = func_get_args();
    foreach ($criteria as $index => $criterion) {
        $criteria[$index] = is_array($criterion)
            ? array_pad($criterion, 3, null)
            : array($criterion, SORT_ASC, null);
    }

    return function($first, $second) use (&$criteria) {
        foreach ($criteria as $criterion) {
            // How will we compare this round?
            list($column, $sortOrder, $projection) = $criterion;
            $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

            // If a projection was defined project the values now
            if ($projection) {
                $lhs = call_user_func($projection, $first[$column]);
                $rhs = call_user_func($projection, $second[$column]);
            }
            else {
                $lhs = $first[$column];
                $rhs = $second[$column];
            }

            // Do the actual comparison; do not return if equal
            if ($lhs < $rhs) {
                return -1 * $sortOrder;
            }
            else if ($lhs > $rhs) {
                return 1 * $sortOrder;
            }
        }

        return 0; // tiebreakers exhausted, so $first == $second
    };
}

?>