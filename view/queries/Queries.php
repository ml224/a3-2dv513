<?php
class Queries{
    public function createDatabase(){}

    public function createProductsTable(){
        return "CREATE TABLE IF NOT EXISTS products(sku VARCHAR(256) PRIMARY KEY, name VARCHAR(256) NOT NULL, discount_percentage INT, category_id INT NOT NULL, price INT, currency VARCHAR(50), size VARCHAR(50), product_id VARCHAR(256) NOT NULL, stock INT NOT NULL)";

    }
    
    public function createCategoriesTable(){
        return "CREATE TABLE IF NOT EXISTS categories(name VARCHAR(256) NOT NULL, category_id INT PRIMARY KEY, parent_id INT)";
    }

    public function createOrdersTable(){
        return "CREATE TABLE IF NOT EXISTS orders(id INT PRIMARY KEY, sku VARCHAR(256) NOT NULL, quantity INT NOT NULL, customer_id VARCHAR(256) NOT NULL, date DATE NOT NULL)";
    }
    
    public function createCustomersTable(){
        return "CREATE TABLE IF NOT EXISTS customers(customer_id INT PRIMARY KEY, firstname VARCHAR(256) NOT NULL, lastname VARCHAR(256) NOT NULL, address VARCHAR(556) NOT NULL, city VARCHAR(256) NOT NULL, country VARCHAR(256) NOT NULL, zip VARCHAR(50) NOT NULL, phone_number INT, email VARCHAR(256) NOT NULL)";
    }

    
    public function addCategory(array $cat): string {
        $name = $cat["name"];
        $id = $cat["id"];
        $parent_id = $cat["parent_id"];

        return "INSERT IGNORE INTO categories (name, category_id, parent_id) VALUES ('$name', '$id', '$parent_id')";
    }
    
    public function addProduct(array $product): string {
        $sku = $product["sku"];
        $name = $product["name"];
        $category_id = $product["category_id"];
        $price = $product["price"];
        $currency = $product["currency"];
        $size = $product["size"];
        $product_id = $product["product_id"];
        $stock = $product["stock"];
        $discount = $product["discount_percentage"];


        return "INSERT IGNORE INTO products (sku, name, category_id, price, currency, size, product_id, stock, discount_percentage) VALUES ('$sku', '$name', '$category_id', '$price', '$currency', '$size', '$product_id', '$stock', '$discount')";
    }

    public function addOrder(array $order){
        $id = $order["id"];
        $sku = $order["sku"];
        $quantity = $order["quantity"];
        $customer_id = $order["customer_id"];
        $date = $order["date"];

        return "INSERT IGNORE INTO orders (id, sku, quantity, customer_id, date) VALUES ('$id', '$sku', '$quantity', '$customer_id', '$date')";        
    }

    public function addCustomer(array $customer){
        $customer_id = $customer["customer_id"];
        $firstname = $customer["firstname"];
        $lastname = $customer["lastname"];
        $address = $customer["address"];
        $city = $customer["city"];
        $country = $customer["country"];
        $zip = $customer["zip"];
        $email = $customer["email"];

        

        return "INSERT IGNORE INTO customers (customer_id, firstname, lastname, address, city, country, zip, email) VALUES ('$customer_id', '$firstname', '$lastname', '$address', '$city', '$country', '$zip', '$email')";        
    }

    public function getMainCategories(){
        return "SELECT name, category_id FROM categories WHERE parent_id = 0";    
    }

    public function getProducts($catName){
        $productsQuery = $this->productsQuery($catName); 
        $orderSumQuery = $this->orderSumQuery();

        $joinedTablesQuery = 
        "SELECT product_name, category_name, category_id, price, p.sku, currency, size, stock, product_id, IFNULL(quantity_sold, 0) AS quantity_sold 
        FROM ($productsQuery) AS p 
        LEFT JOIN  ($orderSumQuery) AS o
        ON p.sku = o.sku";

        return $joinedTablesQuery;
    }

    private function productsQuery($catName){
        $query = "
            SELECT p.name as product_name, c.name as category_name, p.category_id, price, sku, currency, size, stock, product_id 
            FROM products p 
            JOIN categories c on c.category_id = p.category_id
        ";

        if($catName !== 'alla-kategorier'){  
            $query .= " WHERE c.name = '$catName'"; 
        }

        return $query;   
    }

    
    private function orderSumQuery(){
        return "SELECT SUM(quantity) AS quantity_sold, sku FROM orders GROUP BY sku";
    }


    public function getProductsByPrice($catName){
        return 
        $this->getProducts($catName) . 
        " ORDER BY price";
    }

    public function getProductsByStock($catName){
        return 
        $this->getProducts($catName) . 
        " ORDER BY stock";
    }

    public function getProductsByPopularity($catName){
        return $this->getProducts($catName) . " ORDER BY quantity_sold DESC";
    }

    public function getOrderDetails(){
        $q = "
        SELECT date, customer_id, firstname, lastname, address, city, zip, country, email  
        FROM customers
        NATURAL JOIN orders  
        GROUP BY date, customer_id";
        return $q;
    }


    public function getOrderProducts($customerId, $date){
        $ordersAndProducts = $this->ordersAndProductsCombined();
        return 
        "
        SELECT * FROM ($ordersAndProducts) p
        WHERE date = '$date' AND customer_id = $customerId
        ORDER BY date DESC
        ";
    }

    
    private function ordersAndProductsCombined(){
        return "SELECT name, size, price, currency, product_id, customer_id, DATE(date) as date, p.sku, quantity FROM products p NATURAL JOIN orders o";
    }

}