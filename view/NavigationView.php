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
	private static $loginURLID = "login";
	private static $newUserURL = "register";

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
}