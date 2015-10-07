<?php

namespace model;

class UserDontExistException extends \Exception {};

/**
*
*/
class UserDAL
{
	
	private static $table = "Users";
	
	/**
	 * [__construct description]
	 * @param \mysqli $db [description]
	 */
	function __construct(\mysqli $db) {
		$this->database = $db;

		// $stmt = $this->database->prepare("SELECT * FROM " . self::$table);
		// if ($stmt === false) {
		// 	throw new \Exception($this->database->error);
		// }

		// $stmt->execute();

		// $stmt->bind_result($userName, $password);
	}

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
	 * @param User $userToBeAdded user object
	 */
	public function add(User $userToBeAdded){
		
		$stmt = $this->database->prepare("INSERT INTO `me222wm_se`.`Users`(
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
	 * Get user by typing in userName
	 * @return [type] [description]
	 */
	public function getUserByUserName($userName) {
		$stmt = $this->database->prepare("SELECT * FROM Users WHERE userName=?");

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