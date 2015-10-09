<?php

namespace model;

class WrongCredentialsException extends \Exception {};
class MissingUserNameException extends \Exception {};
class MissingPasswordException extends \Exception {};

class LoginModel {

	private static $userSession = "LoginModel::UserSession";
	private static $userClientSession = "LoginModel::UserClientSession";
	private static $folder = "data/";
	private $userFacade;

	public function __construct(\model\userFacade $userFacade) {
		$this->userFacade = $userFacade;
	}

	/**
	 * Checks if user session exists
	 * @param  string $client information on users browser
	 * @return boolean true if session is set
	 */
	public function sessionIsSet($client) {

		if(isset($_SESSION[self::$userSession]) && $_SESSION[self::$userClientSession] === $client)
			return true;
		else
			return false;
	}

	/**
	 * Authorizes users credentials
	 * @param  string $enteredUserName
	 * @param  string $enteredPassword hashed password
	 * @return boolean true if credentials are correct, throws exception if not
	 */
	public function authorize($enteredUserName, $enteredPassword) {
	

		// Checks for empty strings
		if($enteredUserName == "") {
			//throw new \Exception(\view\MessageView::$userNameMissing);
			throw new MissingUserNameException();			
		}
		if ($enteredPassword == "") {
			throw new MissingPasswordException();				
		} 

		$user = $this->userFacade->getUser($enteredUserName);
		$hash = $user->getPassword();

		if (password_verify($enteredPassword, $hash)) {
			return true;
		} else {
			throw new WrongCredentialsException();
		}
	}

	/**
	 * Sets new user session.
	 * @param  string $user, Username
	 * @param  string $client, Users browser
	 * @return void  
	 */
	public function createUserSession($user, $client) {	
		// Saves information on browser in session.
		$_SESSION[self::$userClientSession] = $client;
		// Saves user in session.
		$_SESSION[self::$userSession] = $user;
	}

	/**
	 * Checks if user has been remembered in persistent storage.
	 * @return boolean true if user is remembered
	 */
	public function hasBeenRemembered($user, $password) {
		
		if ($user === null) {
			return false;
		}

		// Reading file.
		$line = @file($this->getFileName($user, $password));		
		$expirationTime = $line[0];

		// Return false if user remember time is expired. 
		if(time() > $expirationTime) {
			return false;
		}

		return file_exists($this->getFileName($user, $password));
	}

	/**
	 * Stores information about user as file.
	 * @param  string $user
	 * @param  string $password random password string.
	 * @param  string $rememberExpiration how long the user is to be rememered.
	 */
	public function rememberUser($user, $password, $rememberExpiration) {
		// Creates file.
		file_put_contents($this->getFileName($user, $password), "");

		// Saving how long user will be remembered in file. 
		$fp = fopen($this->getFileName($user, $password),'a');
		fwrite($fp, $rememberExpiration . "\n");
	}

	/**
	 * @return string file-path
	 */
	public function getFileName($user, $password) {
		return self::$folder . $user . '_' . $password;
	}

	/**
	 * Removes all files in data-folder.
	 */
	public function forgetUser() {
		// http://stackoverflow.com/questions/4594180/deleting-all-files-from-a-folder-using-php
		$files = glob(self::$folder . '*');
		foreach ($files as $file) {
			if(is_file($file)) {
				unlink($file);
			}
		}
	}

	public function removeUserSession() {
		unset($_SESSION[self::$userSession]);
	}

	/**
	 * Creates random string that is used as temporary password
	 */
	public function getRandomStringPassword() {
		return sha1(rand());
	}
}