<?php

class DateTimeView {


	public function show() {
		return '<p>' . date('l') . ", the " . date('jS \of F Y') . ", The time is " . date('h:i:s') . '</p>';
	}
}