<?php
include 'includes/db.php';
include 'includes/functions.php';

$error = '';
$success = '';

// If already logged in, redirect to dashboard
if (isUserLoggedIn()) {
    header("Location: user-dashboard.php");
    exit();
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Email and password are required!";
    } else {
        // Check if user exists
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                
                $success = "Login successful! Redirecting...";
                header("refresh:2;url=user-dashboard.php");
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "User not found!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rydexia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">🚗 RYDEXIA</a>
            <a href="index.php" class="btn btn-secondary ms-auto">Back to Home</a>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="hero" style="min-height: 100vh; justify-content: center;">
        <div class="hero-content" style="width: 100%; max-width: 400px;">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4 text-primary">User Login</h2>
                    
                    <?php if ($error): ?>
                        <?php echo showError($error); ?>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <?php echo showSuccess($success); ?>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
                    </form>

                    <p class="text-center mt-4">
                        Don't have an account? <a href="register.php" class="text-accent">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
