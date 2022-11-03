<?php
namespace Sequel\Tablier;

/**
 * This class holds
 * all tools for the
 * construct class.
 */

class Tools{

    /**
     * This are all the SQL
     * string constructor
     * tools that build 
     * each column.
     */

    public function varchar(array $a){ 
    
        return $this->populate($a, "{%name%} {%type%}({%length%}) {%null%} {%default%} {%unique%}"); 
    
    }

    public function text(array $a){ 
    
        return $this->populate($a, "{%name%} {%type%} {%null%} {%default%}"); 
    
    }

    public function int(array $a){ 
    
        return $this->populate($a, "{%name%} {%type%}({%length%}) {%null%} {%default%} {%unique%} {%increment%}"); 
    
    }

    public function bigint(array $a){ 
    
        return $this->populate($a, "{%name%} {%type%}({%length%}) {%null%} {%default%} {%unique%} {%increment%}"); 
    
    }

    public function date(array $a){ 
    
        return $this->populate($a, "{%name%} {%type%} {%null%} {%default%} {%update%} {%unique%}"); 
    
    }

    public function time(array $a){ 
    
        return $this->populate($a, "{%name%} {%type%} {%null%} {%default%} {%update%} {%unique%}"); 
    
    }

    public function utime(array $a){ 
    
        return $this->populate($a, "{%name%} BIGINT({%length%}) {%null%} {%default%} {%unique%}"); 
    
    }

    public function timestamp(array $a){ 
    
        return $this->populate($a, "{%name%} {%type%} {%null%} {%default%} {%update%} {%unique%}"); 
    
    }

    public function float(array $a){ 
    
        return $this->populate($a, "{%name%} {%type%}({%length%}) {%null%} {%default%} {%unique%}"); 
    
    }

    public function decimal(array $a){ 
    
        return $this->populate($a, "{%name%} {%type%}({%length%}) {%null%} {%default%} {%unique%}"); 
    
    }


    /**
     * This helper method
     * populates the string
     * with the needed
     * values from the
     * object.
     */

    public function populate($a, $string){

        /**
         * Prepare the 
         * replacement 
         * variables.
         */

        $f = $a['fields'];
        
        if(isset($f->null)){ $f->null = " ".$f->null; }

        if(isset($f->default) && $f->default == "CURRENT_TIMESTAMP"){ $f->default = " DEFAULT ".$f->default; $f->null = " NOT NULL"; }elseif(isset($f->default)){ $f->default = " DEFAULT '".$f->default."'"; }
        if(isset($f->onupdate) && $f->onupdate==true){ $f->onupdate = " ON UPDATE CURRENT_TIMESTAMP"; $f->null = " NOT NULL"; }else{ $f->onupdate = ''; }
                
        if(isset($f->unique) && $f->unique==true){ $f->unique = " UNIQUE"; }else{ $f->unique = ''; }
        if(isset($f->increment) && $f->increment==true){ $f->increment = " AUTO_INCREMENT"; $f->null = " NOT NULL"; }else{ $f->increment = ''; }

        /**
         * Populate 
         * the column
         * string.
         */

        $string = str_replace(" ", "", $string);

        $string = str_replace("{%name%}", "`".$f->name."` ", $string);
        $string = str_replace("{%type%}", strtoupper($a['type']), $string);
        $string = str_replace("{%length%}", $a['length'], $string);
        $string = str_replace("{%null%}", $f->null, $string);
        $string = str_replace("{%default%}", $f->default, $string);
        $string = str_replace("{%update%}", $f->onupdate, $string);
        $string = str_replace("{%unique%}", $f->unique, $string);
        $string = str_replace("{%increment%}", $f->increment, $string);

        /**
         * Clean up 
         * the ends.
         */

        $string = rtrim($string);

        return $string;

    }

}