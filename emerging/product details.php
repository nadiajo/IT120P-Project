<?php
include('Php/functions.php');
$conn = db_connect();

function getProductDetails($conn, $product_id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function addToCart($conn, $product_id, $user_id, $quantity, $size, $subscription) {
    // Insert into the cart table
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, size, subscription) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $user_id, $product_id, $quantity, $size, $subscription);
    if ($stmt->execute()) {
        // Success
        $_SESSION['flash'] = 'Item added to cart successfully!';
    } else {
        // Error
        $_SESSION['error'] = "Failed to add item to cart: " . $stmt->error;
    }
}


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = getProductDetails($conn, $product_id);
    if (!$product) {
        header("Location: products.php");
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        $size = $_POST['size'] ?? 'default_size';
        $subscription = $_POST['subscription'] ?? 'no_subscription';
        
        if ($user_id) {
            addToCart($conn, $product_id, $user_id, $quantity, $size, $subscription);
            // Check for errors
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
        } else {
            header("Location: login.php");
            exit;
        }
    }    
} else {
    header("Location: products.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<title>Product Details</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
        <link rel="stylesheet" href="Style/styles.css">
        <link rel="stylesheet" href="Style/product details.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
<div class="navbar">
        <img src="Image/furcologo.svg" alt="FURCO Paw Logo" class="logo">
        <h1>FURCO</h1>
        <div class="search-container">
        <form action="search.php" method="get">
    <img src="Image/furco_search.png" alt="Search" class="search-icon">
    <input type="search" name="query" placeholder="Search" required>
</form>
</div>
        <?php require_once("Php/navigation.php"); ?>
    </div>
    <div class="container">
    <main class="content">
    <?php if(isset($_SESSION['flash'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['flash']; ?>
                <?php unset($_SESSION['flash']); ?>
            </div>
        <?php endif; ?>
    <div class="row">
        <div class="image-column">
            <div class="image-wrapper">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image"/>
            </div>
        </div>
        <div class="details-column">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p class="brand">Brand: <?= htmlspecialchars($product['brand']) ?></p>
            <p class="category">Category: <?= htmlspecialchars($product['category']) ?></p>
            <p class="availability">Availability: <?= htmlspecialchars($product['availability']) ?></p>
            <p class="price">Price: ₱<?= htmlspecialchars($product['price']) ?></p>
            <form id="add-to-cart-form" method="post" action="">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                <label for="size">Size:</label>
                <select name="size" id="size" class="form-control">
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                </select>
                <label for="subscription">Subscription:</label>
                <select name="subscription" id="subscription" class="form-control">
                    <option value="Every Week">Every Week</option>
                    <option value="Every 2 Weeks">Every 2 Weeks</option>
                    <option value="Every 3 Weeks">Every 3 Weeks</option>
                    <option value="Monthly">Every Month</option>
                    <!-- Add more subscription options as needed -->
                </select>
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1">
                <button type="submit" class="btn add-to-cart-btn">Add to Cart</button>
            </form>
        </div>
    </div>
                <div class="description">
                  <h2>Description</h2>
                  <p><?= htmlspecialchars($product['long_description']) ?></p>
                </div>
              </main>
            </div>
          </body>
          <script>
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 5000); // 5000 milliseconds = 5 seconds
});
</script>

          </html>