<?php

include "admin_auth.php";
include "../includes/database.php";

$sql = "SELECT * FROM contact_settings ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();

$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">
            Contact Information
        </h2>

        <?php if (empty($contacts)): ?>

            <a
                href="admindashboard.php?page=addcontact"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-circle"></i>
                Add Contact
            </a>

        <?php endif; ?>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>
                        <th>Opening Hours</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (empty($contacts)): ?>

                    <tr>

                        <td
                            colspan="6"
                            class="text-center text-muted py-4"
                        >
                            No contact information found.
                        </td>

                    </tr>

                <?php else: ?>


                    <?php foreach ($contacts as $contact): ?>

                        <tr>

                            <td>
                                <?= (int)$contact['id']; ?>
                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $contact['weekday_hours'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                                <br>

                                <?= htmlspecialchars(
                                    $contact['saturday_hours'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                                <br>

                                <?= htmlspecialchars(
                                    $contact['sunday_hours'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $contact['address'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                                <br>

                                <?= htmlspecialchars(
                                    $contact['postal_code'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $contact['phone'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $contact['email'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </td>


                            <td>

                                <a
                                    href="admindashboard.php?page=editcontact&id=<?= (int)$contact['id']; ?>"
                                    class="btn btn-warning btn-sm"
                                >
                                    <i class="bi bi-pencil"></i>
                                    Edit
                                </a>


                                <a
                                    href="deletecontact.php?id=<?= (int)$contact['id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this contact information?');"
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