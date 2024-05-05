<?php include('Php/functions.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
     <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Style/Add style.css">
    
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
        <h2>Modify Product</h2>
        <form action="/submit-product" method="post">
            <div class="form-group">
                <label for="productName">Name of Product</label>
                <input type="text" class="form-control" id="productName" required>
            </div>

            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" class="form-control" id="brand" required>
            </div>

            <div class="form-group">
                <label for="availability">Availability</label>
                <select class="form-control" id="availability">
                    <option>In Stock</option>
                    <option>Out of Stock</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price (Pesos)</label>
                <input type="number" class="form-control" id="price" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" id="category">
                    <option>Cat Food</option>
                    <option>Dog Food</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sizeOptions">Size Options</label>
                <input type="text" class="form-control" id="sizeOptions" placeholder="Enter sizes separated by commas">
            </div>

            <div class="form-group">
                <label for="subscriptions">Subscriptions</label>
                <select class="form-control" id="subscriptions">
                    <option>Every 4 days</option>
                    <option>Every 5 days</option>
                    <option>Every 6 days</option>
                    <option>Every week</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shortDescription">1-2 Word Description</label>
                <input type="text" class="form-control" id="shortDescription" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="additionalInfo">Additional Information</label>
                <textarea class="form-control" id="additionalInfo" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="specification">Specification</label>
                <textarea class="form-control" id="specification" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="imageUrl">Image URL Upload</label>
                <input type="url" class="form-control" id="imageUrl">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class = "btn btn-primary2">Cancel</button>
        </form>
    </div>

    <?= template_footer() ?>
