<?php
session_start();
header('Content-Type: application/json');

try {
    // Destroy the session
    session_unset();
    session_destroy();

    echo json_encode(['success' => true, 'message' => 'Logout successful']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Logout failed: ' . $e->getMessage()]);
}
?>