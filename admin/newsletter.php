<?php
include "../includes/database.php";

$search = trim($_GET['search'] ?? '');
$sql = "SELECT * FROM newsletter_subscribers";
if ($search !== '') {
    $sql .= " WHERE
                CAST(id AS TEXT) ILIKE :search
                OR email ILIKE :search
                OR CAST(status AS TEXT) ILIKE :search";
}

$sql .= " ORDER BY id ASC";
$stmt = $conn->prepare($sql);
if ($search !== '') {
    $stmt->execute([
        ':search' => '%' . $search . '%'
    ]);
} else {
    $stmt->execute();
}

$newsletter_subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Newsletter-subscribers</h2>
    </div>

      <form method="GET" action="admindashboard.php" class="mb-4">
        <input type="hidden" name="page" value="newsletter">

        <div class="input-group">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by email..."
                value="<?= htmlspecialchars($search); ?>"
            >
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
                Search
            </button>
            <?php if ($search !== ''): ?>
                <a
                    href="admindashboard.php?page=newsletter"
                    class="btn btn-secondary"
                >
                    Clear
                </a>
            <?php endif; ?>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($newsletter_subscribers)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No newsletter_subscribers found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($newsletter_subscribers as $newsletter_subscriber): ?>
                        <tr>
                            <td><?= $newsletter_subscriber['id']; ?></td>

                            <td class="fw-semibold">
                                <?= htmlspecialchars($newsletter_subscriber['email']); ?>
                            </td>

                            <td>
                                <?php if ($newsletter_subscriber['status']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td>

                                <a href="deletenewsletter.php?id=<?= $newsletter_subscriber['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this newsletter ?');">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                                <?php if ($newsletter_subscriber['status']): ?>
                                    <a href="newsletterstatus.php?id=<?= $newsletter_subscriber['id']; ?>&status=0" class="btn btn-secondary btn-sm" onclick="return confirm('Are you sure you want to deactivate this newsletter ?');">
                                        <i class="bi bi-x-circle"></i> Deactivate
                                    </a>
                                <?php else: ?>
                                    <a href="newsletterstatus.php?id=<?= $newsletter_subscriber['id']; ?>&status=1" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to activate this newsletter ?');">
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