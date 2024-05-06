<?php
include('Php/functions.php');
$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['productName'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $price = $_POST['price'] ?? 0;
    $productType = $_POST['productType'] ?? '';
    $sizeOptions = $_POST['sizeOptions'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = $_POST['image_url'] ?? '';
    $isForSubscription = $_POST['isForSubscription'] === 'Yes' ? 1 : 0;

    $sizesPrices = parseSizeOptions($sizeOptions);
    $defaultPrice = current($sizesPrices);
    $price = $defaultPrice; 

    $stmt = $conn->prepare("INSERT INTO products (name, brand, price, product_type, size_options, description, image_url, is_for_subscription) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsssss", $name, $brand, $price, $productType, $sizeOptions, $description, $image, $isForSubscription);
    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!');</script>";
        header('Location: products.php');
        
    } else {
        echo "<script>alert('Error adding product: " . $conn->error . "');</script>";
    }
    $stmt->close();
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
<meta charset="utf-8">
		<title>Add Product</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
        <link rel="stylesheet" href="Style/styles1.css">
        <link rel="stylesheet" href="Style/Add style.css">

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

    <div class="container2 mt-4">
        <h2>Add Product</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="productName">Name of Product</label>
                <input type="text" class="form-control" id="productName" name="productName"  required>
            </div>

            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" class="form-control" id="brand" name="brand" required>
            </div>

            <div class="form-group">
                <label for="productType">Product Type</label>
                <input type="text" class="form-control" id="productType" name="productType" required>
            </div>

            <div class="form-group">
            <label for="sizeOptions">Size and Price Options</label>
            <input type="text" class="form-control" id="sizeOptions" name="sizeOptions" placeholder="Enter sizes and prices separated by commas, e.g., Small:100, Medium:200">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            
                <div class="form-group">
                <label for="imageUrl">Image URL Upload</label>
                <input type="url" class="form-control" id="image_url" name="image_url">
            </div>
        
            <div id="content">
		
	</div>

            <button type="submit" class="btn btn-primary">Add Product</button>
            <button type="reset" class="btn btn-primary2">Reset</button>
        </form>
    </div>
</form>
</div>
