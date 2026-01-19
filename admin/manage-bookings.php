<?php
include '../includes/db.php';
include '../includes/functions.php';

// Require admin login
requireAdminLogin();

// Fetch all bookings
$query = "SELECT b.*, u.first_name, u.last_name, u.email, c.brand, c.model 
         FROM bookings b 
         JOIN users u ON b.user_id = u.id 
         JOIN cars c ON b.car_id = c.id 
         ORDER BY b.created_at DESC";
$result = $conn->query($query);

// Handle status update
if (isset($_POST['booking_id']) && isset($_POST['status'])) {
    $booking_id = intval($_POST['booking_id']);
    $status = sanitize($_POST['status']);
    
    $update_query = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $booking_id);
    if ($stmt->execute()) {
        header("Location: manage-bookings.php?success=Booking status updated!");
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
    <title>Manage Bookings - Rydexia Admin</title>
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
                        <a class="nav-link" href="manage-cars.php">Manage Cars</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage-bookings.php">Manage Bookings</a>
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
            <h1>Manage Bookings</h1>
            <p>Review and approve user bookings</p>
        </div>
    </section>

    <!-- Bookings Management -->
    <section class="featured-cars">
        <div class="container">
            <?php if (isset($_GET['success'])): ?>
                <?php echo showSuccess($_GET['success']); ?>
            <?php endif; ?>

            <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Car</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $booking['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></strong><br>
                                        <small><?php echo htmlspecialchars($booking['email']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></td>
                                    <td><?php echo formatDate($booking['start_date']); ?></td>
                                    <td><?php echo formatDate($booking['end_date']); ?></td>
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
                                        <form method="POST" action="" style="display: inline;">
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit();">
                                                <option <?php echo $booking['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option <?php echo $booking['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                                <option <?php echo $booking['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                <option <?php echo $booking['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                <option <?php echo $booking['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    No bookings found.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
