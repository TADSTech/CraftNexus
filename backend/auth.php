<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

try {
    // Validate input
    if (!isset($_POST['email'], $_POST['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check artisans table
    $sql = "SELECT id, name, email, password, is_verified_artisan FROM artisans WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $artisan = $result->fetch_assoc();
    $stmt->close();

    // Check non_artisans table if not found in artisans
    if (!$artisan) {
        $sql = "SELECT id, name, email, password, is_verified_non_artisan FROM non_artisans WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Database prepare error: ' . $conn->error);
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $non_artisan = $result->fetch_assoc();
        $stmt->close();
    }

    // Verify password and set session
    if ($artisan && password_verify($password, $artisan['password'])) {
        $_SESSION['user_id'] = $artisan['id'];
        $_SESSION['user_type'] = 'artisan';
        $_SESSION['user_name'] = $artisan['name'];
        $_SESSION['is_verified'] = $artisan['is_verified_artisan'];
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user_type' => 'artisan',
            'user_name' => $artisan['name']
        ]);
    } elseif ($non_artisan && password_verify($password, $non_artisan['password'])) {
        $_SESSION['user_id'] = $non_artisan['id'];
        $_SESSION['user_type'] = 'non_artisan';
        $_SESSION['user_name'] = $non_artisan['name'];
        $_SESSION['is_verified'] = $non_artisan['is_verified_non_artisan'];
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user_type' => 'non_artisan',
            'user_name' => $non_artisan['name']
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid email or password']);
    }

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>