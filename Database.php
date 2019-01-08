<?php
require_once("queries.php");

class Database{
    private $mysqli;

    private $user;
    private $host;
    private $db;
    private $pw;

    function __construct(){
        $this->user = getenv("DB_USER"); 
        $this->host = getenv("DB_HOST");
        $this->db = getenv("DB");
        $this->pw = getenv("DB_PW");

        try{
            $this->connect();
            $this->createDatabase();
            $this->connectWithDb();
        }
        catch(Exception $e){
            echo "failed to connect to database!\n" . $e; 
        }
    }

    public function connect(){
        $this->mysqli = mysqli_connect($this->host, $this->user, $this->pw);
    
        if($this->mysqli->connect_error)
        {
            throw new Exception($this->mysqli->error);
        } 
        else
        {
            echo "connected to MySQL without db!";
        }
    }

    private function createDatabase(){
        $query = "CREATE DATABASE IF NOT EXISTS $this->db";
        if(!$this->mysqli->query($query))
        {
            throw new Exception($this->mysqli->error);
        }
    }

    
    private function connectWithDb(){
        $this->mysqli = mysqli_connect($this->host, $this->user, $this->pw, $this->db);
    
        if($this->mysqli->connect_error)
        {
            throw new Exception($this->mysqli->error);
        } 
        else
        {
            echo "connected to MySQL WITH db!";
        }
    }

    public function createTables(){
        try{
            $queries = new Queries();
            $product = $queries->createProductTable();
            $category = $queries->createCategoryTable();
            
            $this->buildTable($category);
            $this->buildTable($product);
        }
        catch(Exception $e)
        {
            echo "something went wrong!... \n" . $e;
        }
        
    }

    private function buildTable(string $query){
        if(!$this->mysqli->query($query))
        {
            throw new Exception($this->mysqli->error);
        }
    }

    public function getMysqli(){
        return $this->mysqli;
    }

    public function closeConnection(){
        $this->mysqli->close();
    }
}