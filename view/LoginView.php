<?php

require_once('CookieStorage.php');
require_once("controller/LoginController.php");

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

		// See if there is welcome- or bye-bye-message stored as cookie.
		if($this->cookieStorage->load(self::$cookieMessage) !== "") {
			$message = $this->cookieStorage->load(self::$cookieMessage);
		} else {
			$message = self::$message;
		}

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

	public function setCookieMessage($message) {
		$this->cookieStorage->save(self::$cookieMessage, $message);
		// setcookie(self::$cookieMessage, $message, -1);
	}

	public function setMessage($message) {
		self::$message = $message;
	}
}