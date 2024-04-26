<?php 
include('Php/functions.php'); 
$conn = db_connect();
$user_id = $_SESSION['user_id'] ?? 0; // Ensure you handle the case where session is not set


// Handling removal and quantity, size, subscription updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['remove'])) {
    $cart_id = $_POST['cart_id'];
    // Delete based on cart_id, not product_id
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
  } elseif (isset($_POST['update'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $subscription = $_POST['subscription'];
    
    // Now, execute your prepared statement with these variables
    $stmt = $conn->prepare("UPDATE cart SET quantity = ?, size = ?, subscription = ? WHERE id = ?");
    $stmt->bind_param("issi", $quantity, $size, $subscription, $cart_id);
    $stmt->execute();
}


  // Refresh page to reflect changes
  header("Location: shopping cart.php");
  exit;
}



// Fetch cart items after handling POST to ensure updated data is fetched
// Fetch cart items
$cartQuery = "SELECT c.id as cart_id, p.*, c.quantity, c.size, c.subscription FROM products p INNER JOIN cart c ON p.id = c.product_id WHERE c.user_id = ?";
$stmt = $conn->prepare($cartQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


// Calculate total price
$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Fetch user's address and other details
$addressQuery = "SELECT full_name, street_address, phone_number FROM users WHERE id = ?";
$addressStmt = $conn->prepare($addressQuery);
$addressStmt->bind_param("i", $user_id);
$addressStmt->execute();
$addressResult = $addressStmt->get_result();
$userAddress = $addressResult->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Cart</title>
  <link rel="icon" type="image/x-icon" href="Image/furco_logo.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="Style/History style.css">
  <link rel="stylesheet" href="Style/bootstrap.min.css">
  <link rel="stylesheet" href="Style/style.css">
  <link rel="stylesheet" href="Style/cart.css">
  <link rel="stylesheet" href="Style/checkout.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
    
  <div class="container mt-5 p-3 rounded cart" id="cartcontainer">
    <div class="column">
      <div class="col-md-8">
        <h6 class="mb-0">Shopping cart</h6>
        <div>
          You have <?= is_array($cartItems) ? count($cartItems) : 0 ?> items in your cart</div>
        <?php if (is_array($cartItems)): ?>
          <?php foreach ($cartItems as $item): ?>
            <div class="row"> 
            <div class="item-container mt-3 p-2 rounded">
              

  <!-- Franz Severino -->
  <div class="box">
            <div class="item-container">
    <div class="item-image">
        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
    </div>

    <div class="item-details">
        <span class="item-name"><?= htmlspecialchars($item['name']) ?></span>
        <!-- <span class="item-description"><?= htmlspecialchars($item['brand']) ?> - <?= htmlspecialchars($item['size']) ?> - <?= htmlspecialchars($item['subscription']) ?></span> -->
        <form action="shopping cart.php" method="post">
            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
        </form>
    </div>
</div>        
            <div class="form-group">
              <!-- <label for="quantity">Qty:</label> -->
             <input type="number" name="quantity" class="form-control" value="<?= $item['quantity'] ?>">
            </div>
            <div class="update-btn">
                  <button type="submit" name="update" class="btn btn-info">Update</button>
          </div>
          <div class="delete-btn">
                  <button type="submit" name="remove" class="fa fa-trash-o ml-3 text-black-50" style="border: none; background: none;"></button>
          </div>
                  </form>
                <span class="item-price">&#8369;<?= number_format($item['price'], 2) ?></span>
              </div>
            </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
          </div>
      <div class="col-md-4">
        <div class="delivery-address-container">
          <div class="delivery-address-header">
            <h2>Cart Total</h2> 
          </div>
            <hr class="line">
            <div class="d-flex justify-content-between information"><span>Subtotal</span><span>&#8369;430</span></div>
            <div class="d-flex justify-content-between information"><span>Shipping</span><span>&#8369;50</span></div>
            <div class="d-flex justify-content-between information" id="total_price"><span>Total(Incl. taxes)</span><span>&#8369;480</span></div><button class="btn btn-primary btn-block d-flex justify-content-between mt-3" id="checkout_button" type="button"><span>&#8369;480</span><span>Checkout<i class="fa fa-long-arrow-right ml-1"></i></span></button></div>
          </div>
        </div>
      </div>
      <?= template_footer() ?>
    </body>
    </html>