<?php

class LoginController
{
    private static $cookieName = 'LoginView::CookieName';
    private static $cookiePassword = 'LoginView::CookiePassword';

    public function checkIfLoggedIn()
    {
        if(!empty($_SESSION['UserName']) && ($_SESSION['Password']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function attemptLogin($username, $password, $isLoginAttempt, $stayLoggedIn)
    {
        if($isLoginAttempt)
        {
            $db = new Database();
            if($this->checkUserInput($username, $password) === true && !empty($username) && !empty($password))
            {
                $message = $db->loginUser($username, $password);
                if($this->checkIfLoggedIn())
                {
                    $private_key = "!$//%$$//%$&=§$!&%&=§$!&%";
			        setcookie(self::$cookieName, $_SESSION['UserName'], time() + (86400 * 30), "/" );
			        setcookie(self::$cookiePassword, md5($password.$private_key), time() + (86400 * 30), "/" );
                }
                return $message;
            }
            else
            {
                return $this->checkUserInput($username, $password);
            }
        }
    }

    private function checkUserInput($username, $password)
    {
        if(empty($username))
        {
            return 'Username is missing';
        }
        else if(empty($password))
        {
            return 'Password is missing';
        }
        else
        {
            return true;
        }
    }
}