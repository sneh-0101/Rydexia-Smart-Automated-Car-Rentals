<?php
include 'includes/db.php';

// Fetch featured cars
$query = "SELECT * FROM cars WHERE status = 'Available' LIMIT 6";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rydexia - Smart Automated Car Rentals</title>
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
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cars.php">Available Cars</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="admin/">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Rydexia</h1>
            <p>Experience hassle-free car rental with our smart automated system</p>
            <a href="cars.php" class="btn btn-primary">Browse Cars</a>
            <?php if (!isUserLoggedIn()): ?>
                <a href="register.php" class="btn btn-secondary ms-2">Get Started</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Featured Cars Section -->
    <section class="featured-cars">
        <div class="container">
            <h2 class="section-title">Featured Cars</h2>
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
                                    <strong>Seats:</strong> <?php echo htmlspecialchars($car['seat_capacity']); ?><br>
                                    <strong>Fuel:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?>
                                </p>
                                <div class="card-price"><?php echo formatCurrency($car['price_per_day']); ?>/day</div>
                                <?php if (isUserLoggedIn()): ?>
                                    <a href="booking.php?car_id=<?php echo $car['id']; ?>" class="btn btn-primary btn-sm w-100">Book Now</a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-primary btn-sm w-100">Login to Book</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No cars available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="featured-cars" style="background-color: var(--light-bg);">
        <div class="container">
            <h2 class="section-title">Why Choose Rydexia?</h2>
            <div class="row mt-5">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-accent" style="font-size: 32px;">⚡</h5>
                            <h6 class="card-title">Quick Booking</h6>
                            <p class="card-text">Book a car in just a few clicks</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-accent" style="font-size: 32px;">💰</h5>
                            <h6 class="card-title">Affordable Rates</h6>
                            <p class="card-text">Competitive pricing for quality cars</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-accent" style="font-size: 32px;">🚗</h5>
                            <h6 class="card-title">Wide Selection</h6>
                            <p class="card-text">Choose from various vehicle options</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-accent" style="font-size: 32px;">🛡️</h5>
                            <h6 class="card-title">Safe & Secure</h6>
                            <p class="card-text">Fully insured vehicles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>About Rydexia</h4>
                    <p>Your trusted car rental partner for all your transportation needs.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="cars.php">Our Cars</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact Us</h4>
                    <ul>
                        <li>Email: info@rydexia.com</li>
                        <li>Phone: +1 (555) 123-4567</li>
                        <li>Address: 123 Car Lane, Auto City, AC 12345</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Follow Us</h4>
                    <ul>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Instagram</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Rydexia Smart Automated Car Rentals. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
