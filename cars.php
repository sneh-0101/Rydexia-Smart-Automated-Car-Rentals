<?php
include 'includes/db.php';
include 'includes/functions.php';

// Fetch all available cars
$query = "SELECT * FROM cars ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Cars - Rydexia</title>
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
                        <a class="nav-link active" href="cars.php">Available Cars</a>
                    </li>
                    <?php if (isUserLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="user-dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Title -->
    <section class="hero" style="min-height: 250px;">
        <div class="hero-content">
            <h1>Available Cars</h1>
            <p>Choose from our wide selection of vehicles</p>
        </div>
    </section>

    <!-- Cars Listing -->
    <section class="featured-cars">
        <div class="container">
            <div class="car-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($car = $result->fetch_assoc()): ?>
                        <div class="card">
                            <div class="card-image">
                                <img src="<?php echo htmlspecialchars($car['car_image'] ?? 'https://via.placeholder.com/300x250?text=Car+Image'); ?>" alt="<?php echo htmlspecialchars($car['model']); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h5>
                                <p class="card-text">
                                    <strong>Year:</strong> <?php echo htmlspecialchars($car['year']); ?><br>
                                    <strong>Color:</strong> <?php echo htmlspecialchars($car['color']); ?><br>
                                    <strong>Transmission:</strong> <?php echo htmlspecialchars($car['transmission']); ?><br>
                                    <strong>Seats:</strong> <?php echo htmlspecialchars($car['seat_capacity']); ?><br>
                                    <strong>Fuel:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?><br>
                                </p>
                                <p class="card-text"><?php echo htmlspecialchars(substr($car['description'] ?? '', 0, 100)); ?>...</p>
                                <div class="card-price"><?php echo formatCurrency($car['price_per_day']); ?>/day</div>
                                <span class="badge badge-<?php echo strtolower($car['status']) == 'available' ? 'success' : 'danger'; ?>">
                                    <?php echo htmlspecialchars($car['status']); ?>
                                </span>
                                <?php if (strtolower($car['status']) == 'available'): ?>
                                    <?php if (isUserLoggedIn()): ?>
                                        <a href="booking.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary btn-sm w-100 mt-3">Book Now</a>
                                    <?php else: ?>
                                        <a href="login.php" class="btn btn-primary btn-sm w-100 mt-3">Login to Book</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm w-100 mt-3" disabled>Not Available</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info w-100">No cars available at the moment.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-bottom text-center" style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px;">
            <p>&copy; 2026 Rydexia Smart Automated Car Rentals. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
