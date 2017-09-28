<?php
/*
 * Build boxscores and roundups for newsgate
 * @param $results the days game results, array
 * @param $day the current day, str
 * @param $adb database object
 */
function exportNewsgate($results, $day, $adb){
    // files for newsgate
    $roundup_file = V247_ROUND;
    $boxscore_file = V247_BOX;
    // vars
    $roundup="";
    $boxscore="<body>";
    $leadin="";
    $cols = 6;
    $ot = NULL;
    $ot_label = "";
    
    foreach($results as $result){
        // get away record
        $away = $result['away_id'];
        $getAwayRecord = "CALL getTeamRecord('$away')";
        $away_record = $adb->getData($getAwayRecord);
        $ar = teamRecordOverallWL($away_record, $away);
        // get home record
        $home = $result['home_id'];
        $getHomeRecord = "CALL getTeamRecord('$home')";
        $home_record = $adb->getData($getHomeRecord);
        $hr = teamRecordOverallWL($home_record, $home);
        // put the plays in an array
        $scoring_plays = explode("\n", $result['sum_scoring']);
        // get the final score
        if($result['away_final'] != NULL && $result['home_final'] != NULL){
            $away_score = $result['away_final'];
            $home_score = $result['home_final'];
        }
        // check if home or away won and build game header
        if($away_score > $home_score){
            $leadin = $result['away_team'] . " " . $away_score . ", " .  $result['home_team'] . " " . $home_score;
        }else{
            $leadin = $result['home_team'] . " " . $home_score . ", " .  $result['away_team'] . " " . $away_score;
        }
        // add game header and game story to roundup file
        $roundup .= ($leadin . ": " . $result['sum_body'] . "\n\n");
        // check for ot
        if(is_numeric($result['away_ot']) == 1 && is_numeric($result['home_ot']) == 1){
            $cols = 7; 
            $ot = TRUE;
            $ot_label = " (OT)";
        }else{
            $ot = FALSE;
            $ot_label = "";
        }
        // get each game boxscore and add to file 
        $boxscore .= ('<agate_bold>' . $leadin . $ot_label . '<EP></agate_bold><EL,2><QL><EP><TABLE cciformat="1,0" rows=2'); 
        $boxscore .= ('cols=' . $cols . 'tag="z_tb_body__03" dispwidth="13.0040m" topgutter="0.9m" bottomgutter="0.3515m" break="norule" align="center" bottomrule="0.1054m"><TCOL width="25R" dispwidth="18.9788m" align="left"></TCOL><TCOL width="10R"></TCOL><TCOL width="10R" dispwidth="12.3011m"></TCOL><TCOL width="10R" dispwidth="12.6525m"></TCOL><TCOL width="10R" dispwidth="12.6525m"></TCOL>');
        if($ot == TRUE){
           $boxscore .= '<TCOL width="10R" dispwidth="12.6525m"></TCOL>'; 
        }
        $boxscore .= '<TCOL width="15R" dispwidth="17.2215m"></TCOL><TBODY><TROW toprule="0.1054m" bottomrule="0m">';
        $boxscore .= "<TFIELD>{$result['away_abbr']} ({$ar})</TFIELD><TFIELD>{$result['away_q1']}</TFIELD><TFIELD>{$result['away_q2']}</TFIELD><TFIELD>{$result['away_q3']}</TFIELD><TFIELD>{$result['away_q4']}</TFIELD>";
        if($ot == TRUE){
            $boxscore .= "<TFIELD>{$result['away_ot']}</TFIELD>";
        }
        $boxscore .= "<TFIELD>{mdash} {$away_score}</TFIELD></TROW>";
        $boxscore .= '<TROW topgutter="0.3515m" bottomgutter="0.5272m">';
        $boxscore .= "<TFIELD>{$result['home_abbr']} ({$hr})</TFIELD><TFIELD>{$result['home_q1']}</TFIELD><TFIELD>{$result['home_q2']}</TFIELD><TFIELD>{$result['home_q3']}</TFIELD><TFIELD>{$result['home_q4']}</TFIELD>";
        if($ot == TRUE){
            $boxscore .= "<TFIELD>{$result['home_ot']}</TFIELD>";
        }
        $boxscore .= "<TFIELD>{mdash} {$home_score}</TFIELD></TROW></TBODY></TABLE><EL,2><EP><agate_bold>SCORING SUMMARY<EP></agate_bold>";
        // get each play
        foreach($scoring_plays as $play){

            if(strstr($play,'First Quarter') || strstr($play,'Second Quarter') || strstr($play,'Third Quarter') || strstr($play,'Fourth Quarter') || strstr($play,'Overtime')){
                $boxscore .= ("<agate_bold>" . preg_replace( "/\r|\n/", "", $play ) . "<EP></agate_bold>");
            }else{
                $boxscore .= preg_replace("/(-)/","{mdash}",(preg_replace( "/\r|\n/", "", $play)) . "<EP>");
            }  
        }
        // close the box
        $boxscore .= "<EL,3><QL><EP>";
    }
    $boxscore .= "</body><head_kicker__01></head_kicker__01>";
    // clean up the text
    $boxscore = preg_replace("/(>)( )(<)|\r|\n/","><",$boxscore);
    // write the roundup file
    $r = fopen($roundup_file, 'w');
    fwrite($r, $roundup);
    fclose($r);
    // write the boxscore file
    $b = fopen($boxscore_file, 'w');
    fwrite($b, $boxscore);
    fclose($b);
}

?>