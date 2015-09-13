<?php

class CookieStorage {
	public function save($name, $string) {
		// Parametrar(Kakans namn, kakans värde, hur länge ska den leva i sekunder -1 = så länge som möjligt.)
		setcookie($name, $string, -1);

		// var_dump(isset($_COOKIE[$name]) ? $_COOKIE[$name] : "");
		// die();

	}

	public function load($name) {
		// Om det finns annars tom sträng. Istället för att skriva if- else.
		$ret = isset($_COOKIE[$name]) ? $_COOKIE[$name] : "";



		// Tar bort kakan. 
		setcookie($name, "", time() - 1);


		return $ret;
	}
}