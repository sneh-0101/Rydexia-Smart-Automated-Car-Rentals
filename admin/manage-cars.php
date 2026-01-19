<?php
include '../includes/db.php';
include '../includes/functions.php';

// Require admin login
requireAdminLogin();

// Fetch all cars
$query = "SELECT * FROM cars ORDER BY created_at DESC";
$result = $conn->query($query);

// Handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM cars WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: manage-cars.php?success=Car deleted successfully!");
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cars - Rydexia Admin</title>
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
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage-cars.php">Manage Cars</a>
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
            <h1>Manage Cars</h1>
            <p>Add, edit, or remove vehicles from your fleet</p>
        </div>
    </section>

    <!-- Cars Management -->
    <section class="featured-cars">
        <div class="container">
            <?php if (isset($_GET['success'])): ?>
                <?php echo showSuccess($_GET['success']); ?>
            <?php endif; ?>

            <a href="add-car.php" class="btn btn-primary mb-4">➕ Add New Car</a>

            <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Brand / Model</th>
                                <th>Year</th>
                                <th>License Plate</th>
                                <th>Price/Day</th>
                                <th>Seats</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($car = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $car['id']; ?></td>
                                    <td><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></td>
                                    <td><?php echo $car['year']; ?></td>
                                    <td><?php echo htmlspecialchars($car['license_plate']); ?></td>
                                    <td><?php echo formatCurrency($car['price_per_day']); ?></td>
                                    <td><?php echo $car['seat_capacity']; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($car['status']) == 'available' ? 'success' : 'warning'; ?>">
                                            <?php echo htmlspecialchars($car['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit-car.php?id=<?php echo $car['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="manage-cars.php?delete_id=<?php echo $car['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    No cars found. <a href="add-car.php">Add a new car</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
