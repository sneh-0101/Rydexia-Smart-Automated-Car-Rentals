<?php
include '../includes/db.php';
include '../includes/functions.php';

// Require admin login
requireAdminLogin();

// Fetch statistics
$stats = [];

// Total users
$users_query = "SELECT COUNT(*) as count FROM users";
$users_result = $conn->query($users_query);
$stats['users'] = $users_result->fetch_assoc()['count'];

// Total cars
$cars_query = "SELECT COUNT(*) as count FROM cars";
$cars_result = $conn->query($cars_query);
$stats['cars'] = $cars_result->fetch_assoc()['count'];

// Total bookings
$bookings_query = "SELECT COUNT(*) as count FROM bookings";
$bookings_result = $conn->query($bookings_query);
$stats['bookings'] = $bookings_result->fetch_assoc()['count'];

// Total revenue
$revenue_query = "SELECT SUM(total_price) as total FROM bookings WHERE status IN ('Approved', 'Completed')";
$revenue_result = $conn->query($revenue_query);
$stats['revenue'] = $revenue_result->fetch_assoc()['total'] ?? 0;

// Pending bookings
$pending_query = "SELECT COUNT(*) as count FROM bookings WHERE status = 'Pending'";
$pending_result = $conn->query($pending_query);
$stats['pending'] = $pending_result->fetch_assoc()['count'];

// Available cars
$available_query = "SELECT COUNT(*) as count FROM cars WHERE status = 'Available'";
$available_result = $conn->query($available_query);
$stats['available'] = $available_result->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Rydexia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">🚗 RYDEXIA Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage-cars.php">Manage Cars</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage-bookings.php">Manage Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Title -->
    <section class="hero" style="min-height: 250px;">
        <div class="hero-content">
            <h1>Admin Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</p>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="featured-cars">
        <div class="container">
            <h2 class="section-title mb-5">Statistics Overview</h2>

            <div class="row mb-5">
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <h3>Total Users</h3>
                        <div class="stat-number"><?php echo $stats['users']; ?></div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card warning">
                        <h3>Total Cars</h3>
                        <div class="stat-number"><?php echo $stats['cars']; ?></div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card success">
                        <h3>Total Bookings</h3>
                        <div class="stat-number"><?php echo $stats['bookings']; ?></div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-4 mb-3">
                    <div class="stat-card danger">
                        <h3>Total Revenue</h3>
                        <div class="stat-number"><?php echo formatCurrency($stats['revenue']); ?></div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <h3>Pending Bookings</h3>
                        <div class="stat-number"><?php echo $stats['pending']; ?></div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card success">
                        <h3>Available Cars</h3>
                        <div class="stat-number"><?php echo $stats['available']; ?></div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title">Manage Cars</h5>
                            <p class="card-text">Add, edit, or delete vehicles</p>
                            <a href="manage-cars.php" class="btn btn-primary">Go to Cars</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title">Manage Bookings</h5>
                            <p class="card-text">Approve or reject bookings</p>
                            <a href="manage-bookings.php" class="btn btn-primary">Go to Bookings</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title">Add New Car</h5>
                            <p class="card-text">Add a new vehicle to fleet</p>
                            <a href="add-car.php" class="btn btn-primary">Add Car</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
