<?php

namespace controller;

class LoginController {
	private $view;
	private $model;

	public function __construct {
		$this->model = new LoginModel();
		$this->view = new LoginView($this->model)
	}
}