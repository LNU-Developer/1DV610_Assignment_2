<?php
 session_start();
 $_SESSION['message'] ="";
//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');
require_once('controller/LoginController.php');
require_once('controller/RegisterController.php');
require_once('model/Database.php');

if(isset($_COOKIE['LoginView::CookieName']) && isset($_COOKIE['LoginView::CookiePassword']))
{
    if($_COOKIE['LoginView::CookieName'] && $_COOKIE['LoginView::CookiePassword'])
    {
        $_SESSION['UserName'] = $_COOKIE['LoginView::CookieName'];
        $_SESSION['Password'] = $_COOKIE['LoginView::CookiePassword'];
        $_SESSION['message'] = 'Welcome back with cookie';
    }
}

if(isset($_POST['LoginView::Logout']))
{
    if (isset($_SESSION['UserName']))
    {
        $_SESSION['message'] = "Bye bye!";
        unset($_COOKIE['LoginView::CookieName']);
        unset($_COOKIE['LoginView::CookiePassword']);
    }
    session_destroy();
}

else if(isset($_POST['LoginView::Login']))
{
    if(isset($_SESSION['UserName']) &&  isset($_POST['LoginView::KeepMeLoggedIn']))
    {
        $_SESSION['message'] = "Welcome and you will be rembered";
    }
    else if(isset($_SESSION['UserName']))
    {
        $_SESSION['message'] = "Welcome";
    }
    else
    {
        $_SESSION['message'] = "";
    }
}




//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$v = new LoginView();
$dtv = new DateTimeView();
$lv = new LayoutView();
$rv = new RegisterView();
$loginController = new LoginController();
$isloggedIn = $loginController->checkIfLoggedIn();

if(isset($_GET['register']))
{
    $lv->render($isloggedIn, $rv, $dtv);
}
else
{
    $lv->render($isloggedIn, $v, $dtv);
}
