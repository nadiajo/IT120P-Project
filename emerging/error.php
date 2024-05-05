<?php 
include('Php/functions.php');

$conn = db_connect();


// The amount of products to show on each page
$num_products_on_each_page = 8;
// The current page - in the URL, will appear as index.php?page=products&p=1, index.php?page=products&p=2, etc...
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;

// Calculate the offset for the query
$offset = ($current_page - 1) * $num_products_on_each_page;

// Prepare the SQL statement to select products
$stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $num_products_on_each_page);
$stmt->execute();
// Fetch the products from the database and return the result as an Array
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// Get the total number of products
$total_products_res = $conn->query("SELECT COUNT(*) as total FROM products");
$total_products = $total_products_res->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<title>Home</title>
        <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
         
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	      <!--nadia start-->
        <link rel="stylesheet" href="Style/styles1.css">
        <link rel="stylesheet" href="Style/homes.css"> 
        <link rel="stylesheet" href="Style/products.css">
        <!--nadia end-->
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
    </div> <br>
    <br>
    <br>
    <div class="error" style="text-align: center;">
    <h1 style="font-size: 10rem;"><b>404 Not Found!</b></h1>
    <h4 style="font-size: 1.5rem;">Your visited page not found. You may go to the home page.</h4>
    <br>
    <a href="home.php">
    <button type="submit" name="Home Page" style="background-color: red; color: white; width: 250px; height:50px; display: block; margin: 0 auto;">Back to Home Page</button> <br>
</a>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>

    </body>
    <?= template_footer() ?>
</body>
</html>