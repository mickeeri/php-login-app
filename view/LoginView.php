<?php

require_once('CookieStorage.php');

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	public function __construct(LoginController $controller) {
		$this->controller = $controller;
		$this->cookies = new CookieStorage();
	}

	// var_dump($this->controller);

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined.
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message ="";



		// User is logged in.
		if($this->controller->isLoggedIn()) {
			$response = $this->generateLogoutButtonHTML($message);

			//header('Location: '.$_SERVER['PHP_SELF']);

			if($this->didUserPressLogoutButton()) {
				$message = "Bye bye!";
				$this->controller->logout();
			}
		} 
		// User is not logged in.
		else {	
			$response = $this->generateLoginFormHTML($message);	
			// User has pressed login-button		
			if($this->didUserPressLoginButton()) {
				
				header('Location: '.$_SERVER['PHP_SELF']);
				// Saves info in cookies.				
				$this->cookies->save(self::$cookieName, $this->getRequestUserName());
				$this->cookies->save(self::$cookiePassword, $this->getRequestPassword());
				// Saves info on whether user clicked button or not.
				$this->cookies->save("Submitted", $this->didUserPressLoginButton());

				// Reloads page.				
			} 
			// On reload.
			else {			
				// Loads username and password from cookie and preforms log-in attempt.
				$isLoggedIn = $this->controller->login($this->getRequestUserNameCookie(),  $this->getRequestPasswordCookie());

				if($isLoggedIn) {
					$message = "Welcome";
					$response = $this->generateLogoutButtonHTML($message);

				} else {
					// var_dump(isset($_GET["index"]));
					// Only display message if submit-button is clicked.
					if ($this->cookies->load("Submitted") == "1") {
						$message = $this->controller->errorMessage;	
					}											
					$response = $this->generateLoginFormHTML($message);
				}
			}
		}

		return $response;
	}

	private function didUserPressLoginButton() {
		// If user has pressed submit button launch method response.
		if (isset($_POST[self::$login]))
			return true;
		return false;
	}

	private function didUserPressLogoutButton() {
		if (isset($_POST[self::$logout]))
			return true;
		return false;	
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		$ret =  '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserNameCookie() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" id="btnSubmit" name="' . self::$login . '" value="Login" />
				</fieldset>
			</form>
		';

		return $ret;
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName() {
		return isset($_POST[self::$name]) ? $_POST[self::$name] : "";
	}

	private function getRequestPassword() {
		return isset($_POST[self::$password]) ? $_POST[self::$password] : "";
	}

	private function getRequestUserNameCookie() {
		return $this->cookies->load(self::$cookieName);
	}

	private function getRequestPasswordCookie() {
		return $this->cookies->load(self::$cookiePassword);
	}


	
}