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
 * Get the overall record for a team ONLY WL NOT TIE
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
 * Get the overall record for a team ONLY WL NOT TIE
 * @param $record array of all of the games a team has played
 * @param $school
 */
function teamRecordOverallWL($record, $school){
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
    return $win . " - " . $loss;  
}
/*
 * Sorts an array
 * Pass in what columns you want sorted by and it does the rest
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