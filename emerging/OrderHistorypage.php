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
  // The amounts of order histories to show on each page
  $num_orders_on_each_page = 8;
  // The current page - in the URL, will appear as index.php?page=OrderHistorypage&p=1, index.php?page=OrderHistorypage&p=2, etc...
  $current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
  // Select orders ordered by the date added
  $stmt = $pdo->prepare('SELECT * FROM orders ORDER BY id DESC LIMIT ?,?');
  // bindValue will allow us to use an integer in the SQL statement, which we need to use for the LIMIT clause
  $stmt->bindValue(1, ($current_page - 1) * $num_orders_on_each_page, PDO::PARAM_INT);
  $stmt->bindValue(2, $num_orders_on_each_page, PDO::PARAM_INT);
  $stmt->execute();
  // Fetch the orders from the database and return the result as an Array
  $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $total_orders = $pdo->query('SELECT * FROM orders')->rowCount();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>History</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="Style/Order History style.css">
  <link rel="stylesheet" href="Style/bootstrap.min.css">
  <link rel="stylesheet" href="Style/styles.css">
</head>

<body>
  <div class="navbar">
    <img src="Image/furco_logo.png" alt="FURCO Paw Logo" class="logo">
    <h1>FURCO</h1>
    <div class="search-container">
        <form action="search.php" method="get">
    <img src="Image/furco_search.png" alt="Search" class="search-icon">
    <input type="search" name="query" placeholder="Search" required>
</form>
</div>
    <?php require_once("Php/navigation.php"); ?>
  </div>

  <main>
    <h1>Order History</h1>
    <section class="order-list">
      <h2>Recent Orders</h2>
      <div class="order-item">
	  
		<?php foreach ($orders as $order) : ?>
		<a href="Tracker page.php">
            <div class="order-item">
                <div class="order-details">
				<!--Payment method, shipping address and status only 
				shows their id, not the actual value, not sure how to fix this-->	
                    <span><b>Date:</b> <?= $order['order_date'] ?></span>
                    <span><b>Payment Method:</b> <?= $order['payment_method_id'] ?></span>
                    <span><b>Shipping Address:</b> <?= $order['shipping_address_id'] ?></span>
                    <span><b>Total:</b> &dollar;<?= $order['order_total'] ?></span>
                    <span><b>Delivery Date:</b> <?= $order['delivery_date'] ?></span>
                    <span><b>Status:</b> <?= $order['order_status_id'] ?></span>
                </div>
            </div>
        <?php endforeach; ?>
		</a>
      </div>
      <button class="view-more">Next page</button>
    </section>
  </main>
  <?= template_footer() ?>