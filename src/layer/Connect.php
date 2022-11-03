<?php

namespace Sequel\Layer;

use PDO;
use PDOException;
use Logger\Log;
use Config\Config;


/**
 * This class allows you
 * to switch between database types
 * based on the config file.
 */

class Connect{

    /**
     * Connect to the database and
     * run SQL to return stuff.
     */

    private $db;

    public function __construct(string $sql, array $vars = []){

        /**
         * Start a PDO instance.
         */

        $db = $this->connect();
     
        /**
         * Prepare and execute 
         * the SQL string with 
         * prepared variables.
         */

        $exec = $db->prepare($sql);
        $exec->execute($vars);

        Log::to(['Query' => $sql,'Vars' => $vars],'sequel');

        /**
         * On the receiving end
         * we'll make a nice
         * array of data.
         */

        $fetchAll = $exec->fetchAll();

        if(isset($fetchAll[0])){

            $this->result['all'] = $fetchAll; 

        }

        /**
         * If a field is updated
         * throw back a row
         * count to check how
         * many fields are 
         * updated.
         */

        $this->result['count'] = $exec->rowCount();

        /**
         * If a new value is 
         * inserted, throw back
         * the ID.
         */

        $lastId = $db->lastInsertId();

        if($lastId!=0){

            $this->result['id'] = $lastId;    
        }


    
    }

    /**
     * Connection method.
     */

    private function connect(){

        /**
         * Start an instance
         * with a database 
         * connection.
         */

        $dsn = sprintf( 'mysql:dbname=%s;host=%s;port=%s', Config::get('mysql/db'), Config::get('mysql/host'), Config::get('mysql/port') );

        try {

            /**
             * Build the PDO instance
             * with the config data.
             */

            $db = new PDO ( $dsn, Config::get('mysql/user'), Config::get('mysql/pass'));
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            return $db;

        } catch (PDOException $e) {

            /**
             * If it fails we'll write
             * to the logger and die 
             * with an error message.
             */
            
            $error = $e->getMessage();
            Log::to(['DB Connection failed' => $error],'sequel');

            die("No database connection.");

        }

    }

    public function results(){

        /**
         * Return the private
         * variable with the 
         * results.
         */

        return $this->result;
        
    }



}