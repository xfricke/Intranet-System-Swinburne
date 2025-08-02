<?php
header('Content-Type: application/json');

$valid = true;
$errors = [
  "fname" => "",
  "email" => "",
  "mobile" => "",
  "birthdate" => "",
  "password" => "",
  "confirmPassword" => ""
];

// Extract from $_POST (used with FormData)
$data = [
  "fname" => isset($_POST['fname']) ? $_POST['fname'] : '',
  "email" => isset($_POST['email']) ? $_POST['email'] : '',
  "mobile" => isset($_POST['mobile']) ? $_POST['mobile'] : '',
  "birthdate" => isset($_POST['birthDate']) ? $_POST['birthDate'] : '',
  "password" => isset($_POST['password']) ? $_POST['password'] : '',
  "confirmPassword" => isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : ''
];

// Required field validation
foreach ($data as $key => $value) {
  if (empty($value)) {
    $errors[$key] = ucfirst($key) . " is required.";
    $valid = false;
  }
}

// Password match check
if ($data['password'] !== $data['confirmPassword']) {
  $errors['confirmPassword'] = "Passwords do not match.";
  $valid = false;
}

if (!$valid) {
  http_response_code(400);
  echo json_encode(["errors" => $errors]);
  exit;
}

// Load and decode existing users
$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

// Check for existing email
foreach ($users as $user) {
  if ($user['email'] === $data['email']) {
    http_response_code(409);
    echo json_encode(["message" => "Email already exists."]);
    exit;
  }
}

// Append new user
$newUser = [
  "fname" => $_POST["fname"],
  "lname" => $_POST["lname"],
  "email" => $_POST["email"],
  "mobile" => $_POST["mobile"],
  "birthDate" => $_POST["birthDate"],
  "password" => $_POST["password"]
];

$users[] = $newUser;

// Attempt to write
if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT))) {
  http_response_code(201);
  echo json_encode(["message" => "User saved!", "status" => http_response_code()]);
  exit; 
} else {
  http_response_code(500);
  echo json_encode(["message" => "Failed to write to users.json"]);
  exit; 
}

?>
