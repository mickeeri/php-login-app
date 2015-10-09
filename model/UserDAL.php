<?php

namespace model;

class UserDontExistException extends \Exception {};

class UserDAL
{
	
	private static $table = "Users";
	
	/**
	 * @param \mysqli $db initialized in AppController
	 */
	function __construct(\mysqli $db) {
		$this->database = $db;
	}

	/**
	 * Retuns all users in database
	 * @return array 
	 */
	public function getUsers(){
		$users = array();

		$stmt = $this->database->prepare("SELECT * FROM " . self::$table);
		
		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->execute();

		$stmt->bind_result($userID, $userName, $password);

		while ($stmt->fetch()) {
			$user = new User($userName, $password, $password);
			$user->setUserID($userID);
			$users[] = $user;
		}

		return $users;
	}

	/**
	 * Add new user to database
	 */
	public function add(User $userToBeAdded){
				
		$stmt = $this->database->prepare("INSERT INTO `" . \DbSettings::DATABASE .  "`.`" . self::$table . "`(
			`userName`, `password`) 
				VALUES (?, ?)");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$userName = $userToBeAdded->getUserName();
		$password = password_hash($userToBeAdded->getPassword(), PASSWORD_BCRYPT);
		$stmt->bind_param('ss', $userName, $password);

		$stmt->execute();
	}

	/**
	 * @return User $user
	 */
	public function getUserByUserName($userName) {
		$stmt = $this->database->prepare("SELECT * FROM `" . self::$table . "` WHERE userName=?");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->bind_param("s", $userName);
		
		$stmt->execute();

		$stmt->bind_result($userID, $userName, $password);

		$stmt->fetch();

		if ($userName === null) {
			throw new UserDontExistException();
		}

		$user = new User($userName, $password, $password);
		$user->setUserID($userID);
		return $user;
	}
}