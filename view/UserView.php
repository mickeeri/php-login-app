<?php

namespace view;

/**
* 
*/
class UserView
{
	private static $submitPostID = "Register";
	private static $userNamePostID = "name";
	private static $passwordPostID = "password";
	private static $passwordConfirmationPostID = "passwordConfirmation";

	

	function __construct()
	{
		# code...
	}

	/**
	 * User has pressed submit-button
	 * @return boolean true if user has pressed submit.
	 */
	public function userWantToRegister() {
		return isset($_POST[self::$submitPostID]);
	}

	/**
	 * Gets new user.
	 * @return User returns user object if successful else returns null.
	 */
	public function getUser(){
		//$userID = $_POST[self::$userIDPostID];
		$userID = 0;
		$userName = $_POST[self::$userNamePostID];
		$password = $_POST[self::$passwordPostID];
		$passwordConfirmation = $_POST[self::$passwordConfirmationPostID];

		try {
			return new \model\User($userID, $userName, $password);
		} catch (\model\NoUserIdException $e) {
			this->message = "No user id set.";
		} catch (\model\NoUserNameException $e) {
			this->message = "Username has to be entered.";
		} catch (\model\NoPasswordException $e) {
			this->message = "Password has to be entered.";
		} catch (\model\NoPasswordConfirmationException $e) {
			this->message = "Passwrod confirmation has to entered.";
		} catch (Exception $e) {
			this->message = "Something went wrong. Try again!";
		}
		return null;
	}

	/**
	 * Renders user register form.
	 * @return void BUT returns standard output.
	 */
	public function getHTML(){
		return "
		<p>$this->message</p>
		<form method='post'>" . 
			$this->getTextField("Name: ", self::$userNamePostID) . "</br>" .
			$this->getTextField("Password: ", self::$passwordPostID) . "</br>" .
			$this->getTextField("Repeat password: ", self::$pricePostID) . "</br>" .
		"<input type='submit' name='".self::$submitPostID."'>
		</form>" //. $this->catalog->getHTML();
	}

	/**
	 * Get field name.
	 * @param  [type] $field [description]
	 * @return [type]        [description]
	 */
	private function getPostField($field){
		if (isset($_POST[$field])) {
			return trim($_POST[$field]);
		}
		return  "";
	}

	/**
	 * Renders text fields for user input.
	 * @param  string $title input value
	 * @param  string $name  input name and id
	 * @return void        BUT generates output as html.
	 */
	private function getTextField($title, $name){
		$value = $this->getPostField($name);
		return "
			<label for='$name'>$title :</label>
			<input id='$name' type='text' value='$value' name='$name'></input>
			";
	}


}
