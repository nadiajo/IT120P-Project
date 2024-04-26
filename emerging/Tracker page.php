<?php 
include('Php/functions.php');

if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['user']);
        header("location: login.php");
}
$conn = db_connect();
$user_id = $_SESSION['user_id'] ?? 0; // Ensure you handle the case where session is not set

$cartQuery = "SELECT c.id as cart_id, p.*, c.quantity, c.size, c.subscription FROM products p INNER JOIN cart c ON p.id = c.product_id WHERE c.user_id = ?";
$stmt = $conn->prepare($cartQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate total price
$finalPrice = 0;
$shippingFee = 33;
foreach ($cartItems as $item) {
    $finalPrice += $item['price'] * $item['quantity'];
    $finalPrice += $shippingFee;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Tracker</title>
  <link rel="stylesheet" href="Style/Trackerstyle.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
  </head>
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
<body>
<div class="container mt-5 p-5 rounded" id="trackercontainer">
    <div class="card">
            <div class="title">PURCHASE RECEIPT</div>
            <div class="info">
                <div class="row">

                    <div class="col-9">
                        <span id="heading">Date</span><br> <!--Order date here -->
                        <span id="details">10 October 2018</span>
                    </div>
                    <div class="col-3">
                        <span id="heading">Order No.</span><br><!--Order ID here -->
                        <span id="details">012j1gvs356c</span>
                    </div>
                </div>      
            </div>      
            <div class="pricing">
                <div class="row">
                <?php if (is_array($cartItems)): ?>
                    <?php foreach ($cartItems as $item):
                        $totalPrice = 0;
                        $totalPrice += $item['price'] * $item['quantity'];
                        ?>
                    <div class="col-9"><!--Shopping cart items here -->
                    <!-- Currently using the products from the "cart" table for now, please change if needed -->
                        <span id="name"><?= htmlspecialchars($item['brand']) ?> - <?= htmlspecialchars($item['name'])?> (<?= $item['quantity'] ?>)</span>  
                    </div>

                    <div class="col-3">
                        <span id="price">&#8369;<?= htmlspecialchars($totalPrice) ?></span>
                    </div>
                    <?php endforeach; ?> 
                <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-9">
                        <span id="name">Shipping fee</span>
                    </div>
                    <div class="col-3">
                        <span id="price">&#8369;33.00</span>
                    </div>
                </div>
            </div>
            <div class="total">
                <div class="row">
                    <div class="col-9"><span id="name">Total</span></div>
                    <div class="col-3"><big>&#8369;<?= htmlspecialchars($finalPrice) ?></big></div>
                </div>
            </div>
            <div class="tracking">
                <div class="title">TRACK YOUR ORDER</div>
            </div>
            <div class="progress-track">
                <ul id="progressbar">
                    <li class="step0 active " id="step1">Ordered</li>
                    <li class="step0 active text-center" id="step2">Shipped</li>
                    <li class="step0 active text-right" id="step3">On the way</li>
                    <li class="step0 text-right" id="step4">Delivered</li>
                </ul>
            </div>  
        </div>
    </div>   
</body>
<?= template_footer() ?>