<?php
function countCartItems($conn, $user_id) {
    $stmt = $conn->prepare("SELECT SUM(qty) AS total_items FROM shopping_cart_item WHERE cart_id = (SELECT id FROM shopping_cart WHERE user_id = ?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_items'] ?? 0;
}
if (isLoggedIn()) {
    // Common links for both admin and user
    echo '<a href="Home.php">Home</a>';
    echo '<a href="Products.php">Products</a>';
    // Conditional links based on admin status
    if (isAdmin()) {
        // Only show logout for admin
        echo '<a href="Php/logout.php" class="logout">Logout</a>';
    } else {
        // Additional options for regular user
        echo '<a href="shopping cart.php"><button><img src="Image/cart.png" alt="Cart" class="cart"></button></a>';
        echo '<div class="dropdown">
          <button class="dropbtn"><img src="Image/profile.png" alt="Profile" class="profile"></button>
          <div class="dropdown-content">
              <a href="User Profile.php">User Profile</a>
              <a href="Php/logout.php">Logout</a>
          </div>
        </div>';
    }
} else {
    // Links for visitors
    echo '<a href="Home.php">Home</a>';
    echo '<a href="Products.php">Products</a>';
    // Determine if on the login page to show "Register" instead of "Login"
    $currentPage = basename($_SERVER['PHP_SELF']);
    if ($currentPage == 'login.php') {
        echo '<a href="register.php" class="login">Register</a>';
    } else {
        echo '<a href="login.php" class="login">Login</a>';
    }
}
?>
