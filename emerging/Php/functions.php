<?php
session_start();

function db_connect()
{
    // Database connection details
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'ivatanstore';
    $conn = new mysqli($host, $user, $password, $dbname); // "3307" is the MySQL port number :: default is 3306
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Create the database if it does not exist
    $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");

    // Select the database
    $conn->select_db($dbname);

    return $conn;
}

function loginUser($email, $password)
{
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email_address = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            return true;
        }
    }
    return false;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['user_id']) && $_SESSION['is_admin'];
}

function logout()
{
    // Clear session data
    session_unset();
    // Destroy the session
    session_destroy();
}


function sanitizeData($data)
{
    $conn = db_connect();
    $clean_data = mysqli_real_escape_string($conn, trim($data));
    return $clean_data;
}

// Display error messages
function display_error()
{
    if (!empty($_SESSION['error'])) {
        $error = $_SESSION['error'];
        $_SESSION['error'] = ''; // Clear error after displaying
        return '<div class="alert alert-danger">' . $error . '</div>';
    }
    return ''; // No error
}

function getUserById($id)
{
    global $DATABASE_NAME;
    $query = "SELECT * FROM users WHERE id=" . $id;
    $result = mysqli_query($DATABASE_NAME, $query);

    $user = mysqli_fetch_assoc($result);
    return $user;
}

function getUserData($conn, $userId)
{
    $stmt = $conn->prepare("SELECT users.*, address.street_address, address.city, address.region FROM users LEFT JOIN user_address ON users.id = user_address.user_id LEFT JOIN address ON user_address.address_id = address.id WHERE users.id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateUserData($conn, $userId, $fullName, $phoneNumber, $streetAddress, $city, $region)
{
    // Start transaction for atomicity
    $conn->begin_transaction();

    try {
        // Update user information
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone_number = ? WHERE id = ?");
        $stmt->bind_param("ssi", $fullName, $phoneNumber, $userId);
        $stmt->execute();

        // Update address information
        // Assuming one-to-one relationship between users and addresses for simplicity
        $stmt = $conn->prepare("UPDATE address SET street_address = ?, city = ?, region = ? WHERE id = (SELECT address_id FROM user_address WHERE user_id = ?)");
        $stmt->bind_param("sssi", $streetAddress, $city, $region, $userId);
        $stmt->execute();

        // Commit the changes
        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        return false;
    }
}

function getUserPasswordHash($userId)
{
    $conn = db_connect();
    // SQL to get the user's password hash
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('MySQL prepare error: ' . $conn->error);
    }

    // Binding the integer parameter for the user ID
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($passwordHash);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    return $passwordHash;
}

function updateUserPassword($conn, $userId, $newPasswordHash)
{
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newPasswordHash, $userId);
    return $stmt->execute();
}

// for size dropdown from add-product
function generateSizeDropdown($sizes)
{
    $sizesArray = explode(',', $sizes);
    $html = '<select name="size" id="size" class="form-control">';
    foreach ($sizesArray as $index => $size) {
        $size = trim($size);
        $html .= '<option value="' . htmlspecialchars($size) . '">' . htmlspecialchars($size) . '</option>';
    }
    $html .= '</select>';
    return $html;
}

// escape string
function e($val)
{
    $conn = db_connect();
    return mysqli_real_escape_string($conn, trim($val));
}
// Franz Severino
// Template footer
function template_footer()
{
  $year = date('Y');
  echo <<<EOT
  <footer class="d-flex justify-content-center align-items-center" style="background: #000000;padding: 25px;">
  <div class="container">
      <div class="row">
      <!-- Remove Logo
          <div class="col-md-1"><svg width="87" height="75" viewBox="0 0 87 75" fill="none" xmlns="http://www.w3.org/2000/svg">
<path opacity="0.4" d="M69.1661 49.1251C67.0273 38.0938 54.7386 29.125 41.7611 29.125C27.6597 29.125 15.2623 38.9688 14.066 51.0938C13.5948 55.7813 15.3348 60.2188 18.9235 63.5626C22.476 66.9063 27.4785 68.7501 32.916 68.7501H49.8811C56.0073 68.7501 61.3723 66.6876 65.0336 62.9688C68.6948 59.2501 70.1448 54.3126 69.1661 49.1251Z" fill="#FF8E3C"></path>
<path d="M37.2635 24.5625C43.1295 24.5625 47.8848 20.4631 47.8848 15.4062C47.8848 10.3494 43.1295 6.25 37.2635 6.25C31.3976 6.25 26.6423 10.3494 26.6423 15.4062C26.6423 20.4631 31.3976 24.5625 37.2635 24.5625Z" fill="#292D32"></path>
<path d="M61.4075 28.2183C66.2926 28.2183 70.2525 24.8044 70.2525 20.5933C70.2525 16.3821 66.2926 12.9683 61.4075 12.9683C56.5225 12.9683 52.5625 16.3821 52.5625 20.5933C52.5625 24.8044 56.5225 28.2183 61.4075 28.2183Z" fill="#292D32"></path>
<path d="M74.4923 40.4062C78.3964 40.4062 81.561 37.678 81.561 34.3124C81.561 30.947 78.3964 28.2188 74.4923 28.2188C70.5885 28.2188 67.4235 30.947 67.4235 34.3124C67.4235 37.678 70.5885 40.4062 74.4923 40.4062Z" fill="#292D32"></path>
<path d="M14.2825 34.313C19.1674 34.313 23.1275 30.8991 23.1275 26.688C23.1275 22.4768 19.1674 19.063 14.2825 19.063C9.39756 19.063 5.4375 22.4768 5.4375 26.688C5.4375 30.8991 9.39756 34.313 14.2825 34.313Z" fill="#292D32"></path>
</svg></div>  -->

          <div class="col-md-7">
              <h1 style="color: rgb(255,255,255); text-align: left;">Souvenir Shop</h1> <br>
              <p style="color: rgb(255,255,255);"><h3>Subscribe</h3><br> Get 10% off your first order<br></p> <br>
                 <input type="text" placeholder="Enter your Email" style="width: 250px;">

          </div>
          
         <!-- <div class="col-md-3 align-items: right">
              <h3 class="text-uppercase" style="color: rgb(0,0,0);">CONTACT US</h3>
              <p style="color: rgb(0,0,0);">Phone: 1234-567-8910<br> BF Paranaque<br></p> <br>
          </div>  -->
         <!--  <div class="col-md-2">
              <h3 class="text-uppercase" style="color: rgb(0,0,0);">OPEN HOURS</h3>
              <p style="color: rgb(0,0,0);">8am - 8pm<br> Monday-Saturday<br><br> For inquiries during holidays, please contact us! </p>
          </div> -->
          <div class="col-md-3" >
          <h3 class="text-uppercase" style="color: rgb(255,255,255);">QUICK LINKS</h3>
              <div class="d-grid footer-links"><a href="Home.php" style="color: rgb(255,255,255);">Home</a><br><a href="subscriptions.php" style="color: rgb(255,255,255);">Subscriptions</a><br><a href="Products.php" style="color: rgb(255,255,255);">Products</a><br><a href="OrderHistorypage.php" style="color: rgb(255,255,255);">My Orders</a></div>
          </div>
      </div>
      <hr>
      <div class="container">
      <div class="row">
          <div class="col-12 text-center">
              <p style="color: rgb(255,255,255);">© 2024 Ivatan Store All rights reserved</p>
              </div>
          </div>
      </div>
  </div>
</footer>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
EOT;
}
?>