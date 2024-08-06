<?php
// Database credentials
$host = 'localhost';
$db = 'tech_corp';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// DSN for PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Connect to the database
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Function to sanitize data
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Validate input data
function validateInput($name, $email) {
    if (empty($name) || empty($email)) {
        return false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

// Get and sanitize form data
$name = sanitize($_POST['name']);
$email = sanitize($_POST['email']);
$message = sanitize($_POST['message']);

// Validate form data
if (!validateInput($name, $email)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    exit;
}

// Prepare and execute the SQL statement
$sql = "INSERT INTO contact (name, email, message) VALUES (:name, :email, :message)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute(['name' => $name, 'email' => $email, 'message' => $message]);
    echo json_encode(['status' => 'success', 'message' => 'Form submitted successfully']);
} catch (\PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save data']);
}
?>
