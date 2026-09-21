<?php

include "admin_auth.php";
include "../includes/database.php";

$sql = "SELECT * FROM blogs ORDER BY published_at ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();

$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">
            Blog Posts
        </h2>

        <a
            href="admindashboard.php?page=addblog"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle"></i>
            Add Article
        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Published Date</th>
                        <th>Status</th>
                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (empty($blogs)): ?>

                    <tr>

                        <td
                            colspan="7"
                            class="text-center text-muted py-4"
                        >
                            No blog posts found.
                        </td>

                    </tr>

                <?php else: ?>


                    <?php foreach ($blogs as $blog): ?>

                        <tr>

                            <td>
                                <?= (int)$blog['id']; ?>
                            </td>


                            <td>

                                <?php if (!empty($blog['main_image'])): ?>

                                    <img
                                        src="../images/<?= htmlspecialchars(
                                            $blog['main_image'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
                                        alt="<?= htmlspecialchars(
                                            $blog['title'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
                                        style="
                                            width: 80px;
                                            height: 60px;
                                            object-fit: cover;
                                            border-radius: 8px;
                                        "
                                    >

                                <?php else: ?>

                                    <span class="text-muted">
                                        No image
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $blog['title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $blog['describe'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </td>


                            <td>

                                <?= date(
                                    'M d, Y',
                                    strtotime($blog['published_at'])
                                ); ?>

                            </td>


                            <td>

                                <?php if ($blog['status']): ?>

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
                                    href="admindashboard.php?page=editblog&id=<?= (int)$blog['id']; ?>"
                                    class="btn btn-warning btn-sm"
                                >
                                    <i class="bi bi-pencil"></i>
                                    Edit
                                </a>


                                <a
                                    href="deleteblog.php?id=<?= (int)$blog['id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this blog article?');"
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