<?php

namespace controller;

require_once("model/UserDAL.php");
require_once("model/UserModel.php");
require_once("model/UserFacade.php");

require_once("view/RegisterView.php");
require_once("view/LoginView.php");

require_once("controller/UserController.php");
require_once('controller/LoginController.php');

require_once("DbSettings.php");

class AppController {

	private $loginModel; 
	private $userDAL;
	private $appView;
	private $userFacade;

	/**
	 * Created in index.php
	 */
	function __construct(\view\AppView $appView) {
		// Setting up database.
		$this->mysqli = new \mysqli(\DbSettings::HOST, \DbSettings::USERNAME, \DbSettings::PASSWORD, \DbSettings::DATABASE);
		if(mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$this->userDAL = new \model\UserDAL($this->mysqli);
		$this->userFacade = new \model\UserFacade($this->userDAL);
		$this->loginModel = new \model\LoginModel($this->userFacade);
		$this->appView = $appView;
	}

	/**
	 * Based on input selects either LoginView or RegisterView.
	 */
	public function handleInput() {
		if ($this->appView->onLoginPage()) {
			$view = new \view\LoginView($this->loginModel, $this->appView);
			$login = new \controller\LoginController($this->loginModel, $view, $this->appView);

			$login->doLogin();
			$this->view = $login->getView();
		} else {
			$this->view = new \view\RegisterView($this->userFacade, $this->appView);
			$uc = new \controller\UserController($this->userFacade, $this->view, $this->appView);

			$uc->addUser();
		}
		$this->mysqli->close();
	}

	/**
	 * Generates current view.
	 * @return \view\LoginView | \view\RegisterView
	 */
	public function generateOutput() {
		return $this->view;
	}

	/**
	 * Checks if user is logged in or not.
	 * @return boolean
	 */
	public function isLoggedIn() {
		return $this->loginModel->sessionIsSet($this->appView->getClientIdentifier());
	}
}