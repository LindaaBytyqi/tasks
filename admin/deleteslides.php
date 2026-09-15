<?php
include "admin_auth.php";
include "../includes/database.php";


$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);


if (!$id) {
    header(
        "Location: admindashboard.php?page=hero"
    );
    exit;
}

$stmt = $conn->prepare("
    SELECT image
    FROM hero_slides
    WHERE id = :id
");

$stmt->execute([
    "id" => $id
]);

$slide = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$slide) {

    header(
        "Location: admindashboard.php?page=hero"
    );

    exit;
}

$stmt = $conn->prepare("
    DELETE FROM hero_slides
    WHERE id = :id
");

$stmt->execute([
    "id" => $id
]);

if (!empty($slide['image'])) {
    $imagePath = "../images/" . $slide['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}


header(
    "Location: admindashboard.php?page=hero"
);

exit;