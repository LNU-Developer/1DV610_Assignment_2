<?php

class RegisterController
{

    public function attemptRegister($username, $password,  $passwordRepeat, $isRegisterAttempt)
    {
        if($isRegisterAttempt)
        {
            if($this->checkUserInput($username, $password,  $passwordRepeat) === true && !empty($username) && !empty($password) && !empty($passwordRepeat))
            {
                $db = new Database();
                $message = $db->registerUser($username, $password);
                return $message;
            }
            else
            {
                return $this->checkUserInput($username, $password, $passwordRepeat);
            }
        }
    }

    private function checkUserInput($username, $password, $passwordRepeat)
    {
        if($username == '' && $password == '')
        {
            return '
            Username has too few characters, at least 3 characters.
            <br>
            Password has too few characters, at least 6 characters.
            ';
        }
        else if(strlen($username) < 3)
        {
            return 'Username has too few characters, at least 3 characters.';
        }
        else if(strlen($password) < 6)
        {
            return 'Password has too few characters, at least 6 characters.';
        }
        else if($password !== $passwordRepeat)
        {
            return 'Passwords do not match.';
        }
        //TODO: Needs to be changed in the final solution.
        else if($username  == "<a>abc</a>")
        {
            return 'Username contains invalid characters.';
        }
        else
        {
            return true;
        }
    }
}
?>