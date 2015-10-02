<?php

namespace controller;

class LoginController {
	private $loginModel;
	private $layoutView;

	public function __construct(LoginView $loginView) {				
		$this->loginView = $loginView;
		$this->loginModel = new LoginModel($this->loginView);		
	}

	
	/**
	 * Performs diffrent types of sign in/sign out based on user input. Returns true if user is logged in.
	 * @return boolean
	 */
	public function isLoggedIn(){
		$loginView = $this->loginView;
		$loginModel = $this->loginModel;
		// Get information on users client from view.
		$client = $loginView->getClientIdentifier();

		// Session is set and user is logged in. 
		if($this->loginModel->sessionIsSet($client)) {								

			// User wants to log out.
			if($this->loginView->didUserPressLogoutButton()) {
				return $this->logout(MessageView::$regularLogout);
			}

			return true;
		}		
		// If session is missing but user is remembered in persistent storage.
		elseif($loginModel->hasBeenRemembered($loginView->getUserNameCookie(), $loginView->getPasswordCookie())) {
			
			// Creates new user session.
			$this->loginModel->createUserSession($this->loginView->getUserNameCookie(), $client);
			$loginView->reloadPage(MessageView::$welcomeBack);

			//return true;
		}
		// Cookies exists but don't pass the hasBeenRemembered method and therefore are manipulated.
		elseif ($loginView->getUserNameCookie() !== null && $loginView->getPasswordCookie() !== null) {
			
			$this->logout(MessageView::$manipulatedCookie);			
		}
		// User is not logged in and not remembered in persistent storage.
		else {
			// User makes login attempt.
			if($this->loginView->didUserPressLoginButton()) {				
				
				// Gets input from view.
				$userName = $this->loginView->getRequestUserName();
				$password = $this->loginView->getRequestPassword();

				// If user has checked "Keep me logged in"-checkbox.
				if($this->loginView->isKeepMeLoggedInChecked()) {
					
					// If credentials are correct create cookies and remeber user in persistent storage.
					if ($loginModel->authorize($userName, $password)) {
						$cookieExpirationTime = time()+60;
						$randomStringPassword = $loginModel->getRandomStringPassword();						
						$this->loginView->setCookies($userName, $randomStringPassword, $cookieExpirationTime);
						$loginModel->rememberUser($userName, $randomStringPassword, $cookieExpirationTime);
						return $this->login($userName, $password, $client, MessageView::$loginCookie);
					}									
				} 
				// Regular login without "Keep me logged in" checked.
				else {
					return $this->login($userName, $password, $client, MessageView::$regularLogin);
				}
			}
			return false;
		}
	}

	/**
	 * Performs login attempt. Returns true if successful.
	 * @param  string $userName
	 * @param  string $password 
	 * @param  string $client, Users browser.
	 * @param  string $message, Message that is to be displayed after sign in.
	 * @return boolean, Returns true if logged in.
	 */
	public function login($userName, $password, $client, $message) {
		// Calls method authorize in model.
		if($this->loginModel->authorize($userName, $password)) {					
			$this->loginModel->createUserSession($userName, $client);
			$this->loginView->reloadPage($message);

			return true;
		}

		return false;
	}

	/**
	 * Preforms logout. Removes cookies, sessions and user in persistent storage. Returns false.
	 * @param  string $message, Message that is to be displayed after sign out.
	 * @return boolean
	 */
	private function logout($message) {
		$this->loginModel->forgetUser();
		$this->loginModel->removeUserSession();
		$this->loginView->destroyCookies();
		$this->loginView->reloadPage($message);

		return false;
	}
}