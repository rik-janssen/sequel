<?php
namespace Sequel\Tablier;

/**
 * This class takes 
 * are of the additional
 * parameters of the
 * set columns and 
 * stores them in an
 * array for Construct.
 */

class Fields{

    public $name;
    public $null;
    public $default;
    public $primary = false;
    public $increment;
    public $foreign;
    public $unique;
    public $check;
    public $onupdate;

    public function __construct($name){

        $this->name = $name;

    }

    /**
     * Set column to 
     * accept NULL
     */

    public function null(){

        $this->null = "NULL";

        return $this;

    }

    /**
     * Set column not  
     * to accept NULL
     */

    public function notnull(){

        $this->null = "NOT NULL";

        return $this;

    }

    /**
     * Set default
     * value.
     */

    public function default($val = "CURRENT_TIMESTAMP"){

        $this->default = $val;

        return $this;

    }

    /**
     * Primary key
     * with auto
     * increment.
     */

    public function primary($increment = true){

        $this->primary = true;
        $this->increment = $increment;

        return $this;

    }

    /**
     * Set foreign key.
     */

    public function foreign($table){

        $this->foreign = $table;

        return $this;

    }

    /**
     * Only accepts 
     * unique values.
     */

    public function unique(){

        $this->unique = true;

        return $this;

    }

    /**
     * Check if the value
     * is ok before storing.
     */
/*
    public function check($delimiter, $value){

        $this->check[] = [$this->name, $delimiter, $value];

        return $this;

    }*/

    /**
     * On update for
     * date/datetime
     */

    public function onupdate($value = "CURRENT_TIMESTAMP"){

        $this->default = $value;
        $this->onupdate = $value;

        return $this;

    }

    

}
