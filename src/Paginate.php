<?php

namespace Sequel;

use Config\Config;


/**
 * Quick and easy
 * pagination on 
 * your page.
 */

class Paginate{

    public $count;
    public $page;
    public $array = [];

    public function __construct($results){

        $this->count = $results['paginate_count'];
        $this->page = $results['paginate_page']+1;

        //if($this->page == 0){ $this->page = 1; }

        $this->pages = ceil($results['paginate_count'] / Config::get('sequel/paginate'));
        $this->block = $results['count'];

        $this->arrayAssemble();

        return $this->array;

    }

    /**
     * If you want to 
     * build your own 
     * pagination.
     */

    public static function build($results){

        if($results==NULL){ return; }
        return new Paginate($results);

    }

    /**
     * The front end
     * method for 
     * pagination.
     */

    public static function front($results){

        /** 
         * If there is nothing,
         * return!
         */

        if($results==NULL){ return; }
        
        /**
         * Fetch some route
         * and path info.
         */

        $route = V_PATH;
        $path = $route['uri'].'/'.$route['set'];

        /**
         * Rebuild the uri
         * string for later.
         */

        $cleanPath = explode("/", $path);
        array_pop($cleanPath);
        $cleanPath = implode("/", $cleanPath)."/{paginate}";
        //dump($cleanPath); //// MUST DO SOMETHING WITH THIS, 

        /**
         * Fetch the results
         * from Sequel.
         */

        $a = new Paginate($results);

        echo '<nav aria-label="Page navigation example"><ul class="pagination">';
        foreach($a->array['list'] as $list){

            /** Rebuild the string: */
            $path = str_replace("{paginate}", $list['number'], $cleanPath);
            
            /** Echo the HTML */
            if($list['number']==$route['vars']['paginate']){
                echo '<li class="page-item"><a class="page-link active" href="'.$path.'">'.$list['number'].'</a></li>';
            }else{
                echo '<li class="page-item"><a class="page-link" href="'.$path.'">'.$list['number'].'</a></li>';
            }
        }

        echo '</ul></nav>';


    }


    /**
     * Assemble an array
     * based on the results.
     */

    public function arrayAssemble(){

        for ($item = 1; $item <= $this->pages; $item++) {
            
            if($this->page == $item){ $set = true; }else{ $set = false; }

            $array['list'][] = [
                'number' => $item, 
                'this' => $set
            ];

        }

        $array['pages'] = $this->pages;
        $array['results'] = $this->count;
        $array['perpage'] = $this->block;

        $this->array = $array;

    }



}

// Paginate::build($results_array)->front(); // should return html
// Paginate::build($results_array)->return(); // should return an array