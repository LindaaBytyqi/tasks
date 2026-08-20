<?php
session_start();
include "includes/database.php";
include "includes/csrf.php";
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .checkout-container {
            max-width: 600px;
            margin: 120px auto 30px auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="checkout-container">
            <h2 class="pb-2 border-bottom">Checkout</h2>
            <h5 class="mt-4 mb-3 text-secondary">Personal Information</h5>
            <form action="processorder.php" method="POST" id="checkoutForm">
                <input type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(generateCsrfToken()) ?>">

                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" name="fullName" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>
                    <div class="col-md-6">
                        <label for="zipCode" class="form-label">Zip Code</label>
                        <input type="text" class="form-control" id="zipCode" name="zipCode" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Payment Method</label>
                    <select class="form-select" id="paymentMethod" name="paymentMethod" required onchange="toggleCardDetails(this.value)">
                        <option value="0">Cash on Delivery</option>
                        <option value="1">Online Payment</option>
                    </select>
                </div>

               
                <div id="cardFields" style="display: none;">
                    <div class="mb-3">
                        <label for="cardNumber" class="form-label">Card Number</label>
                        <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="16" pattern="\d{16}">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="expiryDate" class="form-label">Expiry Date</label>
                            <input type="text" class="form-control" id="expiryDate" name="expiryDate" placeholder="MM/YY">
                        </div>
                        <div class="col-md-6">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="password" class="form-control" id="cvv" name="cvv" placeholder="123" maxlength="4">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mt-3">Place Order</button>
            </form>
        </div>
    </div>

    <script>
        function toggleCardDetails(val) {
            const cardFields = document.getElementById("cardFields");
            const cardInput = document.getElementById("cardNumber");
            const expiryInput = document.getElementById("expiryDate");
            const cvvInput = document.getElementById("cvv");

            if (val === "1") {
                cardFields.style.display = "block";
                cardInput.required = true;
                expiryInput.required = true;
                cvvInput.required = true;
            } else {
                cardFields.style.display = "none";
                cardInput.required = false;
                expiryInput.required = false;
                cvvInput.required = false;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>