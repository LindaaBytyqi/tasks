<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contactus.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $lastName === '' || $email === '' || $message === '') {
    $_SESSION['contact_error'] = 'Please fill in all required fields.';
    header('Location: contactus.php');
    exit;
}

if (!preg_match('/^[\p{L}\s]+$/u', $name)) {
    $_SESSION['contact_error'] = 'Name can contain only letters and spaces.';
    header('Location: contactus.php');
    exit;
}

if (!preg_match('/^[\p{L}\s]+$/u', $lastName)) {
    $_SESSION['contact_error'] = 'Last name can contain only letters and spaces.';
    header('Location: contactus.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['contact_error'] = 'Please enter a valid email address.';
    header('Location: contactus.php');
    exit;
}

if (!preg_match('/^[0-9+\-\s()]+$/', $phone)) {
    $_SESSION['contact_error'] = 'Please enter a valid phone number.';
    header('Location: contactus.php');
    exit;
}

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;



    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('lindabytyqi64@gmail.com', 'MyShop Contact Form');

    $mail->addAddress('lindabytyqi64@gmail.com');

    $mail->addReplyTo($email, $name . ' ' . $lastName);

    $mail->isHTML(true);
    $mail->Subject = 'MyShop-Message';

    $mail->Body = '
        <h2>MyShop - Contact Us Message</h2>

        <p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>
        <p><strong>Last Name:</strong> ' . htmlspecialchars($lastName) . '</p>
        <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
        <p><strong>Phone:</strong> ' . htmlspecialchars($phone) . '</p>

        <p><strong>Message:</strong></p>
        <p>' . nl2br(htmlspecialchars($message)) . '</p>
    ';

    $mail->AltBody =
        "MyShop - Contact Us Message\n\n" .
        "Name: $name $lastName\n" .
        "Email: $email\n" .
        "Phone: $phone\n\n" .
        "Message:\n$message";

    $mail->send();

    $_SESSION['contact_success'] = 'Your message has been sent successfully!';

} catch (Exception $e) {

    $_SESSION['contact_error'] = 'Message could not be sent. Please try again later.';
}

header('Location: contactus.php');
exit;