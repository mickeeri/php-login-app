<?php

session_start();

require_once("model/LoginModel.php");
require_once("view/LoginView.php");
require_once("view/LayoutView.php");

class LoginController {
	// PROPERTIES
	private $model;
	private $layoutView;
	public $errorMessage = "";

	// CONSTRUCTOR
	public function __construct() {
		$this->model = new LoginModel();
		$this->layoutView = new LayoutView();
		$this->loginView = new LoginView($this);
		$this->dateTimeView = new DateTimeView();

	}

	// FUNCTIONS

	public function isLoggedIn() {
		// Flytta till model.
		if(isset($_SESSION["Username"]))
			return true;
		return false;
	}

	public function renderPage(){
		// Flytta innehåll från response hit. 
		$this->layoutView->render($this->isLoggedIn(), $this->loginView, $this->dateTimeView);
	}
	
	public function login($userName, $password) {
		try {
			// Error handeling
			if($userName == "") {
				throw new Exception("Username is missing");
			} elseif ($password == "") {
				throw new Exception("Password is missing");
			} else {
				// Compares parameters with valid credentials in model.		
				if($userName == $this->model->getCorrectUserName() && $password == $this->model->getCorrectPassword()) {
					// Creates new session.
					$_SESSION["Username"] = $userName;
					// Reloads page.
					header('Location: '.$_SERVER['PHP_SELF']);	
					return true;
				}
				else {
					throw new Exception("Wrong name or password");
					return false;
				}
			}
		
			return false;

		} catch(Exception $e) {
			$this->errorMessage = $e->getMessage();
		}
	}

	public function logout() {
		// Removes session.
		unset($_SESSION["Username"]); // todo: tar bort strängberoende här.
		// Reloads page.
		header('Location: '.$_SERVER['PHP_SELF']);
	}
}