<?php
header('Content-Type: application/json');

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// Initialize validation and errors
$valid = true;
$errors = [
  "email" => "",
  "password" => ""
];

// Frontend-style required field validation
if (empty($data['email'])) {
  $errors['email'] = "Email is required.";
  $valid = false;
}

if (empty($data['password'])) {
  $errors['password'] = "Password is required.";
  $valid = false;
}

if (!$valid) {
  http_response_code(400);
  echo json_encode(["errors" => $errors]);
  exit;
}

// Load users
$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

foreach ($users as $user) {
  if ($user['email'] === $data['email'] && $user['password'] === $data['password']) {
    echo json_encode([
      "fname" => $user["fname"],
      "email" => $user["email"]
    ]);
    exit;
  }
}

// If no match found
http_response_code(401);
echo json_encode(["message" => "Invalid credentials."]);
?>
