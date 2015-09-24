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
	// Username-field value.
	public $nameFieldValue;

	public function __construct() {	
		$this->cookieStorage = new CookieStorage();
	}	

	/**
	 * Creates HTTP response. 
	 * @param  function $isLoggedIn function in LoginController. True if user is logged in.
	 * @return void BUT writes to standard output and cookies!
	 */
	public function response($isLoggedIn) {

		$message = self::$message;	

		// See if there is welcome- or bye-bye-message stored as cookie.
		if($this->cookieStorage->load(self::$cookieMessage) !== "") {
			$message = $this->cookieStorage->load(self::$cookieMessage);
		}
		
		if($isLoggedIn) {
			$response = $this->generateLogoutButtonHTML($message);
		} else {
			$response = $this->generateLoginFormHTML($message);
		}

		return $response;
	}

	/**
	 * Checks if user has pressed login-button.
	 * @return boolean
	 */
	public function didUserPressLoginButton() {
		// If user has pressed submit button launch method response.
		if (isset($_POST[self::$login]))
			return true;
		return false;
	}

	/**
	 * Checks if user has pressed logout-button.
	 * @return boolean
	 */
	public function didUserPressLogoutButton() {
		if (isset($_POST[self::$logout]))		
			return true;
		return false;	
	}

	/**
	 * Is "Keep me logged in"-checkbox checked.
	 * @return boolean
	 */
	public function isKeepMeLoggedInChecked() {
		return isset($_POST[self::$keep]);
	}

	/**
	 * @return string Username stored in cookie.
	 */
	public function getUserNameCookie() {
		return isset($_COOKIE[self::$cookieName]) ? $_COOKIE[self::$cookieName] : NULL;
	}

	/**
	 * @return string Password from cookie.
	 */
	public function getPasswordCookie() {
		return isset($_COOKIE[self::$cookiePassword]) ? $_COOKIE[self::$cookiePassword] : NULL;
	}

	/**
	 * Removes cookies
	 * @return void
	 */
	public function destroyCookies() {
		setcookie(self::$cookieName, "", time() - 1);
		setcookie(self::$cookiePassword, "", time() - 1);
	}

	// public function getMessage($message) {
	// 	return $message;
	// }

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
	
	/**
	 * Gets Username after user input.
	 * @return string
	 */
	public function getRequestUserName() {
		return isset($_POST[self::$name]) ? $_POST[self::$name] : "";
	}

	/**
	 * Gets Password after input.
	 * @return string
	 */
	public function getRequestPassword() {
		return isset($_POST[self::$password]) ? $_POST[self::$password] : "";
	}

	/**
	 * Sets a feedback-message as cookie to be displayed after reload.
	 * @param string $message
	 */
	public function setCookieMessage($message) {
		setcookie(self::$cookieMessage, $message, -1);
	}

	/**
	 * Sets message.
	 * @param string $message
	 */
	public function setMessage($message) {
		self::$message = $message;
	}
	
	/**
	 * Creates Username and Password cookie if user wants to be remembered.
	 * @param string $userName        
	 * @param string $password     
	 * @param string $cookieExpirationTime, How long the the cookies are going to exist.
	 */
	public function setCookies($userName, $password, $cookieExpirationTime){
		setcookie(self::$cookieName, $userName, $cookieExpirationTime);
		setcookie(self::$cookiePassword, $password, $cookieExpirationTime);
	}

	/**
	 * Saves message in cookie and then reloads page.
	 * @param  string $message, To be stored in cookie and displayed after reload.
	 * @return void
	 */
	public function reloadPage($message) {

		$this->setCookieMessage($message);
		header('Location: '.$_SERVER['REQUEST_URI']);
		exit();	
	}

	
	/**
	 * Provides informatino on users browser. 
	 * @return string, User agent
	 */
	public function getClientIdentifier() {
		return $_SERVER["HTTP_USER_AGENT"];
	}
}