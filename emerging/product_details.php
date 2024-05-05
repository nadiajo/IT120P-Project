<?php
include('Php/functions.php');
$conn = db_connect();
$product_id = $_GET['id'] ?? null;

// Fetching the product details
$product = getProductDetails($conn, $product_id);
if (!$product) {
    header("Location: products.php");
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

$sizesPrices = parseSizeOptions($product['size_options']);

function getProductDetails($conn, $product_id)
{
    $stmt = $conn->prepare("SELECT *, IFNULL(is_for_subscription, 0) as is_for_subscription FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function addToCart($conn, $product_id, $user_id, $quantity, $size, $subscription, $price) 
{
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("SELECT id FROM shopping_cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // No cart exists, create a new one
            $stmt = $conn->prepare("INSERT INTO shopping_cart (user_id) VALUES (?)");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $cart_id = $conn->insert_id; // Retrieve the newly created cart ID
        } else {
            // Cart exists, use the existing cart ID
            $cart_id = $result->fetch_assoc()['id'];
        }

        // Prepare to insert a new item into the shopping_cart_item table
        $stmt = $conn->prepare("INSERT INTO shopping_cart_item (cart_id, product_item_id, qty, size, subscription, price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiissd", $cart_id, $product_id, $quantity, $size, $subscription, $price);
        $stmt->execute();
        $conn->commit();
    } catch (mysqli_sql_exception $e) {
        // If an error occurs, roll back the transaction and handle the error
        $conn->rollback();
        return $e->getMessage(); // Return the error message for further handling
    }

    return null; // Indicate success with no errors
}

// If form data has been submitted, add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null; // Ensure user is logged in
    $quantity = $_POST['quantity'] ?? 1;

    $selectedSize = $_POST['size'];
    $sizesPrices = parseSizeOptions($product['size_options']);
    $selectedPrice = $sizesPrices[$selectedSize] ?? 0;

    $subscription = (isset($product['is_for_subscription']) && $product['is_for_subscription'] && isset($_POST['subscription'])) ? $_POST['subscription'] : null;
    if ($user_id) {
        $error = addToCart($conn, $product_id, $user_id, $quantity, $selectedSize, $subscription, $selectedPrice);
        if (!$error) {
           

            header("Location: shopping cart.php"); // Redirect to show success
            exit;
        } else {
            $_SESSION['flash_error'] = 'Error adding item to cart: ' . $error;
            header("Location: product_details.php?id=" . $product_id); // Stay on page to show error
            exit;
        }
    } else {
        header("Location: login.php"); // Redirect to login if not logged in
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<title>Product Details</title>
        <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">

        <!--nadia start-->
        <link rel="stylesheet" href="Style/styles1.css">
        <link rel="stylesheet" href="Style/productdetails.css">
        <link rel="stylesheet" href="Style/products.css">
        <!--nadia end-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
    <div class="large-container">
        <main class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="image-wrapper">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image" />
                    </div>
                </div>
                <div class="details-column">
                    <h1><?= htmlspecialchars($product['name']) ?></h1> <br>
                    <p class="product_type">Category: <b><?= htmlspecialchars($product['product_type']) ?></b></p>
                    <p class="product_type" id="priceDisplay">Price: <b><span id="priceSpan"><?= current($sizesPrices) ?></span></b></p> <br>
                    <div class="row">
                <div class="description">
                    <p><?= htmlspecialchars($product['description']) ?></p>
                </div>
            </div>
                    <hr>
                    <form id="add-to-cart-form" method="post" action="">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                        <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="size">Size:</label>
            <select name="size" id="size" class="form-control" onchange="updatePrice()">
                <?php foreach ($sizesPrices as $size => $price) : ?>
                    <option value="<?= $size ?>" data-price="<?= $price ?>">
                        <?= $size ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" class="form-control" value="<?= $_POST['quantity'] ?? 1 ?>" min="1" required>
        </div>
    </div>
</div>
                        
                        <div class="text-center">
                         <button type="submit" name="add_to_cart" class="btn btn-primary" style="width: 250px; background-color: #db4444; color: white; border-color: red;">Add to Cart</button> <br> <br> <br>
                        </div>
                        <div class="card">
    <section class="delivery-customer_service-moneyback text-center">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="delivery d-inline-block">
                    <img src="Image/delivery.png" alt="Fast Delivery" width="100" height="100">
                    <h5><b>FREE AND FAST DELIVERY</b></h5>
                    <p>Free delivery for all orders over ₱3,000.00</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="customer-service d-inline-block">
                    <img src="Image/customer service.png" alt="Fast Delivery" width="100" height="100">
                    <h5><b>24/7 CUSTOMER SERVICE</b></h5>
                    <p>Friendly 24/7 customer support</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="money-back d-inline-block">
                    <img src="Image/guarantee.png" alt="Fast Delivery" width="100" height="100">
                    <h5><b>MONEY BACK GUARANTEE</b></h5>
                    <p>We return money within 30 days</p>
                </div>
            </div>
        </div>
    </div>
    <break>
</section>
                    </form>

                </div>
            </div>
            <hr>
        </main>
    </div>
    <?= template_footer() ?>
          </body>

          <script>
function updatePrice() {
    var sizeSelect = document.getElementById('size');
    var priceSpan = document.getElementById('priceSpan');
    var selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
    priceSpan.textContent = selectedOption.getAttribute('data-price');
}
</script>


          </html>