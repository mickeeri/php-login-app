<?php

namespace model;

/**
* 
*/
class UserFacade
{
	
	function __construct(UserDAL $db)
	{
		$this->dal = $db;
	}

	/**
	 * Add user to database.
	 * @param model\User $userToBeAdded
	 */
	public function saveUser(User $userToBeAdded){

		$users = $this->getUsers();
		foreach ($users as $user) {
			if ($user->getUserName() === $userToBeAdded->getUserName()) {
				throw new \Exception("You cannot have two users with same username.");
			}
		}

		$this->dal->add($userToBeAdded);
	}

	/**
	 * Returns all users in database.
	 * @return [type] [description]
	 */
	public function getUsers(){
		return $this->dal->getUsers();
	}

	public function getUser($userName) {
		return $this->dal->getUserByUserName($userName);
	}
}