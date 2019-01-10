<?php
require_once("view/Queries.php");

class CategoriesPage{
    private $queries;
    private $dbHandler;
    
    function __construct(DatabaseHandler $dbHandler){
        $this->queries = new Queries();
        $this->dbHandler = $dbHandler;
    }

    public function getPageContent(){
        $catMenu = $this->categoryMenu();
        $products = $this->getProducts();
        
        return '
        <div class="categories-page">
            '.$catMenu.'
            '.$products.'
        </div>';

    }


    private function categoryMenu() : string {
        $query = $this->queries->getMainCategories();
        $categories = $this->dbHandler->fetchArray($query);
        $listCategories = $this->listCategories($categories);

        return '
            <div class="categories-menu">
                '.$listCategories.'
            </div>
        ';
    }

    private function listCategories($categories) : string{
        $cats = '<ul>';
        foreach($categories as $cat){
            $name = $cat["name"];
            $id = $cat["category_id"];    
        
            if($this->isActive($name)){
                $cats .= 
                    '<li id="'.$id.'">
                        <a href="'.$name.'" class="active"> '.$name.' </a>
                    </li>';
            }
            else
            {
                $cats .= 
                '<li id="'.$id.'">
                    <a href="'.$name.'"> '.$name.' </a>
                </li>';
            }
            
        }

        $all = $this->isActive('all') ?
        '<li><a href="all" class="active">all</a></li>':
        '<li><a href="all">all</a></li>';


        return $cats . $all . '</ul>';

    }

    private function getProducts(){
        if($this->activeCategory() === 'all')
        {
            $query = $this->queries->getAllProducts();
            $products = $this->dbHandler->fetchArray($query);
            return $this->listProducts($products);
        } 
        else
        {
            return $this->getProductsByCategory();
        }
    }

    private function activeCategory(){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $currentDir = urldecode($trimmedUrl);

        return $currentDir;
    }

    private function listProducts($products){
        $html = '<div class="products"><ul>';
        foreach($products as $product){
            $title = $product["product_name"];
            $price = $product["price"];
            $currency = $product["currency"];
            $stock = $product["stock"];
            $html .= 
            '<li class="product">
                <p class="title">'.$title.'</p>
                <p class="stock">Amount in stock: '.$stock.'</p>
                <p class="price">'.$price.' '.$currency.'</p>
            </li>';
        }
        return $html . '</ul></div>';
    }



    private function getProductsByCategory(){
        try
        {
            $cat = $this->activeCategory();
            $query = $this->queries->getProductsByCategoryName($cat);
            $products = $this->dbHandler->fetchArray($query);
            return $this->listProducts($products);
        } 
        catch(Exception $e)
        {
            return $this->pageNotFound();
        }
    }

    
    private function pageNotFound(){
        return '
        <h1>404</h1>
        <p>Oops! The page was not found</p>';
    }

    //TODO: place in other class and access both from here and in appview
    private function isActive($dir){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $currentDir = urldecode($trimmedUrl);

        return $currentDir === $dir;
    }

}