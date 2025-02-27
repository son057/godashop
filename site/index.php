<?php
session_start();

$c = $_GET['c'] ?? 'home'; //home (mặc định)
$a = $_GET['a'] ?? 'index'; //index (mặc định)


// import config & database
require '../config.php';
require '../connectDB.php';
// import model
require '../bootstrap.php';

//Load Composer's autoloader
require '../vendor/autoload.php';

$strController = ucfirst($c)."Controller"; //HomeController

// import controller
// require controller/HomeController.php
require "controller/$strController.php";

// $controler = new HomeController();
$controller = new $strController();


// $controller->create();
$controller->$a();
 ?>