<?php

class DatabaseConnection {
    private $dbhost;
    private $dbuser;
    private $dbpass;
    private $dbname;
    private $conn;

    public function __construct($dbhost, $dbuser, $dbpass, $dbname) {
        $this->dbhost = $dbhost;
        $this->dbuser = $dbuser;
        $this->dbpass = $dbpass;
        $this->dbname = $dbname;
        $this->connect();
    }

    public function connect() {
        $this->conn = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function query($sql) {
        return mysqli_query($this->conn, $sql);
    }

    public function escape($value) {
        return mysqli_real_escape_string($this->conn, $value);
    }

    public function close() {
        mysqli_close($this->conn);
    }
}

class User {
    private $db;
    private $name;
    private $email;
    private $password;

    public function __construct(DatabaseConnection $db) {
        $this->db = $db;
    }

    public function setName($name) {
        $this->name = $this->db->escape($name);
    }

    public function setEmail($email) {
        $this->email = $this->db->escape($email);
    }

    public function setPassword($password) {
        $this->password = $this->db->escape($password);
    }

    public function save() {
        $sql = "INSERT INTO users (u_name, email, password1) VALUES ('$this->name', '$this->email', '$this->password')";
        if ($this->db->query($sql)) {
            // Redirect to login page
            header('Location: login.html');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($this->db);
        }
    }
}

// Create a new database connection
$db = new DatabaseConnection('localhost', 'root', '', 'milkvilla');

// Create a new user
$user = new User($db);

// Retrieve the form data and set it on the user object
$user->setName($_POST['u_name']);
$user->setEmail($_POST['email']);
$user->setPassword($_POST['password1']);

// Save the user to the database
$user->save();

// Close the database connection
$db->close();

?>
