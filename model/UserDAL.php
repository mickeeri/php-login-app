<?php

namespace model;

/**
* CREATE TABLE IF NOT EXISTS `users` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`pk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

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

	public function add(User $userToBeAdded){
		$stmt = $this->database->prepare("INSERT INTO `phpassignment`.`Users`(
			`userID`, `userName`, `password`) 
				VALUES (?, ?, ?)");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$userID = null;
		$userName = $userToBeAdded->getUserName();
		$password = $userToBeAdded->getPassword();
		$stmt->bind_param('iss', $userID, $userName, $password);

		$stmt->execute();
	}
}