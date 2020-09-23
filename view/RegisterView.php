<?php
class RegisterView {
	private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
	private static $messageId = 'RegisterView::Message';
	private static $doRegistration = 'RegisterView::Register';

	/**
	 * Create HTTP response
	 *
	 * Should be called after a registration attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message = '';

		$username = !empty($_POST[self::$name]) ? $_POST[self::$name] :'';
		$password = !empty($_POST[self::$password]) ? $_POST[self::$password]: '';
		$passwordRepeat = !empty($_POST[self::$passwordRepeat]) ? $_POST[self::$passwordRepeat] : '';

		$RegisterController = new RegisterController();
		$message = $RegisterController->attemptRegister($username, $password, $passwordRepeat, isset($_POST[self::$doRegistration]));
		$response = $this->generateRegisterFormHTML($message);
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the registration button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateRegisterFormHTML($message) {
        return '
            <h2>Register new user</h2>
			<form method="post" >
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
                    <p id="' . self::$messageId . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="'  . $this->getRequestUserName() .  '" />
                    <br>
					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
                    <br>
					<label for="' . self::$passwordRepeat . '">Repeat password :</label>
					<input type="password" id="' . self::$passwordRepeat . '" name="' . self::$passwordRepeat . '" />
                    <br>
					<input type="submit" name="' . self::$doRegistration . '" value="Register" />
				</fieldset>
			</form>
		';
	}

	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName() {
		if(isset($_POST[self::$name]))
			{
				return $_POST[self::$name];
			}
			return "";
	}
}