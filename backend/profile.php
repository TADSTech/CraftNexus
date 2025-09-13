<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

// Check if user is logged in and is a non-artisan
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'non_artisan') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

try {
    $id = $_SESSION['user_id'];
    $sql = "SELECT name, email, is_verified_non_artisan FROM non_artisans WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
    }

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>