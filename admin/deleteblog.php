<?php

include "admin_auth.php";
include "../includes/database.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: admindashboard.php?page=blog");
    exit;
}

$sql = "DELETE FROM blogs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

header("Location: admindashboard.php?page=blog");
exit;