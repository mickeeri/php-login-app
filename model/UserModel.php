<?php

// session_start();

require_once("view/LoginView.php");

/**
* 
*/
class User {

	// http://stackoverflow.com/questions/4604910/validating-the-construct-making-sure-vars-of-of-correct-type

	private $userName;
	private $password;
	private $loginView;
	
	function __construct($userName, $password) {

		$this->setUserName($userName);
		$this->setPassword($password);
		// $this->loginView = new LoginView;
	}

	public function getUserName() {
		return $this->userName;
	}

	public function getPassword() {
		return $this->password;
	}

	private function setUserName($userName) {
		try {		
			if ($userName == "") {
				throw new Exception("Username is missing");
			}	

			$this->userName = $userName;		
		} catch (Exception $e) {
			echo $e->getMessage();
		}			
	}

	private function setPassword($password) {
		try {
			if ($password == "") {
				throw new Exception("Password is missing");
			}

			$this->password = $password;
			
		} catch (Exception $e) {
			// $this->loginView($e->getMessage());
		}		
	}
}