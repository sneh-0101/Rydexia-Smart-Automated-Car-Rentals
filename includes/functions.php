<?php
// Authentication Helper Functions

// Check if user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Redirect to login if not logged in
function requireLogin() {
    if (!isUserLoggedIn()) {
        header("Location: /Rydexia-Smart-Automated-Car-Rentals/login.php");
        exit();
    }
}

// Redirect to admin login if not admin
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: /Rydexia-Smart-Automated-Car-Rentals/admin/login.php");
        exit();
    }
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Sanitize input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Display error message
function showError($message) {
    return "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

// Display success message
function showSuccess($message) {
    return "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

// Format date
function formatDate($date) {
    return date('d-m-Y', strtotime($date));
}

// Calculate days between dates
function calculateDays($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    return $interval->days + 1;
}

// Format currency
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

?>
