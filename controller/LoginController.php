<?php

class LoginController
{
    private static $cookieName = 'LoginView::CookieName';
    private static $cookiePassword = 'LoginView::CookiePassword';

    public function checkIfLoggedIn()
    {
        if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true)
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
            if($this->checkUserInput($username, $password) === true && !empty($username) && !empty($password))
            {
                $db = new Database();
                $message = $db->loginUser($username, $password);

                if($this->checkIfLoggedIn() && $stayLoggedIn)
                {
                    $message = 'Welcome and you will be rembered';
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
        else
        {
            if(isset($_COOKIE['LoginView::CookieName']) && isset($_COOKIE['LoginView::CookiePassword']))
            {
                if($_COOKIE['LoginView::CookieName'] && $_COOKIE['LoginView::CookiePassword'])
                {
                    $_SESSION['UserName'] = $_COOKIE['LoginView::CookieName'];
                    $_SESSION['Password'] = $_COOKIE['LoginView::CookiePassword'];
                    return 'Welcome back with cookie';
                }
            }
        }
    }

    public function logout()
    {
        if(isset($_POST['LoginView::Logout']))
        {
            if (isset($_SESSION['UserName']))
            {

                setcookie('LoginView::CookieName', "", time() - 3600);
                setcookie('LoginView::CookiePassword', "", time() - 3600);
                session_destroy();
                session_start();
                return 'Bye bye!';
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
?>