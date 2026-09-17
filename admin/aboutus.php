<?php

include "admin_auth.php";
include "../includes/database.php";


$sql = "
    SELECT *
    FROM about_sections
    ORDER BY sort_order ASC, id ASC
";

$stmt = $conn->prepare($sql);
$stmt->execute();

$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            About Us Sections
        </h2>

        <a
            href="admindashboard.php?page=addabout"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-lg"></i>
            Add Section
        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>ID</th>

                            <th>Type</th>

                            <th>Title</th>

                            <th>Order</th>

                            <th>Status</th>

                            <th width="180">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (!empty($sections)): ?>

                        <?php foreach ($sections as $section): ?>

                            <?php

                            $data = json_decode(
                                $section['data'],
                                true
                            );

                            $title = '';

                            if (is_array($data)) {

                                $title =
                                    $data['title']
                                    ?? '';

                            }

                            ?>

                            <tr>

                                <td>
                                    <?= (int)$section['id']; ?>
                                </td>


                                <td>

                                    <span class="badge bg-secondary">

                                        <?= htmlspecialchars(
                                            ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $section['section_type']
                                                )
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    </span>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $title,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>

                                </td>


                                <td>
                                    <?= (int)$section['sort_order']; ?>
                                </td>


                                <td>

                                    <?php if ($section['status']): ?>

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <a
                                        href="admindashboard.php?page=editabout&id=<?= (int)$section['id']; ?>"
                                        class="btn btn-sm btn-warning"
                                    >
                                        <i class="bi bi-pencil"></i>
                                        Edit
                                    </a>


                                    <a
                                        href="deleteabout.php?id=<?= (int)$section['id']; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this section?');"
                                    >
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No sections found.
                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>