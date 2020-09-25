<?php
class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $cookieName = 'LoginView::CookieName';
    private static $cookiePassword = 'LoginView::CookiePassword';

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$loginController = new LoginController;

		$username = !empty($_POST[self::$name]) ? $_POST[self::$name] :'';
		$password = !empty($_POST[self::$password]) ? $_POST[self::$password]: '';
		$message = isset($_POST['LoginView::Logout']) ? $loginController->logout() : $loginController->attemptLogin($username, $password, isset($_POST[self::$login]), isset($_POST[self::$keep]));

		if($loginController->checkIfLoggedIn() && !isset($_POST['LoginView::Logout']))
		{
			return $this->generateLogoutButtonHTML($message);
		}
		else
		{
			return $this->generateLoginFormHTML($message);
		}
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		if(!empty($_SESSION['message']))
		{
			$message = $_SESSION['message'];
			$_SESSION['message'] = '';
		}
		return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="'  . $this->getRequestUserName() .  '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />

					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName() {
		if(!empty($_SESSION['registeredUsername']))
		{
			$_POST[self::$name] = $_SESSION['registeredUsername'];
			$_SESSION['registeredUsername'] = '';
		}
		if(isset($_POST[self::$name]))
			{
				return $_POST[self::$name];
			}
			return "";
	}
}
?>