<?php



session_start();

// require_once('view/CookieStorage.php');

/**
* 
*/
class LoginModel {

	private $correctUserName;
	private $correctPassword;
	private static $userSession = "LoginModel::UserSession";
	private static $sessionMessage = "LoginModel::SessionMessage";
	public static $isLoggingInWithCookies = "LoginModel::isLoggingInWithCookies";
	 
	// private $isLoggedIn;
	
	/**
	 * [__construct description]
	 */
	public function __construct(LoginView $loginView) {
		$this->loginView = $loginView;
		$this->setCorrectUserName();
		$this->setCorrectPassword();
		// $this->cookies = new CookieStorage();
	}

	private function setCorrectUserName() {
		$this->correctUserName = "admin";
	}

	private function setCorrectPassword() {
		$this->correctPassword = "pass";
	}

	public function sessionIsSet() {
		return isset($_SESSION[self::$userSession]);
	}

	public function authorize($userName, $password) {
		try {
			if($userName == "") {
				throw new Exception("Username is missing");			
			} elseif ($password == "") {
				$this->loginView->nameFieldValue = $userName;
				throw new Exception("Password is missing");				
			} elseif ($userName !== $this->correctUserName || $password !== $this->correctPassword) {
				throw new Exception("Wrong name or password");
			}

		return true;
			
		} catch (Exception $e) {
			$this->loginView->setMessage($e->getMessage()); 
			return false;
		}
	}

	public function createUserSession($userName) {
		// Creates new session.
		$_SESSION[self::$userSession] = $userName;
		
		// Relaods page
		// header("Location: http://me222wm.comuv.com/");	
		// Sets welcome message in cookie.
		if(self::$isLoggingInWithCookies == true) {
			$_SESSION[self::$sessionMessage] = "Welcome with cookies";
		} else {
			$_SESSION[self::$sessionMessage] = "Welcome";
		}
		
		// $this->loginView->setCookieMessage("Welcome");
		//$_SESSION["MessageSession"] = "Welcome";
		header('Location: '.$_SERVER['REQUEST_URI']);
		exit();
	}

	public function displayLoginLogoutMessages() {
		if(isset($_SESSION[self::$sessionMessage])) {
			$this->loginView->setMessage($_SESSION[self::$sessionMessage]);
			unset($_SESSION[self::$sessionMessage]);
		}
	}

	public function removeUserSession() {
		unset($_SESSION[self::$userSession]);
		// session_destroy();
		// Reloads page.
	
		// Sets bye bye cookie.	
		//$this->loginView->setCookieMessage("Bye bye!");
		$_SESSION[self::$sessionMessage] = "Bye bye!";
		header('Location: '.$_SERVER['REQUEST_URI']);
		exit();	
	}
}