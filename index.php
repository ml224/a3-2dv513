<?php
require_once("controller/Seeder.php");
require_once("controller/DatabaseHandler.php");
require_once("model/Database.php");
require_once("configDotEnv.php");

$database = new Database();
$dbHandler = new DatabaseHandler($database);
$seeder = new Seeder($dbHandler);

$seeder->seedDb();



