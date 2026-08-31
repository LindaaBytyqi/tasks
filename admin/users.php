<?php

include "admin_auth.php";
include "../includes/database.php";

$current_user_id = $_SESSION['user_id'];

$sql = "SELECT role
        FROM users
        WHERE id = :id";

$stmt = $conn->prepare($sql);

$stmt->execute([
    'id' => $current_user_id
]);

$current_user = $stmt->fetch(PDO::FETCH_ASSOC);
$current_role = $current_user['role'];
$search = trim($_GET['search'] ?? '');

$sql = "SELECT id, first_name, last_name, email, role, status
        FROM users
        WHERE CAST(id AS TEXT) ILIKE :search
           OR first_name ILIKE :search
           OR last_name ILIKE :search
           OR email ILIKE :search
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':search' => '%' . $search . '%'
]);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="container mt-5">

     <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Users</h2>
        <span class="text-muted">
            Total Users: <?= count($users); ?>
        </span>
    </div>

      <form method="GET" action="admindashboard.php" class="mb-4">
        <input type="hidden" name="page" value="users">

        <div class="input-group">
            <input
                type="text"
                name="search"
                class="form-control"
                id="searchBox"
                placeholder="Search by ID, name or email..."
                value="<?= htmlspecialchars($search); ?>"
            >
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
                Search
            </button>
            <?php if ($search !== ''): ?>
                <a 
            href="admindashboard.php?page=users"
            class="btn btn-secondary"
            >
                Clear
             </a>
            <?php endif; ?>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($users as $user): ?>

                        <tr>
                            <td>
                                <?= $user['id']; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $user['first_name'] . ' ' . $user['last_name']
                                ); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($user['email']); ?>
                            </td>

                           <td>
                                <?php if ($user['role'] === 'superadmin'): ?>
                                <span class="badge bg-dark">
                                     Superadmin
                                </span>

                                <?php elseif ($user['role'] === 'admin'): ?>
                                <span class="badge bg-danger">
                                     Admin
                                </span>

                                <?php else: ?>
                                <span class="badge bg-secondary">
                                     User
                                </span>
                                 <?php endif; ?>
                            </td> 
                            <td>
                                <?php if ($user['status']): ?>
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
    <div class="d-flex gap-2">

        <?php if ($user['id'] != $current_user_id): ?>

            <?php if ($current_role === 'superadmin'): ?>

                <?php if ($user['role'] !== 'superadmin'): ?>

                    <a
                        href="admindashboard.php?page=edituser&id=<?= $user['id']; ?>"
                        class="btn btn-sm btn-primary"
                        title="Edit User"
                    >
                        <i class="bi bi-pencil"></i>
                    </a>

                    <a
                        href="updaterole.php?id=<?= $user['id']; ?>"
                        class="btn btn-sm btn-warning"
                        title="Change Role"
                    >
                        <i class="bi bi-person-gear"></i>
                    </a>

                    <a
                        href="changeuserstatus.php?id=<?= $user['id']; ?>"
                        class="btn btn-sm btn-success"
                        title="Change Status"
                    >
                        <i class="bi bi-toggle-on"></i>
                    </a>

                    <a
                        href="deleteuser.php?id=<?= $user['id']; ?>"
                        class="btn btn-sm btn-danger"
                        title="Delete"
                        onclick="return confirm('Are you sure you want to delete this user?');"
                    >
                        <i class="bi bi-trash"></i>
                    </a>

                <?php endif; ?>

            <?php elseif ($current_role === 'admin'): ?>

                <?php if ($user['role'] === 'user'): ?>

                    <a
                        href="admindashboard.php?page=edituser&id=<?= $user['id']; ?>"
                        class="btn btn-sm btn-primary"
                        title="Edit User"
                    >
                        <i class="bi bi-pencil"></i>
                    </a>

                    <a
                        href="updaterole.php?id=<?= $user['id']; ?>"
                        class="btn btn-sm btn-warning"
                        title="Change Role"
                    >
                        <i class="bi bi-person-gear"></i>
                    </a>

                    <a
                        href="changeuserstatus.php?id=<?= $user['id']; ?>"
                        class="btn btn-sm btn-success"
                        title="Change Status"
                    >
                        <i class="bi bi-toggle-on"></i>
                    </a>

                    <a
                        href="deleteuser.php?id=<?= $user['id']; ?>"
                        class="btn btn-sm btn-danger"
                        title="Delete"
                        onclick="return confirm('Are you sure you want to delete this user?');"
                    >
                        <i class="bi bi-trash"></i>
                    </a>

                <?php endif; ?>

            <?php endif; ?>

        <?php endif; ?>

    </div>
</td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script>
const searchInput = document.getElementById('searchBox');

searchInput.addEventListener('input', function () {

    const search = this.value.trim();

    if (search.length >= 3 || search.length === 0) {

        const url = new URL(window.location.href);

        url.searchParams.set('page', 'users');
        url.searchParams.set('search', search);

        window.location.href = url.toString();
    }
});
</script>