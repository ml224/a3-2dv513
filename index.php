<?php
require_once("configDotEnv.php");
require_once("controller/MainController.php");

$controller = new MainController();
$controller->runApplication();




