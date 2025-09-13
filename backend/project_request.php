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
    // Validate input
    if (!isset($_POST['artisan_id'], $_POST['title'], $_POST['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $artisan_id = intval($_POST['artisan_id']);
    $non_artisan_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    // Verify artisan exists
    $sql = "SELECT id FROM artisans WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $artisan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result->fetch_assoc()) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid artisan ID']);
        exit;
    }
    $stmt->close();

    // Insert project
    $sql = "INSERT INTO projects (artisan_id, non_artisan_id, title, description, status) VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    $stmt->bind_param('iiss', $artisan_id, $non_artisan_id, $title, $description);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Project request submitted successfully']);
    } else {
        throw new Exception('Database execute error: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>