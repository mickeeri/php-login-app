<?php

// require_once('CookieStorage.php');

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
		
		// Message to be displayed as user feedback.
		$message ="";
		// Saved username if login attempt is unsuccessful.
		$nameValue = "";

		//$response = $this->generateLoginFormHTML($message, $nameValue);

		if($this->didUserPressLoginButton()) {
			// Entered named.
			$postName = (isset($_POST[self::$name]) ? $_POST[self::$name] : NULL);
			// Entered password.
			$postPassword = $_POST[self::$password];			

			// Blank username
			if ($postName == NULL) {
				$message = "Username is missing";
				$response = $this->generateLoginFormHTML($message, $nameValue);
			}
			// Blank password
			elseif ($postPassword == NULL) {
				$message = "Password is missing";
				$nameValue = $postName;
				$response = $this->generateLoginFormHTML($message, $nameValue);		
			}
			// Both text-field are filled.
			else {
				// OM användaren har skrivit in anv och lösen bör de sparas i Cookies.
				// Correct credentials.
				if($this->controller->checkCredentials($postName, $postPassword)) {
					setcookie("CookieStorage", "logged-in", -1);
					header('Location: '.$_SERVER['PHP_SELF']);
					$message = "Welcome";
					$response = $this->generateLogoutButtonHTML($message);
					
				// Wrong credentials.
				} else {
					setcookie("CookieStorage", "not-logged-in", -1);
					$message = "Wrong name or password";
					$response = $this->generateLoginFormHTML($message, $nameValue);
				}		
			}		
		} 
		// User has not pressed login-button.
		else {
			setcookie("CookieStorage", "not-logged-in", -1);
			$nameValue = "";
			// self::$isLoggedIn = $this->controller->getIsLoggedIn();
			$response = $this->generateLoginFormHTML($message, $nameValue);	
		}
								
		// header('Location: '.$_SERVER['PHP_SELF']);
		return $response;
	}

	private function didUserPressLoginButton() {
		// If user has pressed submit button launch method response.
		if (isset($_POST[self::$login]))
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
	private function generateLoginFormHTML($message, $nameValue) {
		$ret =  '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $nameValue . '" />

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
		//RETURN REQUEST VARIABLE: USERNAME
	}


	
}