<?php

namespace model;

/**
* 
*/
class UserFacade
{
	
	///https://github.com/dntoll/1DV608/blob/master/lectures/LectureCode/model/AdminFacade.php

	function __construct(UserDAL $db)
	{
		$this->dal = $db;
	}

	/**
	 * Add user to database.
	 * @param User $u user to be added
	 */
	public function add(User $u){
		$this->dal->add($u);
	}

	/**
	 * Returns all users in database.
	 * @return [type] [description]
	 */
	public function getUsers(){
		return $this->dal->getUsers();
	}
}