<?php

/**
* 
*/
class LoginModel {

	private $correctUserName;
	private $correctPassword;
	// private $isLoggedIn;
	
	/**
	 * [__construct description]
	 */
	public function __construct() {
		$this->correctUserName = "Admin";
		$this->correctPassword = "Password";
		// $this->isLoggedIn = false;
	}

	public function getCorrectUserName() {
		return $this->correctUserName;
	}

	public function getCorrectPassword() {
		return $this->correctPassword;
	}


	// public function getIsLoggedIn() {
	// 	return $this->isLoggedIn;
	// }

	// public function setIsLoggedIn($isLoggedIn) {
	// 	$this->isLoggedIn = $isLoggedIn;
	// }
}