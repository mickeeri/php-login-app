<?php

namespace controller;

// Models
require_once("model/LoginModel.php");
require_once("model/UserFacade.php");
require_once("model/UserDAL.php");
require_once("model/UserModel.php");

// Views
require_once("view/UserView.php");
require_once("view/NavigationView.php");
require_once("view/LoginView.php");

// Controllers
require_once("controller/UserController.php");
require_once('controller/LoginController.php');

/**
* 
*/
class AppController {
	
	private $navigationView;

	function __construct() {
		$this->mysqli = new \mysqli("localhost", "root", "", "phpassignment");
		if(mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$this->userDAL = new \model\UserDAL($this->mysqli);
		$this->navigationView = new \view\NavigationView();
	}

	public function handleInput() {
		if ($this->navigationView->onLoginPage()) {
			$model = new \model\LoginModel();
			$view = new \view\LoginView($this->navigationView);
			$login = new \controller\LoginController($model, $view, $this->navigationView);

			// Handle input 
			$login->isLoggedIn();

			$this->view = $login->getView();
		} else {
			$model = new \model\UserFacade($this->userDAL);
			$this->view = new \view\UserView($model, $this->navigationView);
			$uc = new \controller\UserController($model, $this->view, $this->navigationView);

			$uc->addUser();
		}
		$this->mysqli->close();
	}

	public function generateOutput() {
		return $this->view;
	}
}