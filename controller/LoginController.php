<?php

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

		// If session is set return true;
		if($this->loginModel->sessionIsSet()) {								

			// Logout
			if($this->loginView->didUserPressLogoutButton()) {
				$this->loginView->destroyCookies();
				$this->loginModel->removeUserSession();
			}

			return true;
		}
		elseif ($this->loginView->getUserNameCookie() !== NULL) {
			$this->loginModel->rememberMe = true;
			$userName = $this->loginView->getUserNameCookie();
			$password = $this->loginView->getPasswordCookie();
			if ($this->loginModel->authorize($userName, $password)) {
				$this->loginModel->createUserSession($userName, $password);
			} else {
				$this->loginView->destroyCookies();
				return false;
			}			
		}

		else {
			// $userName = "";
			// $password = "";

			// // Check if credentials is stoored in cookies.
			// if($this->loginView->getUserNameCookie() !== NULL && $this->loginView->getPasswordCookie() !== NULL) {
			// 	$userName = $this->loginView->getUserNameCookie();
			// 	$password = $this->loginView->getPasswordCookie();
			// 	$this->loginModel->rememberMe = true;
			// 	$this->loginModel->createUserSession($userName, $password);
			// 	// $this->login($userName, $password);
			// } 
			// Login
			if($this->loginView->didUserPressLoginButton()) {				
				
				// Gets credentials from view.
				$userName = $this->loginView->getRequestUserName();
				$password = $this->loginView->getRequestPassword();

				if($this->loginView->isKeepMeLoggedInChecked()) {
					$this->loginView->setCookies($userName, $password);
					header('Location: '.$_SERVER['REQUEST_URI']);
					// $this->loginModel->rememberMe = true;
					// $this->login($this->loginView->getUserNameCookie(), $this->loginView->getPasswordCookie());
				} else {
					$this->loginModel->rememberMe = false;
					$this->login($userName, $password);
				}
			}
			return false;
		}
	}

	public function login($userName, $password) {
		// Calls method authorize in model. 
		if($this->loginModel->authorize($userName, $password)) {					
			$this->loginModel->createUserSession($userName);
		}
	}
}