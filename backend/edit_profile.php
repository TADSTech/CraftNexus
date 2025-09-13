<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

// Check if user is logged in and is an artisan
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'artisan') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

try {
    // Validate input
    if (!isset($_POST['name'], $_POST['skills'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $phone_number = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : null;
    $skills = trim($_POST['skills']);
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : null;

    // Handle file upload
    $portfolio_image = null;
    if (isset($_FILES['portfolio_image']) && $_FILES['portfolio_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_name = uniqid() . '-' . basename($_FILES['portfolio_image']['name']);
        $file_path = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['portfolio_image']['tmp_name'], $file_path)) {
            $portfolio_image = 'assets/uploads/' . $file_name;
        } else {
            throw new Exception('Failed to upload image');
        }
    }

    // Update artisan profile
    $sql = "UPDATE artisans SET name = ?, phone_number = ?, skills = ?, bio = ?" . 
           ($portfolio_image ? ", portfolio_image = ?" : "") . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    if ($portfolio_image) {
        $stmt->bind_param('sssssi', $name, $phone_number, $skills, $bio, $portfolio_image, $id);
    } else {
        $stmt->bind_param('ssssi', $name, $phone_number, $skills, $bio, $id);
    }
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
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