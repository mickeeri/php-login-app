<?php

namespace view;

class RegisterView
{
	private static $submitPostID = "RegisterView::Register";
	private static $userNamePostID = "RegisterView::UserName";
	private static $passwordPostID = "RegisterView::Password";
	private static $messageID = "RegisterView::Message";
	private static $passwordConfirmationPostID = "RegisterView::PasswordRepeat";

	private $message = "";
	private static $registrationHasSucceeded = false;

	private $userFacade;
	private $appView;
	
	public function __construct(\model\UserFacade $m, \view\AppView $appView)
	{
		$this->userFacade = $m;
		$this->appView = $appView;
	}

	/**
	 * @return boolean true if user has pressed submit
	 */
	public function userWantToRegister() {
		return isset($_POST[self::$submitPostID]);
	}

	/**
	 * Creates proper response based on registration success or not
	 */
	public function response() {
		if(self::$registrationHasSucceeded) {
			// Saves user name to be displayed in login text field.
			$this->appView->saveNewUsersName($_POST[self::$userNamePostID]);
			$this->appView->redirect(\view\MessageView::$successfulRegistration);
		} else {
			return $this->getHTML();
		}		
	}

	/**
	 * Gets new user.
	 * @return User returns User object if successful else returns null.
	 */
	public function getUser(){
		$userName = $_POST[self::$userNamePostID];
		$password = $_POST[self::$passwordPostID];
		$passwordConfirmation = $_POST[self::$passwordConfirmationPostID];

		try {
			return new \model\User($userName, $password, $passwordConfirmation);			
		} catch (\model\NoCredentialsException $e) {
			$this->message = \view\MessageView::$userNameTooFewChars . \view\MessageView::$break . \view\MessageView::$passwordTooFewChars;
		} catch (\model\NoUserNameException $e) {
			$this->message = \view\MessageView::$userNameTooFewChars;
		} catch (\model\NoPasswordException $e) {
			$this->message = \view\MessageView::$passwordTooFewChars;
		} catch (\model\UserNameInvalidCharException $e) {
			$this->message = \view\MessageView::$userNameInvalidChars;
		} catch (\model\PasswordConfirmationMatchException $e) {
			$this->message = \view\MessageView::$passwordMatch;
		} catch (Exception $e) {
			$this->message = \view\MessageView::$generalErrorMessage;
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
				$this->getTextField("Name", self::$userNamePostID, "text") . "<br>" .
				$this->getTextField("Password", self::$passwordPostID, "password") . "<br>" .
				$this->getTextField("Repeat password", self::$passwordConfirmationPostID, "password") . "<br>" .
			"<input type='submit' name='".self::$submitPostID."' value='Register'>
			</form>
		</fieldset>";
	}

	/**
	 * Get value of specific field.
	 * @param  string $field fields name
	 * @return string        field value
	 */
	private function getPostField($field){
		if (isset($_POST[$field])) {			
			// Trims and removes special chars. 
			return filter_var(trim($_POST[$field]), FILTER_SANITIZE_STRING);
		}
		return  "";
	}

	/**
	 * Renders text fields for user input.
	 * @param  string $title input value
	 * @param  string $name  input name and id
	 */
	private function getTextField($title, $name, $type){
		$value = $this->getPostField($name);
		return "
			<label for='$name'>$title :</label>
			<input id='$name' type='$type' value='$value' name='$name'>
			";
	}

	/**
	 * Assigns true if registration is successful.
	 */
	public function setRegistrationHasSucceeded() {
		self::$registrationHasSucceeded = true; 
	}

	/**
	 * If user already exists in database.
	 */
	public function setUserExists() {
		$this->message = \view\MessageView::$userExists;
	}

}

