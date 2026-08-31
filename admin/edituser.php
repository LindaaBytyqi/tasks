<?php
include "admin_auth.php";
include "../includes/csrf.php";
include "../includes/database.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: admindashboard.php?page=users");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$current_role = $_SESSION['role'];

$sql = "SELECT role FROM users WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    'id' => $id
]);

$target_user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$target_user) {
    header("Location: admindashboard.php?page=users");
    exit();
}

if ((int)$current_user_id === (int)$id) {
    header("Location: admindashboard.php?page=users&error=no_permission");
    exit();
}

if ($current_role === 'admin' && $target_user['role'] !== 'user') {
    header("Location: admindashboard.php?page=users&error=no_permission");
    exit();
}

if ($current_role === 'user') {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT id, first_name, last_name, email
        FROM users
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id' => $id
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: admindashboard.php?page=users");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');

    if ($first_name === '' || $last_name === '' || $email === '') {

        $error = "All fields are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        $sql = "UPDATE users
                SET first_name = :first_name,
                    last_name = :last_name,
                    email = :email
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name'  => $last_name,
            ':email'      => $email,
            ':id'         => $id
        ]);

        header("Location: admindashboard.php?page=users");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h3 class="mb-0">
                        Edit User
                    </h3>

                </div>

                <div class="card-body">

                    <?php if (isset($error)): ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>

                    <?php endif; ?>


                    <form method="POST">

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $user['id']; ?>">

                              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>"
                        >
                        <div class="mb-3">

                            <label class="form-label">
                                First Name
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                class="form-control"
                                value="<?= htmlspecialchars($user['first_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Last Name
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                class="form-control"
                                value="<?= htmlspecialchars($user['last_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>"
                                required
                            >

                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                User ID
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                 value="<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                disabled
                            >

                        </div>

                        <button
                            type="submit"
                            name="update_user"
                            class="btn btn-success"
                        >
                            Update User
                        </button>


                        <a
                            href="admindashboard.php?page=users"
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