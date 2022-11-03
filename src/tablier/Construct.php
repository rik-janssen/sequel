<?php
namespace Sequel\Tablier;

class Construct extends Tools{

    public $instance;
    public $sql;

    public function __construct($instance){

        
        $this->instance = $instance;

    }

    /**
     * All SQL is constructed
     * and the string should be
     * in this->sql right now.
     */

    public function build(){

        /**
         * Grab the tempate
         * from the instance.
         */

        $sql = $this->instance->template;

        /**
         * Replace the 
         * table name.
         */

        $sql = str_replace("{%tablename%}", $this->instance->table, $sql);

        /**
         * Build a string
         * from all the 
         * columns.
         */

        $params = implode(", ", array_filter([$this->columns(), $this->primary(), $this->foreign()]));

        /**
         * Replace those
         * said columns 
         * in the template.
         */

        $this->sql = str_replace("{%columns%}", $params, $sql);

        return $this->sql;
        
    }

    /**
     * Build a string
     * from all columns.
     */

    public function columns(){

        $sqlPart = [];
        foreach($this->instance->field as $column){
            
            $type = $column['type'];
            $sqlPart[] = $this->$type($column);

        }

        return implode(", ", $sqlPart);

    }

    /**
     * Build the primary
     * key string.
     */

     public function primary(){

        $primary = '';

        foreach($this->instance->field as $column){
            
            if(isset($column['fields']->primary) && $column['fields']->primary == true){ 
                $primary = 'PRIMARY KEY (`'.$column['fields']->name.'`)';
            }
        }

        return $primary;
        
     }

    /**
     * Build the foreign
     * key string.
     */

    public function foreign(){

        $foreign = [];

        foreach($this->instance->field as $column){
            
            if(isset($column['fields']->foreign) && $column['fields']->foreign != false){ 
                $foreign[] = 'FOREIGN KEY (`'.$column['fields']->foreign.'id`) REFERENCES '.$column['fields']->foreign.'(`id`)';
            }
        }

        return implode(", ", $foreign);
        
     }


    /**
     * Build the check
     * string.
     */

    /*public function check(){

        $foreign = [];

        foreach($this->instance->field as $column){
            
            if(isset($column['fields']->check) && $column['fields']->check != NULL){ 

                foreach($column['fields']->check as $check){
                    $foreign[] = 'CHECK ('.$check[0].' '.$check[1].' '.$check[2].')';
                }
                
            }
        }

        return implode(", ", $foreign);
        
     }*/


     

}
