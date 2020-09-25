<?php
class Database {
    private $db;

    public function registerUser($username, $password)
    {
        $sql = "SELECT id FROM users WHERE username=?";
		$stmt = mysqli_stmt_init($this->db);
		if(!mysqli_stmt_prepare($stmt, $sql))
		{
			echo 'Failed';
		}
		else
		{
			mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);

			if(mysqli_stmt_num_rows($stmt)>0)
			{
                return false;
			}
			else
			{
		        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
				$stmt = mysqli_stmt_init($this->db);
				if(!mysqli_stmt_prepare($stmt, $sql))
				{
					echo 'Failed';
				}
				else
				{
					$password = md5($password);
					mysqli_stmt_bind_param($stmt, "ss", $username, $password);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_store_result($stmt);
                    return true;
                }
            }
                mysqli_stmt_close($stmt);
                mysqli_close($this->db);
            }
                //TODO: real_escape_string
    }

    public function loginUser($username, $password)
    {
        //TODO: real_escape_string
        $password = md5($password);
        $sql = "SELECT id FROM users WHERE username=? AND password=?";
        $stmt = mysqli_stmt_init($this->db);
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            echo 'Failed';
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt)>0)
            {
                $_SESSION['UserName'] = $username;
                $_SESSION['Password'] = $password;
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['browserInfo'] = $_SERVER['HTTP_USER_AGENT'];
                return true;
            }
            else
            {
                return false;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($db);
    }

    public function __construct()
    {
        $this->db = mysqli_connect('localhost', 'root', '', 'assignment2');
    }
}
?>