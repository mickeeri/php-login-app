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
	function __construct(\mysqli $db)
	{
		$this->database = $db;
	}

	public function getUsers(){

	}

	public function add(User $toBeAdded){
		
	}
}