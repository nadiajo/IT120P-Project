<?php
include('Php/functions.php');
function mysqli_connect_db()
{
  $DATABASE_HOST = 'localhost';
  $DATABASE_USER = 'root';
  $DATABASE_PASS = '';
  $DATABASE_NAME = 'ivatanstore';
  $conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
  $user_id = $_SESSION['user_id'] ?? 0;

  if ($user_id === 0) {
    header("Location: login.php");
    exit;
  }

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

$conn = mysqli_connect_db();
$user_id = $_SESSION['user_id'];
$num_orders_on_each_page = 4;
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;

// Calculate the offset for the query
$offset = ($current_page - 1) * $num_orders_on_each_page;

// Modify existing SQL statement for getting orders
$stmt = $conn->prepare('
    SELECT o.*, pm.provider AS payment_method, a.street_address, a.city, a.region, os.status AS order_status
    FROM orders o
    JOIN user_payment_method pm ON o.payment_method_id = pm.id
    JOIN address a ON o.shipping_address_id = a.id
    JOIN order_status os ON o.order_status_id = os.id
    WHERE o.user_id = ?   -- Filtering orders by the current session user
    ORDER BY o.id DESC LIMIT ?, ?
');

$stmt->bind_param('iii', $user_id, $offset, $num_orders_on_each_page);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Fetch the total number of orders
$total_result = $conn->prepare('SELECT COUNT(*) as total FROM orders WHERE user_id = ?');
$total_result->bind_param('i', $user_id);
$total_result->execute();
$total_row = $total_result->get_result()->fetch_assoc();
$total_orders = $total_row['total'];
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>History</title>
  <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="Style/Order History style.css">
  <link rel="stylesheet" href="Style/bootstrap.min.css">
         
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="Style/styles1.css">
</head>

<body>
  <div class="navbar">
    <h1>Ivatan Store</h1>
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
      <?php foreach ($orders as $order) : ?>
        <div class="order-item">
          <div class="order-details">
            <div class="row">
              <div class="col-2">
              <span><b>Order ID:</b> <?= $order['id'] ?></span><br></div>
              <div class="col-3">
              <span><b>Payment Method:</b> <?= $order['payment_method'] ?></span><br></div>
              <div class="col-5">
              <span><b>Shipping Address:</b> <?= $order['street_address'] ?>, <?= $order['city'] ?>, <?= $order['region'] ?></span><br></div>
              <div class="col-sm-2">
              <span><b>Total:</b> ₱<?= $order['order_total'] ?></span><br></div>
              <div class="col-10">
              <span><b>Delivery Date:</b> <?= $order['delivery_date'] ?></span><br></div>
              <div class="col-2">
              <span><b>Status:</b> <?= $order['order_status'] ?></span></div>
          </div>
        </div>
          <a href="tracker page.php?order_id=<?= $order['id'] ?>" class="btn-view-order">View Order</a>
        </div>
      <?php endforeach; ?>
      <div class="buttons">
            <?php if ($current_page > 1) : ?>
                <a href="OrderHistoryPage.php?p=<?= $current_page - 1 ?>" class="button">Prev</a>
            <?php endif; ?>
            <?php if ($total_orders > ($current_page * $num_orders_on_each_page)) : ?>
                <a href="OrderHistoryPage.php?p=<?= $current_page + 1 ?>" class="button">Next</a>
            <?php endif; ?>  
        </div>
    </section>
  </main>

  <?= template_footer() ?>