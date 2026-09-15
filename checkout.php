<?php
session_start();

include "includes/database.php";
include "includes/csrf.php";

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$subtotal = 0;

foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = 2.50;
$total = $subtotal + $shipping;

?>

<!DOCTYPE html>
<html lang="sq">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        body {
            background: #f8f8f8;
            color: #222;
        }

        .checkout-page {
            max-width: 1250px;
            margin: 150px auto 90px;
            padding: 0 20px;
        }

        .checkout-heading {
            text-align: center;
            margin-bottom: 50px;
        }

        .checkout-heading span {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 4px;
            color: #999;
        }

        .checkout-heading h1 {
            margin: 0;
            font-size: 42px;
            font-weight: 500;
            color: #171717;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            gap: 35px;
            align-items: start;
        }

        .checkout-card {
            background: #fff;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }

        .checkout-card-title {
            margin-bottom: 28px;
        }

        .checkout-card-title h3 {
            margin: 0;
            font-size: 26px;
            font-weight: 600;
            color: #171717;
        }

        .checkout-card-title p {
            margin: 7px 0 0;
            color: #999;
            font-size: 17px;
        }
        .checkout-product {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 0;
            border-bottom: 1px solid #eee;
        }

        .checkout-product:first-of-type {
            padding-top: 0;
        }

        .checkout-product-image {
            width: 78px;
            height: 78px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f7f7;
            border-radius: 12px;
            overflow: hidden;
        }

        .checkout-product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
        }

        .checkout-product-info {
            flex: 1;
            min-width: 0;
        }

        .checkout-product-info h5 {
            margin: 0 0 6px;
            font-size: 17px;
            font-weight: 600;
            color: #222;
        }

        .checkout-product-info span {
            font-size: 15px;
            color: #999;
        }

        .checkout-product-price {
            text-align: right;
            white-space: nowrap;
        }

        .checkout-product-price span {
            display: block;
            font-size: 15px;
            color: #999;
            margin-bottom: 4px;
        }

        .checkout-product-price strong {
            font-size: 15px;
            color: #222;
        }

        .checkout-summary {
            margin-top: 25px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            font-size: 16px;
            color: #777;
        }
        .summary-row strong {
            color: #222;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .summary-total span {
            font-size: 19px;
            font-weight: 600;
            color: #222;
        }

        .summary-total strong {
            font-size: 24px;
            font-weight: 700;
            color: #222;
        }

        .back-cart {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 25px;
            color: #777;
            text-decoration: none;
            font-size: 17px;
            transition: .3s ease;
        }

        .back-cart:hover {
            color: #e681b3;
        }

        .form-label {
            margin-bottom: 8px;
            font-size: 17px;
            font-weight: 600;
            color: #444;
        }
        .form-control,
        .form-select {
            min-height: 46px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: none !important;
            font-size: 17px;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: #e681b3;
        }
        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545 !important;
        }

        .form-control.is-valid,
        .form-select.is-valid {
            border-color: #198754 !important;
        }

        .validation-error {
            display: none;
            margin-top: 6px;
            font-size: 15px;
            color: #dc3545;
        }

        .validation-error.show {
            display: block;
        }

        .payment-title {
            margin-top: 25px;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: 600;
            color: #444;
        }

        #cardFields {
            margin-top: 18px;
            padding: 20px;
            background: #fafafa;
            border-radius: 12px;
        }

        .place-order-button {
            width: 100%;
            margin-top: 25px;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #e681b3;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            transition: .3s ease;
        }

        .place-order-button:hover {
            background: #d96fa5;
            box-shadow: 0 10px 25px rgba(230, 129, 179, .20);
            transform: translateY(-1px);
        }

        .secure-checkout {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            margin-top: 18px;
            color: #999;
            font-size: 12px;
        }

        .empty-checkout {
            max-width: 600px;
            margin: 160px auto 100px;
            text-align: center;
            background: #fff;
            padding: 60px 30px;
            border-radius: 18px;
        }

        .empty-checkout i {
            font-size: 55px;
            color: #e681b3;
        }

        @media (max-width: 991px) {
            .checkout-page {
                margin-top: 130px;
            }
            .checkout-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 767px) {
            .checkout-page {
                margin-top: 110px;
                padding: 0 15px;
            }
            .checkout-heading {
                margin-bottom: 30px;
            }
            .checkout-heading h1 {
                font-size: 34px;
            }
            .checkout-card {
                padding: 25px 20px;
                border-radius: 14px;
            }
            .checkout-product-image {
                width: 65px;
                height: 65px;
            }
            .checkout-product-info h5 {
                font-size: 14px;
            }
            .checkout-product-price strong {
                font-size: 14px;
            }
        }

    </style>

</head>

<body>

<?php if (empty($cart_items)): ?>

    <div class="empty-checkout">

        <i class="bi bi-cart-x"></i>

        <h2 class="mt-3">
            Your cart is empty
        </h2>

        <p class="text-muted">
            Add some products before proceeding to checkout.
        </p>

        <a href="product.php" class="btn mt-3"
           style="background:#e681b3; color:#fff; border-radius:8px; padding:12px 25px;">
            Continue Shopping
        </a>

    </div>

