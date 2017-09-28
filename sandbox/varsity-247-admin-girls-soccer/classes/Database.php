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
     * 
     * 
     */
    public function inputData($_id,$_area,$_date,$_time,$_awayteam,$_hometeam,$_fac,$_win,
                              $_h1a,$_h2a,$_ota,$_h1h,$_h2h,$_oth,
                              $_head,$_body,$_note,
                              $_aabr,$_habr,$_aname,$_hname,$_afinal,$_hfinal,$_apk,$_hpk,
                              $_goalsaway,$_goalshome,$_savesaway,$_saveshome)
    {   
        if($this->databaseConnection()) {
            $query_update_data = $this->db_connection->prepare("UPDATE schedule 
                                                                   SET area = :area,
                                                                       game_date = :game_date,
                                                                       game_time = :game_time,
                                                                       facility = :facility,
                                                                       away_id = :away_id,
                                                                       away_abbr = :away_abbr,
                                                                       away_h1 = :away_h1,
                                                                       away_h2 = :away_h2,
                                                                       away_ot = :away_ot,
                                                                       away_final = :away_final,
                                                                       away_pk = :away_pk,
                                                                       home_id = :home_id,
                                                                       home_abbr = :home_abbr,
                                                                       home_h1 = :home_h1,
                                                                       home_h2 = :home_h2,
                                                                       home_ot = :home_ot,
                                                                       home_final = :home_final,
                                                                       home_pk = :home_pk,
                                                                       winner = :winner,
                                                                       sum_head = :sum_head,
                                                                       sum_body = :sum_body,
                                                                       sum_note = :sum_note,
                                                                       away_goals = :away_goals,
                                                                       home_goals = :home_goals,
                                                                       away_saves = :away_saves,
                                                                       home_saves = :home_saves 
                                                                 WHERE game_id = :game_id");
            
            /************************************************************************/
            /*** GAME INFO **********************************************************/
            /************************************************************************/

            $query_update_data->bindValue(':area', $_area, PDO::PARAM_INT);
            $query_update_data->bindValue(':game_date', $_date, PDO::PARAM_STR);
            $query_update_data->bindValue(':game_time', $_time, PDO::PARAM_STR);
            $query_update_data->bindValue(':facility', $_fac, PDO::PARAM_STR);
            $query_update_data->bindValue(':game_id', $_id, PDO::PARAM_STR);
            
            /************************************************************************/
            /*** AWAY TEAM **********************************************************/
            /************************************************************************/
            
            $query_update_data->bindValue(':away_id', $_awayteam, PDO::PARAM_STR);
            
            if($_aabr != ''){
                $query_update_data->bindValue(':away_abbr', $_aabr, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':away_abbr', NULL, PDO::PARAM_NULL);
            }
            if(is_numeric($_h1a)){
                $query_update_data->bindValue(':away_h1', $_h1a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_h1', NULL, PDO::PARAM_NULL);
            }
            if(is_numeric($_h2a)){
                $query_update_data->bindValue(':away_h2', $_h2a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_h2', NULL, PDO::PARAM_NULL);
            }
            if(is_numeric($_ota)){
                $query_update_data->bindValue(':away_ot', $_ota, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_ot', NULL, PDO::PARAM_NULL);
            }
            if($_afinal != ''){
                $query_update_data->bindValue(':away_final', $_afinal, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_final', NULL, PDO::PARAM_NULL);
            }
            if($_apk != ''){
                $query_update_data->bindValue(':away_pk', $_apk, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_pk', NULL, PDO::PARAM_NULL);
            }

            /************************************************************************/
            /*** HOME TEAM **********************************************************/
            /************************************************************************/
            
            $query_update_data->bindValue(':home_id', $_hometeam, PDO::PARAM_STR);
            
            if($_habr != ''){
                $query_update_data->bindValue(':home_abbr', $_habr, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':home_abbr', NULL, PDO::PARAM_NULL);
            }
            if(is_numeric($_h1h)){
                $query_update_data->bindValue(':home_h1', $_h1h, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_h1', NULL, PDO::PARAM_NULL);
            }
            if(is_numeric($_h2h)){
                $query_update_data->bindValue(':home_h2', $_h2h, PDO::PARAM_INT);
            }
            else{
                $query_update_data->bindValue(':home_h2', NULL, PDO::PARAM_NULL);
            }
            if(is_numeric($_oth)){
                $query_update_data->bindValue(':home_ot', $_oth, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_ot', NULL, PDO::PARAM_NULL);
            }
            if($_hfinal != ''){
                $query_update_data->bindValue(':home_final', $_hfinal, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_final', NULL, PDO::PARAM_NULL);
            }
            if($_hpk != ''){
                $query_update_data->bindValue(':home_pk', $_hpk, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_pk', NULL, PDO::PARAM_NULL);
            }

            /************************************************************************/
            /*** RESULTS ************************************************************/
            /************************************************************************/

            if($_win != NULL){
                $query_update_data->bindValue(':winner', $_win, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':winner', NULL, PDO::PARAM_NULL);
            }
            if($_head != ''){
                $query_update_data->bindValue(':sum_head', $_head, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':sum_head', NULL, PDO::PARAM_NULL);
            }
            if($_body != ''){
                $query_update_data->bindValue(':sum_body', $_body, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':sum_body', NULL, PDO::PARAM_NULL);
            }
            if($_note != ''){
                $query_update_data->bindValue(':sum_note', $_note, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':sum_note', NULL, PDO::PARAM_NULL);
            }
            if($_goalshome != ''){
                $query_update_data->bindValue(':home_goals', $_goalshome, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':home_goals', NULL, PDO::PARAM_NULL);
            }
            if($_goalsaway != ''){
                $query_update_data->bindValue(':away_goals', $_goalsaway, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':away_goals', NULL, PDO::PARAM_NULL);
            }
            if($_saveshome != ''){
                $query_update_data->bindValue(':home_saves', $_saveshome, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':home_saves', NULL, PDO::PARAM_NULL);
            }
            if($_savesaway != ''){
                $query_update_data->bindValue(':away_saves', $_savesaway, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':away_saves', NULL, PDO::PARAM_NULL);
            }

            /************************************************************************/
            /*** QUERY **************************************************************/
            /************************************************************************/

            if($query_update_data->execute()){
              $this->messages = "{$_aname} at {$_hname} has been updated successfully.";
            }
            else{
              $this->messages = var_dump($query_update_data->errorInfo());
            }   
        }
        else{
            $this->messages = "There was a problem updating the game, please try again or contact your system administrator.";
        }  
    }
    /*
     * 
     * 
     */
    public function inputDataAway($_id,$_area,$_date,$_time,$_awayteam,$_hometeam,$_fac,$_win,
                                  $_h1a,$_h2a,$_ota,$_h1h,$_h2h,$_oth,
                                  $_aname,$_hname,$_afinal,$_hfinal,$_apk,$_hpk)
    {   
        if($this->databaseConnection()) {
            $query_update_data = $this->db_connection->prepare("UPDATE schedule 
                                                                   SET area = :area,
                                                                       game_date = :game_date,
                                                                       game_time = :game_time,
                                                                       facility = :facility,
                                                                       away_id = :away_id,
                                                                       away_h1 = :away_h1,
                                                                       away_h2 = :away_h2,
                                                                       away_ot = :away_ot,
                                                                       away_final = :away_final,
                                                                       away_pk = :away_pk,
                                                                       home_id = :home_id,
                                                                       home_h1 = :home_h1,
                                                                       home_h2 = :home_h2,
                                                                       home_ot = :home_ot,
                                                                       home_final = :home_final,
                                                                       home_pk = :home_pk,
                                                                       winner = :winner
                                                                 WHERE game_id = :game_id");
            
            /************************************************************************/
            /*** GAME INFO **********************************************************/
            /************************************************************************/

            $query_update_data->bindValue(':area', $_area, PDO::PARAM_INT);
            $query_update_data->bindValue(':game_date', $_date, PDO::PARAM_STR);
            $query_update_data->bindValue(':game_time', $_time, PDO::PARAM_STR);
            $query_update_data->bindValue(':facility', $_fac, PDO::PARAM_STR);
            $query_update_data->bindValue(':game_id', $_id, PDO::PARAM_STR);
            
            /************************************************************************/
            /*** AWAY TEAM **********************************************************/
            /************************************************************************/
            
            $query_update_data->bindValue(':away_id', $_awayteam, PDO::PARAM_STR);
            
            if(is_numeric($_h1a)){
                $query_update_data->bindValue(':away_h1', $_h1a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_h1', NULL, PDO::PARAM_NULL);
            }
            if(is_numeric($_h2a)){
                $query_update_data->bindValue(':away_h2', $_h2a, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_h2', NULL, PDO::PARAM_NULL);
            }
            if(is_numeric($_ota)){
                $query_update_data->bindValue(':away_ot', $_ota, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_ot', NULL, PDO::PARAM_NULL);
            }
            if($_afinal != ''){
                $query_update_data->bindValue(':away_final', $_afinal, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_final', NULL, PDO::PARAM_NULL);
            }
            if($_apk != ''){
                $query_update_data->bindValue(':away_pk', $_apk, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':away_pk', NULL, PDO::PARAM_NULL);
            }

            /************************************************************************/
            /*** HOME TEAM **********************************************************/
            /************************************************************************/
            
            $query_update_data->bindValue(':home_id', $_hometeam, PDO::PARAM_STR);
            
            if(is_numeric($_h1h)){
                $query_update_data->bindValue(':home_h1', $_h1h, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_h1', NULL, PDO::PARAM_NULL);
            }
            if(is_numeric($_h2h)){
                $query_update_data->bindValue(':home_h2', $_h2h, PDO::PARAM_INT);
            }
            else{
                $query_update_data->bindValue(':home_h2', NULL, PDO::PARAM_NULL);
            }
            if(is_numeric($_oth)){
                $query_update_data->bindValue(':home_ot', $_oth, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_ot', NULL, PDO::PARAM_NULL);
            }
            if($_hfinal != ''){
                $query_update_data->bindValue(':home_final', $_hfinal, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_final', NULL, PDO::PARAM_NULL);
            }
            if($_hpk != ''){
                $query_update_data->bindValue(':home_pk', $_hpk, PDO::PARAM_INT);
            }else{
                $query_update_data->bindValue(':home_pk', NULL, PDO::PARAM_NULL);
            }

            /************************************************************************/
            /*** RESULTS ************************************************************/
            /************************************************************************/

            if($_win != NULL){
                $query_update_data->bindValue(':winner', $_win, PDO::PARAM_STR);
            }else{
                $query_update_data->bindValue(':winner', NULL, PDO::PARAM_NULL);
            }

            /************************************************************************/
            /*** QUERY **************************************************************/
            /************************************************************************/

            if($query_update_data->execute()){
              $this->messages = "{$_aname} at {$_hname} has been updated successfully.";
            }
            else{
              $this->messages = var_dump($query_update_data->errorInfo());
            } 
        }
        else{
            $this->messages = "There was a problem updating the game, please try again or contact your system administrator.";
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
     * Deletes a row
     * @param $game game id
     * @param $away away team name
     * @param $home home team name
     */
    public function deleteData($game,$away,$home){
        if($this->databaseConnection()) {
            // set sql query
            $sql = "DELETE FROM schedule WHERE game_id = '{$game}'";
            // database query, deleting a game
            $query_delete_data = $this->db_connection->prepare($sql);
            // execute query
            if($query_delete_data->execute()){
              $this->messages = "{$away} at {$home} has been successfully deleted.";
            }
            else{
              $this->messages = var_dump($query_delete_data->errorInfo());
            } 
        }else{
            $this->messages = "There was a problem deleting {$away} at {$home} please try again or contact your system administrator.";
        }
    }
    /*
     * Adds a new row
     * @param bunch of stuffs
     */
    public function addData($_id,$_awayteam,$_hometeam,$_week,$_date,$_time,$_fac,$_area){

        if($this->databaseConnection()){
            //
            $query_add_game = $this->db_connection->prepare("INSERT INTO schedule 
                                                                         (game_id, 
                                                                          week, 
                                                                          game_date, 
                                                                          game_time, 
                                                                          facility, 
                                                                          area, 
                                                                          away_id, 
                                                                          away_abbr, 
                                                                          away_h1, 
                                                                          away_h2, 
                                                                          away_ot, 
                                                                          away_final,
                                                                          away_pk, 
                                                                          home_id, 
                                                                          home_abbr, 
                                                                          home_h1, 
                                                                          home_h2, 
                                                                          home_ot, 
                                                                          home_final,
                                                                          home_pk,
                                                                          winner,
                                                                          sum_head,
                                                                          sum_body,
                                                                          sum_note,
                                                                          away_goals,
                                                                          home_goals,
                                                                          away_saves,
                                                                          home_saves) 
                                                                  VALUES (:game_id, 
                                                                          :week, 
                                                                          :game_date, 
                                                                          :game_time, 
                                                                          :facility, 
                                                                          :area, 
                                                                          :away_id, 
                                                                          :away_abbr, 
                                                                          :away_h1, 
                                                                          :away_h2, 
                                                                          :away_ot, 
                                                                          :away_final,
                                                                          :away_pk, 
                                                                          :home_id, 
                                                                          :home_abbr, 
                                                                          :home_h1, 
                                                                          :home_h2, 
                                                                          :home_ot, 
                                                                          :home_final,
                                                                          :home_pk,
                                                                          :winner, 
                                                                          :sum_head, 
                                                                          :sum_body,
                                                                          :sum_note,
                                                                          :away_goals,
                                                                          :home_goals,
                                                                          :away_saves,
                                                                          :away_saves)");

            
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
            $query_add_game->bindValue(':away_h1', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_h2', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_ot', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_final', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_pk', NULL, PDO::PARAM_NULL);

            /************************************************************************/
            /*** HOME TEAM **********************************************************/
            /************************************************************************/

            $query_add_game->bindValue(':home_id', $_hometeam, PDO::PARAM_STR);
            $query_add_game->bindValue(':home_abbr', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_h1', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_h2', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_ot', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_final', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_pk', NULL, PDO::PARAM_NULL);

            /************************************************************************/
            /*** RESULTS ************************************************************/
            /************************************************************************/

            $query_add_game->bindValue(':winner', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':sum_head', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':sum_body', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':sum_note', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_goals', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_goals', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':away_saves', NULL, PDO::PARAM_NULL);
            $query_add_game->bindValue(':home_saves', NULL, PDO::PARAM_NULL);

            /************************************************************************/
            /*** QUERY **************************************************************/
            /************************************************************************/

            if($query_add_game->execute()){
              $this->messages = "Your game was added successfully.";
            }
            else{
              $this->messages = var_dump($query_add_game->errorInfo());
            }
            
        }
        else{
            $this->messages = "There was a problem adding your game, please try again or contact your system administrator.";
        }
    }
    /*
     * 
     * 
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