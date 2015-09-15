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
	public $rememberMe = false;
	 
	// private $isLoggedIn;
	
	/**
	 * [__construct description]
	 */
	public function __construct(LoginView $loginView) {
		$this->loginView = $loginView;
		$this->correctUserName = "admin";
		$this->correctPassword = "pass";
		// $this->cookies = new CookieStorage();
	}

	// private function setCorrectUserName() {
	// 	$this->correctUserName = "admin";
	// }

	private function setCorrectPassword($newPassword) {
		$this->correctPassword = $newPassword;
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

	public function createUserSession($user) {	
		// Creates new session.
		$_SESSION[self::$userSession] = $user;
		
		// Relaods page
		// Sets welcome message in cookie.
		// if($this->rememberedMe) {
		// 	$_SESSION[self::$sessionMessage] = "Welcome and you will be remembered";
		// } else {
		// 	$_SESSION[self::$sessionMessage] = "Welcome";
		// }
		
		
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

	public function hasBeenRemembered($userIdentifier) {
		$lines = @file("model/LoginModel.txt");

		if ($lines === false) {
			return false;
		}

		foreach ($lines as $existingValue) {
			$existingValue = trim($existingValue);
			if ($existingValue === $userIdentifier) {
				return true;
			}
		}

		return false;
	}

	public function rememberUser($userIdentifier, $password) {
		// if ($this->hasBeenRemembered($userIdentifier)) {
		// 	return;
		// }

		$fp = fopen("model/LoginModel.txt", 'a');
		fwrite($fp, $userIdentifier . "\n" . $password);
	}

	public function forgetUser() {
		$fp = fopen("model/LoginModel.txt", 'r+');
		@ftruncate($fp, 0);
	}

	public function removeUserSession() {
		unset($_SESSION[self::$userSession]);
		// session_destroy();
		// Reloads page.
	
		// Sets bye bye cookie.	
		$this->loginView->setCookieMessage("Bye bye!");
		//$_SESSION[self::$sessionMessage] = "Bye bye!";
		header('Location: '.$_SERVER['REQUEST_URI']);
		exit();	
	}
}