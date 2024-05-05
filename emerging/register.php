<?php
include('Php/functions.php');

if (isset($_POST['register_btn'])) {
    // Gather form data
    $email = sanitizeData($_POST['email']);
    $password = sanitizeData($_POST['password']);
    $fullName = sanitizeData($_POST['full_name']);
    $phoneNumber = sanitizeData($_POST['phone_number']);
    $streetAddress = sanitizeData($_POST['street_address']);
    $city = sanitizeData($_POST['city']);
    $region = sanitizeData($_POST['region']);

    // Attempt to register the user with the address
    $registrationSuccess = registerUser($email, $password, $fullName, $phoneNumber, $streetAddress, $city, $region);

    if ($registrationSuccess) {
        $_SESSION['success_msg'] = 'Registration successful! You can now log in.';
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['error'] = isset($_SESSION['error']) ? $_SESSION['error'] : 'Registration failed due to an unknown error.';
    }
}

function registerUser($email, $password, $fullName, $phoneNumber, $streetAddress, $city, $region)
{
    $conn = db_connect();

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email_address = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Email already exists.';
        return false;
    }

    // Start transaction
    $conn->begin_transaction();

    // Insert address
    $stmt = $conn->prepare("INSERT INTO address (street_address, city, region) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $streetAddress, $city, $region);
    $stmt->execute();
    $addressId = $stmt->insert_id;

    // Insert new user
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (full_name, email_address, password, phone_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $email, $hashedPassword, $phoneNumber);
    $stmt->execute();
    $userId = $stmt->insert_id;

    // Link user and address
    $stmt = $conn->prepare("INSERT INTO user_address (user_id, address_id, is_default) VALUES (?, ?, TRUE)");
    $stmt->bind_param("ii", $userId, $addressId);
    if ($stmt->execute()) {
        $conn->commit();
        return true;
    } else {
        $conn->rollback();
        $_SESSION['error'] = 'Error during registration.';
        return false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<title>Register</title>
        <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
        <link rel="stylesheet" href="Style/styles1.css">
        <link rel="stylesheet" href="Style/auth.css">
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	</head>
    <body style="background-color: #ffff;">
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
    <section class="d-flex justify-content-center align-items-center position-relative py-4 py-xl-5">
        <div class="container">
            <div class="card mb-5 mx-auto" style="max-width: 800px; background: #ffff;">
                <div class="card-body p-5">
                    <h2 class="mb-4">Create an Account</h2>
                    <hr class="mb-4"> 
                    <p class="mb-4">Enter your details below.</p>
                    <form method="post" action="">
                    <?php
                    // Display error message if present
                        if (isset($_SESSION['error'])) {
                            echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
                            unset($_SESSION['error']); // Clear the error message after displaying
                        }
                        ?>
                        <div class="form-group">
                            <label for="full_name">Full Name*</label>
                            <input type="text" id="full_name" name="full_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email*</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password*</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number*</label>
                            <input type="text" id="phone_number" name="phone_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="Street Address*">Street Address*</label>
                            <input type="text" id="street_address" name="street_address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="city">City*</label>
                            <input type="text" id="city" name="city" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="region">Region*</label>
                            <input type="text" id="region" name="region" class="form-control" required>
                        </div>
                        <button type="submit" name="register_btn" class="btn btn-primary btn-block" style="width: 600px; margin-left: 8%; background-color: red; color: white;">Create Account</button>
                    </form>
                    <div class="mt-4 text-center">
                        <button class="btn btn-outline-primary btn-block" style="width: 500px; background-color: white; color: black; border-color: black;">Sign up with Google</button>
                        <button class="btn btn-outline-primary btn-block" style="width: 500px; background-color: white; color: black; border-color: black;">Sign up with Apple</button>
                        <button class="btn btn-outline-primary btn-block" style="width: 500px; background-color: white; color: black; border-color: black;">Sign up with Facebook</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?= template_footer() ?>
</body>
</html>