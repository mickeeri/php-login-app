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
	private static $folder = "data/";
	// public $rememberMe = false;
	 
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
	}

	// public function displayLoginLogoutMessages() {
	// 	if(isset($_SESSION[self::$sessionMessage])) {
	// 		$this->loginView->setMessage($_SESSION[self::$sessionMessage]);
	// 		unset($_SESSION[self::$sessionMessage]);
	// 	}
	// }

	public function hasBeenRemembered($user, $password) {
		if ($user === null) {
			return false;
		}

		// var_dump(file_exists($this->getFileName($user)));
		return file_exists($this->getFileName($user, $password));

		// $lines = @file("data/rememberedUser.txt");

		// if ($lines === false) {
		// 	return false;
		// }

		// foreach ($lines as $existingValue) {
		// 	$existingValue = trim($existingValue);
		// 	if ($existingValue === $rememberToken) {
		// 		return true;
		// 	}
		// }

		// return false;
	}

	public function rememberUser($user, $password) {
		// var_dump($user);

		// if ($this->hasBeenRemembered($user)) {
		// 	return;
		// }

		//file_put_contents("data/rememberedUser.txt", "");

		// $fp = fopen("data/rememberedUser.txt", 'a');
		// fwrite($fp, $user . "\n");
		// 
		file_put_contents($this->getFileName($user, $password), "");
	}

	public function getFileName($user, $password) {
		return self::$folder . $user . '_' . $password;
	}

	public function forgetUser($user, $password) {
		// $fp = fopen("data/rememberedUser.txt", 'r+');
		// @ftruncate($fp, 0);
		unlink($this->getFileName($user, $password));
	}

	public function removeUserSession() {
		unset($_SESSION[self::$userSession]);
		// session_destroy();
		// Reloads page.
	
		// // Sets bye bye cookie.	
		// $this->loginView->setCookieMessage("Bye bye!");
		// //$_SESSION[self::$sessionMessage] = "Bye bye!";
		// header('Location: '.$_SERVER['REQUEST_URI']);
		// exit();	
	}
}