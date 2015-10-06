<?php

namespace controller;

/**
* 
*/
class UserController {
	
	// https://github.com/dntoll/1DV608/blob/master/lectures/LectureCode/controller/AdminController.php

	function __construct(\model\UserFacade $userFacade, \view\UserView $userView) {
		$this->userFacade = $userFacade;
		$this->userView = $userView;
	}

	/**
	 * Gets user from userView new user form and passes to userFacade. 
	 */
	public function addUser(){
		// If user has pressed submit-button.
		if($this->userView->userWantToRegister()) {
			$user = $this->userView->getUser();
			if ($user != null) {
				try {
					$this->userFacade->add($user);
					$this->userView->setRegistrationHasSucceeded();
				} catch (\Exception $e) {
					//$this->userView->setDuplicate();
					$this->userView->setMessage($e->getMessage());	// Or other error.			
				}
			}
		}
	}
}