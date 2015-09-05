<?php

class DateTimeView {

	public function show() {

		$timeString = date('l, \t\h\e jS \of F Y, \T\h\e \t\i\m\e \i\s G:i:s');

		return '<p>' . $timeString . '</p>';
	}
}