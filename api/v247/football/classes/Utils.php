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
        }else{
            $resultArrFuture = null;
        }
    }
    if(is_array($resultArrFuture)){
        usort($resultArrFuture,make_comparer([0,SORT_ASC]));
        return $resultArrFuture[0][1];
    }else{
        return $dates[0];
    }       
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
    $ccc_d1e = [];
    $ccc_d1w = [];
    $ccc_d2e = [];
    $ccc_d2w = [];
    $ccc_d3e = [];
    $ccc_d3w = [];
    $csc = [];
    $ecc_d1 = [];
    $ecc_d2 = [];
    $fciac_d1 = [];
    $fciac_d2 = [];
    $nvl_brass = [];
    $nvl_copper = [];
    $nvl_iron = [];
    $peq_east = [];
    $peq_south = [];
    $peq_west = [];
    $scc_t1 = [];
    $scc_t2 = [];
    $scc_t3 = [];
    $swc_colonial = [];
    $swc_patriot = [];
    
    $db_standings = new Database();
    //
    foreach($allSchools as $team){
        $getTeamRecord = "CALL getRecord('$team[0]')";
        $record = $db_standings->getData($getTeamRecord);
        $record = getRecords($record, $team);

        if($record[1]=='Central Connecticut' && $record[2]=='Div I East'){
            array_push($ccc_d1e, $record);
        }
        elseif($record[1]=='Central Connecticut' && $record[2]=='Div I West'){
            array_push($ccc_d1w, $record);
        }
        elseif($record[1]=='Central Connecticut' && $record[2]=='Div II East'){
            array_push($ccc_d2e, $record);
        }
        elseif($record[1]=='Central Connecticut' && $record[2]=='Div II West'){
            array_push($ccc_d2w, $record);
        }
        elseif($record[1]=='Central Connecticut' && $record[2]=='Div III East'){
            array_push($ccc_d3e, $record);
        }
        elseif($record[1]=='Central Connecticut' && $record[2]=='Div III West'){
            array_push($ccc_d3w, $record);
        }
        elseif($record[1]=='Constitution State'){
            array_push($csc, $record);
        }
        elseif($record[1]=='Eastern Connecticut' && $record[2]=='Div I'){
            array_push($ecc_d1, $record);
        }
        elseif($record[1]=='Eastern Connecticut' && $record[2]=='Div II'){
            array_push($ecc_d2, $record);
        }
        elseif($record[1]=='Fairfield County Interscholastic Athletic' && $record[2]=='Div I'){
            array_push($fciac_d1, $record);
        }
        elseif($record[1]=='Fairfield County Interscholastic Athletic' && $record[2]=='Div II'){
            array_push($fciac_d2, $record);
        }
        elseif($record[1]=='Naugatuck Valley' && $record[2]=='Brass'){
            array_push($nvl_brass, $record);
        }
        elseif($record[1]=='Naugatuck Valley' && $record[2]=='Copper'){
            array_push($nvl_copper, $record);
        }
        elseif($record[1]=='Naugatuck Valley' && $record[2]=='Iron'){
            array_push($nvl_iron, $record);
        }
        elseif($record[1]=='Pequot' && $record[2]=='East'){
            array_push($peq_east, $record);
        }
        elseif($record[1]=='Pequot' && $record[2]=='South'){
            array_push($peq_south, $record);
        }
        elseif($record[1]=='Pequot' && $record[2]=='West'){
            array_push($peq_west, $record);
        }
        elseif($record[1]=='Southern Connecticut' && $record[2]=='Tier 1'){
            array_push($scc_t1, $record);
        }
        elseif($record[1]=='Southern Connecticut' && $record[2]=='Tier 2'){
            array_push($scc_t2, $record);
        }
        elseif($record[1]=='Southern Connecticut' && $record[2]=='Tier 3'){
            array_push($scc_t3, $record);
        }
        elseif($record[1]=='South West' && $record[2]=='Colonial'){
            array_push($swc_colonial, $record);
        }
        elseif($record[1]=='South West' && $record[2]=='Patriot'){
            array_push($swc_patriot, $record);
        }
        else{
           array_push($all_records, $record); 
        }

        // Sort ccc arrays by overal w, l
        usort($ccc_d1e,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($ccc_d1w,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($ccc_d2e,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($ccc_d2w,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($ccc_d3e,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($ccc_d3w,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        // Sort csc arrays by overal w, l
        usort($csc,make_comparer([9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        // Sort ecc arrays by overal w, l
        usort($ecc_d1,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($ecc_d2,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        // Sort fcica arrays by overal w, l
        usort($fciac_d1,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($fciac_d2,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        // Sort nvl arrays by overal w, l
        usort($nvl_brass,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($nvl_copper,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($nvl_iron,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        // Sort peq arrays by overal w, l
        usort($peq_east,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($peq_south,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($peq_west,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        // Sort scc arrays by overal w, l
        usort($scc_t1,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($scc_t2,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($scc_t3,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        // Sort swc arrays by overal w, l
        usort($swc_colonial,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        usort($swc_patriot,make_comparer([4,SORT_DESC],[5,SORT_ASC],[7,SORT_DESC],[9,SORT_DESC],[10,SORT_ASC],[12,SORT_DESC],[14,SORT_DESC],[15,SORT_ASC],[17,SORT_DESC],[0,SORT_ASC]));
        // Combine divisions into league array
        $ccc = [$ccc_d1e,$ccc_d1w,$ccc_d2e,$ccc_d2w,$ccc_d3e,$ccc_d3w];
        $ecc = [$ecc_d1,$ecc_d2];
        //var_dump($ecc);
        $fciac = [$fciac_d1,$fciac_d2];
        $nvl = [$nvl_brass,$nvl_copper,$nvl_iron];
        $peq = [$peq_east,$peq_south,$peq_west];
        $scc = [$scc_t1,$scc_t2,$scc_t3];
        $swc = [$swc_colonial,$swc_patriot];
        // Combine sorted divisions into all leagues array
        $all_leagues = ['central connecticut'=>$ccc,
                        'constitution state'=>$csc,
                        'eastern connecticut'=>$ecc,
                        'fairfield county interscholastic athletic'=>$fciac,
                        'naugatuck valley'=>$nvl,
                        'pequot'=>$peq,
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
    // get all the standing info
    $d_win = 0;
    $d_loss = 0;
    $d_tie = 0;
    //
    $l_win = 0;
    $l_loss = 0;
    $l_tie = 0;
    //
    $o_win = 0;
    $o_loss = 0;
    $o_tie = 0;
    //
    $d_pf = 0;
    $d_pa = 0;
    //
    $l_pf = 0;
    $l_pa = 0;
    //
    $o_pf = 0;
    $o_pa = 0;
    //
    foreach($record as $result){
        if($result['winner'] != NULL){
            //get the final scores for both teams
            if($result['away_final'] != NULL && $result['home_final'] != NULL){
               $away_score = $result['away_final'];
               $home_score = $result['home_final']; 
            }else{
               $away_score = $result['away_q1'] + $result['away_q2'] + $result['away_q3'] + $result['away_q4'] + $result['away_ot'];
               $home_score = $result['home_q1'] + $result['home_q2'] + $result['home_q3'] + $result['home_q4'] + $result['home_ot'];  
            }
            //get pf/pa and w/l for divison
            if($result['away_division'] == $result['home_division']){
                //if school won, +1 to win, else +1 to loss
                if($result['team_id'] == $result['winner']){ 
                    $d_win++; 
                    $l_win++; 
                }
                elseif($result['winner'] == "TIE"){
                    $d_tie++;
                    $l_tie++;
                }
                else{ 
                    $d_loss++; 
                    $l_loss++; 
                }
                //is school home or away, add points to appropriate total
                if($result['team_id'] == $result['away_id']){
                    // if away, add score to points for and opponent to points against 
                    $d_pf += $away_score;
                    $d_pa += $home_score;
                    $l_pf += $away_score;
                    $l_pa += $home_score;
                }else{
                    // if home, add score to points for and opponent to points against
                    $d_pf += $home_score;
                    $d_pa += $away_score;
                    $l_pf += $home_score;
                    $l_pa += $away_score;
                }
            }
            //get pf/pa and w/l for league
            elseif($result['away_league'] == $result['home_league'] && $result['away_division'] != $result['home_division']){
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
            }
            elseif($result['winner'] == "TIE"){
                $o_tie++;
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
    //
    $team_record = [$team['name'],$team['league'],$team['division'],$team['class'],$d_win,$d_loss,$d_tie,$d_pf,$d_pa,$l_win,$l_loss,$l_tie,$l_pf,$l_pa,$o_win,$o_loss,$o_tie,$o_pf,$o_pa,$team['school_id']];
    //
    return $team_record;
}
/*
 *
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