<?php

namespace view;



/**
* 
*/
class UserView
{
	/// https://github.com/dntoll/1DV608/blob/master/lectures/LectureCode/view/AdminView.php

	private static $submitPostID = "register";
	private static $userNamePostID = "name";
	private static $passwordPostID = "password";
	private static $passwordConfirmationPostID = "passwordConfirmation";

	public $message = "";

	

	public function __construct(\model\UserFacade $m, \view\NavigationView $navigationView)
	{
		$this->userFacade = $m;
		$this->navigationView = $navigationView;
	}

	/**
	 * User has pressed submit-button
	 * @return boolean true if user has pressed submit.
	 */
	public function userWantToRegister() {
		return isset($_POST[self::$submitPostID]);
	}

	public function response() {
		return $this->getHTML();
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
			$this->message = "No user id set.";
		} catch (\model\NoUserNameException $e) {
			$this->message = "You have to enter name";
		} catch (\model\NoPasswordException $e) {
			$this->message = "Password can't be blank.";
		} catch (\model\NoPasswordConfirmationException $e) {
			$this->message = "Password confirmation can't be blank.";
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
		<p>$this->message</p>
		<form method='post'>" . 
			$this->getTextField("Name", self::$userNamePostID) . "</br>" .
			$this->getTextField("Password", self::$passwordPostID) . "</br>" .
			$this->getTextField("Repeat password", self::$passwordConfirmationPostID) . "</br>" .
		"<input type='submit' name='".self::$submitPostID."'>
		</form>"; //. $this->catalog->getHTML();
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

	public function setMessage($message){
		$this->message = $message;
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

