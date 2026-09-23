<?php
include "admin_auth.php";
include "../includes/database.php";

$sql ="SELECT * FROM hero_slides ORDER BY sort_order ASC, id ASC";
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
            <th width="50"></th>
            <th>ID</th>
            <th>Image</th>
            <th>Tag</th>
            <th>Title</th>
            <th>Order</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody id="sortable-body">

        <?php if (empty($slides)): ?>

            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    No hero slides found.
                </td>
            </tr>

        <?php else: ?>

            <?php foreach ($slides as $slide): ?>

                <tr data-id="<?= (int)$slide['id']; ?>">

                    <td
                        class="drag-handle text-center"
                        style="cursor: grab;"
                        title="Drag to reorder"
                    >
                        <i class="bi bi-grip-vertical fs-5"></i>
                    </td>

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

                    <td class="order-number">

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


            fetch("update_hero.php", {
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

