<?php

include "admin_auth.php";
include "../includes/csrf.php";
include "../includes/database.php";

if (isset($_GET['id'])) {

    $id = filter_input(
        INPUT_GET,
        'id',
        FILTER_VALIDATE_INT
    );

    if (!$id) {
        header("Location: admindashboard.php?page=contact");
        exit;
    }


    $sql = "
        SELECT *
        FROM contact_settings
        WHERE id = :id
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "id" => $id
    ]);

    $contact = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$contact) {
        header("Location: admindashboard.php?page=contact");
        exit;
    }

}

if (isset($_POST['update_contact'])) {

    verifyCsrfToken();


    $id = filter_input(
        INPUT_POST,
        'id',
        FILTER_VALIDATE_INT
    );

    $weekday_hours = trim(
        $_POST['weekday_hours'] ?? ''
    );

    $saturday_hours = trim(
        $_POST['saturday_hours'] ?? ''
    );

    $sunday_hours = trim(
        $_POST['sunday_hours'] ?? ''
    );

    $address = trim(
        $_POST['address'] ?? ''
    );

    $postal_code = trim(
        $_POST['postal_code'] ?? ''
    );

    $phone = trim(
        $_POST['phone'] ?? ''
    );

    $email = trim(
        $_POST['email'] ?? ''
    );

    $map_embed = trim(
        $_POST['map_embed'] ?? ''
    );


    $sql = "
        UPDATE contact_settings

        SET
            weekday_hours = :weekday_hours,
            saturday_hours = :saturday_hours,
            sunday_hours = :sunday_hours,
            address = :address,
            postal_code = :postal_code,
            phone = :phone,
            email = :email,
            map_embed = :map_embed,
            updated_at = CURRENT_TIMESTAMP

        WHERE id = :id
    ";


    $stmt = $conn->prepare($sql);

    $stmt->execute([

        "id" => $id,

        "weekday_hours" => $weekday_hours,

        "saturday_hours" => $saturday_hours,

        "sunday_hours" => $sunday_hours,

        "address" => $address,

        "postal_code" => $postal_code,

        "phone" => $phone,

        "email" => $email,

        "map_embed" => $map_embed

    ]);


    echo  
        '<script>
            window.location.href = "admindashboard.php?page=contact";
        </script>';
    exit;
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">

                <div class="card-header bg-warning">

                    <h3 class="mb-0">
                        Edit Contact Information
                    </h3>

                </div>


                <div class="card-body">
                    <form method="POST">
                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= htmlspecialchars(
                                generateCsrfToken(),
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                        >


                        <input
                            type="hidden"
                            name="id"
                            value="<?= (int)$contact['id']; ?>"
                        >

                        <div class="mb-3">
                            <label class="form-label">
                                Monday - Friday Hours
                            </label>
                            <input
                                type="text"
                                name="weekday_hours"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $contact['weekday_hours'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                required
                            >

                        </div>


                        <div class="mb-3">
                            <label class="form-label">
                                Saturday Hours
                            </label>
                            <input
                                type="text"
                                name="saturday_hours"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $contact['saturday_hours'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                required
                            >
                        </div>


                        <div class="mb-3">
                            <label class="form-label">
                                Sunday Hours
                            </label>

                            <input
                                type="text"
                                name="sunday_hours"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $contact['sunday_hours'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                required
                            >

                        </div>


                        <div class="mb-3">
                            <label class="form-label">
                                Address
                            </label>

                            <input
                                type="text"
                                name="address"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $contact['address'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                required
                            >
                        </div>


                        <div class="mb-3">
                            <label class="form-label">
                                Postal Code
                            </label>

                            <input
                                type="text"
                                name="postal_code"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $contact['postal_code'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                required
                            >
                        </div>


                        <div class="mb-3">
                            <label class="form-label">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $contact['phone'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                required
                            >
                        </div>


                        <div class="mb-3">
                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $contact['email'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                required
                            >
                        </div>


                        <div class="mb-3">
                            <label class="form-label">
                                Google Maps Embed URL
                            </label>

                            <textarea
                                name="map_embed"
                                class="form-control"
                                rows="4"
                            ><?= htmlspecialchars(
                                $contact['map_embed'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?></textarea>
                        </div>


                        <button
                            type="submit"
                            name="update_contact"
                            class="btn btn-success"
                        >
                            Update
                        </button>


                        <a
                            href="admindashboard.php?page=contact"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>