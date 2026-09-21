<?php

ob_start();

include "admin_auth.php";
include "../includes/database.php";

ob_clean();

header('Content-Type: application/json; charset=UTF-8');

try {

    $input = json_decode(
        file_get_contents("php://input"),
        true
    );

    if (
        !isset($input['order']) ||
        !is_array($input['order'])
    ) {

        echo json_encode([
            'success' => false,
            'message' => 'Invalid order data'
        ]);

        exit;
    }

    $conn->beginTransaction();
    $sql = "
        UPDATE hero_slides
        SET sort_order = :sort_order
        WHERE id = :id
    ";

    $stmt = $conn->prepare($sql);

    foreach ($input['order'] as $item) {

        if (
            !isset($item['id']) ||
            !isset($item['sort_order'])
        ) {
            continue;
        }

        $stmt->execute([
            ':id' => (int)$item['id'],
            ':sort_order' => (int)$item['sort_order']
        ]);

    }


    $conn->commit();


    echo json_encode([
        'success' => true
    ]);

    exit;


} catch (Throwable $e) {

    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);

    exit;
}