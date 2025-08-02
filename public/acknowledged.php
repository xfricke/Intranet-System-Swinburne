<?php

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$email = isset($data['email']) ? $data['email'] : '';
$announcement = isset($data['announcement']) ? $data['announcement'] : '';

if (!$email || !$announcement) {
  http_response_code(400);
  echo json_encode(["message" => "Missing email or announcement"]);
  exit;
}

//define path
$file = 'acknowledged.json';
$acknowledged = [];

if (file_exists($file)) {
  $json = file_get_contents($file);
  $acknowledged = json_decode($json, true);
}

// ensure user key exists
if (!isset($acknowledged[$email])) {
  $acknowledged[$email] = [];
}

// avoid duplicate announcements
if (!in_array($announcement, $acknowledged[$email])) {
  $acknowledged[$email][] = $announcement;
  file_put_contents($file, json_encode($acknowledged, JSON_PRETTY_PRINT));
}

echo json_encode(["message" => "Acknowledged"]);
?>
