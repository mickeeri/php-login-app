<?php



require_once("model/LoginModel.php");
require_once("view/LoginView.php");
require_once('view/CookieStorage.php');
require_once('model/UserModel.php');

class LoginController {
	// PROPERTIES
	private $loginModel;
	private $layoutView;
	public static $message = 'LoginController::Message';
	// private $userModel;

	// CONSTRUCTOR
	public function __construct(LoginModel $loginModel, LoginView $loginView) {		
		$this->loginModel = $loginModel;
		$this->loginView = $loginView;
		$this->cookies = new CookieStorage();
	}

	// FUNCTIONS
	public function isLoggedIn(){
		// See if there is welcome or bye bye message.
		$this->loginView->message = $this->cookies->load(self::$message);

		if($this->loginModel->sessionIsSet()) {								

			if($this->loginView->didUserPressLogoutButton()) {
				$this->loginModel->removeUserSession();
			}

			return true;			
		} else {
			if($this->loginView->didUserPressLoginButton()) {				
				
				$userName = $this->loginView->setUserNameCookie();
				$password = $this->loginView->setPasswordCookie();

				// $this->cookies->save('LoginView::CookieName', $this->loginView->getRequestUserName());



				$isValid = $this->loginModel->authorize($userName, $password);

				// $loginView->message = $this->loginModel->getMessage();

				if($isValid) {					
					$this->loginModel->createUserSession($userName);
				} else {

				}
			}

			return false;
		}
	}
	
	public function login() {



		// $userName = $loginView->getRequestUserNameCookie();
		//$password = $loginView->getRequestPasswordCookie();
		//
		

		// var_dump($userName);

		// $this->model->authorize();

		// 	// Compares parameters with valid credentials in model.		
		// 	if($userName == $this->model->getCorrectUserName() && $password == $this->model->getCorrectPassword()) {

		// 	}
		// 	else {
		// 		throw new Exception("Wrong name or password");
		// 		return false;
		// 	}
		
	
		// return false;


	}

	public function logout() {
		// // Removes session.
		// unset($_SESSION["Username"]); // todo: tar bort strängberoende här.
		// // Reloads page.
		// header('Location: '.$_SERVER['PHP_SELF']);
	}
}