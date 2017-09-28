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
        $ar = teamRecordOverall($away_record, $away);
        // get home record
        $home = $result['home_id'];
        $getHomeRecord = "CALL getTeamRecord('$home')";
        $home_record = $adb->getData($getHomeRecord);
        $hr = teamRecordOverall($home_record, $home);
        // get home and away goals
        $goals_away = $result['away_goals'];
        $goals_home = $result['home_goals'];
        // get home and away saves
        $saves_away = $result['away_saves'];
        $saves_home = $result['home_saves'];
        // get the final score, if there is no final score add the 2 halves
        if($result['away_final'] != NULL && $result['home_final'] != NULL){
            $away_score = $result['away_final'];
            $home_score = $result['home_final'];
        }
        // check for ot // is_numeric()
        if($result['home_ot'] != NULL){
            // set ot scores
            $ot_away = $result['away_ot'];
            $ot_home = $result['home_ot'];
            // check for pks
            if($result['home_pk'] != NULL){
                // set pk scores
                $pk_away = " (" . $result['away_pk'] . ")";
                $pk_home = " (" . $result['home_pk'] . ")";
                $extra_label = "";
            }
            // if no pks, set scores to nothing
            else{
                $pk_away = "";
                $pk_home = "";
                $extra_label = " (OT)";
            }  
        }
        // if no ot, set scores to nothing
        else{
            $ot_away = "";
            $ot_home = "";
            $pk_away = "";
            $pk_home = "";
            $extra_label = "";
        }
        // check if home or away won and build game header
        if($result['winner'] == $result['home_id']){
            $leadin = $result['home_team'] . " " . $home_score . $pk_home . ", " .  $result['away_team'] . " " . $away_score . $pk_away . $extra_label;
        }
        else{
            $leadin = $result['away_team'] . " " . $away_score . $pk_away . ", " .  $result['home_team'] . " " . $home_score . $pk_home . $extra_label;
        }
        // add game header and game story to roundup file
        $roundup .= ($leadin . ": " . $result['sum_body'] . "\n\n");
        // get each game boxscore and add to file 
        $boxscore .= ('<agate_bold>' . $leadin . '<EP></agate_bold><EL,2><QL><EP>'); 
        $boxscore .= ('<TABLE cciformat="1,0" rows=2 cols=5 dispwidth="29.8741m" topgutter="0.7056m" break="norule" align="right" bottomrule="0.1058m">'); 
        $boxscore .= ('<TCOL width="100R" dispwidth="22.8449m" tag="z_tb_body__03" align="left"></TCOL><TCOL width="35R" tag="z_tb_body__03"></TCOL><TCOL width="35R" tag="z_tb_body__03"></TCOL><TCOL width="35R" tag="z_tb_body__03"></TCOL><TCOL width="45R" tag="z_tb_body__03"></TCOL>');
        // add away team name, record, h1, h2
        $boxscore .= '<TBODY height="2.6458m" tag="z_tb_body__03"><TROW toprule="0.1058m" bottomrule="0m">';
        $boxscore .= "<TFIELD>{$result['away_abbr']} ({$ar})</TFIELD><TFIELD>{$result['away_h1']}</TFIELD><TFIELD>{$result['away_h2']}</TFIELD><TFIELD>{$ot_away}</TFIELD><TFIELD>{mdash}{$away_score}{$pk_away}</TFIELD></TROW>";
        // add home team name, record, h1, h2
        $boxscore .= '<TROW height="1.9403m" topgutter="0m">';
        $boxscore .= "<TFIELD>{$result['home_abbr']} ({$hr})</TFIELD><TFIELD>{$result['home_h1']}</TFIELD><TFIELD>{$result['home_h2']}</TFIELD><TFIELD>{$ot_home}</TFIELD><TFIELD>{mdash}{$home_score}{$pk_home}</TFIELD></TROW>";
        // and home final
        $boxscore .= "</TBODY></TABLE><EL,2><EP><agate_bold>SCORING SUMMARY<EP></agate_bold>";
        
        ////////////////////////////////////
        // FIX THIS FOR SOCCER BOX STYLES //
        ////////////////////////////////////
        
        // check if both teams scored
        if($goals_away != NULL && $goals_home != NULL){
            $boxscore .= "<agate_bold>Goals:</agate_bold> {$result['away_abbr']} {mdash} {$goals_away}; {$result['home_abbr']} {mdash} {$goals_home}. ";
        }
        // check if just home team scored
        elseif($goals_away == NULL && $goals_home != NULL){
            $boxscore .= "<agate_bold>Goals:</agate_bold> {$result['home_abbr']} {mdash} {$goals_home}. ";
        }
        // check if just away team scored
        elseif($goals_away != NULL && $goals_home == NULL){
            $boxscore .= "<agate_bold>Goals:</agate_bold> {$result['away_abbr']} {mdash} {$goals_away}. ";
        }
        else{
            $boxscore .= "";
        }
        // check if both teams had saves
        if($saves_away != NULL && $saves_home != NULL){
            $boxscore .= "<agate_bold>Saves:</agate_bold> {$result['away_abbr']} {mdash} {$saves_away}; {$result['home_abbr']} {mdash} {$saves_home}.";
        }
        // check if just home team had saves
        elseif($saves_away == NULL && $saves_home != NULL){
            $boxscore .= "<agate_bold>Saves:</agate_bold> {$result['home_abbr']} {mdash} {$saves_home}.";
        }
        // check if just away team had saves
        elseif($saves_away != NULL && $saves_home == NULL){
            $boxscore .= "<agate_bold>Saves:</agate_bold> {$result['away_abbr']} {mdash} {$saves_away}.";
        }
        else{
            $boxscore .= "";
        }
        // check if there's a note
        if($result['sum_note'] != NULL){
            $boxscore .= "<agate_bold>Of Note:</agate_bold> {$result['sum_note']}";
        }
        else{
            $boxscore .= "";
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