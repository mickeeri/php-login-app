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

	public function getLinkToLogin() {
		return "<a href='?'>back</a>";
	}

	public function onLoginPage() {
		return isset($_GET[self::$newUserURL]) == false;
	}
}