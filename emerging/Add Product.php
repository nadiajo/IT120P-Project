<?php
include('Php/functions.php');
$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['productName'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $availability = ($_POST['availability'] ?? '') == 'In Stock' ? 'In Stock' : 'Out of Stock';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? '';
    $sizes = $_POST['sizeOptions'] ?? '';
    $shortDescription = $_POST['shortDescription'] ?? '';
    $longDescription = $_POST['description'] ?? '';
    $image = $_POST['imageUrl'] ?? '';

    $stmt = $conn->prepare("INSERT INTO products (name, brand, availability, price, category, sizes, short_description, long_description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdsssss", $name, $brand, $availability, $price, $category, $sizes, $shortDescription, $longDescription, $image);
    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding product: " . $conn->error . "');</script>";
    }
    $stmt->close();
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
        <link rel="stylesheet" href="Style/styles.css">
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
    <img src="Image/furco_search.png" alt="Search" class="search-icon">
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
        <input type="text" class="form-control" id="productName" name="productName" required>
    </div>

    <div class="form-group">
        <label for="brand">Brand</label>
        <input type="text" class="form-control" id="brand" name="brand" required>
    </div>

    <div class="form-group">
        <label for="availability">Availability</label>
        <select class="form-control" id="availability" name="availability">
            <option>In Stock</option>
            <option>Out of Stock</option>
        </select>
    </div>

    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" class="form-control" id="price" name="price" required>
    </div>

    <div class="form-group">
        <label for="category">Category</label>
        <input type="text" class="form-control" id="category" name="category" required>
    </div>

    <div class="form-group">
        <label for="sizeOptions">Size Options</label>
        <select class="form-control" id="sizeOptions" name="sizeOptions">
            <option>Small</option>
            <option>Medium</option>
            <option>Large</option>
        </select>
    </div>

    <div class="form-group">
        <label for="shortDescription">1-2 Word Description</label>
        <input type="text" class="form-control" id="shortDescription" name="shortDescription" required>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>

    <div class="form-group">
        <label for="imageUrl">Image URL Upload</label>
        <input type="url" class="form-control" id="imageUrl" name="imageUrl">
    </div>

    <button type="submit" class="btn btn-primary">Add Product</button>
    <button type="reset" class="btn btn-primary2">Reset</button>
</form>
</div>
