<?php

//INCLUDE THE FILES NEEDED...
require_once('model/LoginModel.php');
require_once('view/Messages.php');
require_once("view/AppView.php");
require_once('view/LayoutView.php');
require_once('view/DateTimeView.php');
require_once('controller/AppController.php');

// // MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
if (false) {
	error_reporting(-1);
	ini_set('display_errors', 'ON');
}

// Start session before creating model
session_start();

$av = new \view\AppView();
$ac = new \controller\AppController($av);

$ac->handleInput();
$view = $ac->generateOutput();

$dtv = new \view\DateTimeView();
$lv = new \view\LayoutView();

// Render layout and login-form. 
$lv->render($ac->isLoggedIn(), $view, $dtv, $av);

//TODO
//- Ändra namn på UserController?
//- Lägg info om databasinställningar på annat ställe.
//- Ta bort länkar.
