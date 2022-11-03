<?php
namespace Sequel\Tablier;

use Sequel\Tablier\Fields;

/**
 * Here we define all
 * available column 
 * types for the
 * database table
 * to build.
 */

class Columns{

    public function varchar($name, $length = 100){

        return $this->template($name, 'varchar', $length);
    
    }

    public function text($name){

        return $this->template($name, 'text', 0);

    }
    
    public function int($name, $length = 20){

        return $this->template($name, 'int', $length);

    }

    public function bigint($name, $length = 60){

        return $this->template($name, 'bigint', $length);

    }
    
    public function decimal($name, $length = 10, $float = 2 ){

        return $this->template($name, 'decimal', $length.','.$float);

    }

    public function float($name, $length = 60){

        return $this->template($name, 'float', $length);

    }

    public function date($name){

        return $this->template($name, 'date', 0);

    }

    public function time($name){

        return $this->template($name, 'time', 0);

    }

    public function timestamp($name){

        return $this->template($name, 'timestamp', 0);

    }

    public function utime($name, $length = 12){

        return $this->template($name, 'utime', $length);

    }


    /**
     * The array template
     * for all the column
     * types.
     */

    public function template($name, $type, $length){

        $fields = new Fields($name);
        
        $a = [
            'type'=> $type,
            'length' => $length,
            'fields' => $fields
        ];

        $this->field[] = $a;

        return $fields;

    }
    

}