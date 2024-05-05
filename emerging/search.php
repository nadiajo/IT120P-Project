<?php
// Include database connection
include('Php/functions.php');
$conn = db_connect();

// Check for the search term
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $stmt = $conn->prepare("SELECT id, name FROM products WHERE name LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("s", $searchQuery);
    $stmt->execute();
    $results = $stmt->get_result();
    // Fetch all results
    $products = $results->fetch_all(MYSQLI_ASSOC);
} else {
    // Handle the situation where no search term is provided
    echo "Please enter a search term.";
    exit;
}

// Close the statement
$stmt->close();
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
        <link rel="stylesheet" href="Style/product.css">

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
    <h1>Search Results for "<?= htmlspecialchars($searchQuery) ?>"</h1>
    <?php if (!empty($products)): ?>
        <ul>
            <?php foreach ($products as $product): ?>
                <li>
                    <a href="product details.php?id=<?= $product['id'] ?>">
                        <?= htmlspecialchars($product['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</body>
<?= template_footer() ?>
</html>