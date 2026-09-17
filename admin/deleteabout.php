<?php

include "admin_auth.php";
include "../includes/database.php";
include "../includes/csrf.php";


$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);


if (!$id) {

    header(
        "Location: admindashboard.php?page=aboutus"
    );

    exit;
}


$stmt = $conn->prepare("
    SELECT *
    FROM about_sections
    WHERE id = :id
");

$stmt->execute([
    'id' => $id
]);


$section = $stmt->fetch(
    PDO::FETCH_ASSOC
);


if (!$section) {

    header(
        "Location: admindashboard.php?page=aboutus"
    );

    exit;
}


$data = json_decode(
    $section['data'],
    true
);


if (is_array($data)) {

    if (!empty($data['image'])) {

        $imagePath =
            "../images/"
            . $data['image'];


        if (file_exists($imagePath)) {

            unlink($imagePath);

        }

    }


    if (
        isset($data['members']) &&
        is_array($data['members'])
    ) {

        foreach ($data['members'] as $member) {

            if (!empty($member['image'])) {

                $imagePath =
                    "../images/"
                    . $member['image'];


                if (file_exists($imagePath)) {

                    unlink($imagePath);

                }

            }

        }

    }

}


$stmt = $conn->prepare("
    DELETE FROM about_sections
    WHERE id = :id
");


$stmt->execute([
    'id' => $id
]);


header(
    "Location: admindashboard.php?page=aboutus"
);

exit;