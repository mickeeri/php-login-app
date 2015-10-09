<?php

namespace view;

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
	
	private static $message;

	private $loginModel;
	private $appView;
	private $cookieStorage;

	public function __construct(\model\LoginModel $loginModel, \view\AppView $appView) {	
		$this->appView = $appView;
		$this->cookieStorage = new CookieStorage();
		$this->loginModel = $loginModel;
	}	

	/**
	 * Creates HTTP response. 
	 * @return void BUT writes to standard output and cookies!
	 */
	public function response() {

		$sessionMessage = $this->appView->getSessionMessage();

		// Replace self::$message with message stored in session if that is not empty string.
		if ($sessionMessage !== "") {
			self::$message = $sessionMessage;
		}
		
		// Asks to model if the user is logged in. 
		if($this->loginModel->sessionIsSet($this->appView->getClientIdentifier())) {
			$response = $this->generateLogoutButtonHTML();
		} else {			
			$response = $this->generateLoginFormHTML();
		}

		return $response;
	}

	/**
	 * Checks if user has pressed login-button.
	 */
	public function didUserPressLoginButton() {
		// If user has pressed submit button launch method response.
		if (isset($_POST[self::$login]))
			return true;
		return false;
	}

	/**
	 * Checks if user has pressed logout-button.
	 */
	public function didUserPressLogoutButton() {
		if (isset($_POST[self::$logout]))		
			return true;
		return false;	
	}

	/**
	 * Is "Keep me logged in"-checkbox checked.
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

	public function destroyCookies() {
		setcookie(self::$cookieName, "", time() - 1);
		setcookie(self::$cookiePassword, "", time() - 1);
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML() {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . self::$message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML() {
		$ret =  '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . self::$message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserName()  . '" />

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
	 */
	public function getRequestUserName() {
		if (isset($_POST[self::$name])) {
			return trim($_POST[self::$name]);
		} else {			
			// If username is stoored in cookie.
			return $this->appView->getSavedUserName();
		}
	}

	/**
	 * Gets Password after input.
	 */
	public function getRequestPassword() {
		return isset($_POST[self::$password]) ? $_POST[self::$password] : "";
	}
	
	/**
	 * Creates Username and Password cookie if user wants to be remembered.
	 * @param string $userName        
	 * @param string $password     
	 * @param string $cookieExpirationTime how long the the cookies is going to exist.
	 */
	public function setCookies($userName, $password, $cookieExpirationTime){
		setcookie(self::$cookieName, $userName, $cookieExpirationTime);
		setcookie(self::$cookiePassword, $password, $cookieExpirationTime);
	}

	// Sets private static message.
	public function setMessage($message) {
		self::$message = $message;
	}
}