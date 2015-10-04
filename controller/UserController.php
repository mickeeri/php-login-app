<?php

namespace controller;

/**
* 
*/
class UserController
{
	
	// https://github.com/dntoll/1DV608/blob/master/lectures/LectureCode/controller/AdminController.php

	function __construct(\model\UserFacade $m, \view\UserView $v)
	{
		$this->model = $m;
		$this->view = $v;
	}

	/**
	 * Gets user from view new user form and passes to model. 
	 */
	public function addUser(){
		$user = $this->view->getUser();
		if ($user != null) {
			try {
				$this->model->add($user);
			} catch (\Exception $e) {
				$this->view->setMessage($e->getMessage());	// Or other error.			
			}
		}
	}
}