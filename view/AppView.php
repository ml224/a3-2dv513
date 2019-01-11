<?php
require_once("view/pages/CategoriesPage.php");
require_once("view/pages/OrdersPage.php");

class AppView{
    function __construct(DatabaseHandler $dbHandler){
        $this->dbHandler = $dbHandler;
    }

    public function renderApp() : void {
        $body = $this->getBody();
        echo $this->getContent($body);
    }

    private function getBody(){
        if($this->renderHomepage())
        {
            return $this->homepageHtml();
        }
        elseif($this->isActive('orders'))
        {
            $ordersPage = new OrdersPage();
            return $ordersPage->getPageContent();
        } 
        else
        {
            $categoriesPage = new CategoriesPage($this->dbHandler);
            return $categoriesPage->getPageContent();
        }
    }
    
    private function renderHomepage() : bool {
        return strlen($_SERVER['REQUEST_URI']) <= 1;
    }

    
    private function homepageHtml() : string {
        return 
        '<div class="homepage">
            <p>home page!</p>
        </div>';
    }

    private function getContent($body) : string {
        return '
        <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>web shop</title>
                <link rel="stylesheet" href="/public/css/style.css">
            </head>
            <body>
                <div id="container">
                    '.$this->mainMenu().'
                    '.$body.'
                </div>
                <script src="/public/js/script.js"></script>
            </body>
        </html>
        ';
    }

    private function mainMenu(){
        $listElements = "";
        if($this->isActive('orders')){
            $listElements = '
            <li class="menu-products"> <a href="products">products</a> </li>
            <li class="menu-orders"> <a href="orders" class="active-main-menu">oders</a> </li>
            ';
        }
        elseif($this->renderHomepage()){
            $listElements = '
            <li class="menu-products"> <a href="products">products</a> </li>
            <li class="menu-orders"> <a href="orders">orders</a> </li>
            ';
        }
        else
        {
            $listElements = '
            <li class="menu-products"> <a href="products" class="active-main-menu">products</a> </li>
            <li class="menu-orders"> <a href="orders">oders</a> </li>
            ';
        }

        return '
        <div class="main-menu">
            <ul>
                '.$listElements.' 
            </ul>
        </div>';
        
    }


    private function isActive($dir){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $currentDir = urldecode($trimmedUrl);

        return $currentDir === $dir;
    }

}