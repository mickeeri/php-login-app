<?php

namespace model;

class LoginModel {

	private $correctUserName;
	private $correctPassword;
	private static $userSession = "LoginModel::UserSession";
	private static $userClientSession = "LoginModel::UserClientSession";
	private static $folder = "data/";

	public function __construct() {
		//$this->loginView = $loginView;
		$this->correctUserName = "Admin";
		$this->correctPassword = "Password";
	}

	/**
	 * Checks if user session exists.
	 * @param  string $client, Information on users browser.
	 * @return boolean, True if session is set.
	 */
	public function sessionIsSet($client) {

		if(isset($_SESSION[self::$userSession]) && $_SESSION[self::$userClientSession] === $client)
			return true;
		else
			return false;
	}

	/**
	 * Authorizes users credentials.
	 * @param  string $userName
	 * @param  string $password 
	 * @return boolean, True if input matches correct credentials.
	 */
	public function authorize($userName, $password) {

		try {
			if($userName == "") {
				throw new \Exception(\view\MessageView::$userNameMissing);			
			} elseif ($password == "") {
				//$this->loginView->nameFieldValue = $userName;
				throw new \Exception(\view\MessageView::$passWordMissing);				
			} elseif ($userName !== $this->correctUserName || $password !== $this->correctPassword) {
				throw new \Exception(\view\MessageView::$wrongCredentials);
			}

		return true;
			
		} catch (\Exception $e) {
			// TODO find other way to display message. Kasta egna exceptions t.ex. NoUsernameException.
			//$this->loginView->setMessage($e->getMessage()); 
			return false;
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
	 * @param  string  $user, Username
	 * @param  string  $password
	 * @return boolean, True if user is remembered
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
	 * @param  string $password, Random password string.
	 * @param  string $rememberExpiration, How long the user is to be rememered.
	 * @return void
	 */
	public function rememberUser($user, $password, $rememberExpiration) {
		// Creates file.
		file_put_contents($this->getFileName($user, $password), "");

		// Saving how long user will be remembered in file. 
		$fp = fopen($this->getFileName($user, $password),'a');
		fwrite($fp, $rememberExpiration . "\n");
	}

	/**
	 * Gets filename
	 * @param  string $user     
	 * @param  string $password 
	 * @return string, File-path
	 */
	public function getFileName($user, $password) {
		return self::$folder . $user . '_' . $password;
	}

	/**
	 * Removes all files in data-folder.
	 * @return void
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

	/**
	 * Removes user session
	 * @return void
	 */
	public function removeUserSession() {
		unset($_SESSION[self::$userSession]);
	}

	/**
	 * Creates random string that is used as temporary password.
	 * @return string, Random string.
	 */
	public function getRandomStringPassword() {
		return sha1(rand());
	}
}