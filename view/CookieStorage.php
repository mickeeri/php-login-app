<?php

class CookieStorage {
	public function save($string) {
		// Parametrar(Kakans namn, kakans värde, hur länge ska den leva i sekunder -1 = så länge som möjligt.)
		setcookie("CookieStorage", $string, -1);
	}

	public function load() {
		// Om det finns annars tom sträng. Istället för att skriva if- else.
		$ret = isset($_COOKIE["CookieStorage"]) ? $_COOKIE["CookieStorage"] : "";

		// Tar bort kakan. 
		setcookie("CookieStorage", "", time() - 1);

		return $ret;
	}
}