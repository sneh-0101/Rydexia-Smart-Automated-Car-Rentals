<?php
include 'includes/db.php';
include 'includes/functions.php';

// Require login
requireLogin();

$booking_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Fetch booking details
$query = "SELECT b.*, c.* FROM bookings b 
         JOIN cars c ON b.car_id = c.id 
         WHERE b.id = ? AND b.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("Booking not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - Rydexia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">🚗 RYDEXIA</a>
            <a href="user-dashboard.php" class="btn btn-secondary ms-auto">Back to Dashboard</a>
        </div>
    </nav>

    <!-- Booking Details -->
    <section class="featured-cars">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-body p-5">
                            <h2 class="card-title mb-4 text-primary">Booking Details</h2>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Vehicle Information</h6>
                                    <p>
                                        <strong>Brand & Model:</strong> <?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?><br>
                                        <strong>Year:</strong> <?php echo $booking['year']; ?><br>
                                        <strong>License Plate:</strong> <?php echo htmlspecialchars($booking['license_plate']); ?><br>
                                        <strong>Color:</strong> <?php echo htmlspecialchars($booking['color']); ?><br>
                                        <strong>Transmission:</strong> <?php echo htmlspecialchars($booking['transmission']); ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <img src="<?php echo htmlspecialchars($booking['car_image']); ?>" class="img-fluid rounded" alt="Car">
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Rental Period</h6>
                                    <p>
                                        <strong>Start Date:</strong> <?php echo formatDate($booking['start_date']); ?><br>
                                        <strong>End Date:</strong> <?php echo formatDate($booking['end_date']); ?><br>
                                        <strong>Number of Days:</strong> <?php echo $booking['number_of_days']; ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Locations</h6>
                                    <p>
                                        <strong>Pickup:</strong> <?php echo htmlspecialchars($booking['pickup_location']); ?><br>
                                        <strong>Dropoff:</strong> <?php echo htmlspecialchars($booking['dropoff_location']); ?>
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Pricing</h6>
                                    <p>
                                        <strong>Price per Day:</strong> <?php echo formatCurrency($booking['price_per_day']); ?><br>
                                        <strong>Total Price:</strong> <span style="font-size: 18px; color: var(--accent-color); font-weight: bold;"><?php echo formatCurrency($booking['total_price']); ?></span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Booking Status</h6>
                                    <p>
                                        <span class="badge badge-<?php 
                                            echo strtolower($booking['status']) == 'approved' ? 'success' : 
                                                (strtolower($booking['status']) == 'pending' ? 'warning' : 
                                                (strtolower($booking['status']) == 'rejected' ? 'danger' : 'info')); 
                                        ?>" style="font-size: 14px; padding: 8px 12px;">
                                            <?php echo htmlspecialchars($booking['status']); ?>
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <?php if (!empty($booking['notes'])): ?>
                                <hr>
                                <h6 class="text-primary">Additional Notes</h6>
                                <p><?php echo htmlspecialchars($booking['notes']); ?></p>
                            <?php endif; ?>

                            <div class="mt-4">
                                <a href="user-dashboard.php" class="btn btn-primary">Back to Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
