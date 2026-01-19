<?php
include '../includes/db.php';
include '../includes/functions.php';

// Require admin login
requireAdminLogin();

$error = '';
$success = '';
$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch car details
$car_query = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($car_query);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car_result = $stmt->get_result();
$car = $car_result->fetch_assoc();

if (!$car) {
    $error = "Car not found!";
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$error) {
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
    $status = sanitize($_POST['status']);
    $description = sanitize($_POST['description']);
    $car_image = sanitize($_POST['car_image']);

    // Update car
    $update_query = "UPDATE cars SET brand = ?, model = ?, year = ?, color = ?, license_plate = ?, 
                    price_per_day = ?, transmission = ?, fuel_type = ?, seat_capacity = ?, 
                    mileage = ?, status = ?, description = ?, car_image = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("siisisssissssi", $brand, $model, $year, $color, $license_plate, $price_per_day,
                     $transmission, $fuel_type, $seat_capacity, $mileage, $status, $description, $car_image, $car_id);

    if ($stmt->execute()) {
        $success = "Car updated successfully!";
        header("refresh:2;url=manage-cars.php");
    } else {
        $error = "Failed to update car! " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car - Rydexia Admin</title>
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

    <!-- Edit Car Form -->
    <section class="featured-cars">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                        <a href="manage-cars.php" class="btn btn-primary">Back to Cars</a>
                    <?php else: ?>
                        <div class="card shadow">
                            <div class="card-body p-5">
                                <h2 class="card-title mb-4 text-primary">Edit Car</h2>

                                <?php if ($success): ?>
                                    <?php echo showSuccess($success); ?>
                                <?php endif; ?>

                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="brand">Brand *</label>
                                                <input type="text" class="form-control" id="brand" name="brand" value="<?php echo htmlspecialchars($car['brand']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="model">Model *</label>
                                                <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="year">Year</label>
                                                <input type="number" class="form-control" id="year" name="year" value="<?php echo $car['year']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="color">Color</label>
                                                <input type="text" class="form-control" id="color" name="color" value="<?php echo htmlspecialchars($car['color']); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="license_plate">License Plate *</label>
                                                <input type="text" class="form-control" id="license_plate" name="license_plate" value="<?php echo htmlspecialchars($car['license_plate']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price_per_day">Price per Day ($) *</label>
                                                <input type="number" class="form-control" id="price_per_day" name="price_per_day" step="0.01" value="<?php echo $car['price_per_day']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="transmission">Transmission</label>
                                                <select class="form-control" id="transmission" name="transmission">
                                                    <option <?php echo $car['transmission'] == 'Manual' ? 'selected' : ''; ?>>Manual</option>
                                                    <option <?php echo $car['transmission'] == 'Automatic' ? 'selected' : ''; ?>>Automatic</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fuel_type">Fuel Type</label>
                                                <select class="form-control" id="fuel_type" name="fuel_type">
                                                    <option <?php echo $car['fuel_type'] == 'Petrol' ? 'selected' : ''; ?>>Petrol</option>
                                                    <option <?php echo $car['fuel_type'] == 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                                                    <option <?php echo $car['fuel_type'] == 'Hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                                                    <option <?php echo $car['fuel_type'] == 'Electric' ? 'selected' : ''; ?>>Electric</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="seat_capacity">Seat Capacity</label>
                                                <input type="number" class="form-control" id="seat_capacity" name="seat_capacity" value="<?php echo $car['seat_capacity']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mileage">Mileage (km)</label>
                                                <input type="number" class="form-control" id="mileage" name="mileage" value="<?php echo $car['mileage']; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option <?php echo $car['status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                                            <option <?php echo $car['status'] == 'Booked' ? 'selected' : ''; ?>>Booked</option>
                                            <option <?php echo $car['status'] == 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                            <option <?php echo $car['status'] == 'Retired' ? 'selected' : ''; ?>>Retired</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="car_image">Car Image URL</label>
                                        <input type="url" class="form-control" id="car_image" name="car_image" value="<?php echo htmlspecialchars($car['car_image']); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($car['description']); ?></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">Update Car</button>
                                    <a href="manage-cars.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
