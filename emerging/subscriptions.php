<?php
include('Php/functions.php'); 
$conn = db_connect();

// Fetch the subscriptions from the database
$stmt = $conn->prepare("SELECT * FROM subscriptions ORDER BY id");
$stmt->execute();
// Fetch the subscriptions from the database and return the result as an Array
$subscriptions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>

		<meta charset="utf-8">
		<title>Subscriptions</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
        <link rel="stylesheet" href="Style/styles.css">
        <link rel="stylesheet" href="Style/subscriptions.css">

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
  
    <main>
        <h1>Subscriptions</h1>
        <section class="subscription-list">
            <?php foreach ($subscriptions as $subscription): ?>
                <a href="subscription-view.php?id=<?= htmlspecialchars($subscription['id']) ?>">
                    <div class="subcription-item">
                        <img src="<?= htmlspecialchars($subscription['image']) ?>" alt="<?= htmlspecialchars($subscription['name']) ?>">
                        <div class="subcription-desc">
                            <h3><?= htmlspecialchars($subscription['name']) ?></h3>
                            <p><?= htmlspecialchars($subscription['description']) ?></p>
                            <span class="price">₱<?= htmlspecialchars($subscription['price']) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </section>
    </main>
    <?= template_footer() ?>
</body>
</html> 