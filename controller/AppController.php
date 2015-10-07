<?php

namespace controller;

// Models
//require_once("model/LoginModel.php");

require_once("model/UserDAL.php");
require_once("model/UserModel.php");
require_once("model/UserFacade.php");

// Views
require_once("view/UserView.php");
//require_once("view/NavigationView.php");
require_once("view/LoginView.php");

// Controllers
require_once("controller/UserController.php");
require_once('controller/LoginController.php');

/**
* 
*/
class AppController {

	private $loginModel; 
	private $userDAL;
	private $appView;
	private $userFacade;

	/**
	 * Created in index.php
	 * @param \model\LoginModel $loginModel 
	 * @param \view\AppView     $appView  
	 */
	function __construct(\view\AppView $appView) {
		// Lägg detta i någon annan fil. 
		$this->mysqli = new \mysqli("me222wm.se.mysql", "me222wm_se", "6aVQjh4v", "me222wm_se");
		if(mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$this->userDAL = new \model\UserDAL($this->mysqli);
		$this->userFacade = new \model\UserFacade($this->userDAL);
		$this->loginModel = new \model\LoginModel($this->userFacade);
		$this->appView = $appView;
	}

	public function handleInput() {
		if ($this->appView->onLoginPage()) {
			//$model = new \model\LoginModel();
			$view = new \view\LoginView($this->loginModel, $this->appView);
			$login = new \controller\LoginController($this->loginModel, $view, $this->appView);

			// Handle input 
			$login->doLogin();
			$this->view = $login->getView();
		} else {
			//$model = new \model\UserFacade($this->userDAL);
			$this->view = new \view\UserView($this->userFacade, $this->appView);
			$uc = new \controller\UserController($this->userFacade, $this->view, $this->appView);

			$uc->addUser();
		}
		$this->mysqli->close();
	}

	public function generateOutput() {
		return $this->view;
	}

	public function isLoggedIn() {
		return $this->loginModel->sessionIsSet($this->appView->getClientIdentifier());
	}
}