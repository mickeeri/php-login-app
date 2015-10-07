<?php

//INCLUDE THE FILES NEEDED...
// require_once('view/LoginView.php');
// require_once('view/DateTimeView.php');
// require_once('view/LayoutView.php');
// require_once('controller/LoginController.php');
require_once('model/LoginModel.php');
// require_once('view/Messages.php');
// 


require_once('view/Messages.php');
require_once("view/AppView.php");
require_once('view/LayoutView.php');
require_once('view/DateTimeView.php');

require_once('controller/AppController.php');

// // MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
if (true) {
	error_reporting(-1);
	ini_set('display_errors', 'ON');
}

// Start session before creating model
session_start();

// Creating objects of views and controller. 
// $loginView = new LoginView();
// $loginController = new loginController($loginView);
// $dateTimeView = new DateTimeView();
// $layoutView = new LayoutView();

// $m = new \model\LoginModel();
// $v = new \view\LoginView($m);
// $c = new \controller\LoginController($m, $v);
// 
//$uf = new \model\userFacade(); 
//$lm = new \model\LoginModel();
$av = new \view\AppView();
$ac = new \controller\AppController($av);
$ac->handleInput();

$view = $ac->generateOutput();

$dtv = new \view\DateTimeView();
$lv = new \view\LayoutView();


// Returns true if user has session or is stored in file.
//$isLoggedIn = $c->isLoggedIn();
//var_dump($lm->sessionIsSet($nv->getClientIdentifier()));

// Render layout and login-form. 
$lv->render($ac->isLoggedIn(), $view, $dtv, $av);


//$lv->render($m->isLoggedIn($v->getUserClient()), $v, $dtv);