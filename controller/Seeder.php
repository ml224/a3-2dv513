<?php
require_once("DatabaseHandler.php");

class Seeder{
    private $dbHandler;

    function __construct(DatabaseHandler $dbHandler){
        $this->dbHandler = $dbHandler;
    }

    public function seedDb(){
        $path = getenv("DATA_PATH");
        $file = file_get_contents($path);
    
        $json = json_decode($file, true);
        $categories = $json["categories"];
        $products = $json["products"];
        $customers = $json["customers"];
        $orders = $json["orders"];
        
        $this->addCategories($categories);
        $this->addProducts($products);
        $this->addCustomers($customers);
        $this->addOrders($orders);

        $this->dbHandler->close();
     }

    private function addCategories($categories){
        foreach($categories as $cat){
            $this->dbHandler->addCategory($cat);
        }
    }

    private function addProducts($products){
        foreach($products as $product){
            $this->dbHandler->addProduct($product);
        }
    }

    private function addOrders($orders){
        foreach($orders as $order){
            $this->dbHandler->addOrder($order);
        }
    }

    private function addCustomers($customers){
        foreach($customers as $customer){
            $this->dbHandler->addCustomer($customer);
        }
    }

}


