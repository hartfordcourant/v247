<?php

/**
 * Handles db connection
 * Inputs and retrieves data
 */

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
            // confirm db connection
            $this->messages = "Successfully connected to database.";
        }
        else{
            // alerts connection error
            $this->messages = "There was a problem connecting to the database.";
        }
    }
    /**
     * Checks if db connection is open and opens it if it's not
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
    /**
     * Gets the results of db query
     * @param $sql, sql query
     * @return $object_array, all of data
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
     * Closes db connection
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