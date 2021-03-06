<?php

namespace controller;

class UserController {

	private $userFacade;
	private $registerView;

	function __construct(\model\UserFacade $userFacade, \view\RegisterView $registerView) {
		$this->userFacade = $userFacade;
		$this->registerView = $registerView;
	}

	/**
	 * Gets new user from form in RegisterView.
	 */
	public function addUser(){
		// If user has pressed submit-button.
		if($this->registerView->userWantToRegister()) {
			$user = $this->registerView->getUser();
			if ($user != null) {
				try {
					$this->userFacade->saveUser($user);
					$this->registerView->setRegistrationHasSucceeded();				
				} catch (\Exception $e) {
					$this->registerView->setUserExists();		
				}
			}
		}
	}
}