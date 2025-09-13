<?php
require_once 'db.php';

header('Content-Type: application/json');

try {
    // Validate input
    if (!isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['user_type'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    // Validate user type
    if (!in_array($user_type, ['artisan', 'non_artisan'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid user type']);
        exit;
    }

    // Prepare SQL based on user type
    if ($user_type === 'artisan') {
        $sql = "INSERT INTO artisans (name, email, password, skills, joined_date) VALUES (?, ?, ?, '', CURDATE())";
    } else {
        $sql = "INSERT INTO non_artisans (name, email, password) VALUES (?, ?, ?)";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }

    $stmt->bind_param('sss', $name, $email, $password);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
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