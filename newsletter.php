<?php
include "includes/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];

    $check = "SELECT * FROM newsletter_subscribers WHERE email = :email";

    $stmt = $conn->prepare($check);
    $stmt->execute([
        ":email" => $email
    ]);

    if($stmt->rowCount() > 0){

        echo "This email is already subscribed!";

    } else {
        
        $sql = "INSERT INTO newsletter_subscribers(email)
                VALUES (:email)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ":email" => $email
        ]);

        echo "Successfully subscribed!";
    }
}
?>


