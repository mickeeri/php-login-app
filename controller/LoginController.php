<?php

session_start();

require_once("model/LoginModel.php");
require_once("view/LoginView.php");
require_once("view/LayoutView.php");

class LoginController {
	// PROPERTIES
	private $model;
	private $layoutView;

	// CONSTRUCTOR
	public function __construct() {
		$this->model = new LoginModel();
		$this->layoutView = new LayoutView();
		$this->loginView = new LoginView($this);
		$this->dateTimeView = new DateTimeView();

	}

	// FUNCTIONS

	public function isLoggedIn() {
		if(isset($_SESSION["Username"]))
			return true;
		return false;
	}

	public function renderPage(){
		$this->layoutView->render($this->isLoggedIn(), $this->loginView, $this->dateTimeView);
	}
	
	public function login($userName, $password) {
		// Compares parameters with valid credentials in model.		
		if($userName == $this->model->getCorrectUserName() && $password == $this->model->getCorrectPassword()) {
			// Creates new session.
			$_SESSION["Username"] = $userName;
			// Reloads page.
			header('Location: '.$_SERVER['PHP_SELF']);		
			return true;
		}
		else {
			return false;
		}
	}

	public function logout() {
		// Removes session.
		unset($_SESSION["Username"]); // todo: tar bort strängberoende här.
		// Reloads page.
		header('Location: '.$_SERVER['PHP_SELF']);
	}
}