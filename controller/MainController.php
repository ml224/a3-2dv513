<?php

require_once("controller/Seeder.php");
require_once("controller/DatabaseHandler.php");
require_once("model/Database.php");
require_once("view/AppView.php");

class MainController{
    private $view;

    function __construct(){
        $database = new Database();
        $dbHandler = new DatabaseHandler($database);
        $seeder = new Seeder($dbHandler);
        $this->view = new AppView($dbHandler);
        
        $seeder->seedDb();
    }

    public function runApplication(){
        $this->view->renderApp();
    }

}