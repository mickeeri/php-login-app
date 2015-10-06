<?php

namespace model;

class NoUserIdException extends \Exception {};
class NoUserNameException extends \Exception {};
class NoPasswordException extends \Exception {};
class NoPasswordConfirmationException extends \Exception {};
class PasswordConfirmationMatchException extends \Exception {};

class User {
	//https://github.com/dntoll/1DV608/blob/master/lectures/LectureCode/model/Product.php
	
	private $userID;
	private $userName;
	private $password;
	private $passwordConfirmation;
	
	function __construct($userName, $password, $passwordConfirmation) {
		$this->userID = 0;
		$this->userName = $userName;
		$this->password = $password;
		$this->passwordConfirmation = $passwordConfirmation;
	}

	public function getUserID() {
		return $this->userID;
	}

	public function getUserName() {
		return $this->userName;
	}

	public function getPassword() {
		return $this->password;
	}
}