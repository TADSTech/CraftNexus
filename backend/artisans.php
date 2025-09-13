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
    $sql = "SELECT id, name, skills FROM artisans";
    $result = $conn->query($sql);

    if ($result) {
        $artisans = [];
        while ($row = $result->fetch_assoc()) {
            $artisans[] = $row;
        }
        echo json_encode(['success' => true, 'artisans' => $artisans]);
    } else {
        throw new Exception('Database query error: ' . $conn->error);
    }

    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>