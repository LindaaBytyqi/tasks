<?php
include "admin_auth.php";
include "../includes/database.php";

$sql = "SELECT * FROM categories ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Categories</h2>
        <a href="admindashboard.php?page=addcategory" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Category
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No categories found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['id']; ?></td>

                            <td class="fw-semibold">
                                <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($category['description'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <?php if ($category['status']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="admindashboard.php?page=editcategory&id=<?= $category['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <a href="deletecategory.php?id=<?= $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                                <?php if ($category['status']): ?>
                                    <a href="status.php?id=<?= $category['id']; ?>&status=0" class="btn btn-secondary btn-sm" onclick="return confirm('Are you sure you want to deactivate this category?');">
                                        <i class="bi bi-x-circle"></i> Deactivate
                                    </a>
                                <?php else: ?>
                                    <a href="status.php?id=<?= $category['id']; ?>&status=1" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to activate this category?');">
                                        <i class="bi bi-check-circle"></i> Activate
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>