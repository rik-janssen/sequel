<?php 

namespace Sequel\Layer;

use Sequel\Sequel;
use Config\Config;
use Sequel\Layer\Collate;
use Sequel\Layer\Relation;

/**
 * This class houses the 
 * executes for the DB query 
 * classes.
 */

class Execute{

    /**
     * This should always be 
     * the last method in an
     * assembled static.
     */

    public function do(){

        /**
         * Build the query and when
         * there should be results, 
         * fetch the results.
         */

        $collate = $this->collate();
        
        $this->results = Sequel::query($collate, $this->vars);

        $this->paginateCount();

        /**
         * Sequel query accepts 
         * the collated SQL string
         * and the variables as
         * an array.
         * 
         * The process method adds
         * the related tables.
         */

        
        return $this->process();


        
    }

    public function first(){

        /**
         * Build the query and when
         * there should be a result, 
         * fetch the first result.
         */

         $results = $this->do();

         if(isset($results['all'])){
            return $results['all'][0];
         }else{
             return $results;
         }
        
    }

    /**
     * This uses the Collate
     * class to assemble the
     * SQL query.
     */

    public function collate($paginate = false){

        $collate = new Collate($this);

        $sql = $this->template;

        /**
         * When we have the SQl
         * template string, we 
         * work our magic.
         */
        
        $sql = str_replace("{%table%}", $this->table, $sql);
        $sql = str_replace("{%columns%}", $collate->columns(), $sql);
        $sql = str_replace("{%where%}", $collate->where(), $sql);
        $sql = str_replace("{%sort%}", $collate->sort(), $sql);
        $sql = str_replace("{%fields%}", $collate->fields(), $sql);
        $sql = str_replace("{%values%}", $collate->values(), $sql);

        if($paginate == true){
            $sql = str_replace("{%limit%}", '', $sql);
        }else{
            $sql = str_replace("{%limit%}", $collate->limit(), $sql);
        }


        /**
         * Some cleanup, removing
         * spaces and stuff.
         */

        $sql = preg_replace('/\s+/', ' ', $sql); // double spaces
        $sql = rtrim($sql).";"; // end space

        /**
         * Store the 
         * finished query.
         */

        $this->query = $sql;

        /**
         * Return the finished
         * SQL query.
         */

        return $sql;

    }

    /**
     * Process the query
     * results and attach
     * the relational data. 
     */

    public function process(){

        /**
         * Check if it is 
         * a full array.
         */

        if(isset($this->results['all'])){

            /**
             * Run a foreach to 
             * every database row.
             */

            $id=0;

            foreach($this->results['all'] as $result){
                
                /**
                 * Then we rebuild the
                 * columns within the
                 * array.
                 */

                foreach($result as $column => $contents){

                    $processed['results'][$id][$column] = $contents;

                    
                }

                /**
                 * And we stick on the 
                 * related columns from
                 * the Relation class.
                 */

                $processed['results'][$id]['related'] = Relation::attach($this->relation, $processed['results'][$id]);

                $id++;
            }

            /**
             * Add some count
             * information.
             */

            $processed['count'] = $this->results['count'];

            /**
             * Add in the paginate
             * information if 
             * there is some.
             */

            if(is_array($this->paginate)){
                $processed['paginate_count'] = $this->paginate['total_rows'];
                $processed['paginate_page'] = $this->paginate['current_page'];
            }
            

            // debug Query build.
            if(Config::get('sequel/debug')){
                dump($this, 'Sequel query internals'); 
            }

            return $processed;

        }
        
    }

    /**
     * Run a separate query
     * for pagination count.
     */

     public function paginateCount(){

        /**
         * If the pagination array
         * is set and used.
         */

        if(is_array($this->paginate)){

            /**
             * Run the collate
             * without the
             * limit and order.
             */

            $fullColl = $this->collate(true);

            //dump($this, 'get count with pagination'); // debug Query build.

            /**
             * Run the query.
             */

            $count = Sequel::query($fullColl, $this->vars);
            
            /**
             * If the variable
             * is set, return 
             * value. Else
             * return 0.
             */

            if(isset($count['count'])){
                $this->paginate['total_rows'] = $count['count'];
            }else{
                $this->paginate['total_rows'] = 0;
            }

            //$this->paginate['total_rows'];

        }

     }

}