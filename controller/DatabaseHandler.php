<?php
require_once("view/Queries.php");

class DatabaseHandler{
    private $database;
    private $queries;

    function __construct(Database $database){
        $this->queries = new Queries();
        $this->database = $database;

        $this->createTables();
    }

    private function createTables(){
        try{
            $queries = new Queries();

            $createProductsTable = $queries->createProductsTable();
            $createCategoriesTable = $queries->createCategoriesTable();
            $createOrdersTable = $queries->createOrdersTable();
            $createCustomersTable = $queries->createCustomersTable();

            $this->database->query($createCategoriesTable);
            $this->database->query($createProductsTable);
            $this->database->query($createCustomersTable);
            $this->database->query($createOrdersTable);
        }
        catch(Exception $e)
        {
            echo "something went wrong!... \n" . $e;
        }
    }

    public function fetchArray($query){
        $result = $this->database->fetchArray($query);
        if(empty($result)){
            throw new Exception('Category does not exist');
        } 

        return $result;
    }

    //TODO: restructure so that view sends in query as arg

    public function addCategory(array $cat) : void {
        $query = $this->queries->addCategory($cat);
        $this->database->query($query);
    } 

    public function addProduct(array $product) : void {
        $query = $this->queries->addProduct($product);
        $this->database->query($query);
    }

    public function addOrder(array $order) : void {
        $query = $this->queries->addOrder($order);
        $this->database->query($query);
    }

    
    public function addCustomer(array $customer) : void {
        $query = $this->queries->addCustomer($customer);
        $this->database->query($query);
    }

    public function getMainCategories(){
        $query = $this->queries->getMainCategories();
        $mainCategories = $this->database->fetchArray($query);
        return $mainCategories;
    }

    public function getProductsByCategoryName($cat){
        $query = $this->queries->getProductsByCategoryName($cat);
        $products = $this->database->fetchArray($query);
        return $products;

        //TODO: throw exception here and in db class if category name does not exist

    }

    public function getAllProducts(){
        $query = $this->queries->getAllProducts();
        $products = $this->database->fetchArray($query);
        return $products;
    }

    public function close(){
        $this->database->closeConnection();
    }

}