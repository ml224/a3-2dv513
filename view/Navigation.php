<?php

class Navigation{

    public function getActiveDir(){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $urlArray = explode("/", $trimmedUrl);
        $currentDir = urldecode($urlArray[0]);

        return $currentDir;
    }
    

    public function hasSubDir(){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $urlArray = explode("/", $trimmedUrl);
        return count($urlArray) > 1;
    }

    
    public function getSubDir(){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $urlArray = explode("/", $trimmedUrl);
        $subDir = urldecode($urlArray[1]);

        return $subDir;
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
}
