<?php
require_once("Seeder.php");
require_once("Database.php");
require_once("configDotEnv.php");

$database = new Database();
$database->createTables();
$mysqli = $database->getMysqli();

$seeder = new Seeder($mysqli);
$seeder->seedDb();

