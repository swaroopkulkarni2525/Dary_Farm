<?php
session_start();

class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'milkvilla';
    private $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->host, $this->user, $this->password);
        mysqli_select_db($this->conn, $this->database);
    }

    public function query($sql) {
        return mysqli_query($this->conn, $sql);
    }

    public function close() {
        mysqli_close($this->conn);
    }
}

class User {
    private $name;
    private $password;

    public function __construct($name, $password) {
        $this->name = $name;
        $this->password = $password;
    }

    public function authenticate() {
        $db = new Database();
        $sql = "SELECT * FROM users WHERE u_name='{$this->name}' AND password1='{$this->password}'";
        $result = $db->query($sql);
        $num = mysqli_num_rows($result);
        $db->close()

        if ($num == 1) {
            $_SESSION['name'] = $this->name;
            header('location:index.html');
        } else {
            $msg = "Warning! Incorrect Username or Password";
            $msgEncoded = base64_encode($msg);
            header("location:index.html?msg={$msgEncoded}");
        }
    }
}

$name = $_POST['name'];
$password = $_POST['pass'];

$user = new User($name, $password);
$user->authenticate();
?>
