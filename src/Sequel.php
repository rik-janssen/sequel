<?php
namespace Sequel;

use Sequel\Layer\Connect;
use Sequel\Layer\Properties;


class Sequel extends Properties{


    /**
     * The most basic of them all
     * run an SQL query.
     */

    public $template;
    public $table;
    public $where = [];
    public $vars = [];
    public $relation = [];
    public $limit = NULL;
    public $offset = NULL;
    public $paginate;
    public $sort;
    public $key = [];
    public $fields = [];

    public function __construct(string $sql, string $table = NULL, string $qtype = 'select'){

        $this->template = $sql; // the SQL template string 
        $this->table = $table; // the table name
        $this->type = $qtype; // the type of query (SELECT|INSERT|UPDATE|DELETE)

    }

    public static function query(string $sql, array $prepare = array()){

        /**
         * Run a basic query with 
         * prepared statements.
         */

        $db = new Connect($sql, $prepare);
        return $db->results();

    }

    public static function sql(string $sql){

        /**
         * Run a vanilla sql string. 
         * Warning! This is not a safe
         * way of accessing the db!
         */
        
        $db = new Connect($sql);
        return $db->results();

        /**
         * Mainly used within the 
         * Tablier class and other
         * non-accessable internals.
         */

    }

    /**
     * A simplified select
     * method to fetch basic
     * information quickly.
     */

    public static function select(string $table){

        return new Sequel("SELECT {%columns%} FROM {%table%} {%where%} {%limit%} {%sort%}", $table, 'select');

    }
 
    /**
     * Add data to your
     * table on an 
     * intuitive manner.
     */

    public static function insert(string $table){

        return new Sequel("INSERT INTO {%table%} ({%fields%}) VALUES ({%values%})", $table, 'insert');

    }

    /**
     * Quick, safe and
     * easy row updating.
     */

    public static function update(string $table){

        return new Sequel("UPDATE {%table%} SET {%fields%} {%where%}", $table, 'update');

    }

    /**
     * A super easy
     * delete method.
     */

    public static function delete(string $table){

        return new Sequel("DELETE FROM {%table%} {%where%}", $table, 'delete');

    }

    /**
     * Build a model.
     * Pretty much a Select.
     */

    public static function model(string $table){

        return new Sequel("SELECT {%columns%} FROM {%table%} {%where%} {%limit%} {%sort%}", $table, 'model');

    } 

}
