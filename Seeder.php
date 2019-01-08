<?php
require_once("Queries.php");

class Seeder{
    private $mysqli;
    private $queries;

    function __construct($mysqli){
        $this->mysqli = $mysqli;
        $this->queries = new Queries();
    }

    public function seedDb(){
        $path = getenv("DATA_PATH");
        $file = file_get_contents($path);
    
        $json = json_decode($file, true);
        $categories = $json["categories"];
        $products = $json["products"];
        
        $this->addToCategories($categories);
        $this->addToProducts($products);
     }

    private function addToCategories($categories){
        foreach($categories as $cat){
            $query = $this->queries->addCategory($cat);
            $this->mysqli->query($query);
        }
    }

    private function addToProducts($products){
       
    }

}


