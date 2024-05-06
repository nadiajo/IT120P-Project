<?php
// Include database connection
include('Php/functions.php');
$conn = db_connect();

// SQL query to fetch details about the product based on the name or brand
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $stmt = $conn->prepare("SELECT id, name, brand, price, image_url FROM products WHERE name LIKE CONCAT('%', ?, '%') OR brand LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("ss", $searchQuery, $searchQuery);
    $stmt->execute();
    $results = $stmt->get_result();
    $products = $results->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}

// Only fetch default product listing if no search query is provided
if (empty($_GET['query'])) {
    $num_products_on_each_page = 8;
    $current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
    $offset = ($current_page - 1) * $num_products_on_each_page;

    $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $num_products_on_each_page);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<title>Search Results</title>
        <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
        <link rel="stylesheet" href="Style/styles1.css">
        <link rel="stylesheet" href="Style/products.css">

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
    <main>
        <h1>Search Results for "<?= htmlspecialchars($searchQuery ?? '') ?>"</h1>
        <?php if (!empty($products)) : ?>
            <section class="product-list">
                <?php foreach ($products as $product) : ?>
                    <div class="product-item">
                    <a href="product_details.php?page=product&id=<?= htmlspecialchars($product['id']) ?>">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width:100px; height:auto;">
                        <div class="product-desc">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p><?= htmlspecialchars($product['brand']) ?></p>
                            <?php $priceOptions = explode(',', $product['price']);?>
                            <span class="price">₱<?= number_format($product['price'], 2) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php else : ?>
            <p class="no-products">No products found.</p>
        <?php endif; ?>
    </main>
</body>
<?= template_footer() ?>
</html>