<?php

include "admin_auth.php";
include "../includes/csrf.php";
include "../includes/database.php";


if (isset($_POST['add_slide'])) {

    verifyCsrfToken();


    $tag = trim(
        $_POST['tag'] ?? ''
    );

    $title_line1 = trim(
        $_POST['title_line1'] ?? ''
    );

    $title_line2 = trim(
        $_POST['title_line2'] ?? ''
    );

    $description = trim(
        $_POST['description'] ?? ''
    );

    $primary_button_text = trim(
        $_POST['primary_button_text'] ?? ''
    );

    $primary_button_link = trim(
        $_POST['primary_button_link'] ?? ''
    );

    $secondary_button_text = trim(
        $_POST['secondary_button_text'] ?? ''
    );

    $secondary_button_link = trim(
        $_POST['secondary_button_link'] ?? ''
    );

    $sort_order = filter_input(
        INPUT_POST,
        'sort_order',
        FILTER_VALIDATE_INT
    );

    $status = isset($_POST['status']) ? 1 : 0;

    $imageName = '';


    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK
    ) {

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        $originalName = $_FILES['image']['name'];

        $extension = strtolower(
            pathinfo(
                $originalName,
                PATHINFO_EXTENSION
            )
        );


        if (!in_array(
            $extension,
            $allowedExtensions,
            true
        )) {

            die("Invalid image format.");

        }


        $imageName = uniqid(
            '',
            true
        ) . '.' . $extension;


        $uploadPath = "../images/" . $imageName;


        if (!move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $uploadPath
        )) {

            die("Image upload failed.");

        }

    } else {

        die("Please select an image.");

    }


    $sql = "
        INSERT INTO hero_slides (
            tag,
            title_line1,
            title_line2,
            description,
            primary_button_text,
            primary_button_link,
            secondary_button_text,
            secondary_button_link,
            image,
            sort_order,
            status
        )

        VALUES (
            :tag,
            :title_line1,
            :title_line2,
            :description,
            :primary_button_text,
            :primary_button_link,
            :secondary_button_text,
            :secondary_button_link,
            :image,
            :sort_order,
            :status
        )
    ";


    $stmt = $conn->prepare($sql);


    $stmt->execute([

        "tag" => $tag,

        "title_line1" => $title_line1,

        "title_line2" => $title_line2,

        "description" => $description,

        "primary_button_text" => $primary_button_text,

        "primary_button_link" => $primary_button_link,

        "secondary_button_text" => $secondary_button_text,

        "secondary_button_link" => $secondary_button_link,

        "image" => $imageName,

        "sort_order" => $sort_order ?: 0,

        "status" => $status

    ]);


    header(
        "Location: admindashboard.php?page=heroslides"
    );

    exit;
}

?>


<!DOCTYPE html>

<html>

<head>

    <title>Add Hero Slide</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-9">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h3 class="mb-0">
                        Add Hero Slide
                    </h3>

                </div>


                <div class="card-body">


                    <form
                        method="POST"
                        enctype="multipart/form-data"
                    >


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
                                Tag
                            </label>

                            <input
                                type="text"
                                name="tag"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="row">

                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Title Line 1
                                    </label>

                                    <input
                                        type="text"
                                        name="title_line1"
                                        class="form-control"
                                        required
                                    >

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Title Line 2
                                    </label>

                                    <input
                                        type="text"
                                        name="title_line2"
                                        class="form-control"
                                    >

                                </div>

                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="4"
                                required
                            ></textarea>

                        </div>


                        <div class="row">

                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Primary Button Text
                                    </label>

                                    <input
                                        type="text"
                                        name="primary_button_text"
                                        class="form-control"
                                        required
                                    >

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Primary Button Link
                                    </label>

                                    <input
                                        type="text"
                                        name="primary_button_link"
                                        class="form-control"
                                        required
                                    >

                                </div>

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Secondary Button Text
                                    </label>

                                    <input
                                        type="text"
                                        name="secondary_button_text"
                                        class="form-control"
                                        required
                                    >

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Secondary Button Link
                                    </label>

                                    <input
                                        type="text"
                                        name="secondary_button_link"
                                        class="form-control"
                                        required
                                    >

                                </div>

                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Hero Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Sort Order
                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                class="form-control"
                                value="1"
                                min="0"
                                required
                            >

                        </div>


                        <div class="form-check mb-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="status"
                                id="status"
                                checked
                            >

                            <label
                                class="form-check-label"
                                for="status"
                            >
                                Active
                            </label>

                        </div>


                        <button
                            type="submit"
                            name="add_slide"
                            class="btn btn-success"
                        >
                            Save Hero Slide
                        </button>


                        <a
                            href="admindashboard.php?page=heroslides"
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