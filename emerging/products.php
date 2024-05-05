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
		<title>Product</title>
        <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
        <!--Nadia start-->
        <link rel="stylesheet" href="Style/styles1.css">
        <link rel="stylesheet" href="Style/products.css">
        <!--Nadia end-->

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
<main>
    <h1>Products</h1>
    <section class="product-list">
            <?php foreach ($products as $product) : ?>
                <!-- Check if user is admin to provide edit link, else provide view details link -->
                <?php if (isAdmin()) : ?>
                    <a href="edit_product.php?page=product&id=<?= htmlspecialchars($product['id']) ?>">
                    <?php else : ?>
                        <a href="product_details.php?page=product&id=<?= htmlspecialchars($product['id']) ?>">
                        <?php endif; ?>
                        <div class="product-item">
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="product-desc">
                                <h3><?= htmlspecialchars($product['name']) ?></h3>
                                <p><?= htmlspecialchars($product['brand']) ?></p>
                                <?php $priceOptions = explode(',', $product['price']);?>
                                <span class="price">₱<?= htmlspecialchars($priceOptions[0]) ?></span>
                            </div>
                        </div>
                        </a>
                    <?php endforeach; ?>
        </section>
        <div class="buttons">
            <?php if ($current_page > 1) : ?>
                <a href="Products.php?p=<?= $current_page - 1 ?>" class="button">Prev</a>
            <?php endif; ?>
            <?php if ($total_products > ($current_page * $num_products_on_each_page)) : ?>
                <a href="Products.php?p=<?= $current_page + 1 ?>" class="button">Next</a>
            <?php endif; ?>
            <!-- Admin-only buttons -->
            <?php if (isAdmin()) : ?>
                <a href="add_product.php" class="button">Add Item</a>
            <?php endif; ?>
        </div>
        <br>
        </main>
    </body>
    <?= template_footer() ?>
    </html>