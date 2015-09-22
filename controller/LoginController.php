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

		$loginView = $this->loginView;
		$loginModel = $this->loginModel;

		$client = $loginView->getClientIdentifier();

		if($this->loginModel->sessionIsSet($client)) {								

			// Logout
			if($this->loginView->didUserPressLogoutButton()) {
				return $this->logout("logout");
			}

			return true;
		}

		//$this->loginModel->displayLoginLogoutMessages();

		elseif($loginModel->hasBeenRemembered($loginView->getUserNameCookie(), $loginView->getPasswordCookie())) {
			
			

			//$this->loginView->setCookieMessage("Welcome back with cookie");
			$this->loginModel->createUserSession($this->loginView->getUserNameCookie(), $client);
			$loginView->reloadPage("welcome-back-login");
			
			// if($this->loginModel->sessionIsSet() === false) {
			// 	// print("Hej!");
			// 	// exit();
			// 	$this->loginView->setCookieMessage("Welcome back with cookie");
			// 	$this->loginModel->createUserSession($this->loginView->getUserNameCookie());
			// }

			//$this->loginView->getPasswordCookie();
			
			// // Duplication of code. 
			// if($this->loginView->didUserPressLogoutButton()) {
			// 	//$this->logout();
			// 	// $this->loginModel->forgetUser();
			// 	// $this->loginView->destroyCookies();
			// 	// $this->loginModel->removeUserSession();
			// }

			return true;
		}
		// Cookies exists but don't pass the hasBeenRemembered method.
		elseif ($loginView->getUserNameCookie() !== NULL) {
			
			$this->logout("manipulated-cookie");

			//return false;

			// $userName = $this->loginView->getUserNameCookie();
			// $password = $this->loginView->getPasswordCookie();
			
			// if ($this->loginModel->authorize($userName, $password)) {
				
			// 	$this->loginModel->rememberUser($userName, $loginView->getPasswordCookie);
				
			// 	$this->login($userName, $password, "login-cookie");
			// } else {
			// 	$this->loginView->destroyCookies();
			// 	return false;
			// }			
		}

		else {
			// User makes login attempt.
			if($this->loginView->didUserPressLoginButton()) {				
				
				// Gets credentials from view.
				$userName = $this->loginView->getRequestUserName();
				$password = $this->loginView->getRequestPassword();

				if($this->loginView->isKeepMeLoggedInChecked()) {
					// Sets cookies and then reloads page.
					
					//$this->loginView->setCookies($userName, $password);
					
					if ($loginModel->authorize($userName, $password)) {
						$cookieExpirationTime = time()+20;
						$randomStringPassword = sha1(rand());
						$this->loginView->setCookies($userName, $randomStringPassword, $cookieExpirationTime);
						$loginModel->rememberUser($userName, $randomStringPassword, $cookieExpirationTime);
						return $this->login($userName, $password, $client, "login-cookie");
					}
					//$this->loginView->reloadPage();											
				} else {
					return $this->login($userName, $password, $client, "regular-login");
				}
			}
			return false;
		}
	}

	public function login($userName, $password, $client, $messageType) {
		// Calls method authorize in model. 
		if($this->loginModel->authorize($userName, $password)) {					
			$this->loginModel->createUserSession($userName, $client);
			$this->loginView->reloadPage($messageType);

			return true;
		}

		return false;
	}

	private function logout($message) {
		$this->loginModel->forgetUser($this->loginView->getUserNameCookie(), $this->loginView->getPasswordCookie());
		$this->loginModel->removeUserSession();
		$this->loginView->destroyCookies();
		$this->loginView->reloadPage($message);

		return false;
	}
}