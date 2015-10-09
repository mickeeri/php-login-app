<?php

namespace controller;

class LoginController {

	private $loginModel;
	private $loginView;
	private $appView;


	public function __construct(\model\LoginModel $loginModel, \view\LoginView $loginView, \view\AppView $appView) {				
		$this->loginModel = $loginModel;
		$this->loginView = $loginView;
		$this->appView = $appView;
	}
	
	/**
	 * Performs different types of sign in/sign out based on user input.
	 */
	public function doLogin(){
		$loginView = $this->loginView;
		$loginModel = $this->loginModel;
		
		// Get information on users client from view.
		$client = $this->appView->getClientIdentifier();

		// Session is set and user is logged in. 
		if($loginModel->sessionIsSet($client)) {								

			// User wants to log out.
			if($loginView->didUserPressLogoutButton()) {
				$this->logout(\view\MessageView::$regularLogout);
			}
		}	
		// If session is missing but user is remembered in persistent storage.
		elseif($loginModel->hasBeenRemembered($loginView->getUserNameCookie(), $loginView->getPasswordCookie())) {
			
			// Creates new user session.
			$this->loginModel->createUserSession($loginView->getUserNameCookie(), $client);
			$this->appView->redirect(\view\MessageView::$welcomeBack);

		}
		// Cookies exists but don't pass the hasBeenRemembered method and therefore is manipulated.
		elseif ($loginView->getUserNameCookie() !== null && $loginView->getPasswordCookie() !== null) {
			
			$this->logout(\view\MessageView::$manipulatedCookie);			
		}
		// User is not logged in and not remembered in persistent storage.
		else {
			// User makes login attempt.
			if($this->loginView->didUserPressLoginButton()) {				
				
				// Gets input from view.
				$userName = $loginView->getRequestUserName();
				$password = $loginView->getRequestPassword();

				// If user has checked "Keep me logged in"-checkbox.
				if($this->loginView->isKeepMeLoggedInChecked()) {
					
					// If credentials are correct create cookies and remeber user in persistent storage.
					if ($this->checkCredentials($userName, $password)) {
						$cookieExpirationTime = time()+60;
						$randomStringPassword = $loginModel->getRandomStringPassword();						
						$this->loginView->setCookies($userName, $randomStringPassword, $cookieExpirationTime);
						$loginModel->rememberUser($userName, $randomStringPassword, $cookieExpirationTime);
						$this->login($userName, $password, $client, \view\MessageView::$loginCookie);
					}									
				} 
				// Regular login without "Keep me logged in" checked.
				else {
					$this->login($userName, $password, $client, \view\MessageView::$regularLogin);
				}
			}
		}
	}

	/**
	 * Checks if entered username and password is valid and credentials are correct   
	 */
	public function checkCredentials($userName, $password) {
		try {
			// Calls method authorize in model.
			if ($this->loginModel->authorize($userName, $password)) {
				return true;
			} else {
				return false;
			}				
		} catch (\model\MissingUserNameException $e) {
			$this->loginView->setMessage(\view\MessageView::$userNameMissing);
		} catch (\model\MissingPasswordException $e) {
			$this->loginView->setMessage(\view\MessageView::$passwordMissing);
		} catch (\model\UserDontExistException $e) {
			$this->appView->redirect(\view\MessageView::$wrongCredentials);
		} catch (\model\WrongCredentialsException $e) {
			$this->appView->redirect(\view\MessageView::$wrongCredentials);
		} 
	}

	/**
	 * Performs login attempt. Returns true if successful.
	 * @param  string $client Users browser
	 * @param  string $message Message that is to be displayed after sign in
	 * @return redirects or boolean false
	 */
	private function login($userName, $password, $client, $message) {

		if($this->checkCredentials($userName, $password)) {					
			$this->loginModel->createUserSession($userName, $client);
			$this->appView->redirect($message);
		}
		return false;
	}

	/**
	 * Preforms logout. Removes cookies, sessions and user in persistent storage, then redirects.
	 * @param  string $message Message that is to be displayed after sign out.
	 */
	private function logout($message) {
		$this->loginModel->forgetUser();
		$this->loginModel->removeUserSession();
		$this->loginView->destroyCookies();
		$this->appView->redirect($message);
	}

	/**
	 * Call from AppController
	 * @return LoginView returns this view
	 */
	public function getView() {
		return $this->loginView;
	}
}