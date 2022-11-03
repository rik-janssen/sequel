<?php

namespace Sequel\Layer;

use Elemental\Re;
use Config\Config;
use Sequel\Layer\Execute;


class Properties extends Execute{

    /**
     * This class has the most used
     * SQL helper methods. This populates
     * the instance.
     */


    /**
     * Filter information with the 
     * where attribute. This populates
     * the array for Collate;
     */

    public function where(string $key, string $modifier, string $value, string $extend = "AND"){

        /**
         * Build an array of where
         * clauses for the Collate
         * class to work with.
         */

        $this->where[] = [$key, $modifier, $value, $extend, $this->table];
        $this->vars['where_'.$key] = $value;

        return $this;
    
    }

    /**
     * Search a table with the like
     * attribute. Basically a Where.
     * This populates the array.
     */

    public function like(string $key, string $value, string $pattern="%*%", string $extend = "AND"){

        $value = str_replace("*", $value, $pattern);
        $this->where[] = [$key, "LIKE", $value, $extend, $this->table];
        $this->vars['where_'.$key] = $value;

        return $this;

    }

    /**
     * Limit the amount of results.
     * This populates the string.
     */

    public function limit(int $count = NULL){

        /**
         * Build an array of where
         * clauses for the Collate
         * class to work with.
         */

        if($count == NULL){ $count = Config::get('sequel/limit'); }
        $this->limit = $count;

        return $this;
    
    }

    /**
     * Order by column and 
     * change asc to desc.
     * This pupulates an
     * array.
     */

    public function sort(string $by = "id", string $order = "ASC"){

        /**
         * Build an array of where
         * clauses for the Collate
         * class to work with.
         */

        $this->orderby = [$by, strtoupper($order)];

        return $this;
    
    }

    /**
     * Insert fields and
     * values to the instance
     * for Insert Into and
     * Update Static.
     */

    public function set(string $field, string $value){

        $this->fields[] = $field;
        $this->vars['set_'.$field] = $value;

        return $this;
    }

    /**
     * Insert the same fields 
     * as set() but then 
     * as an array.
     */

    public function mass(array $array){

        foreach($array as $key => $value){

            $this->set($key, $value);

        }

        return $this;
    }

    /**
     * Set up related tables
     * via the Relation method.
     */

    public function one(string $table, string $field = NULL, string $parent = NULL){

        /**
         * Fetch rows from another table 
         * where the child.parentid
         * equals the parent.id.
         */

        if($field==NULL){ $field = $this->table."id" ;}
        if($parent==NULL){ $parent = "id" ;}

        $this->relation['one'][] = [
            'table' => $table, 
            'field' => $field,
            'parent' => $parent,
        ];

        return $this;
        
    }

    /**
     * Stick a table based 
     * on a parent.childid
     * to the array.
     */

    public function join(string $table, string $field = NULL, string $parent = NULL){

        /**
         * Fetch rows from another table 
         * where the child.parentid
         * equals the parent.id.
         */

        if($field==NULL){ $field = "id" ;}
        if($parent==NULL){ $parent = $table."id" ;}

        $this->relation['one'][] = [
            'table' => $table, 
            'field' => $field,
            'parent' => $parent,
        ];

        return $this;
        
    }

    /**
     * Set up related tables
     * via the Relation method.
     */

    public function many(string $parent, string $child){

        /**
         * Fetches rows via an
         * relationship table.
         */


        $this->relation['many'][] = ['parent' => $parent, 'child' => $child];

        return $this;
        
    }

    /**
     * Compare the session
     * with the table field
     */

    public function me(string $key = NULL){

        /**
         * Check if there is
         * an available
         * user session.
         */

        if(isset($_SESSION['user']['id']) AND $_SESSION['user']['id']!=0){

            /**
             * Add the WHERE 
             * attribute with 
             * the user details.
             */

            if($key==NULL){ $key = 'id'; }

            $this->where[] = [$key, '=', $_SESSION['user']['id'], 'AND', $this->table];
            $this->vars['where_'.$key] = $_SESSION['user']['id'];

        }else{

            /**
             * If there is no
             * user/id session
             * then activate 
             * the failsafe.
             */

            Re::direct(Config::get('errordirect'));
            die('There is no available session.');

        }

        return $this;

    }

    /**
     * Set up quick and
     * easy pagination.
     */

    public function paginate(array $page){

        /**
         * Pick the part
         * from the path 
         * that we need.
         */

        if(!isset($page['paginate'])){ $page['paginate'] = 1; }

        $page = $page['paginate']-1;

        /**
         * Page variable is the
         * number from the Route
         * string passed on.
         * (page 0,1,2,3,4,5 etc..)
         */


        if($this->limit == NULL){

            /**
             * If the limit is not set yet,
             * get the config value.
             */

            $this->limit = Config::get('sequel/paginate');

        }

        /**
         * Set the starting point
         * of returned records.
         */

        $this->offset = $page * $this->limit;

        $this->paginate['current_page'] = $page;
        $this->paginate['total_rows'] = 0;
        
        return $this;

        /**
         * If ->limit() is used this
         * has to be IN FRONT of ->paginate
         * in order for it to work 
         * properly.
         */

    }

}