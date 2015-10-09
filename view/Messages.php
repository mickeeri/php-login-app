<?php
 
namespace view;

/**
 * Messages to be displayed as feedback.
 */
class MessageView {

	public static $regularLogin = "Welcome";
	public static $loginCookie = "Welcome and you will be remembered";
	public static $regularLogout = "Bye bye!";
	public static $welcomeBack = "Welcome back with cookie";
	public static $manipulatedCookie = "Wrong information in cookies";
	public static $userNameMissing = "Username is missing";
	public static $passwordMissing = "Password is missing";
	public static $wrongCredentials = "Wrong name or password";
	//public static $userDontExist = "There is no user with that user name";
	
	public static $userNameTooFewChars = "Username has too few characters, at least 3 characters."; 
	public static $passwordTooFewChars = "Password has too few characters, at least 6 characters.";
	public static $break = "<br>";
	public static $userNameInvalidChars = "Username contains invalid characters.";
	public static $passwordMatch = "Passwords do not match.";
	public static $generalErrorMessage = "Something went wrong. Try again!";
	public static $userExists = "User exists, pick another username.";
	public static $successfulRegistration = "Registered new user.";
}