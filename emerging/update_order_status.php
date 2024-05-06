<?php
include('Php/functions.php');
if (isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['user']);
  header("location: login.php");
}
function pdo_connect_mysql()
{
    // Update the details below with your MySQL details
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    global $DATABASE_NAME;
    $DATABASE_NAME = 'furco';
    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to database!');
    }
}

$pdo = pdo_connect_mysql();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Prepare SQL statement to update order status in the orders table
        $stmt = $pdo->prepare('UPDATE orders SET order_status_id = :status WHERE id = :id');

        // Iterate through status array and update database
        foreach ($_POST['status'] as $id => $status) {
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                // Handle error
                echo "Error updating order with ID $id: " . $stmt->errorInfo()[2];
            }
        }

        // Redirect the user
        if (isset($_POST['redirect'])) {
            header("Location: " . $_POST['redirect']);
            exit;
        }
    } catch (PDOException $e) {
        // Handle PDO exception
        echo "PDO Exception: " . $e->getMessage();
    }
}
?>