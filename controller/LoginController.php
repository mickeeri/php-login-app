<?php

require_once("model/LoginModel.php");
require_once("view/LoginView.php");

class LoginController {
	// private $view;
	private $model;
	private $isLoggedIn;

	public function __construct() {
		$this->model = new LoginModel();
		$this->isLoggedIn = false;
		// $this->loginView = new LoginView($this->model);
	}

	public function getIsLoggedIn() {
		return $this->isLoggedIn;
	}

	private function setIsLoggedIn($isLoggedIn) {
		$this->isLoggedIn = $isLoggedIn;
	}

	// public function doLogin() {
	// 	// Hämta utdata. Har användaren tryckt på knappen.
	// 	if($this->loginView->didUserPressLoginButton()) {
			
	// 	}
	// 	// Genererar uttdata. 
	// 	return $this->loginView->response($message);
	// }

	// If user name and password is correct return true.
	public function checkCredentials($userName, $password) {
		if($userName == $this->model->getCorrectUserName() && $password == $this->model->getCorrectPassword()) {
			// $this->model->isLoggedIn(true);
			$this->setIsLoggedIn(true);
			return true;
		} else {
			return false;
		}
	}
}