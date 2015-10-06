<?php

namespace view;

/**
* 
*/
class NavigationView {
	
	/**
	 * [$loginURLID description]
	 * @var string
	 */
	//private static $loginURLID = "login";
	private static $newUserURL = "register";
	private static $sessionSaveLocation = "\\view\\NavigationView\\message";


	/**
	 * Provides link to register new user page.
	 */
	public function getLinkToRegisterNewUser() {
		return "<a href='?register'>Register a new user<a/>";
	}

	/**
	 * Returns link to login page if user is on register page.
	 */
	public function getLinkToLogin() {
		return "<a href='?'>Back to login</a>";
	}

	/**
	 * Returns true if user is on login page.
	 */
	public function onLoginPage() {
		return isset($_GET[self::$newUserURL]) == false;
	}

	public function getSessionMessage() {
		if (isset($_SESSION[self::$sessionSaveLocation])) {
			$message = $_SESSION[self::$sessionSaveLocation];
			unset($_SESSION[self::$sessionSaveLocation]);
			return $message;
		}
		return "";
	}

	public function redirect($message) {
		$_SESSION[self::$sessionSaveLocation] = $message;
		$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		header("Location: $actual_link");
	}
}