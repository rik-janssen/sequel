<?php

namespace Sequel\Layer;

use Sequel\Sequel;

/**
 * This class handles
 * gathering data from
 * relationship- and
 * related tables by id.
 */

class Relation{

    public $relation = [];
    public $instance = [];
    public $result = [];


    public function __construct($instance, $result){

        $this->instance = $instance;
        $this->result = $result;    

    }

    /**
     * This is the method
     * ran by the Execute 
     * class that pulls
     * all rows.
     */

    public static function attach($instance, $result){

        $rel = new Relation($instance, $result);
        $rel->oneToOne();
        $rel->oneToMany();

        return $rel->relation;

    }
    
    /**
     * Pull information from
     * a related table and 
     * build an array
     * around it.
     */

    public function oneToOne(){

        /**
         * First we check if the
         * method has to be ran.
         */

        if(isset($this->instance['one'])){

            /**
             * Pull all the relation
             * requests from the 
             * array.
             */

            $relations = $this->instance['one'];
            
            /**
             * Loop trough the 
             * presented array.
             */
            
            if(!is_array($relations)){ return; }
            foreach($relations as $relation){
                
                /**
                 * Pull the matching value 
                 * from the results var.
                 */

                $value = $this->result[$relation['parent']];

                /**
                 * And build an SQL query
                 * to fetch the information
                 * from the related table.
                 */

                $ret[$relation['table']] = Sequel::select($relation['table'])->where($relation['field'], '=', $value)->do();

            }

            /** 
             * And then we
             * return the array.
             */

            $this->relation = array_merge($ret, $this->relation);

        }

    }


    /**
     * Pull information via
     * an relationship table.
     */

    public function oneToMany(){

        /**
         * First we check if the
         * method has to be ran.
         */

        if(isset($this->instance['many'])){

            /**
             * Pull all the relation
             * requests from the 
             * array.
             */

            $relations = $this->instance['many'];
            
            /**
             * Loop trough the 
             * presented array.
             */

            if(!is_array($relations)){ return; }
            foreach($relations as $relation){
                
                /**
                 * Set up all needed
                 * properties.
                 */

                $parentid = $this->result['id'];
                $parenttable = $relation['parent'];
                $childtable = $relation['child'];
                $table = $parenttable."_".$childtable."_relation";
                $res = [];

                /**
                 * Pull the rows from
                 * the relation table.
                 */

                $attachments = Sequel::select($table)->where($parenttable."id", '=', $parentid)->do();

                /**
                 * Let's loop trough
                 * these results.
                 */
                
                if(!is_array($attachments)){ return; }
                foreach($attachments as $attach){

                    /**
                     * Pull the rows from 
                     * the child table.
                     */

                    $query = Sequel::select($childtable)->where("id", '=', $attach[$childtable.'id'])->do();

                    /**
                     * If it holds data
                     * then add it to the
                     * array.
                     */
                    
                    if($query!=NULL){
                        $res = array_merge($res, $query);
                    }

                }

                /**
                 * Store the results
                 * in an array.
                 */

                $ret[$childtable] = $res;
            
            }

            /** 
             * And then we
             * return the array.
             */

            $this->relation = array_merge($ret, $this->relation);

        }

    }


}