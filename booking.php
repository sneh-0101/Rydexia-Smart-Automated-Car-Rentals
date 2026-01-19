<?php
include 'includes/db.php';
include 'includes/functions.php';

// Require login
requireLogin();

$error = '';
$success = '';
$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;

// Fetch car details
$car_query = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($car_query);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car_result = $stmt->get_result();
$car = $car_result->fetch_assoc();

if (!$car) {
    $error = "Invalid car selected!";
}

$stmt->close();

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$error) {
    $start_date = sanitize($_POST['start_date']);
    $end_date = sanitize($_POST['end_date']);
    $pickup_location = sanitize($_POST['pickup_location']);
    $dropoff_location = sanitize($_POST['dropoff_location']);
    $notes = sanitize($_POST['notes']);

    // Validation
    if (empty($start_date) || empty($end_date) || empty($pickup_location) || empty($dropoff_location)) {
        $error = "All required fields must be filled!";
    } elseif (strtotime($end_date) <= strtotime($start_date)) {
        $error = "End date must be after start date!";
    } else {
        // Calculate days and total price
        $number_of_days = calculateDays($start_date, $end_date);
        $total_price = $number_of_days * $car['price_per_day'];

        // Insert booking
        $user_id = $_SESSION['user_id'];
        $insert_query = "INSERT INTO bookings (user_id, car_id, start_date, end_date, pickup_location, 
                        dropoff_location, number_of_days, total_price, notes) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iissssids", $user_id, $car_id, $start_date, $end_date, 
                         $pickup_location, $dropoff_location, $number_of_days, $total_price, $notes);

        if ($stmt->execute()) {
            $success = "Booking created successfully! Your booking is pending approval.";
            header("refresh:3;url=user-dashboard.php");
        } else {
            $error = "Booking failed! Please try again.";
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
    <title>Book Car - Rydexia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">🚗 RYDEXIA</a>
            <a href="cars.php" class="btn btn-secondary ms-auto">Back to Cars</a>
        </div>
    </nav>

    <!-- Booking Section -->
    <section class="featured-cars">
        <div class="container">
            <div class="row">
                <?php if (!$error && $car): ?>
                    <div class="col-md-5">
                        <div class="card shadow mb-4">
                            <div class="card-image">
                                <img src="<?php echo htmlspecialchars($car['car_image'] ?? 'https://via.placeholder.com/400x300?text=Car+Image'); ?>" alt="<?php echo htmlspecialchars($car['model']); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h5>
                                <p class="card-text">
                                    <strong>Year:</strong> <?php echo htmlspecialchars($car['year']); ?><br>
                                    <strong>Color:</strong> <?php echo htmlspecialchars($car['color']); ?><br>
                                    <strong>Transmission:</strong> <?php echo htmlspecialchars($car['transmission']); ?><br>
                                    <strong>Seats:</strong> <?php echo htmlspecialchars($car['seat_capacity']); ?><br>
                                    <strong>Fuel:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?><br>
                                    <strong>Mileage:</strong> <?php echo htmlspecialchars($car['mileage']); ?> km
                                </p>
                                <div class="stat-card">
                                    <div class="stat-number"><?php echo formatCurrency($car['price_per_day']); ?></div>
                                    <p>per day</p>
                                </div>
                                <p><?php echo htmlspecialchars($car['description'] ?? 'Premium quality car'); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card shadow">
                            <div class="card-body p-5">
                                <h2 class="card-title mb-4 text-primary">Booking Details</h2>

                                <?php if ($error): ?>
                                    <?php echo showError($error); ?>
                                <?php endif; ?>

                                <?php if ($success): ?>
                                    <?php echo showSuccess($success); ?>
                                <?php endif; ?>

                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="pickup_location">Pickup Location</label>
                                        <input type="text" class="form-control" id="pickup_location" name="pickup_location" placeholder="Enter pickup address" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="dropoff_location">Dropoff Location</label>
                                        <input type="text" class="form-control" id="dropoff_location" name="dropoff_location" placeholder="Enter dropoff address" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="notes">Additional Notes (Optional)</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special requests?"></textarea>
                                    </div>

                                    <div class="alert alert-info">
                                        <strong>Booking Info:</strong> Your booking will be pending approval. You'll receive confirmation via email once approved.
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">Confirm Booking</button>
                                    <a href="cars.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <?php echo $error ?: 'Invalid booking request!'; ?>
                        </div>
                        <a href="cars.php" class="btn btn-primary">Back to Cars</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
