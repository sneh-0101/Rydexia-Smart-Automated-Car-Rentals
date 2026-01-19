<?php
include '../includes/db.php';
include '../includes/functions.php';

// Require admin login
requireAdminLogin();

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand = sanitize($_POST['brand']);
    $model = sanitize($_POST['model']);
    $year = intval($_POST['year']);
    $color = sanitize($_POST['color']);
    $license_plate = sanitize($_POST['license_plate']);
    $price_per_day = floatval($_POST['price_per_day']);
    $transmission = sanitize($_POST['transmission']);
    $fuel_type = sanitize($_POST['fuel_type']);
    $seat_capacity = intval($_POST['seat_capacity']);
    $mileage = intval($_POST['mileage']);
    $description = sanitize($_POST['description']);
    $car_image = sanitize($_POST['car_image']);

    // Validation
    if (empty($brand) || empty($model) || empty($license_plate) || empty($price_per_day)) {
        $error = "Required fields are missing!";
    } else {
        // Insert car
        $insert_query = "INSERT INTO cars (brand, model, year, color, license_plate, price_per_day, 
                        transmission, fuel_type, seat_capacity, mileage, description, car_image) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssisisssiiss", $brand, $model, $year, $color, $license_plate, $price_per_day,
                         $transmission, $fuel_type, $seat_capacity, $mileage, $description, $car_image);

        if ($stmt->execute()) {
            $success = "Car added successfully!";
            header("refresh:2;url=manage-cars.php");
        } else {
            $error = "Failed to add car! " . $stmt->error;
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
    <title>Add Car - Rydexia Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">🚗 RYDEXIA Admin</a>
            <a href="manage-cars.php" class="btn btn-secondary ms-auto">Back to Cars</a>
        </div>
    </nav>

    <!-- Add Car Form -->
    <section class="featured-cars">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-body p-5">
                            <h2 class="card-title mb-4 text-primary">Add New Car</h2>

                            <?php if ($error): ?>
                                <?php echo showError($error); ?>
                            <?php endif; ?>

                            <?php if ($success): ?>
                                <?php echo showSuccess($success); ?>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="brand">Brand *</label>
                                            <input type="text" class="form-control" id="brand" name="brand" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="model">Model *</label>
                                            <input type="text" class="form-control" id="model" name="model" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="year">Year</label>
                                            <input type="number" class="form-control" id="year" name="year" min="2000" max="2030">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="color">Color</label>
                                            <input type="text" class="form-control" id="color" name="color">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="license_plate">License Plate *</label>
                                            <input type="text" class="form-control" id="license_plate" name="license_plate" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price_per_day">Price per Day ($) *</label>
                                            <input type="number" class="form-control" id="price_per_day" name="price_per_day" step="0.01" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="transmission">Transmission</label>
                                            <select class="form-control" id="transmission" name="transmission">
                                                <option>Manual</option>
                                                <option>Automatic</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fuel_type">Fuel Type</label>
                                            <select class="form-control" id="fuel_type" name="fuel_type">
                                                <option>Petrol</option>
                                                <option>Diesel</option>
                                                <option>Hybrid</option>
                                                <option>Electric</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="seat_capacity">Seat Capacity</label>
                                            <input type="number" class="form-control" id="seat_capacity" name="seat_capacity" min="1" max="8">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mileage">Mileage (km)</label>
                                            <input type="number" class="form-control" id="mileage" name="mileage">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="car_image">Car Image URL</label>
                                    <input type="url" class="form-control" id="car_image" name="car_image" placeholder="https://example.com/car.jpg">
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Add Car</button>
                                <a href="manage-cars.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