<?php else: ?>

    <main class="checkout-page">
        <div class="checkout-heading">
            <span>YOUR ORDER</span>
            <h1>Checkout</h1>
        </div>


        <div class="checkout-grid">

            <div class="checkout-card">
                <div class="checkout-card-title">
                    <h3>
                        Order Summary
                    </h3>

                    <p>
                        Review the items in your cart
                    </p>
                </div>


                <?php foreach ($cart_items as $item): ?>

                    <?php
                        $product_total =
                            $item['price'] * $item['quantity'];
                    ?>

                    <div class="checkout-product">

                        <div class="checkout-product-image">

                            <img
                                src="images/<?= htmlspecialchars(
                                    $item['image'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                alt="<?= htmlspecialchars(
                                    $item['name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                            >

                        </div>


                        <div class="checkout-product-info">

                            <h5>
                                <?= htmlspecialchars($item['name']); ?>
                            </h5>

                            <span>
                                $<?= number_format($item['price'], 2); ?>
                                ×
                                <?= (int)$item['quantity']; ?>
                            </span>

                        </div>


                        <div class="checkout-product-price">

                            <span>
                                Total
                            </span>

                            <strong>
                                $<?= number_format(
                                    $product_total,
                                    2
                                ); ?>
                            </strong>

                        </div>

                    </div>

                <?php endforeach; ?>


                <div class="checkout-summary">

                    <div class="summary-row">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            $<?= number_format($subtotal, 2); ?>
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Shipping
                        </span>

                        <strong>
                            $<?= number_format($shipping, 2); ?>
                        </strong>

                    </div>


                    <div class="summary-total">

                        <span>
                            Total
                        </span>

                        <strong>
                            $<?= number_format($total, 2); ?>
                        </strong>

                    </div>

                </div>


                <a href="cart.php" class="back-cart">

                    <i class="bi bi-arrow-left"></i>

                    Back to Cart

                </a>

            </div>


            <div class="checkout-card">

                <div class="checkout-card-title">

                    <h3>
                        Personal Information
                    </h3>

                    <p>
                        Enter your details to complete your order
                    </p>

                </div>


                <form
                    action="processorder.php"
                    method="POST"
                    id="checkoutForm"
                >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars(
                            generateCsrfToken()
                        ); ?>"
                    >


                    <div class="mb-3">

                        <label
                            for="fullName"
                            class="form-label"
                        >
                            Full Name
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="fullName"
                            name="fullName"
                            required
                            pattern="[A-ZÀ-ÖØ-Þ][a-zà-öø-ÿ]*( [A-ZÀ-ÖØ-Þ][a-zà-öø-ÿ]*)+"
                            title="Full Name can contain only letters and spaces."
                        >
                         <div class="validation-error" id="fullNameError"></div>

                    </div>


                    <div class="mb-3">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            required
                            maxlength="100"
                        >
                         <div class="validation-error" id="emailError"></div>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="phone"
                                class="form-label"
                            >
                                Phone
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="phone"
                                name="phone"
                                required
                                pattern="[0-9+\-\s]{7,20}"
                                title="Phone number can contain only numbers, +, spaces and -."
                            >
                             <div class="validation-error" id="phoneError"></div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label
                                for="address"
                                class="form-label"
                            >
                                Address
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="address"
                                name="address"
                                required
                                pattern="[A-ZÀ-ÖØ-Þ][A-Za-zÀ-ÖØ-öø-ÿ0-9\s.,'\/\-]*"
                                title="Address contains invalid characters."
                            >
                             <div class="validation-error" id="addressError"></div>

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="city"
                                class="form-label"
                            >
                                City
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="city"
                                name="city"
                                required
                                pattern="[A-ZÀ-ÖØ-Þ][a-zà-öø-ÿ]*( [A-ZÀ-ÖØ-Þ][a-zà-öø-ÿ]*)*"
                                title="City can contain only letters and spaces."
                            >
                             <div class="validation-error" id="cityError"></div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label
                                for="zipCode"
                                class="form-label"
                            >
                                Zip Code
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="zipCode"
                                name="zipCode"
                                required
                                pattern="[0-9]{5}"
                                maxlength="5"
                                inputmode="numeric"
                                title="Zip Code must contain exactly 5 numbers."
                            >
                             <div class="validation-error" id="zipCodeError"></div>

                        </div>

                    </div>


                    <div class="payment-title">
                        Payment Method
                    </div>


                    <select
                        class="form-select"
                        id="paymentMethod"
                        name="paymentMethod"
                        required
                        onchange="toggleCardDetails(this.value)"
                    >

                        <option value="0">
                            Cash on Delivery
                        </option>

                        <option value="1">
                            Online Payment
                        </option>

                    </select>


                    <div id="cardFields" style="display:none;">

                        <div class="mb-3">

                            <label
                                for="cardNumber"
                                class="form-label"
                            >
                                Card Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="cardNumber"
                                name="cardNumber"
                                placeholder="1234567890123456"
                                maxlength="16"
                                pattern="[0-9]{16}"
                                inputmode="numeric"
                                title="Card number must contain exactly 16 numbers."
                            >
                             <div class="validation-error" id="cardNumberError"></div>

                        </div>


                        <div class="row">

                            <div class="col-md-6">

                                <label
                                    for="expiryDate"
                                    class="form-label"
                                >
                                    Expiry Date
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="expiryDate"
                                    name="expiryDate"
                                    placeholder="MM/YY"
                                    maxlength="5"
                                    pattern="(0[1-9]|1[0-2])\/[0-9]{2}"
                                    title="Enter expiry date in MM/YY format."
                                >
                                <div class="validation-error" id="expiryDateError"></div>

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="cvv"
                                    class="form-label"
                                >
                                    CVV
                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="cvv"
                                    name="cvv"
                                    placeholder="123"
                                    maxlength="4"
                                    pattern="[0-9]{3,4}"
                                    inputmode="numeric"
                                    title="CVV must contain 3 or 4 numbers."
                                >
                                <div class="validation-error" id="cvvError"></div>

                            </div>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="place-order-button"
                    >
                        Place Order
                    </button>


                    <div class="secure-checkout">

                        <i class="bi bi-shield-check"></i>

                        Secure checkout

                    </div>

                </form>

            </div>

        </div>

    </main>

<?php endif; ?>


<!-- <script>

function toggleCardDetails(val) {

    const cardFields =
        document.getElementById("cardFields");

    const cardInput =
        document.getElementById("cardNumber");

    const expiryInput =
        document.getElementById("expiryDate");

    const cvvInput =
        document.getElementById("cvv");


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

</script> -->

<script>

const form = document.getElementById("checkoutForm");

const fields = {

    fullName: {
        error: "Please enter your full name using letters only."
    },

    email: {
        error: "Please enter a valid email address."
    },

    phone: {
        error: "Please enter a valid phone number."
    },

    address: {
        error: "Please enter a valid address."
    },

    city: {
        error: "Please enter your city using letters only."
    },

    zipCode: {
        error: "Zip Code must contain exactly 5 numbers."
    },

    cardNumber: {
        error: "Card number must contain exactly 16 numbers."
    },

    expiryDate: {
        error: "Please enter the expiry date in MM/YY format."
    },

    cvv: {
        error: "CVV must contain 3 or 4 numbers."
    }

};


function showError(input, message) {

    const error =
        document.getElementById(input.id + "Error");

    input.classList.remove("is-valid");
    input.classList.add("is-invalid");

    error.textContent = message;
    error.classList.add("show");
}


function clearError(input) {

    const error =
        document.getElementById(input.id + "Error");

    input.classList.remove("is-invalid");
    input.classList.add("is-valid");

    error.textContent = "";
    error.classList.remove("show");
}


function validateField(input) {
    if (
        (input.id === "cardNumber" ||
         input.id === "expiryDate" ||
         input.id === "cvv")
        &&
        document.getElementById("paymentMethod").value !== "1"
    ) {
        input.classList.remove("is-invalid", "is-valid");

        const error =
            document.getElementById(input.id + "Error");

        error.textContent = "";
        error.classList.remove("show");

        return true;
    }


    if (!input.checkValidity()) {

        showError(
            input,
            fields[input.id].error
        );

        return false;

    }


    clearError(input);

    return true;
}


Object.keys(fields).forEach(function(id) {

    const input = document.getElementById(id);

    if (!input) {
        return;
    }

    input.addEventListener("blur", function() {

        validateField(input);

    });


    input.addEventListener("input", function() {

        if (input.classList.contains("is-invalid")) {

            validateField(input);

        }

    });

});


form.addEventListener("submit", function(event) {

    let isValid = true;

    Object.keys(fields).forEach(function(id) {

        const input = document.getElementById(id);

        if (!input) {
            return;
        }

        if (!validateField(input)) {

            isValid = false;

        }

    });


    if (!isValid) {

        event.preventDefault();

        const firstError =
            document.querySelector(".is-invalid");

        if (firstError) {

            firstError.focus();

        }

    }

});


function toggleCardDetails(val) {

    const cardFields =
        document.getElementById("cardFields");

    const cardInput =
        document.getElementById("cardNumber");

    const expiryInput =
        document.getElementById("expiryDate");

    const cvvInput =
        document.getElementById("cvv");


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

        clearCardErrors();

    }

}


function clearCardErrors() {

    const cardInputs = [
        document.getElementById("cardNumber"),
        document.getElementById("expiryDate"),
        document.getElementById("cvv")
    ];


    cardInputs.forEach(function(input) {

        input.classList.remove(
            "is-invalid",
            "is-valid"
        );

        const error =
            document.getElementById(input.id + "Error");

        error.textContent = "";
        error.classList.remove("show");

    });

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>