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
	private static $cookieMessage = 'LoginView::CookieMessage';
	private static $message; 
	// public $message;
	public $nameFieldValue;

	public function __construct() {	
		$this->cookieStorage = new CookieStorage();
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined.
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response($isLoggedIn) {
		
		$message = "";		

		// // See if there is welcome- or bye-bye-message stored as cookie.
		// if($this->cookieStorage->load(self::$cookieMessage) !== "") {
		// 	$message = $this->cookieStorage->load(self::$cookieMessage);
		// } else {
		// 	$message = self::$message;
		// }
		// 
		$message = self::$message;

		if($isLoggedIn) {
			$response = $this->generateLogoutButtonHTML($message);
		} else {
			$response = $this->generateLoginFormHTML($message);
		}

		return $response;
	}

	public function didUserPressLoginButton() {
		// If user has pressed submit button launch method response.
		if (isset($_POST[self::$login]))
			return true;
		return false;
	}

	public function didUserPressLogoutButton() {
		if (isset($_POST[self::$logout]))		
			return true;
		return false;	
	}

	public function isKeepMeLoggedInChecked() {
		return isset($_POST[self::$keep]);
	}

	public function getUserNameCookie() {
		return isset($_COOKIE[self::$cookieName]) ? $_COOKIE[self::$cookieName] : NULL;
	}

	public function getPasswordCookie() {
		$ret = isset($_COOKIE[self::$cookiePassword]) ? $_COOKIE[self::$cookiePassword] : NULL;
		setcookie(self::$cookiePassword, $this->generateRandomString(), strtotime( '+30 days' ));
		return $ret;
	}

	public function destroyCookies() {
		setcookie(self::$cookieName, "", time() - 1);
		setcookie(self::$cookiePassword, "", time() - 1);
	}

	public function getMessage($message) {
		return $message;
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
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->nameFieldValue  . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" id="btnSubmit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';

		return $ret;
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName() {
		return isset($_POST[self::$name]) ? $_POST[self::$name] : "";
	}

	public function getRequestPassword() {
		return isset($_POST[self::$password]) ? $_POST[self::$password] : "";
	}

	// public function setCookieMessage($message) {
	// 	$this->cookieStorage->save(self::$cookieMessage, $message);
	// 	// setcookie(self::$cookieMessage, $message, -1);
	// }

	public function setMessage($message) {
		self::$message = $message;
	}

	public function setCookies($userName, $password){
		setcookie(self::$cookieName, $userName, strtotime( '+30 days' ));
		setcookie(self::$cookiePassword, $password, strtotime( '+30 days' ));
	}

	private function generateRandomString($length = 30) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
}