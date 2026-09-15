<?php
include "admin_auth.php";
include "../includes/database.php";

$sql = "SELECT id, email, status
        FROM newsletter_subscribers
        ORDER BY id ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=newsletter_subscribers.csv');

$output = fopen('php://output', 'w');

fputcsv($output, array_keys($subscribers[0]));

foreach ($subscribers as $subscriber) {
    fputcsv($output, $subscriber);
}

fclose($output);
exit();