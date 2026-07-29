<?php
include "includes/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $sql = "INSERT INTO newsletter_subscribers(email)
            VALUES (:email)";

    $stmt = $conn->prepare($sql);
    try {

        $stmt->execute([
            ":email" => $email
        ]);

        echo "Successfully subscribed!";

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();

    }
}
?>


<section class="newsletter-section">
    <div class="newsletter-card">
        <div class="newsletter-icon">
            ✉
        </div>
        <h2>
            Subscribe to our newsletter
        </h2>
        <h6>
            Get latest products and offers.
        </h6>
        <form action="newsletter.php" method="POST">
            <input type="email"  name="email"
                placeholder="Enter your email"
                required >
            <button type="submit">
                Subscribe
            </button>
        </form>
    </div>
</section>