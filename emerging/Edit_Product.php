<?php
include('Php/functions.php');
$conn = db_connect();


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Retrieve product details from the database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Close the prepared statement
    $stmt->close();
} else {
    // Redirect if no product ID is provided or it's not a valid number
    header("Location: products.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['delete_product'])) {
        // Prepare a SQL statement to delete the product
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Product deleted successfully, redirect to products page
            header("Location: products.php");
            exit;
        } else {
            // Error occurred while deleting the product
            echo "<script>alert('Error deleting product: " . $conn->error . "');</script>";
        }
        // Close the statement
        $stmt->close();
    } else {

    $name = $_POST['name'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $price = $_POST['price'] ?? 0;
    $productType = $_POST['product_type'] ?? '';
    $sizeOptions = $_POST['size_options'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = $_POST['image_url'] ?? '';
    $isForSubscription = $_POST['is_for_subscription'] === 'Yes' ? 1 : 0;

    $sizesPrices = parseSizeOptions($sizeOptions);
    $defaultPrice = current($sizesPrices);
    $price = $defaultPrice; 

    $stmt = $conn->prepare("UPDATE products SET name = ?, brand = ?, price = ?, product_type = ?, size_options = ?, description = ?, image_url = ?, is_for_subscription = ? WHERE id = ?");
    $stmt->bind_param("ssdssssii", $name, $brand, $price, $productType, $sizeOptions, $description, $image, $isForSubscription, $product_id);
    if ($stmt->execute()) {
        $_SESSION['success_msg'] = 'Product updated successfully!';
        header('Location: products.php');
        exit();
    } else {
        echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
    }
    $stmt->close();
    }
} else {
    // Fetch product details to populate the form
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
        <link rel="stylesheet" href="Style/styles1.css">
        <link rel="stylesheet" href="Style/auth.css">
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Style/Add style.css">
    
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
    

  <div class="container2 mt-4">
        <h2>Edit Product</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="productName">Name of Product</label>
                <input type="text" class="form-control" id="productName" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" class="form-control" id="brand" name="brand" value="<?= htmlspecialchars($product['brand']) ?>" required>
            </div>

            <div class="form-group">
                <label for="productType">Product Type</label>
                <input type="text" class="form-control" id="productType" name="product_type" value="<?= htmlspecialchars($product['product_type']) ?>" required>
            </div>

            <div class="form-group">
                <label for="sizeOptions">Size and Price Options</label>
                <input type="text" class="form-control" id="sizeOptions" name="size_options" value="<?= htmlspecialchars($product['size_options']) ?>" required>
            </div>

            <!--
            <div class="form-group">
                <label for="subscriptions">For Subscription?</label>
                <select class="form-control" id="isForSubscription" name="is_for_subscription">
                    <option value="Yes" <?= $product['is_for_subscription'] ? 'selected' : '' ?>>Yes</option>
                    <option value="No" <?= !$product['is_for_subscription'] ? 'selected' : '' ?>>No</option>
                </select>
            </div>
-->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="imageUrl">Image URL Upload</label>
                <input type="url" class="form-control" id="imageUrl" name="image_url" value="<?= htmlspecialchars($product['image_url']) ?>">
                
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
            <button type="reset" class="btn btn-primary2">Reset</button>
            </form>
            <form action="delete product.php" method="post" id="delete-product-form">
            <!-- Hidden input to send product ID -->
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
            <!-- Button to trigger form submission -->
            <button type="button" class="btn btn-danger" id="delete-product-btn">Delete Product</button>
            </form>
            
            </div>

            <script>
            document.getElementById('delete-product-btn').addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this product?')) {
            document.getElementById('delete-product-form').submit();
            }
            });
            </script>


        
    </div>

    <?= template_footer() ?>
