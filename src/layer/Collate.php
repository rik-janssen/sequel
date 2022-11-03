<?php

namespace Sequel\Layer;

class Collate{

    /**
     * This class builds the actual
     * query from the variables
     * available within a Sequel
     * instance.
     */

    private $instance;
    

    public function __construct($instance){

        $this->instance = $instance;

    }

    /**
     * Build a SQL string part
     * from the where array.
     */

    public function where(){

        /**
         * How many where
         * clauses are there
         * to work with?
         */

        $count = count($this->instance->where);

        switch ($count) {
            case 0:

                /**
                 * If there are no where
                 * clauses, then return
                 * an empty string.
                 */

                return '';

            break;
            default:

                /**
                 * When there are more than
                 * 0, we go for a loop
                 */

                $where = $this->instance->where;
                
                $sql = [];
                $i = 1;
                foreach($where as $parts){

                    /**
                     * Check if this is not
                     * the last where clause
                     * within the query
                     */

                    if($i!=$count){ $extender = ' ' . $parts[3]; }
                    else{ $extender = ''; }

                    /**
                     * Add the query part 
                     * to an array.
                     */
                    
                    $sql[] = $parts[4] . '.' . $parts[0] . ' ' . $parts[1] . ' :where_' . $parts[0] . $extender;
                    $i++;
                    
                }

                /**
                 * Return this array 
                 * as a string.
                 */

                return  "WHERE " . implode(' ',$sql) . " ";

            break;

        }

    }

    /**
     * Build a SQL string part
     * from the limit string.
     */

    public function limit(){

        /**
         * Limit the amount
         * of results.
         */

        $offset = '';

        if($this->instance->offset != NULL){
            $offset = " OFFSET ". $this->instance->offset;
        }

        if($this->instance->limit != NULL){
            return "LIMIT ". $this->instance->limit . $offset;
        }

        /**
         * Or return an 
         * empty string.
         */

        return '';
    }

    /**
     * Build a SQL string part
     * from the sort array.
     */

    public function sort(){

        /**
         * Check if the orderby
         * array is filled.
         */

        $sort = $this->instance->sort;

        /**
         * If so, return a 
         * SQL string.
         */

        if(!empty($sort)){
            return "ORDER BY ". $sort[0] . ' ' . $sort[1];
        }

        /**
         * Or return an 
         * empty string.
         */
        
        return '';

    }

    /**
     * Build a SQL string
     * from the columns array.
     */

    public function columns(){

        return $this->instance->table.'.*';

    }

    /**
     * Create a Fields
     * string for an 
     * Insert Into.
     */

    public function fields(){

        $fields = $this->instance->fields;

        /**
         * When the query
         * type is INSERT:
         */

        if($this->instance->type=="insert"){

            foreach($fields as $key){

                $sql[] = "`".$key."`";

            }

            return implode(', ',$sql);

        }
        

        /**
         * When the query
         * type is UPDATE:
         */

        if($this->instance->type=="update"){

            foreach($fields as $key){

                $sql[] = "`".$key."`= :set_".$key."";

            }

            return implode(', ',$sql);
            
        }

    }

    /**
     * Create a Values
     * string for an 
     * Insert Into.
     */

    public function values(){

        $values = $this->instance->vars;

        /**
         * When the query
         * type is INSERT:
         */

        if($this->instance->type=="insert"){

            foreach($values as $key => $value){

                $sql[] = ":".$key."";

            }

            return implode(', ',$sql);

        }
        
    }

}