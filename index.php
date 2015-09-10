<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('view/CookieStorage.php');
// require_once('model/LoginModel.php');

// MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Create objects of controller
// 
$loginView = new LoginView();
$loginModel = new loginModel($loginView);
$loginController = new loginController($loginModel, $loginView);

//CREATE OBJECTS OF THE VIEWS
$dateTimeView = new DateTimeView();
$layoutView = new LayoutView();



$isLoggedIn = $loginController->isLoggedIn();


// Passing loginView as parameter to method isLoggedIn in loginController.
$layoutView->render($isLoggedIn, $loginView, $dateTimeView);