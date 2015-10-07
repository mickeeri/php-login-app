<?php

namespace view;



/**
* 
*/
class UserView
{
	/// https://github.com/dntoll/1DV608/blob/master/lectures/LectureCode/view/AdminView.php

	private static $submitPostID = "UserView::Login";
	private static $userNamePostID = "UserView::Username";
	private static $passwordPostID = "UserView::Password";
	private static $messageID = "UserView::Message";
	private static $passwordConfirmationPostID = "passwordConfirmation";

	public $message = "";
	public $messageArray = array();

	private static $registrationHasSucceeded = false;
	
	public function __construct(\model\UserFacade $m, \view\AppView $appView)
	{
		$this->userFacade = $m;
		$this->appView = $appView;
	}

	/**
	 * User has pressed submit-button
	 * @return boolean true if user has pressed submit.
	 */
	public function userWantToRegister() {
		return isset($_POST[self::$submitPostID]);
	}

	/**
	 * Creates proper response based on registration success or not
	 */
	public function response() {
		if(self::$registrationHasSucceeded) {
			$this->appView->redirect("Registerd new user.");
		} else {
			return $this->getHTML();
		}		
	}

	/**
	 * Gets new user.
	 * @return User returns user object if successful else returns null.
	 */
	public function getUser(){
		//$userID = $_POST[self::$userIDPostID];
		//$userID = 0;
		$userName = $_POST[self::$userNamePostID];
		$password = $_POST[self::$passwordPostID];
		$passwordConfirmation = $_POST[self::$passwordConfirmationPostID];

		try {
			return new \model\User($userName, $password, $passwordConfirmation);			
		} catch (\model\NoCredentialsException $e) {
			$this->message = "Username has too few characters, at least 3 characters.</br>Password has too few characters, at least 6 characters.";
		} catch (\model\NoUserNameException $e) {
			$this->message = "Username has too few characters, at least 3 characters.";
		} catch (\model\NoPasswordException $e) {
			$this->message = "Password has too few characters, at least 6 characters.";
		} catch (\model\UserNameInvalidCharException $e) {
			$this->message = "Username contains invalid characters.";
		} catch (\model\PasswordConfirmationMatchException $e) {
			$this->message = "Passwords do not match.";
		} catch (Exception $e) {
			$this->message = "Something went wrong. Try again!";
		}
		return null;
	}

	/**
	 * Renders user register form.
	 * @return void BUT returns standard output.
	 */
	public function getHTML(){
		return "		
		<fieldset>
			<legend>Register a new user - Write username and password</legend>
			<p id=" . self::$messageID . ">" . $this->message . "</p>
			<form method='post'>" . 
				$this->getTextField("Name", self::$userNamePostID, "text") . "</br>" .
				$this->getTextField("Password", self::$passwordPostID, "password") . "</br>" .
				$this->getTextField("Repeat password", self::$passwordConfirmationPostID, "password") . "</br>" .
			"<input type='submit' name='".self::$submitPostID."' value='Register'>
			</form>
		</fieldset>";
	}

	/**
	 * Get field name.
	 * @param  [type] $field [description]
	 * @return [type]        [description]
	 */
	private function getPostField($field){
		if (isset($_POST[$field])) {			
			// Trims and removes special chars. 
			return filter_var(trim($_POST[$field]), FILTER_SANITIZE_STRING);
			//return trim($_POST[$field]);
		}
		return  "";
	}

	// public function setMessage($message){
	// 	$this->message = $message;
	// }

	/**
	 * Renders text fields for user input.
	 * @param  string $title input value
	 * @param  string $name  input name and id
	 * @return void        BUT generates output as html.
	 */
	private function getTextField($title, $name, $type){
		$value = $this->getPostField($name);
		return "
			<label for='$name'>$title :</label>
			<input id='$name' type='$type' value='$value' name='$name'></input>
			";
	}

	public function setRegistrationHasSucceeded() {
		self::$registrationHasSucceeded = true; 
	}

	public function setUserExists() {
		$this->message = "User exists, pick another username.";
	}

}

