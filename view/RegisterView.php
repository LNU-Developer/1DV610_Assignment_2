<?php

class RegisterView {
	private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
	private static $cookieName = 'RegisterView::CookieName';
	private static $cookiePassword = 'RegisterView::CookiePassword';
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
		if(isset($_POST[self::$name]) || isset($_POST[self::$password]))
		{
			if($_POST[self::$name] == "" && $_POST[self::$password] == "")
			{
				$message = '
				Username has too few characters, at least 3 characters.
				<br>
				Password has too few characters, at least 6 characters.
				';
			}
			else if(strlen($_POST[self::$name])<3)
			{
				$message = 'Username has too few characters, at least 3 characters.';
			}
			else if(strlen($_POST[self::$password]) < 6)
			{
				$message = 'Password has too few characters, at least 6 characters.';
			}
			else if($_POST[self::$password] !== $_POST[self::$passwordRepeat])
			{
				$message = 'Passwords do not match.';
			}
			//TODO: Needs to be changed in the final solution.
			else if($_POST[self::$name] == "<a>abc</a>")
			{
				$message = 'Username contains invalid characters.';
				$_POST[self::$name] = 'abc';
			}
			else {
				$db = mysqli_connect('localhost', 'root', '', 'assignment2');
				//TODO: real_escape_string

				$sql = "SELECT id FROM users WHERE username=?";
				$stmt = mysqli_stmt_init($db);
				if(!mysqli_stmt_prepare($stmt, $sql))
				{
					$message = 'Failed';
				}
				else
				{
					$username = $_POST[self::$name];
					mysqli_stmt_bind_param($stmt, "s", $username);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_store_result($stmt);

					if(mysqli_stmt_num_rows($stmt)>0)
					{
						$message = "User exists, pick another username.";
					}
					else
					{
						$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
						$stmt = mysqli_stmt_init($db);
						if(!mysqli_stmt_prepare($stmt, $sql))
						{
							$message = 'Failed';
						}
						else
						{
							$password = $_POST[self::$password];
							$password = md5($password);
							mysqli_stmt_bind_param($stmt, "ss", $username, $password);
							mysqli_stmt_execute($stmt);
							mysqli_stmt_store_result($stmt);
							$message = 'REGISTERED';

						}
					}
					mysqli_stmt_close($stmt);
					mysqli_close($db);}
			}
		}

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