<?php

namespace view;

/**
* Collects functionality that all views have in common.
*/
class AppView {
	
	private static $newUserURL = "register";
	private static $sessionSaveLocation = "\\view\\AppView\\message";
	private static $messageCookieName = "AppView::Message";
	private static $fieldValueCookieName = "AppView::InputFieldValue";
	private static $newUserCookieName = "AppView::NewUsersName";


	/**
	 * Provides link to register new user page.
	 */
	public function getLinkToRegisterNewUser() {
		return "<a href='?register'>Register a new user</a>";
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

	// Some messages are saved as session and fetched after redirect. 
	public function getSessionMessage() {
		if (isset($_SESSION[self::$sessionSaveLocation])) {
			$message = $_SESSION[self::$sessionSaveLocation];
			unset($_SESSION[self::$sessionSaveLocation]);
			return $message;
		}
		return "";
	}

	// Get saved username. 
	public function getSavedUserName() {
		$ret = isset($_COOKIE[self::$newUserCookieName]) ? $_COOKIE[self::$newUserCookieName] : "";
		setcookie(self::$newUserCookieName, "", time() - 1);
		return $ret;
	}

	// Save username to be displayed later in LoginView username text field. 
	public function saveNewUsersName($userName) {
		setcookie(self::$newUserCookieName, $userName, -1);
	}

	/**
	 * Saves message and redirects user to index page.
	 * @param  string $message feedback to user
	 */
	public function redirect($message) {
		$_SESSION[self::$sessionSaveLocation] = $message;
		//setcookie(self::$messageCookieName, $message, -1);
		$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		header("Location: $actual_link");
		exit();
	}

	/**
	 * Provides information on users browser to avoid session hijacking.
	 * @return string info on user browser
	 */
	public function getClientIdentifier() {
		return $_SERVER["HTTP_USER_AGENT"];
	}
}