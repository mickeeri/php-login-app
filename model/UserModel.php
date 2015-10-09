<?php

namespace model;

class NoCredentialsException extends \Exception {};
class NoUserNameException extends \Exception {};
class NoPasswordException extends \Exception {};
class UserNameInvalidCharException extends \Exception {};
class PasswordConfirmationMatchException extends \Exception {};

class User {
	
	private $userID;
	private $userName;
	private $password;
	private $passwordConfirmation;
	
	function __construct($userName, $password, $passwordConfirmation) {
		
		// Validation
		if(mb_strlen($userName) < 3 && mb_strlen($password) < 6) {
			throw new NoCredentialsException();
		}
		if (is_string($userName) === false || mb_strlen($userName) < 3) {
			throw new NoUserNameException();
		}
		if (is_string($password) === false || mb_strlen($password) < 6) {
			throw new NoPasswordException();
		}
		if($password !== $passwordConfirmation) {
			throw new PasswordConfirmationMatchException();
		}
		if (filter_var($userName, FILTER_SANITIZE_STRING) !== $userName) {
			throw new UserNameInvalidCharException();
		}

		$this->userName = $userName;
		$this->password = $password;
		$this->passwordConfirmation = $passwordConfirmation;
	}

	public function setUserID($id) {
		$this->userID = $id;
	}

	public function getUserName() {
		return $this->userName;
	}

	public function getPassword() {
		return $this->password;
	}
}