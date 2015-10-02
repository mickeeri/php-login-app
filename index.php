<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('model/LoginModel.php');
require_once('view/Messages.php');

// // MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
if (Settings::DISPLAY_ERRORS) {
	error_reporting(-1);
	ini_set('display_errors', 'ON');
}

// Start session before creating model
session_start();

// Creating objects of views and controller. 
$loginView = new LoginView();
$loginController = new loginController($loginView);
$dateTimeView = new DateTimeView();
$layoutView = new LayoutView();

// Returns true if user has session or is stored in file.
$isLoggedIn = $loginController->isLoggedIn();

// Render layout and login-form. 
$layoutView->render($isLoggedIn, $loginView, $dateTimeView);