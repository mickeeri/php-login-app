<?php

namespace model;

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

	}

	/**
	 * Add new user to database
	 * @param User $userToBeAdded user object
	 */
	public function add(User $userToBeAdded){
		
		$stmt = $this->database->prepare("INSERT INTO `phpassignment`.`Users`(
			`userName`, `password`) 
				VALUES (?, ?)");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$userName = $userToBeAdded->getUserName();
		$password = $userToBeAdded->getPassword();
		$stmt->bind_param('ss', $userName, $password);

		$stmt->execute();
	}
}