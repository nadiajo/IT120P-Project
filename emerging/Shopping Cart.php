<?php
include('Php/functions.php');
include('Php/checkout.php');

$conn = db_connect();
$user_id = $_SESSION['user_id'] ?? 0;

// Redirect if user is not logged in
if ($user_id === 0) {
  header("Location: login.php");
  exit;
}

function parseSizeOptions($sizeOptions) {
  $options = explode(',', $sizeOptions);
  $sizesPrices = [];
  foreach ($options as $option) {
      list($size, $price) = explode(':', $option);
      $sizesPrices[trim($size)] = trim($price);
  }
  return $sizesPrices;
}

// Fetch cart items
$stmt = $conn->prepare("SELECT sci.id as cart_item_id, sci.qty, sci.size, sci.subscription, sci.price, p.size_options, p.is_for_subscription, p.name, p.brand, p.image_url FROM shopping_cart_item sci JOIN products p ON sci.product_item_id = p.id WHERE sci.cart_id = (SELECT id FROM shopping_cart WHERE user_id = ?)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch user payment methods
$stmt = $conn->prepare("SELECT id, card_name, card_number, expiration_date FROM user_payment_method WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$paymentMethods = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate total price
$totalPrice = array_sum(array_map(function ($item) {
  
    $selectedSize = $item['size'];
    $sizesPrices = parseSizeOptions($item['size_options']);
    $selectedPrice = $sizesPrices[$selectedSize] ?? 0;
  return $selectedPrice * $item['qty'];
  
}, $cartItems));

// Fetch user address details
$addressStmt = $conn->prepare("SELECT u.full_name, a.street_address, a.city, a.region, u.phone_number FROM users u JOIN user_address ua ON u.id = ua.user_id JOIN address a ON ua.address_id = a.id WHERE u.id = ? AND ua.is_default = 1");
$addressStmt->bind_param("i", $user_id);
$addressStmt->execute();
$userAddress = $addressStmt->get_result()->fetch_assoc();

// Handling POST requests for checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
  $conn->begin_transaction();

  try {
    // Retrieve the default shipping address ID for the user
    $addressQuery = $conn->prepare("SELECT a.id FROM address a JOIN user_address ua ON a.id = ua.address_id WHERE ua.user_id = ? AND ua.is_default = 1");
    $addressQuery->bind_param("i", $user_id);
    $addressQuery->execute();
    $addressResult = $addressQuery->get_result();
    $addressRow = $addressResult->fetch_assoc();

    if (!$addressRow) {
      throw new Exception("Default shipping address not found.");
    }

    $shipping_address_id = $addressRow['id'];
  
    // Check if new payment method details are provided
    if (!empty($_POST['newCardName'])) {
      $paymentInsert = $conn->prepare("INSERT INTO user_payment_method (user_id, provider, card_name, card_number, expiration_date, cvv) VALUES (?, ?, ?, ?, ?, ?)");
      $provider = 'Master Card'; // Example provider
      $paymentInsert->bind_param("isssss", $user_id, $provider, $_POST['newCardName'], $_POST['newCardNumber'], $_POST['newExpirationDate'], $_POST['newCvv']);
      $paymentInsert->execute();
      $payment_method_id = $conn->insert_id;
    } else {
      $payment_method_id = $_POST['paymentMethodId'];
    }

    // Fetch cart items
    $cartQuery = $conn->prepare("SELECT sci.*, p.is_for_subscription FROM shopping_cart_item sci JOIN products p ON sci.product_item_id = p.id WHERE sci.cart_id = (SELECT id FROM shopping_cart WHERE user_id = ?)");
    $cartQuery->bind_param("i", $user_id);
    $cartQuery->execute();
    $cartItems = $cartQuery->get_result()->fetch_all(MYSQLI_ASSOC);

    $totalPrice = $totalPrice+58;
    $order_status_id = 1;

    // Create a new order
    $delivery_date = new DateTime(); // Today's date
    $delivery_date->modify('+3 day'); // Adds 3 days

    // Prepare SQL to insert the order
    $orderInsert = $conn->prepare("INSERT INTO orders (user_id, order_date, payment_method_id, shipping_address_id, order_total, order_status_id, delivery_date) VALUES (?, NOW(), ?, ?, ?, ?, ?)");
    $orderInsert->bind_param("iiidis", $user_id, $payment_method_id, $shipping_address_id, $totalPrice, $order_status_id, $delivery_date->format('Y-m-d'));
    $orderInsert->execute();
    $orderId = $conn->insert_id;

    // Process each item in the cart
    foreach ($cartItems as $item) {
      $itemInsert = $conn->prepare("INSERT INTO order_items (order_id, product_id, qty) VALUES (?, ?, ?)");
      $itemInsert->bind_param("iii", $orderId, $item['product_item_id'], $item['qty']);
      $itemInsert->execute();

      // Handle subscription if applicable
      if ($item['is_for_subscription']) {
        $subscriptionInsert = $conn->prepare("INSERT INTO subscriptions (user_id, product_id, address_id, payment_method_id, start_date, qty) VALUES (?, ?, ?, ?, CURDATE(), ?)");
        $subscriptionInsert->bind_param("iiiii", $user_id, $item['product_item_id'], $shipping_address_id, $payment_method_id, $item['qty']);
        $subscriptionInsert->execute();
      }

      // Remove item from cart
      $removeItem = $conn->prepare("DELETE FROM shopping_cart_item WHERE id = ?");
      $removeItem->bind_param("i", $item['id']);
      $removeItem->execute();
    }

    $conn->commit();
    header("Location: success.php");
    exit;
  } catch (Exception $e) {
    $conn->rollback();
    echo "Error during checkout: " . $e->getMessage();
    exit;
  }
}

// Handling POST requests for item updates or removal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['remove'])) {
    $cart_item_id = $_POST['cart_item_id'];
    $stmt = $conn->prepare("DELETE FROM shopping_cart_item WHERE id = ?");
    $stmt->bind_param("i", $cart_item_id);
    if (!$stmt->execute()) {
      echo "Error removing item: " . $stmt->error;
    }
  } elseif (isset($_POST['update'])) {
    $cart_item_id = $_POST['cart_item_id'];
    $newQuantity = $_POST['quantity'];
    $newSize = $_POST['size'];
    
    
    $newSubscription = $_POST['subscription'];
    $stmt = $conn->prepare("UPDATE shopping_cart_item SET qty = ?, size = ?, subscription = ? WHERE id = ?");
    $stmt->bind_param("issi", $newQuantity, $newSize, $newSubscription, $cart_item_id);
    if (!$stmt->execute()) {
      echo "Error updating item: " . $stmt->error;
    }
  }
  header("Location: shopping cart.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Cart</title>
  <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="Style/History style.css">
  <link rel="stylesheet" href="Style/bootstrap.min.css">
  <link rel="stylesheet" href="Style/styles1.css">
  <link rel="stylesheet" href="Style/cart.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
  <div class="navbar">
    <h1>Ivatan Store</h1>
    <div class="search-container">
        <form action="search.php" method="get">
    <img src="Image/search.png" alt="Search" class="search-icon">
    <input type="search" name="query" placeholder="Search" required>
</form>
</div>
    <?php require_once("Php/navigation.php"); ?>
  </div>
  <!-- Start of shopping cart container -->
  <div class="container mt-5 p-3 rounded" id="cartcontainer">
    <div class="row">
      <div class="col-md-8">
        <h4 class="mb-0">Your Shopping Cart</h4></br>
        <h5 class="mb-0">You have <?= is_array($cartItems) ? count($cartItems) : 0 ?> items in your cart</h5>
        </br>

        <?php if (empty($cartItems)) : ?>
          <div class="alert alert-info" role="alert">
            Your shopping cart is empty. <a href="Products.php">Start adding some products!</a>
          </div>
        <?php else : ?>
          <?php foreach ($cartItems as $item) : 
            $selectedSize = $item['size'];
            $sizesPrices = parseSizeOptions($item['size_options']);
            $selectedPrice = $sizesPrices[$selectedSize] ?? 0;
            $cartItemPrice = $selectedPrice * $item['qty']; //Calculate cart item price with qty
            ?>

            <div class="card mb-3 items">
              <div class="card-body rounded">
                <div class="row">
                  <div class="d-flex align-items-center justify-content-center col-md-3" id="cart_card">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" class="img-fluid" alt="<?= htmlspecialchars($item['name']) ?>">
                  </div>
                  <div class="col-md-9">
                    <div class="product-info">
                    <div class="row">

                      <div class="col-6">
                      <h5 class="card-title"><?= htmlspecialchars($item['name']) ?> - <?= $item['brand'] ?? 'No Brand' ?></h5>
                      </div>
                      <div class="col-6">
                      <div class="float-right"><h5 class="mb-0">&#8369;<?= htmlspecialchars($cartItemPrice)?></h5></div>
          </div>
                      
                    </div>
                      <form action="shopping cart.php" method="post">
                        <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                        <div class="product-details">
                          <div class="row">

                          <div class="col-md-4">
                          <label>Quantity:</label>
                          <input type="number" name="quantity" class="quantity_input form-control mb-2" value="<?= $item['qty'] ?>" min="1" required>
                           </div>

                           <div class="col-md-4">
                          <label>Size:</label>
                          <select name="size" id="size" class="size_input form-control mb-2" >
                            <?php 
                            $sizesPrices = parseSizeOptions($item['size_options']);

                            foreach ($sizesPrices as $size => $price) : ?>
                                <option value="<?= $size ?>" <?= $size == $item['size'] ? 'selected' : '' ?> data-price="<?= $price ?>">
                                    <?= $size ?>
                                </option>
                            <?php endforeach; ?>
                          </select>
                            </div>
                            
                            </div>
                        </div> <!-- Franz -->
                        <div class="button-group" style="display: flex;">
                         <button type="submit" name="update" class="btn btn-primary mb-2" style="margin-top: 30px; margin-left: 10px; background-color: black;" >Update</button>
                          <button type="submit" name="remove" class="fa fa-trash-o ml-3 text-black-50" style="font-size:30px; border: none; background: none; margin-top: 20px;"></button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <?php endforeach; ?>
        <?php endif; ?>
      </div>


      <!-- Payment-->
      <div class="col-md-4">
        <div class="delivery-address-container rounded">
          <div class="delivery-address-details">
          <div class="delivery-address-header">
            <h2>Delivery Address</h2>
          </div>
            <p><strong>Full Name:</strong> <span><?= htmlspecialchars($userAddress['full_name'] ?? 'N/A') ?></span></p>
            <p><strong>Street Address:</strong> <span><?= htmlspecialchars($userAddress['street_address'] ?? 'N/A') ?></span></p>
            <p><strong>City:</strong> <span><?= htmlspecialchars($userAddress['city'] ?? 'N/A') ?></span></p>
            <p><strong>Region:</strong> <span><?= htmlspecialchars($userAddress['region'] ?? 'N/A') ?></span></p>
            <p><strong>Phone Number:</strong> <span><?= htmlspecialchars($userAddress['phone_number'] ?? 'N/A') ?></span></p>
            <button onclick="window.location.href='user profile.php'" style="background-color: black; color: white;  border-radius: 8px; width: 70px; padding: 5px 10px; font-size: 15px; letter-spacing: 1px; cursor: pointer;" >EDIT</button>
          </div>
        </div>
        <div class="payment-method-container">
          <h3>Payment Method</h3>
          <div id="dropin-container">
            <div class="payment-methods d-flex align-items-center justify-content-center">
            <label style="margin-right: 10px;" class="radio"> <input type="radio" name="card" value="payment"> <span><img width="40" height="35" src="https://img.icons8.com/officel/48/000000/visa.png"/></span> </label>
            <label style="margin-right: 10px;" class="radio"> <input type="radio" name="card" value="payment"> <span><img width="40" height="35" src="https://1000logos.net/wp-content/uploads/2023/05/GCash-Logo-tumb.png"/></span> </label>
            <label style="margin-right: 10px;" class="radio"> <input type="radio" name="card" value="payment"> <span><img width="40" height="35" src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png"/></span> </label>
            </div>
            <form action="shopping cart.php" method="post">
              <!-- Template form elements -->
              <!-- Dropdown for saved methods -->
              <!--<select name="paymentMethodId" id="paymentMethod" class="form-control" required>
                <option value="">Select a saved method</option>
                <?php foreach ($paymentMethods as $method) : ?>
                  <option value="<?= $method['id'] ?>">
                    <?= htmlspecialchars($method['card_name']) ?> ending in <?= substr(htmlspecialchars($method['card_number']), -4) ?> (Exp: <?= htmlspecialchars($method['expiration_date']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>-->

              <!-- Template payment method input fields -->
              <!-- Franz -->


              <div class="card">
    <div class="card-body">
        <form>
                <label for="newCardName">Card Name:</label>
                <input type="text" name="newCardName" id="newCardNames" class="form-control" placeholder=" " required>

                <label for="newCardNumber">Card Number:</label>
                <input type="text" name="newCardNumber" id="newCardNumber" class="form-control" placeholder="XXXX-XXXX-XXXX" required>

                <div class="row">
    <div class="col">
        <label for="newExpirationDate">Expiration Date:</label>
        <input type="text" name="newExpirationDate" id="newExpirationDate" class="form-control exp_date" placeholder="MM/YY" required>
    </div>
    <div class="col">
        <label for="newCvv">CVV:</label>
        <input type="text" name="newCvv" id="newCvv" class="form-control new_cvv" placeholder="123" required> <br>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row shipping-row">
            <div class="col-6">
                <h6 class="mb-0">Shipping Fee</h6>
            </div>
            <?php $shippingFee = 58; ?>
            <div class="col-6">
                <div class="float-right"><h6 class="mb-0">&#8369;<?=htmlspecialchars($shippingFee)?></h6></div>
            </div>
        </div>

        <div class="row price-row">
            <div class="col-6">
                <h6 class="mb-0">Total Price</h6>
            </div>

            <div class="col-6">
                <div class="float-right"><h6 class="mb-0">&#8369;<?=htmlspecialchars($totalPrice+$shippingFee)?></h6></div> <br>
            </div>
        </div> <br>
        <div class="text-center">
        <button type="submit" name="checkout" class="checkout-buttons" style="background-color: #db4444; color: white; border: 2px solid red; border-radius: 8px; width: 180px; padding: 10px 20px; font-size: 15px; letter-spacing: 1px; cursor: pointer; transition: background-color 0.3s, color 0.3s, border-color 0.3s;">Checkout</button>
                </div>
    </div>
</div>

        </form>
    </div>
</div>



            <!-- payment gateway integration here -->
            <?php $totalPrice ?>
          </div>
        </div>
      </div>

    </div>
  </div>


      <?= template_footer() ?>
    </body>
    </html>