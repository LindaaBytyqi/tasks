<?php

include "admin_auth.php";
include "../includes/csrf.php";
include "../includes/database.php";

$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);


if (!$id) {
    header(
        "Location: admindashboard.php?page=contact"
    );
    exit;
}

$sql = "
    DELETE FROM contact_settings
    WHERE id = :id
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    "id" => $id
]);


header(
    "Location: admindashboard.php?page=contact"
);
exit;