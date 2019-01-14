<?php
require_once("view/queries/Queries.php");

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
    }

  

    public function query(string $query){
        if(!$this->mysqli->query($query))
        {
            throw new Exception($this->mysqli->error);
        }
    }

    public function fetchArray($query){
        try{
            $categories = array();
            $result = $this->mysqli->query($query) or print($this->mysqli->error);

            while($cat = $result->fetch_array(MYSQLI_ASSOC)) {
                array_push($categories, $cat);
            }

            $result->free();
            return $categories;
            
        } catch(Exception $e){
            echo "uh oh! Something went wrong! \n" . $e;
        }
        
    }

    public function closeConnection(){
        return $this->mysqli;
    }
}