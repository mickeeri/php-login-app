<?php

class DateTimeView {

	/**
	 * Shows current date and time.
	 * @return string
	 */
	public function show() {
		date_default_timezone_set('Europe/Stockholm');
		
		$timeString = date('l, \t\h\e jS \of F Y, \T\h\e \t\i\m\e \i\s G:i:s');

		return '<p>' . $timeString . '</p>';
	}
}