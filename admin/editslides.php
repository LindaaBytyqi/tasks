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
        "Location: admindashboard.php?page=heroslides"
    );
    exit;
}


$stmt = $conn->prepare("
    SELECT *
    FROM hero_slides
    WHERE id = :id
");

$stmt->execute([
    "id" => $id
]);

$slide = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$slide) {
    header(
        "Location: admindashboard.php?page=heroslides"
    );
    exit;
}

if (isset($_POST['update_slide'])) {

    verifyCsrfToken();
    $id = filter_input(
        INPUT_POST,
        'id',
        FILTER_VALIDATE_INT
    );


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
    $imageName = $slide['image'];


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


        $extension = strtolower(
            pathinfo(
                $_FILES['image']['name'],
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


        $newImageName = uniqid(
            '',
            true
        ) . '.' . $extension;


        $uploadPath = "../images/" . $newImageName;


        if (!move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $uploadPath
        )) {

            die("Image upload failed.");

        }

        $oldImagePath = "../images/" . $slide['image'];


        if (
            !empty($slide['image']) &&
            file_exists($oldImagePath)
        ) {

            unlink($oldImagePath);

        }


        $imageName = $newImageName;

    }

    $sql = "
        UPDATE hero_slides

        SET
            tag = :tag,
            title_line1 = :title_line1,
            title_line2 = :title_line2,
            description = :description,
            primary_button_text = :primary_button_text,
            primary_button_link = :primary_button_link,
            secondary_button_text = :secondary_button_text,
            secondary_button_link = :secondary_button_link,
            image = :image,
            sort_order = :sort_order,
            status = :status,
            updated_at = CURRENT_TIMESTAMP

        WHERE id = :id
    ";


    $stmt = $conn->prepare($sql);


    $stmt->execute([

        "id" => $id,

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
        "Location: admindashboard.php?page=hero"
    );

    exit;
}

?>


<!DOCTYPE html>

<html>

<head>

    <title>Edit Hero Slide</title>

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

                <div class="card-header bg-warning">

                    <h3 class="mb-0">
                        Edit Hero Slide
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


                        <input
                            type="hidden"
                            name="id"
                            value="<?= (int)$slide['id']; ?>"
                        >


                        <div class="mb-3">

                            <label class="form-label">
                                Tag
                            </label>

                            <input
                                type="text"
                                name="tag"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $slide['tag'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
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
                                        value="<?= htmlspecialchars(
                                            $slide['title_line1'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
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
                                        value="<?= htmlspecialchars(
                                            $slide['title_line2'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
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
                            ><?= htmlspecialchars(
                                $slide['description'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?></textarea>

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
                                        value="<?= htmlspecialchars(
                                            $slide['primary_button_text'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
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
                                        value="<?= htmlspecialchars(
                                            $slide['primary_button_link'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
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
                                        value="<?= htmlspecialchars(
                                            $slide['secondary_button_text'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
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
                                        value="<?= htmlspecialchars(
                                            $slide['secondary_button_link'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
                                        required
                                    >

                                </div>

                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Current Image
                            </label>

                            <br>

                            <img
                                src="../images/<?= htmlspecialchars(
                                    $slide['image'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                alt="Current Hero"
                                style="
                                    width:220px;
                                    height:120px;
                                    object-fit:cover;
                                    border-radius:10px;
                                "
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Change Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp"
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
                                value="<?= (int)$slide['sort_order']; ?>"
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
                                <?= $slide['status'] ? 'checked' : ''; ?>
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
                            name="update_slide"
                            class="btn btn-success"
                        >
                            Update
                        </button>


                        <a
                            href="admindashboard.php?page=hero"
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