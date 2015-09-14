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
				$this->loginView->destroyCookies();
				$this->loginModel->removeUserSession();
			}

			return true;

		} else {
			$userName = "";
			$password = "";

			// Check if credentials is stoored in cookies.
			if($this->loginView->getUserNameCookie() !== NULL && $this->loginView->getPasswordCookie() !== NULL) {
				$userName = $this->loginView->getUserNameCookie();
				$password = $this->loginView->getPasswordCookie();
				$this->loginModel->isLoggingInWithCookies = true;
				$this->login($userName, $password);
			} 
			// Login
			elseif($this->loginView->didUserPressLoginButton()) {				
				
				// Gets credentials from view.
				$userName = $this->loginView->getRequestUserName();
				$password = $this->loginView->getRequestPassword();

				if($this->loginView->isKeepMeLoggedInChecked()) {
					$this->loginView->setCookies($userName, $password);
					header('Location: '.$_SERVER['REQUEST_URI']);
				}

				$this->loginModel->isLoggingInWithCookies = false;
				$this->login($userName, $password);
			}

			return false;
		}
	}

	public function login($userName, $password) {
		// Calls method authorize in model. 
		$isValid = $this->loginModel->authorize($userName, $password);

		// Correct username and password.
		if($isValid) {					
			$this->loginModel->createUserSession($userName);
		}
	}
}