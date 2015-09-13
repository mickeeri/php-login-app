<?php

// require_once("model/LoginModel.php");
// require_once("view/LoginView.php");
// require_once('view/CookieStorage.php');

class LoginController {
	// PROPERTIES
	private $loginModel;
	private $layoutView;
	// public static $message = 'LoginController::Message';

	// CONSTRUCTOR
	public function __construct(LoginView $loginView) {				
		$this->loginView = $loginView;
		$this->loginModel = new LoginModel($this->loginView);		
	}

	// FUNCTIONS
	public function isLoggedIn(){

		$this->loginModel->displayLoginLogoutMessages();

		if($this->loginModel->sessionIsSet()) {								

			// Logout
			if($this->loginView->didUserPressLogoutButton()) {
				$this->loginModel->removeUserSession();
			}

			return true;

		} else {
			// Login
			if($this->loginView->didUserPressLoginButton()) {				
				
				// Gets credentials from view.
				$userName = $this->loginView->getRequestUserName();
				$password = $this->loginView->getRequestPassword();

				// Calls method authorize in model. 
				$isValid = $this->loginModel->authorize($userName, $password);

				// Correct username and password.
				if($isValid) {					
					$this->loginModel->createUserSession($userName);
				}
			}

			return false;
		}
	}
}