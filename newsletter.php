<?php
session_start();
include "includes/database.php";

header("Content-Type: application/json");
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);
    exit();
}

$email = trim($_POST["email"] ?? "");
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address."
    ]);
    exit();
}

$sql = "SELECT id
        FROM newsletter_subscribers
        WHERE email = :email";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ":email" => $email
]);

if ($stmt->fetch()) {
    echo json_encode([
        "success" => false,
        "message" => "This email is already subscribed!"
    ]);
    exit();
}

$sql = "INSERT INTO newsletter_subscribers (email)
        VALUES (:email)";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ":email" => $email
]);

echo json_encode([
    "success" => true,
    "message" => "Successfully subscribed!"
]);

exit();