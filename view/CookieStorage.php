<?php

namespace view;

class CookieStorage {
	/**
	 * Saves cookie.
	 * @param  string $name, Name of cookie.
	 * @param  string $string, Value to be stored.
	 */
	public function save($name, $string) {		
		setcookie($name, $string, -1);
	}

	/**
	 * Loads and removes cookie.
	 * @param  string $name, Name of cookie.
	 * @return string, Cookie-value
	 */
	public function load($name) {
		$ret = isset($_COOKIE[$name]) ? $_COOKIE[$name] : "";

		// Removes cookie.
		setcookie($name, "", time() - 1);


		return $ret;
	}
}