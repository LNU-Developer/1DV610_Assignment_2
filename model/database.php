<?php
class Database {
    private $db;

    public function findUser($username, $password = null)
    {   $hashedPassword = md5($password);
        $password !== null ? $sql = "SELECT id FROM users WHERE username=? AND password=?" : $sql = "SELECT id FROM users WHERE username=?";
        $stmt = mysqli_stmt_init($this->db);
		if(!mysqli_stmt_prepare($stmt, $sql))
		{
			echo 'Failed';
		}
		else
		{
			$password !== null ? mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword) : mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

			if(mysqli_stmt_num_rows($stmt)>0)
			{
                return true;
			}
            else
            {
                return false;
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($this->db);
    }

    public function registerUser($username, $password)
    {
        $hashedPassword = md5($password);
        if($this->findUser($username) == false)
		{
		    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
			$stmt = mysqli_stmt_init($this->db);
			if(!mysqli_stmt_prepare($stmt, $sql))
			{
					echo 'Failed';
			}
			else
			{
				mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_store_result($stmt);
                return true;
            }
        }
        else
        {
            return false;
        }
        mysqli_stmt_close($stmt);
        mysqli_close($this->db);
                //TODO: real_escape_string
    }

    public function addCookie($username, $password)
    {
        $hashedPassword = md5($password);
        $sql = "SELECT id FROM users WHERE username=? AND password=?";
        $stmt = mysqli_stmt_init($this->db);
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            echo 'Failed';
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt)>0)
            {
                $sql = "UPDATE users SET cookiePassword=? WHERE username =?";
				$stmt = mysqli_stmt_init($this->db);
				if(!mysqli_stmt_prepare($stmt, $sql))
				{
					echo 'Failed';
				}
				else
				{
					mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $username);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    $_SESSION['cookiePassword'] = $hashedPassword;
                    return $hashedPassword;
                }
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