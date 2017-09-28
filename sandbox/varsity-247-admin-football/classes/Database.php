<?php

class Database
{
    /**
     * @var object $db_connection The database connection
     */
    private $db_connection            = null;
    /**
     * @var the results from the db query
     */
    public  $results = "";
    /**
     * @var array collection of error messages
     */
    public  $errors                   = array();
    /**
     * @var array collection of success / neutral messages
     */
    public  $messages = "";
    

    public function __construct()
    {
        if ($this->databaseConnection()){
            //confirm db connection
            //$this->messages = "Successfully connected to database.";
        }
        else{
            $this->messages = "There was a problem connecting to the database.";
        }
    }

    /*
     * Checks if database connection is opened and open it if not
     */
    private function databaseConnection()
    {
        // connection already opened
        if ($this->db_connection != null) {
            return true;
        } else {
            // create a database connection, using the constants from config/config.php
            try {
                // Generate a database connection, using the PDO connector
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_LOCAL_INFILE=>1));
                //$this->db_connection(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return true;
            // If an error is catched, database connection failed
            } catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR;
                return false;
            }
        }
    }
    /*
     * inputData
     * updates an area game in the database
     * @param all the possible inputs for an area game
     */
    public function inputData($_id,$_area,$_date,$_time,$_awayteam,$_hometeam,$_fac,$_win,$_q1a,$_q2a,$_q3a,$_q4a,$_ota,$_q1h,$_q2h,$_q3h,$_q4h,$_oth,$_head,$_body,$_plays,$_aabr,$_habr,$_aname,$_hname,$_afinal,$_hfinal,$_vid)
    {   
        if($this->databaseConnection()) {
            $query_update_data = $this->db_connection->prepare("UPDATE schedule 
                                                                   SET area = :area, 
                                                                       game_date = :game_date,
                                                                       game_time = :game_time,
                                                                       facility = :facility,
                                                                       away_id = :away_id,
                                                                       away_q1 = :away_q1,
                                                                       away_q2 = :away_q2,
                                                                       away_q3 = :away_q3,
                                                                       away_q4 = :away_q4,
                                                                       away_ot = :away_ot,
                                                                       home_q1 = :home_q1,
                                                                       home_q2 = :home_q2,
                                                                       home_q3 = :home_q3,
                                                                       home_q4 = :home_q4,
                                                                       home_ot = :home_ot,
                                                                       winner = :winner,
                                                                       sum_head = :sum_head,
                                                                       sum_body = :sum_body,
                                                                       sum_scoring = :sum_scoring,
                                                                       away_abbr = :away_abbr,
                                                                       home_id = :home_id,
                                                                       home_abbr = :home_abbr,
                                                                       away_final = :away_final,
                                                                       home_final = :home_final,
                                                                       video = :video
                                                                 WHERE game_id = :game_id");
            
            /************************************************************************/
            /*** GAME INFO **********************************************************/
            /************************************************************************/ 

            $query_update_data->bindValue(':area', $_area, PDO::PARAM_INT);
            $query_update_data->bindValue(':game_date', $_date, PDO::PARAM_STR);
            $query_update_data->bindValue(':game_time', $_time, PDO::PARAM_STR);
            $query_update_data->bindValue(':facility', $_fac, PDO::PARAM_STR);
            
            /************************************************************************/
            /*** AWAY TEAM **********************************************************/
            /************************************************************************/

            $query_update_data->bindValue(':away_id', $_awayteam, PDO::PARAM_STR);
            //
            if(is_numeric($_q1a)){
                $query_update_data->bindValue(':away_q1', $_q1a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_q1', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q2a)){
                $query_update_data->bindValue(':away_q2', $_q2a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_q2', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q3a)){
                $query_update_data->bindValue(':away_q3', $_q3a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_q3', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q4a)){
                $query_update_data->bindValue(':away_q4', $_q4a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_q4', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_ota)){
                $query_update_data->bindValue(':away_ot', $_ota, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_ot', NULL, PDO::PARAM_NULL);
            }
            
            /************************************************************************/
            /*** HOME TEAM **********************************************************/
            /************************************************************************/

            $query_update_data->bindValue(':home_id', $_hometeam, PDO::PARAM_STR);
            //
            if(is_numeric($_q1h)){
                $query_update_data->bindValue(':home_q1', $_q1h, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_q1', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q2h)){
                $query_update_data->bindValue(':home_q2', $_q2h, PDO::PARAM_INT);
            }
            else{
                $query_update_data->bindValue(':home_q2', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q3h)){
                $query_update_data->bindValue(':home_q3', $_q3h, PDO::PARAM_INT);  
            }else{
                $query_update_data->bindValue(':home_q3', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q4h)){
                $query_update_data->bindValue(':home_q4', $_q4h, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_q4', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_oth)){
                $query_update_data->bindValue(':home_ot', $_oth, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_ot', NULL, PDO::PARAM_NULL);
            }
            
            /************************************************************************/
            /*** RESULTS ************************************************************/
            /************************************************************************/

            if($_afinal != '' && $_hfinal != '' && $_win == ''){
                $_win = 'TIE';
            }
            if($_win != ''){
                $query_update_data->bindValue(':winner', $_win, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':winner', NULL, PDO::PARAM_NULL);
            }
            //
            if($_head != ''){
                $query_update_data->bindValue(':sum_head', $_head, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':sum_head', NULL, PDO::PARAM_NULL);
            }
            //
            if($_body != ''){
                $query_update_data->bindValue(':sum_body', $_body, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':sum_body', NULL, PDO::PARAM_NULL);
            }
            //
            if($_plays != ''){
                $query_update_data->bindValue(':sum_scoring', $_plays, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':sum_scoring', NULL, PDO::PARAM_NULL);
            }
            //
            if($_aabr != ''){
                $query_update_data->bindValue(':away_abbr', $_aabr, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':away_abbr', NULL, PDO::PARAM_NULL);
            }
            //
            if($_habr != ''){
                $query_update_data->bindValue(':home_abbr', $_habr, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':home_abbr', NULL, PDO::PARAM_NULL);
            }
            //
            if($_afinal != ''){
                $query_update_data->bindValue(':away_final', $_afinal, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':away_final', NULL, PDO::PARAM_NULL);
            }
            //
            if($_hfinal != ''){
                $query_update_data->bindValue(':home_final', $_hfinal, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':home_final', NULL, PDO::PARAM_NULL);
            }
            /************************************************************************/
            /*** VIDEO **************************************************************/
            /************************************************************************/
            //
            if($_vid != ''){
                $query_update_data->bindValue(':video', $_vid, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':video', NULL, PDO::PARAM_NULL);
            }
            /************************************************************************/
            /*** QUERY **************************************************************/
            /************************************************************************/

            $query_update_data->bindValue(':game_id', $_id, PDO::PARAM_STR);
            // update database
            $query_update_data->execute();
            // print message saying the game was updated
            $this->messages = "{$_aname} at {$_hname} has been updated successfully.";
            // close database connection
            $this->close_connection();
        }
        else{
            // print message that there was a problem
            $this->messages = "There was a problem uploading the game, please try again.";
            // close database connection
            $this->close_connection();
        }  
    }
    /*
     * inputDataAway
     * updates an out of area game in the database
     * @param all the possible inputs for an out of area game
     */
    public function inputDataAway($_id,$_area,$_date,$_time,$_awayteam,$_hometeam,$_fac,$_win,$_q1a,$_q2a,$_q3a,$_q4a,$_ota,$_q1h,$_q2h,$_q3h,$_q4h,$_oth,$_aname,$_hname,$_afinal,$_hfinal)
    {   

        if($this->databaseConnection()) {
            $query_update_data = $this->db_connection->prepare("UPDATE schedule 
                                                                   SET area = :area,
                                                                       game_date = :game_date,
                                                                       game_time = :game_time,
                                                                       facility = :facility,
                                                                       away_q1 = :away_q1,
                                                                       away_q2 = :away_q2,
                                                                       away_q3 = :away_q3,
                                                                       away_q4 = :away_q4,
                                                                       away_ot = :away_ot,
                                                                       home_q1 = :home_q1,
                                                                       home_q2 = :home_q2,
                                                                       home_q3 = :home_q3,
                                                                       home_q4 = :home_q4,
                                                                       home_ot = :home_ot,
                                                                       winner = :winner,
                                                                       away_id = :away_id,
                                                                       home_id = :home_id,
                                                                       away_final = :away_final,
                                                                       home_final = :home_final
                                                                 WHERE game_id = :game_id");
            
            /************************************************************************/
            /*** GAME INFO **********************************************************/
            /************************************************************************/ 

            $query_update_data->bindValue(':area', $_area, PDO::PARAM_INT);
            $query_update_data->bindValue(':game_date', $_date, PDO::PARAM_STR);
            $query_update_data->bindValue(':game_time', $_time, PDO::PARAM_STR);
            $query_update_data->bindValue(':facility', $_fac, PDO::PARAM_STR);
            
            /************************************************************************/
            /*** AWAY TEAM **********************************************************/
            /************************************************************************/
            
            $query_update_data->bindValue(':away_id', $_awayteam, PDO::PARAM_STR);
            //
            if(is_numeric($_q1a)){
                $query_update_data->bindValue(':away_q1', $_q1a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_q1', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q2a)){
                $query_update_data->bindValue(':away_q2', $_q2a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_q2', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q3a)){
                $query_update_data->bindValue(':away_q3', $_q3a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_q3', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q4a)){
                $query_update_data->bindValue(':away_q4', $_q4a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_q4', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_ota)){
                $query_update_data->bindValue(':away_ot', $_ota, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_ot', NULL, PDO::PARAM_NULL);
            }

            /************************************************************************/
            /*** HOME TEAM **********************************************************/
            /************************************************************************/

            $query_update_data->bindValue(':home_id', $_hometeam, PDO::PARAM_STR);
            //
            if(is_numeric($_q1h)){
                $query_update_data->bindValue(':home_q1', $_q1h, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_q1', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q2h)){
                $query_update_data->bindValue(':home_q2', $_q2h, PDO::PARAM_INT);
            }
            else{
                $query_update_data->bindValue(':home_q2', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q3h)){
                $query_update_data->bindValue(':home_q3', $_q3h, PDO::PARAM_INT);  
            }else{
                $query_update_data->bindValue(':home_q3', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_q4h)){
                $query_update_data->bindValue(':home_q4', $_q4h, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_q4', NULL, PDO::PARAM_NULL);
            }
            //
            if(is_numeric($_oth)){
                $query_update_data->bindValue(':home_ot', $_oth, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_ot', NULL, PDO::PARAM_NULL);
            }

            /************************************************************************/
            /*** RESULTS ************************************************************/
            /************************************************************************/

            if($_afinal != '' && $_hfinal != '' && $_win == ''){
                $_win = 'TIE';
            }
            if($_win != ''){
                $query_update_data->bindValue(':winner', $_win, PDO::PARAM_STR);
            }
            else{
                $query_update_data->bindValue(':winner', NULL, PDO::PARAM_NULL);
            }
            //
            if($_afinal != ''){
                $query_update_data->bindValue(':away_final', $_afinal, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':away_final', NULL, PDO::PARAM_NULL);
            }
            //
            if($_hfinal != ''){
                $query_update_data->bindValue(':home_final', $_hfinal, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':home_final', NULL, PDO::PARAM_NULL);
            }

            /************************************************************************/
            /*** QUERY **************************************************************/
            /************************************************************************/

            $query_update_data->bindValue(':game_id', $_id, PDO::PARAM_STR);
            // send to database
            $query_update_data->execute();
            // print message that database was updated
            $this->messages = "{$_aname} at {$_hname} has been updated successfully.";
            // close database connection
            $this->close_connection();
        }
        else{
            // print message that there was a problem
            $this->messages = "There was a problem uploading the game, please try again.";
            // close database connection
            $this->close_connection();
        }  
    }    
    /*
     * Gets the results
     * @return $object_array all results
     */
    public function getData($sql){
        if($this->databaseConnection()) {
            // database query, getting all the info of the selected user
            $query_get_data = $this->db_connection->prepare($sql);
            $query_get_data->execute();
            // get result row (as an object)
            $object_array = $query_get_data->fetchAll();
        }
        return $object_array;
    }
    /*
     * deleteData
     * Deletes a game from the database
     * @param $game game id
     * @param $away away team name
     * @param $home home team name
     */
    public function deleteData($game,$away,$home){
        if($this->databaseConnection()) {
            //
            $sql = "DELETE FROM schedule WHERE game_id = '{$game}'";
            // database query, getting all the info of the selected user
            $query_get_data = $this->db_connection->prepare($sql);
            $query_get_data->execute();
            // update message
            $this->messages = "{$away} at {$home} was a successfully deleted.";
            // close database connection
            $this->close_connection();
        }else{
            $this->messages = "There was a problem deleting {$away} at {$home} please try again or contact your system administrator.";
            // close database connection
            $this->close_connection();
        }
    }
    /*
     * addData
     * adds a new game to the database
     * @param bunch of stuffs
     */
    public function addData($_id,$_awayteam,$_hometeam,$_week,$_date,$_time,$_fac,$_area){

        if($this->databaseConnection()){
            //
            $query_add_game = $this->db_connection->prepare("INSERT INTO schedule 
                                                                         (game_id, week, game_date, game_time, facility, area, away_id, away_abbr, away_q1, away_q2, away_q3, away_q4, away_ot, home_id, home_abbr, home_q1, home_q2, home_q3, home_q4, home_ot, winner, sum_head, sum_body, sum_scoring) 
                                                                  VALUES (:game_id, :week, :game_date, :game_time, :facility, :area, :away_id, :away_abbr, :away_q1, :away_q2, :away_q3, :away_q4, :away_ot, :home_id, :home_abbr, :home_q1, :home_q2, :home_q3, :home_q4, :home_ot, :winner, :sum_head, :sum_body, :sum_scoring)");
            
            /************************************************************************/
            /*** GAME INFO **********************************************************/
            /************************************************************************/

            $query_add_game->bindValue(':game_id', $_id, PDO::PARAM_STR);
            $query_add_game->bindValue(':week', $_week, PDO::PARAM_INT);
            $query_add_game->bindValue(':game_date', $_date, PDO::PARAM_STR);
            $query_add_game->bindValue(':game_time', $_time, PDO::PARAM_STR);
            $query_add_game->bindValue(':facility', $_fac, PDO::PARAM_STR);
            $query_add_game->bindValue(':area', $_area, PDO::PARAM_INT);

            /************************************************************************/
            /*** AWAY TEAM **********************************************************/
            /************************************************************************/

            $query_add_game->bindValue(':away_id', $_awayteam, PDO::PARAM_STR);
            $query_add_game->bindValue(':away_abbr', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_q1', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_q2', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_q3', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_q4', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_ot', NULL, PDO::PARAM_NULL);

            /************************************************************************/
            /*** HOME TEAM **********************************************************/
            /************************************************************************/

            $query_add_game->bindValue(':home_id', $_hometeam, PDO::PARAM_STR);
            $query_add_game->bindValue(':home_abbr', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_q1', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_q2', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_q3', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_q4', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_ot', NULL, PDO::PARAM_NULL);

            /************************************************************************/
            /*** RESULTS ************************************************************/
            /************************************************************************/

            $query_add_game->bindValue(':winner', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':sum_head', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':sum_body', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':sum_scoring', NULL, PDO::PARAM_NULL);

            /************************************************************************/
            /*** QUERY **************************************************************/
            /************************************************************************/

            $query_add_game->execute();
            // print message that database was updated
            $this->messages = "Your game was added successfully.";
            // close database connection
            $this->close_connection();
        }
        else{
            // print message that there was a problem
            $this->messages = "There was a problem adding your game, please try again or contact your system administrator.";
            // close database connection
            $this->close_connection();
        }
    }
    /*
     * close_connection
     * close database connection
     */
    public function close_connection()
    {
        if(isset($this->connection))
        {
            mysql_close($this->connection);
            unset($this->connection);
        }
    }
    
}