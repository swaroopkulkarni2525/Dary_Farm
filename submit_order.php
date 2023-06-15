<?php
class Order {
  private $pdo;

  public function __construct() {
    // define database connection constants
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'milkvilla');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');

    // create a database connection object
    $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
  }

  public function placeOrder($recivername, $reciveraddress, $phone, $email, $milk_type, $quantity, $delivery_date, $delivery_time) {
    // prepare a SQL statement to insert the form data into the milk_order table
    $stmt = $this->pdo->prepare("INSERT INTO milk_orders (recivername, reciveraddress, phone, email, milk_type, quantity, delivery_date, delivery_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // bind the form data to the SQL statement parameters
    $stmt->bindParam(1, $recivername);
    $stmt->bindParam(2, $reciveraddress);
    $stmt->bindParam(3, $phone);
    $stmt->bindParam(4, $email);
    $stmt->bindParam(5, $milk_type);
    $stmt->bindParam(6, $quantity);
    $stmt->bindParam(7, $delivery_date);
    $stmt->bindParam(8, $delivery_time);

    // execute the SQL statement to insert the form data into the milk_order table
    if ($stmt->execute() === TRUE) {
      echo "Order placed successfully";
      header('location:index.html');
    } else {
      echo "Error: " . $stmt->error;
    }
  }
}

// create a new Order object
$order = new Order();

// check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // place the order using the form data
  $result = $order->placeOrder($_POST['recivername'], $_POST['reciveraddress'], $_POST['phone'], $_POST['email'], $_POST['milk_type'], $_POST['quantity'], $_POST['delivery_date'], $_POST['delivery_time']);

  // display the result to the user
  echo $result;
}
?>
