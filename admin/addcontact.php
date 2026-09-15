<?php

include "admin_auth.php";
include "../includes/csrf.php";
include "../includes/database.php";


if (isset($_POST['add_contact'])) {

    verifyCsrfToken();


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
        INSERT INTO contact_settings (
            weekday_hours,
            saturday_hours,
            sunday_hours,
            address,
            postal_code,
            phone,
            email,
            map_embed
        )

        VALUES (
            :weekday_hours,
            :saturday_hours,
            :sunday_hours,
            :address,
            :postal_code,
            :phone,
            :email,
            :map_embed
        )
    ";


    $stmt = $conn->prepare($sql);

    $stmt->execute([

        "weekday_hours" => $weekday_hours,

        "saturday_hours" => $saturday_hours,

        "sunday_hours" => $sunday_hours,

        "address" => $address,

        "postal_code" => $postal_code,

        "phone" => $phone,

        "email" => $email,

        "map_embed" => $map_embed

    ]);


    header(
        "Location: admindashboard.php?page=contact"
    );

    exit;
}

?>


<!DOCTYPE html>

<html>

<head>

    <title>Add Contact</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h3 class="mb-0">
                        Add Contact Information
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


                        <div class="mb-3">

                            <label class="form-label">
                                Monday - Friday Hours
                            </label>

                            <input
                                type="text"
                                name="weekday_hours"
                                class="form-control"
                                placeholder="Mon–Fri: 9 AM – 6 PM"
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
                                placeholder="Saturday: 11 AM – 4 PM"
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
                                placeholder="Sunday: Closed"
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
                                placeholder="123 Street, London Eye"
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
                                placeholder="10014"
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
                                placeholder="+383 49 123 456"
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
                                placeholder="info@yoursite.com"
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
                                placeholder="https://www.google.com/maps/embed?pb=..."
                            ></textarea>

                        </div>


                        <button
                            type="submit"
                            name="add_contact"
                            class="btn btn-success"
                        >
                            Save Contact
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