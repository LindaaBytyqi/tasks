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
                            <th width="50"></th>

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


                    <tbody id="sortable-body">

                    <?php if (!empty($sections)): ?>

                        <?php foreach ($sections as $section): ?>

                            <?php

                            $data = json_decode(
                                $section['data'],
                                true
                            );

                            $title = '';

                            if (is_array($data)) {

                                $title = $data['title'] ?? '';

                            }

                            ?>

                            <tr data-id="<?= (int)$section['id']; ?>">

                                <td
                                    class="drag-handle text-center"
                                    style="cursor: grab;"
                                    title="Drag to reorder"
                                >
                                    <i class="bi bi-grip-vertical fs-5"></i>
                                </td>

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

                                <td class="order-number">

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
                                colspan="7"
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const sortableBody = document.getElementById("sortable-body");

    if (!sortableBody) {
        return;
    }


    new Sortable(sortableBody, {
        handle: ".drag-handle",
        animation: 150,
        ghostClass: "sortable-ghost",
        chosenClass: "sortable-chosen",
        dragClass: "sortable-drag",

        onEnd: function () {

            const rows = sortableBody.querySelectorAll("tr[data-id]");

            const order = [];


            rows.forEach(function (row, index) {

                const id = row.getAttribute("data-id");

                const sortOrder = index + 1;


                order.push({

                    id: id,

                    sort_order: sortOrder

                });

                const orderCell =
                    row.querySelector(".order-number");

                if (orderCell) {

                    orderCell.textContent = sortOrder;

                }
            });

            fetch("update_about_order.php", {

                method: "POST",

                headers: {
                    "Content-Type": "application/json"
                },

                body: JSON.stringify({
                    order: order
                })

            })

            .then(function (response) {
                return response.json();
            })

            .then(function (data) {
                if (!data.success) {
                    alert(
                        "Gabim gjatë ruajtjes së renditjes."
                    );
                }
            })

            .catch(function (error) {
                console.error(error);
                alert(
                    "Ndodhi një gabim gjatë ruajtjes së renditjes."
                );
            });
        }
    });

});
</script>

<style>

.drag-handle {
    cursor: grab !important;
    user-select: none;
}

.drag-handle:active {
    cursor: grabbing !important;
}

.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    background-color: #f8f9fa;
}

.sortable-drag {
    opacity: 0.9;
}

</style>

