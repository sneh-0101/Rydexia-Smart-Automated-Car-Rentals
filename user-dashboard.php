<?php
include 'includes/db.php';
include 'includes/functions.php';

// Require login
requireLogin();

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

// Fetch user bookings
$bookings_query = "SELECT b.*, c.brand, c.model, c.price_per_day FROM bookings b 
                   JOIN cars c ON b.car_id = c.id 
                   WHERE b.user_id = ? 
                   ORDER BY b.created_at DESC";
$stmt = $conn->prepare($bookings_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings_result = $stmt->get_result();
$stmt->close();

// Count bookings by status
$stats_query = "SELECT status, COUNT(*) as count FROM bookings WHERE user_id = ? GROUP BY status";
$stmt = $conn->prepare($stats_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stats_result = $stmt->get_result();
$stats = [];
while ($stat = $stats_result->fetch_assoc()) {
    $stats[$stat['status']] = $stat['count'];
}
$stmt->close();

// Calculate total spent
$spent_query = "SELECT SUM(total_price) as total FROM bookings WHERE user_id = ? AND status IN ('Approved', 'Completed')";
$stmt = $conn->prepare($spent_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$spent_result = $stmt->get_result();
$spent = $spent_result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rydexia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">🚗 RYDEXIA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cars.php">Available Cars</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="user-dashboard.php">Dashboard</a>
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
            <h1>Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="featured-cars">
        <div class="container">
            <!-- User Statistics -->
            <div class="row mb-5">
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <h3>Total Bookings</h3>
                        <div class="stat-number"><?php echo $bookings_result->num_rows; ?></div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card warning">
                        <h3>Pending</h3>
                        <div class="stat-number"><?php echo $stats['Pending'] ?? 0; ?></div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card success">
                        <h3>Approved</h3>
                        <div class="stat-number"><?php echo $stats['Approved'] ?? 0; ?></div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card danger">
                        <h3>Rejected</h3>
                        <div class="stat-number"><?php echo $stats['Rejected'] ?? 0; ?></div>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="card shadow mb-5">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">👤 Profile Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>License Number:</strong> <?php echo htmlspecialchars($user['license_number'] ?? 'N/A'); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></p>
                            <p><strong>City:</strong> <?php echo htmlspecialchars($user['city'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                    <p><strong>Total Spent:</strong> <span class="text-accent" style="font-size: 18px; font-weight: bold;"><?php echo formatCurrency($spent['total'] ?? 0); ?></span></p>
                </div>
            </div>

            <!-- Booking History -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📋 Booking History</h5>
                </div>
                <div class="card-body">
                    <?php if ($bookings_result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Car</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Days</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $bookings_result->data_seek(0);
                                    while ($booking = $bookings_result->fetch_assoc()): 
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></td>
                                            <td><?php echo formatDate($booking['start_date']); ?></td>
                                            <td><?php echo formatDate($booking['end_date']); ?></td>
                                            <td><?php echo $booking['number_of_days']; ?></td>
                                            <td><?php echo formatCurrency($booking['total_price']); ?></td>
                                            <td>
                                                <span class="badge badge-<?php 
                                                    echo strtolower($booking['status']) == 'approved' ? 'success' : 
                                                        (strtolower($booking['status']) == 'pending' ? 'warning' : 
                                                        (strtolower($booking['status']) == 'rejected' ? 'danger' : 'info')); 
                                                ?>">
                                                    <?php echo htmlspecialchars($booking['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="booking-details.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-info">Details</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            No bookings yet. <a href="cars.php">Book a car now</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <a href="cars.php" class="btn btn-primary mt-4">Book Another Car</a>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
