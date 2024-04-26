<?php 
include('Php/functions.php');
$userId = $_SESSION['user_id']; // Default to 0 if not set
$userData = getUserData($userId);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['emailAddress'] ?? '';
    $phoneNumber = $_POST['phoneNumber'] ?? '';
    $streetAddress = $_POST['streetAddress'] ?? '';

    $hasDataChanged = $fullName !== $userData['full_name'] || 
                      $phoneNumber !== $userData['phone_number'] || 
                      $streetAddress !== $userData['street_address'];

    $passwordUpdateResult = true; // Assume true unless a password update is attempted and fails

    $incorrectPassword = false; // Flag to track incorrect password

    if (isset($_POST['changePassword']) && $_POST['changePassword'] === 'yes') {
        $oldPassword = $_POST['oldPassword'] ?? '';
        $newPassword = $_POST['newPassword'] ?? '';
        $currentPasswordHash = getUserPasswordHash($userId);

        if (password_verify($oldPassword, $currentPasswordHash)) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $passwordUpdateResult = updateUserPassword($userId, $newPasswordHash);
            if (!$passwordUpdateResult) {
                echo "Error updating password!";
            }
        } else {
          $errorMessage = "Incorrect old password!";
          $passwordUpdateResult = false;
        }
    }

    if ($hasDataChanged) {
        $result = updateUserData($userId, $fullName, $email, $phoneNumber, $streetAddress);
    } else {
        $result = true; // No changes to user data, but treat as successful update
    }

    if ($result && $passwordUpdateResult) {
        header("Location: home.php"); // Redirect to home.php after successful update
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Style/styles.css">
  <title>Pet Products</title>
  <link rel="icon" type="image/x-icon" href="Image/furco_logo.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
	<style>

		
	body {
		margin: 0;
		padding: 0;
		background-color: #EAC0A2;
		}
		
	.container-form{
		margin-left: 30%;
		margin-right: 30%;
		margin-top: 50px;
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
		
    
	</style>
</head>

<body>
<div class="navbar">
        <img src="Image/furco_logo.png" alt="FURCO Paw Logo" class="logo">
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
  <div class="container-form">
  <div class="title-container">
    <h2>Edit Profile</h2>
	<img src="Image/user_icon.png" width="100" height="100" alt="User Icon" class="logo">
</div>

<!-- Error message display -->
<?php if (!empty($errorMessage)): ?>
    <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
  <?php endif; ?>

    <form method="post"> 
      <div class="form-group">
        <label for="fullName">Full Name:</label>
        <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $userData['full_name'] ?? ''; ?>" required>
      </div>
      <div class="form-group">
        <label for="emailAddress">Email Address:</label>
        <input type="email" class="form-control" id="emailAddress" name="emailAddress" value="<?php echo $userData['email'] ?? ''; ?>" readonly>
      </div>
      <div class="form-group">
        <label for="address">Address:</label>
        <input type="text" class="form-control" id="address" name="streetAddress" value="<?php echo $userData['street_address'] ?? ''; ?>" required>
      </div>
      <div class="form-group">
        <label for="contactNumber">Contact Number:</label>
        <input type="tel" class="form-control" id="contactNumber" name="phoneNumber" value="<?php echo $userData['phone_number'] ?? ''; ?>" required>
      <!-- Password Change Option --><br>
      <div class="form-group">
        <label>Do you want to change your password?</label>
        <div>
          <input type="radio" id="yes" name="changePassword" value="yes" onclick="togglePasswordFields(true)">
          <label for="yes">Yes</label>
          <input type="radio" id="no" name="changePassword" value="no" checked onclick="togglePasswordFields(false)">
          <label for="no">No</label>
        </div>
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
      <button type="reset" class="btn btn-secondary">Reset</button>
      <button type="submit" class="btn btn-primary">Save</button>
    </form>
  </div>
</main>
<script>
  function togglePasswordFields(show) {
    document.getElementById('passwordFields').style.display = show ? 'block' : 'none';
  }
</script>
<?= template_footer() ?>