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
        $customers = $json["customers"];
        $orders = $json["orders"];
        
        $this->addCategories($categories);
        $this->addProducts($products);
        $this->addCustomers($customers);
        $this->addOrders($orders);

        $this->mysqli->close();
     }

    private function addCategories($categories){
        foreach($categories as $cat){
            $query = $this->queries->addCategory($cat);
            $this->mysqli->query($query);
        }
    }

    private function addProducts($products){
        foreach($products as $product){
            $query = $this->queries->addProduct($product);
            $this->mysqli->query($query);
        }
    }

    private function addOrders($orders){
        foreach($orders as $order){
            $query = $this->queries->addOrder($order);
            $this->mysqli->query($query);
        }
    }

    private function addCustomers($customers){
        foreach($customers as $customer){
            $query = $this->queries->addCustomer($customer);
            $this->mysqli->query($query);
        }
    }

}


