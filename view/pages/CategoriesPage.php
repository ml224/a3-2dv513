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
        $sortOptions = $this->sortingOptions();
        
        return '
        <div class="categories-page">
            '.$sortOptions.'
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
                        <a href="/'.$name.'" class="active"> '.$name.' </a>
                    </li>';
            }
            else
            {
                $cats .= 
                '<li id="'.$id.'">
                    <a href="/'.$name.'"> '.$name.' </a>
                </li>';
            }
            
        }

        $all = $this->isActive('alla-kategorier') ?
        '<li><a href="/alla-kategorier" class="active">alla kategorier</a></li>':
        '<li><a href="/alla-kategorier">alla kategorier</a></li>';


        return $cats . $all . '</ul>';

    }

    private function getProducts(){
        if($this->isActive('alla-kategorier'))
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
            $productId = $product["product_id"];
            $size = $product["size"];
            $price = $product["price"];
            $currency = $product["currency"];
            $stock = $product["stock"];
            $html .= 
            '<li class="product">
                <p class="title">'.$title.'</p>
                <p class="product-id">produkt id: '.$productId.'</p>
                <p class="product-id">strl: '.$size.'</p>
                <p class="stock">Lagerantal: '.$stock.'</p>
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

    private function sortingOptions(){
        $listElements = $this->getListElements();

        return '
        <div class="sort-by">
        <ul>
            '.$listElements.'
        <ul>
        </div>
        ';
    }

    private function getListElements(){
        $sortUrl = $this->getActiveDir() . '/?sort=';
        
        $sortPrice = $sortUrl . 'price';
        $sortStockAmount = $sortUrl . 'stock';
        $sortPopularity = $sortUrl . 'popular';

        if($this->sortBy()){
            if($this->sortByPrice()){
                return '
                <li><a href="/'.$sortPrice.'" class="active">pris</a></li>
                <li><a href="/'.$sortStockAmount.'">lagerantal</a></li>
                <li><a href="/'.$sortPopularity.'">popularitet</a></li>';
            }
            if($this->sortByStock()){
                return '
                <li><a href="/'.$sortPrice.'">pris</a></li>
                <li><a href="/'.$sortStockAmount.'" class="active">lagerantal</a></li>
                <li><a href="/'.$sortPopularity.'">popularitet</a></li>
                ';
            } 
            if($this->sortByPopularity()){
                return '
                <li><a href="/'.$sortPrice.'">pris</a></li>
                <li><a href="/'.$sortStockAmount.'">lagerantal</a></li>
                <li><a href="/'.$sortPopularity.'" class="active">popularitet</a></li>
                ';
            }
        }
          
        return '
        <li><a href="/'.$sortPrice.'">pris</a></li>
        <li><a href="/'.$sortStockAmount.'">lagerantal</a></li>
        <li><a href="/'.$sortPopularity.'">popularitet</a></li>
        ';
    }

    private function sortBy(){
        return isset($_GET['sort']);
    }

    private function sortByPrice(){
        return $_GET['sort'] === 'price';
    }

    private function sortByStock(){
        return $_GET['sort'] === 'stock';
    }

    private function sortByPopularity(){
        return $_GET['sort'] === 'popular';
    }

    private function sortRequested(){
        return isset($_GET);
    }

    //TODO: place in other class and access both from here and in appview
    private function isActive($dir){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $currentDir = urldecode($trimmedUrl);

        return $currentDir === $dir;
    }

    private function getActiveDir(){
        $trimmedUrl = ltrim($_SERVER['REQUEST_URI'], '/');
        $urlArray = explode("/?", $trimmedUrl);
        $currentDir = urldecode($urlArray[0]);

        return $currentDir;
    }

}