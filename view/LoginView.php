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
	// private static $nameValue = "LoginView::NameValue";
	// private $cookies;

	// public function __construct() {
	// 	$this->cookies = new \view\CookieStorage();
	// }


	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined. // Men den anropas ju från början efet layuout view.
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message = "";
		$nameValue = "";

		if($this->didUserPressLoginButton()) {
			

			$postName = (isset($_POST[self::$name]) ? $_POST[self::$name] : NULL);
			$postPassword = $_POST[self::$password];			
			//print("Har klickat på knappen.");

			// Blank username
			if ($postName == NULL) {
				$message = "Username is missing";
			}
			// Blank password
			elseif ($postPassword == NULL) {
				$message = "Password is missing";
				$nameValue = $postName;		
			}

			// header('Location: '.$_SERVER['PHP_SELF']);
				
		} else {

			$nameValue = "";		
		}
						


		$response = $this->generateLoginFormHTML($message, $nameValue);

		// If login attempt is successful. 
		// $response .= $this->generateLogoutButtonHTML($message);

		return $response;
	}

	public function didUserPressLoginButton() {
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