<?php

class Navigation{

    public function getActiveDir(){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $urlArray = explode("/?", $trimmedUrl);
        $currentDir = urldecode($urlArray[0]);

        return $currentDir;
    }
    
    public function sortBy(){
        return isset($_GET['sort']);
    }

    public function sortByPrice(){
        return $_GET['sort'] === 'price';
    }

    public function sortByStock(){
        return $_GET['sort'] === 'stock';
    }

    public function sortByPopularity(){
        return $_GET['sort'] === 'popular';
    }

    public function sortRequested(){
        return isset($_GET);
    }
}