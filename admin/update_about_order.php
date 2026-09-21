<?php

include "admin_auth.php";
include "../includes/database.php";

header('Content-Type: application/json');


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
            'message' => 'Invalid data'
        ]);

        exit;

    }


    $conn->beginTransaction();


    $sql = "
        UPDATE about_sections
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
            ':sort_order' => (int)$item['sort_order'],
            ':id' => (int)$item['id']
        ]);

    }


    $conn->commit();


    echo json_encode([
        'success' => true
    ]);

} catch (Exception $e) {

    if ($conn->inTransaction()) {
        $conn->rollBack();
    }


    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);

}
