<?php

namespace controller;

// require_once("model/LoginModel.php");
// require_once("view/LoginView.php");
//require_once("view/NavigationView.php");

class LoginController {
	private $loginModel;
	private $loginView;
	private $appView;

	/**
	 * Created in AppController.
	 * @param \model\LoginModel $loginModel 
	 * @param \view\LoginView   $loginView  
	 * @param \view\AppView     $appView   
	 */
	public function __construct(\model\LoginModel $loginModel, \view\LoginView $loginView, \view\AppView $appView) {				
		$this->loginModel = $loginModel;
		$this->loginView = $loginView;
		$this->appView = $appView;
		//$this->navigationView = $navigationView;
	}

	
	/**
	 * Performs diffrent types of sign in/sign out based on user input. Returns true if user is logged in.
	 * @return boolean
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

			//self::$isLoggedIn = true;
		}		
		// If session is missing but user is remembered in persistent storage.
		elseif($loginModel->hasBeenRemembered($loginView->getUserNameCookie(), $loginView->getPasswordCookie())) {
			
			// Creates new user session.
			$this->loginModel->createUserSession($loginView->getUserNameCookie(), $client);
			//$loginView->reloadPage(\view\MessageView::$welcomeBack);
			$this->appView->redirect(\view\MessageView::$welcomeBack);

			//self::$isLoggedIn = true;
		}
		// Cookies exists but don't pass the hasBeenRemembered method and therefore are manipulated.
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
	 * @param  string $userName 
	 * @param  string $password
	 * @return boolean      
	 */
	public function checkCredentials($userName, $password) {
		try {
			// Calls method authorize in model.
			if ($this->loginModel->authorize($userName, $password)) {
				return true;
			} else {
				return false;
			}				
		} catch (\model\UserDontExistException $e) {
			$this->appView->redirect(\view\MessageView::$userDontExist);
		} catch (\model\WrongCredentialsException $e) {
			$this->appView->redirect(\view\MessageView::$wrongCredentials);
		}	
	}

	/**
	 * Performs login attempt. Returns true if successful.
	 * @param  string $userName
	 * @param  string $password 
	 * @param  string $client Users browser.
	 * @param  string $message Message that is to be displayed after sign in.
	 * @return boolean redirect | false
	 */
	private function login($userName, $password, $client, $message) {

		if($this->checkCredentials($userName, $password)) {					
			$this->loginModel->createUserSession($userName, $client);
			$this->appView->redirect($message);
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
		//$this->loginView->reloadPage($message);
		$this->appView->redirect($message);
	}

	/**
	 * Call from AppController
	 * @return LoginView loginView
	 */
	public function getView() {
		return $this->loginView;
	}
}