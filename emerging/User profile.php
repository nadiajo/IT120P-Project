<?php
include('Php/functions.php');
$userId = $_SESSION['user_id'];
$conn = db_connect();
$userData = getUserData($conn, $userId);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullName = $_POST['fullName'] ?? '';
  $phoneNumber = $_POST['phoneNumber'] ?? '';
  $streetAddress = $_POST['streetAddress'] ?? '';
  $city = $_POST['city'] ?? '';
  $region = $_POST['region'] ?? '';
  $errorMessage = '';

  // Check if any user data has changed
  $hasDataChanged = $fullName !== $_SESSION['full_name'] ||
    $phoneNumber !== $_SESSION['phone_number'] ||
    $streetAddress !== $_SESSION['street_address'] ||
    $city !== $_SESSION['city'] ||
    $region !== $_SESSION['region'];

  // Assume no errors initially
  $passwordUpdateResult = true;
  $result = true;

  if (isset($_POST['changePassword']) && $_POST['changePassword'] === 'yes') {
    $oldPassword = $_POST['oldPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $currentPasswordHash = getUserPasswordHash($userId);

    if (password_verify($oldPassword, $currentPasswordHash)) {
      $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
      $passwordUpdateResult = updateUserPassword($conn, $userId, $newPasswordHash);
      if (!$passwordUpdateResult) {
        $errorMessage = "Error updating password.";
      }
    } else {
      $errorMessage = "Incorrect old password.";
      $passwordUpdateResult = false;
    }
  }

  if ($hasDataChanged) {
    $result = updateUserData($conn, $userId, $fullName, $phoneNumber, $streetAddress, $city, $region);
    if (!$result) {
      $errorMessage = "Error updating user data.";
    }
  }

  // Determine overall success
  $allUpdatesSuccessful = $passwordUpdateResult && $result;

  if ($allUpdatesSuccessful) {
    $_SESSION['flash_success'] = 'Profile updated successfully!';
  } else {
    $_SESSION['flash_error'] = $errorMessage ?: 'Failed to update profile. Please try again.';
  }

  header("Location: user_profile.php");
  exit;
}

// Message display and clearing
$successMessage = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);

$errorMessage = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Style/styles1.css">
  <title>User Profile</title>
  <link rel="icon" type="image/x-icon" href="Image/furco_logo.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
	<style>

		
	body {
		margin: 0;
		padding: 0;
		background-color: #ffffff;
		}
		
	.container-form{
		margin-left: 30%;
		margin-right: 30%;
		margin-top: -150px;
		margin-bottom: 50px;
	}
	
	.title-container {
		display: flex;
		justify-content: space-between; /* Align items horizontally with space between them */
		align-items: center; /* Align items vertically */
	}

	.title-container h2 {
		margin-right: auto; /* Push the h2 element to the left as much as possible */
	}

  .error-message {
    color: #FF0000; /* Red color for error text */
    background-color: #FFECEC; /* Light red background */
    border: 1px solid #FFD2D2; /* Light red border */
    padding: 10px;
    margin-top: 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
}
		
.form-group label {
    display: inline-block;
    width: 120px; /* Adjust width as needed */
    margin-bottom: 5px;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
}
.form-group {
    flex: 1;
    margin-right: 10px; /* Adjust spacing between form groups */
}

.single-line-label {
    display: inline-block;
    width: auto;
    white-space: nowrap;
}

.menu {
    margin-left: 50px;
    margin-top: 15px;
}

.title-menu{
  font-size:1.1rem;
  margin-left: 30px;
   margin-top: 45px;
}
.order {
    margin-left: 50px;
    margin-right: -50px;
    padding: 0;
}

.title-order{
  font-size:1.1rem;
  margin-left: 30px;
  margin-top: 15px;
  margin-right: -50px;
  padding: 0;
}

/* Switch */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #db4444;
}

input:focus + .slider {
  box-shadow: 0 0 1px #db4444;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}



	</style>
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
    <div class="title-menu">
      <p><b>Manage My Account</b></p>
    </div>
  <div class="menu"> <!-- Change "link" to actual link -->
  <a style="color: #db4444">My Profile</a> <br>
  <a style="color: gray">Address Book</a> <br>
  <a style="color: gray" href="link">My Payment Options</a>
</div>
<div class="title-order">
      <p><b> My Orders</b></p>
    </div>
  <div class="order"> <!-- Change "link" to actual link -->
  <a style="color: gray" href="OrderHistoryPage.php">Order History</a> <br>
  <a style="color: gray" href="link">My Cancellations</a> <br>
</div>
  <div class="container-form">
  <div class="title-container">
  <h3 style="color: #db4444;">Edit Your Profile</h3> 
</div>

<!-- Error message display -->
<?php if (!empty($errorMessage)): ?>
    <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
  <?php endif; ?>
  
    <form method="post"> 
    <div class="form-row">
    
    <div class="form-group  col-md-6">
    <label for="fullName">First Name:</label>
    <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $userData['full_name'] ?? ''; ?>" required>
</div>
<div class="form-group">
    <label for="lastName">Last Name:</label>
    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter your last name" required>
</div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="emailAddress">Email Address:</label>
        <input type="email" class="form-control" id="emailAddress" name="emailAddress" value="<?php echo $userData['email_address'] ?? ''; ?>" readonly>
    </div>
    <div class="form-group col-md-6">
        <label for="address">Address:</label>
        <input type="text" class="form-control" id="address" name="streetAddress" value="<?php echo $userData['street_address'] ?? ''; ?>" required>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="city">City:</label>
        <input type="city" class="form-control" id="emailAddress" name="emailAddress" value="<?php echo $userData['email_address'] ?? ''; ?>" readonly>
    </div>
    <div class="form-group col-md-6">
        <label for="region">Region:</label>
        <input type="text" class="form-control" id="address" name="streetAddress" value="<?php echo $userData['street_address'] ?? ''; ?>" required>
    </div>
</div>
<label for="region">Contact Number:</label>
        <input type="text" class="form-control" id="address" name="streetAddress" value="<?php echo $userData['street_address'] ?? ''; ?>" required>
  
      <!-- Password Change Option --><br>
      <div>
      <label class = black>TOGGLE TO CHANGE PASSWORD: </label>
          <label class="switch">
            <input type="checkbox" id="changePassword" name="changePassword" value="yes" onclick="togglePasswordFields(this.checked)">
            <span class="slider"></span>
      </label>
    </div>


      <!-- Password Fields -->
      <div id="passwordFields" style="display: none;">
        <div class="form-group">
          <label for="oldPassword">Old Password:</label>
          <input type="password" class="form-control" id="oldPassword" name="oldPassword">
        </div>
        <div class="form-group">
          <label for="newPassword">New Password:</label>
          <input type="password" class="form-control" id="newPassword" name="newPassword">
        </div>
      </div>
      <button style="background-color: white; color: black;"type="cancel" class="btn btn-secondary">Cancel</button>
      <button style="background-color: #db4444; color: white;" type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  </div>
</main>
<script>
  function togglePasswordFields(show) {
    document.getElementById('passwordFields').style.display = show ? 'block' : 'none';
  }
</script>
<?= template_footer() ?>