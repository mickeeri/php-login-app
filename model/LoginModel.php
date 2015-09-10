<?php



session_start();

require_once('view/CookieStorage.php');

/**
* 
*/
class LoginModel {

	private $correctUserName;
	private $correctPassword;
	private static $session = "LoginModel::Session";
	 
	// private $isLoggedIn;
	
	/**
	 * [__construct description]
	 */
	public function __construct(LoginView $loginView) {
		$this->loginView = $loginView;
		$this->setCorrectUserName();
		$this->setCorrectPassword();
		$this->cookies = new CookieStorage();
	}

	private function setCorrectUserName() {
		$this->correctUserName = "Admin";
	}

	private function setCorrectPassword() {
		$this->correctPassword = "Password";
	}

	public function sessionIsSet() {
		return isset($_SESSION[self::$session]);
	}

	public function authorize($userName, $password) {
		try {
			if($userName == "") {
				throw new Exception("Username is missing");
			} elseif ($password == "") {
				throw new Exception("Password is missing");
			} elseif ($userName !== $this->correctUserName || $password !== $this->correctPassword) {
				throw new Exception("Wrong name or password");
			}

		return true;
			
		} catch (Exception $e) {
			$this->loginView->message = $e->getMessage(); 
			return false;
		}
	}

	public function getMessage() {
		return $this->message;
	}

	public function createUserSession($userName) {
		// Creates new session.
		$_SESSION[self::$session] = $userName;
		// Sets welcome message in cookie.
		$this->cookies->save(LoginController::$message, "Welcome");
		// Relaods page
		header('Location: '.$_SERVER['PHP_SELF']);
		
	}

	public function removeUserSession() {
		unset($_SESSION[self::$session]);
		// session_destroy();
		// Sets bye bye cookie.
		$this->cookies->save(LoginController::$message, "Bye bye!");
		// Reloads page.
		header('Location: '.$_SERVER['PHP_SELF']);
	}

	// public function setIsLoggedIn($isLoggedIn) {
	// 	$this->isLoggedIn = $isLoggedIn;
	// }
}