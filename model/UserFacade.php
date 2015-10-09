<?php

namespace model;

/**
 * Handles communication with DAL-class.
 */
class UserFacade {
	
	function __construct(UserDAL $db) {
		$this->dal = $db;
	}

	/**
	 * Add user to database.
	 * @param model\User $userToBeAdded
	 */
	public function saveUser(User $userToBeAdded){

		$users = $this->getUsers();
		foreach ($users as $user) {
			// Makes sure username does not already exist
			if ($user->getUserName() === $userToBeAdded->getUserName()) {
				throw new \Exception();
			}
		}

		$this->dal->add($userToBeAdded);
	}

	/**
	 * Returns all users in database.
	 * @return array
	 */
	public function getUsers(){
		return $this->dal->getUsers();
	}

	/**
	 * Get one user from database
	 * @return \model\User
	 */
	public function getUser($userName) {
		return $this->dal->getUserByUserName($userName);
	}
}