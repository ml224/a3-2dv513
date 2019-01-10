<?php

class AppView{
    private $body;
    private $products;
    private $dbHandler;

    function __construct(DatabaseHandler $dbHandler){
        $this->dbHandler = $dbHandler;

        if(!$this->renderHomepage()){
            $products = $this->populateProducts();
            $this->body = $this->getProducts($products);
        }
    }

    
    private function getProducts($products){
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

    private function populateProducts(){
        try{
            return $this->dbHandler->getProductsByCategoryName($this->activeCategory());
        } catch(Exception $e){
            $body = $this->pageNotFound();
        }
    }

    private function pageNotFound(){
        return '
        <h1>404</h1>
        <p>Oops! The page was not found</p>';
    }

    public function renderApp() : void {
        if($this->renderHomepage()){
            $this->body = $this->homepageHtml();
        }

        echo $this->renderPage();
    }

    private function renderHomepage() : bool {
        return strlen($_SERVER['REQUEST_URI']) <= 1;
    }

    private function renderPage() : string {
        return '
        <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>web shop</title>
                <link rel="stylesheet" href="public/css/style.css">
            </head>
            <body>
                <div id="container">
                    '.$this->mainMenu().'
                    '.$this->categoryMenu().'
                    '.$this->body.'
                </div>
                <script src="public/js/script.js"></script>
            </body>
        </html>
        ';
    }

    private function mainMenu(){
        return '
        <div class="main-menu">
            <ul>
                <li class="products">products</li>
                <li class="orders">oders</li>
                <li class="sort">sort by</li>
            </ul>
        </div>';
    }

    private function categoryMenu() : string {
        $categories = $this->dbHandler->getMainCategories();
        $listCategories = $this->getCategoriesListed($categories);

        return '
            <div class="categories-menu">
                '.$listCategories.'
            </div>
        ';
    }

    private function getCategoriesListed($categories) : string{
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

        return $cats . '</ul>';

    }

    

    private function isActive($dir){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $currentDir = urldecode($trimmedUrl);

        return $currentDir === $dir;
    }


    private function activeCategory(){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $currentDir = urldecode($trimmedUrl);

        return $currentDir;
    }

    private function homepageHtml() : string {
        return 
        '<div class="homepage">
            <p>home page!</p>
        </div>';
    }


}