<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

// Check if user is logged in and is a non-artisan (entrepreneur)
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'non_artisan') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

try {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or missing artisan ID']);
        exit;
    }

    $id = intval($_GET['id']);
    $sql = "SELECT id, name, skills, portfolio_image, joined_date, phone_number, 
                   is_verified_artisan, sudo_verified_status, jobs_completed, jobs_failed, 
                   bio FROM artisans WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $artisan = $result->fetch_assoc();
    $stmt->close();

    if ($artisan) {
        // Add bio if not already in database (for backward compatibility)
        $artisan['bio'] = isset($artisan['bio']) ? $artisan['bio'] : 'No bio available';
        echo json_encode(['success' => true, 'artisan' => $artisan]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Artisan not found']);
    }

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>