<?php
include "admin_auth.php";
include "../includes/database.php";

$sql ="SELECT * FROM hero_slides order by id asc";
$stmt = $conn -> prepare($sql);
$stmt -> execute();

$slides = $stmt-> fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">
            Hero Slides
        </h2>

        <a
            href="admindashboard.php?page=addslides"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle"></i>
            Add Hero Slide
        </a>
    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Image</th>

                            <th>Tag</th>

                            <th>Title</th>

                            <th>Order</th>

                            <th>Status</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (empty($slides)): ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center text-muted py-4"
                            >
                                No hero slides found.
                            </td>

                        </tr>

                    <?php else: ?>


                        <?php foreach ($slides as $slide): ?>

                            <tr>

                                <td>
                                    <?= (int)$slide['id']; ?>
                                </td>


                                <td>

                                    <img
                                        src="../images/<?= htmlspecialchars(
                                            $slide['image'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
                                        alt="Hero Slide"
                                        style="
                                            width:120px;
                                            height:70px;
                                            object-fit:cover;
                                            border-radius:8px;
                                        "
                                    >

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $slide['tag'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $slide['title_line1'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                    <br>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $slide['title_line2'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    </strong>

                                </td>


                                <td>

                                    <?= (int)$slide['sort_order']; ?>

                                </td>


                                <td>

                                    <?php if ($slide['status']): ?>

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <a
                                        href="admindashboard.php?page=editslides&id=<?= (int)$slide['id']; ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        <i class="bi bi-pencil"></i>
                                        Edit
                                    </a>


                                    <a
                                        href="deleteslides.php?id=<?= (int)$slide['id']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this hero slide?');"
                                    >
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>