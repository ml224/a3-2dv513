<?php
class Queries{
    public function createTables(){}
    public function createDatabase(){}

    public function addCategory(array $cat): string {
        $name = $cat["name"];
        $id = $cat["id"];
        $parent_id = $cat["parent_id"];

        return "INSERT IGNORE INTO category (name, id, parent_id) VALUES ('$name', '$id', '$parent_id')";
    }

    public function createProductTable(){
        return "CREATE TABLE IF NOT EXISTS product(name VARCHAR(50) NOT NULL, id INT PRIMARY KEY, discount INT, category_id INT NOT NULL, price INT, currency VARCHAR(50), size VARCHAR(50), product_id VARCHAR(50) NOT NULL)";

    }
    
    public function createCategoryTable(){
        return "CREATE TABLE IF NOT EXISTS category(name VARCHAR(50) NOT NULL, id INT PRIMARY KEY, parent_id INT)";
    }
}