<?php
include 'Php/functions.php';
$conn = db_connect();
if (isset($_POST['login_btn'])) {
    if (loginUser($_POST['email'], $_POST['password'])) {
        // Redirect based on role
        if (isAdmin()) {
            header('Location: products.php');
        } else {
            header('Location: home.php');
        }
        exit;
    } else {
        $error = 'Login failed';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<title>Login</title>
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
        <div class="card d-flex justify-content-center align-items-center mb-5" style="width: 637px;height: 422px;border-radius: 19px;background: #ffffff00;">
                    <div class="card-body" style="background: #f9c180;width: 637px;height: 422px;border-radius: 19px;box-shadow: 0px 5px 10px;">
                    <div class="d-flex justify-content-start" style="width: 592.2969px;border-bottom-width: 2px;border-bottom-style: solid;">
                        <h2>Login</h2>
                    </div>
                        <p>Welcome Back! Sign in to your account.</p>
                        <?php if (!empty($error)): ?>
                            <p class="alert alert-danger"><?= $error ?></p>
                        <?php endif; ?>
                        <form method="post" action="">
                            <div class="mb-3">
                                <label class="form-control-plaintext">Email Address *</label>
                                <input class="form-control" type="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-control-plaintext">Password *</label>
                                <input class="form-control" type="password" name="password" required>
                            </div>
                            <button class="btn btn-primary w-100" type="submit" name="login_btn" style="background: #ff8e3c; font-family: Poppins, sans-serif; border-color: #ff8e3c; border-radius: 22px; color: black; font-weight: bold;" onmouseover="this.style.color='white';" onmouseout="this.style.color='black';">Login</button>
                            <p class="text-muted">Don't have an account? <a href="register.php"><strong>Sign Up</strong></a></p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <?= template_footer() ?>
    </body>
</html>