<?php 
include('Php/functions.php');
$conn = db_connect();

if (isset($_POST['register_btn'])) {
    // Gather form data
    $fullName = sanitizeData($_POST['full_name']);
    $email = sanitizeData($_POST['email']);
    $password = sanitizeData($_POST['password']);
    $phoneNumber = sanitizeData($_POST['phone_number']);
    $streetAddress = sanitizeData($_POST['street_address']);

    // Attempt to register the user
    $registrationSuccess = registerUser($fullName, $email, $password, $phoneNumber, $streetAddress);

    if ($registrationSuccess) {
        // Set success message and redirect to login
        $_SESSION['success_msg'] = 'Registration successful! You can now log in.';
        header('Location: login.php');
        exit();
    } else {
        // Set error message to display on the same page
        $errorMessage = $_SESSION['error'] ?? 'Registration failed due to an unknown error.';
    }
}

function registerUser($fullName, $email, $password, $phoneNumber, $streetAddress) {
    $conn = db_connect(); // Ensure db_connect() is designed to handle the connection properly

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email_address = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Email already exists.';
        return false;
    }

    // Insert new user
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (full_name, email_address, password, phone_number, street_address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullName, $email, $hashedPassword, $phoneNumber, $streetAddress);
    if ($stmt->execute()) {
        return true;
    } else {
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="Style/bootstrap.min.css">
        <link rel="stylesheet" href="Style/styles.css">
        <link rel="stylesheet" href="Style/auth.css">
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	</head>
<body style="background: url('Image/pawsfull.svg') left / contain no-repeat, rgb(234,192,162); margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif;">
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
    <section class="d-flex justify-content-center align-items-center position-relative py-4 py-xl-5">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="card d-flex justify-content-center align-items-center mb-5" style="width: 637px;height: 690px;border-radius: 19px;background: #ffffff00;">
                <div class="card-body" style="background: #f9c180;width: 637px;height: 637px;border-radius: 19px;box-shadow: 0px 5px 10px;">
                    <div class="d-flex justify-content-start" style="width: 592.2969px;border-bottom-width: 2px;border-bottom-style: solid;">
                        <h2 style="font-family: Poppins, sans-serif;">Register</h2>
                    </div>
                    <p class="w-lg-50" style="font-family: Poppins, sans-serif;font-size: 13px;"><br>Create new account today to reap the benefits of a personalized shopping experience.<br><br></p>
                    <form class="text-center" method="post" action="">
                        <div class="mb-3"><input class="form-control-plaintext" type="text" value="Full Name*" readonly="" style="font-weight: bold; font-family: Poppins, sans-serif; font-size: 14px;"><input class="form-control" type="text" name="full_name" placeholder="" required style="width: 598px; border-radius: 22px; height: 42px; font-family: Poppins, sans-serif;"></div>
                        <div class="mb-3"><input class="form-control-plaintext" type="text" value="Email Address*" readonly="" style="font-weight: bold; font-family: Poppins, sans-serif; font-size: 14px;"><input class="form-control" type="email" name="email" placeholder="" required style="width: 598px; border-radius: 22px; height: 42px; font-family: Poppins, sans-serif;"></div>
                        <div class="mb-3"><input class="form-control-plaintext" type="text" value="Password*" readonly="" style="font-size: 14px; font-family: Poppins, sans-serif; font-weight: bold;"><input class="form-control" type="password" name="password" placeholder="" required style="height: 42px; border-radius: 22px; font-family: Poppins, sans-serif;"></div>
                        <div class="mb-3"><input class="form-control-plaintext" type="text" value="Phone Number*" readonly="" style="font-size: 14px; font-family: Poppins, sans-serif; font-weight: bold;"><input class="form-control" type="text" name="phone_number" placeholder="" required style="height: 42px; border-radius: 22px; font-family: Poppins, sans-serif;"></div>
                        <div class="mb-3"><input class="form-control-plaintext" type="text" value="Street Address*" readonly="" style="font-size: 14px; font-family: Poppins, sans-serif; font-weight: bold;"><input class="form-control" type="text" name="street_address" placeholder="" required style="height: 42px; border-radius: 22px; font-family: Poppins, sans-serif;"></div>
                        <div class="mb-3"><button class="btn btn-primary d-block w-100" type="submit" name="register_btn" style="background: #ff8e3c; font-family: Poppins, sans-serif; border-color: #ff8e3c; border-radius: 22px; color: black; font-weight: bold;" onmouseover="this.style.color='white';" onmouseout="this.style.color='black';">Register</button></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?= template_footer() ?>
</body>
</html>