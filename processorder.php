<?php
session_start();
include "includes/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
    exit;
}

$fullname = trim($_POST['fullName'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$zipcode = trim($_POST['zipCode'] ?? '');
$payment_method = $_POST['paymentMethod'] ?? '';

if (
    empty($fullname) ||
    empty($email) ||
    empty($phone) ||
    empty($address) ||
    empty($city) ||
    empty($zipcode) ||
    $payment_method === ''
) {
    die("Please fill in all required fields.");
}

if (!in_array($payment_method, ['0', '1'])) {
    die("Invalid payment method.");
}
$total = 0;

try {
    $conn->beginTransaction();

    foreach ($_SESSION['cart'] as $product_id => $item) {

        $sql = "SELECT id, price, sale_price, stock
                FROM products
                WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id' => $product_id
        ]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$product) {
            throw new Exception("Product not found.");
        }

        if ($product['stock'] < $item['quantity']) {
            throw new Exception("Not enough stock for product ID: " . $product_id);
        }

       $price = !empty($product['sale_price'])
        ? $product['sale_price']
        : $product['price'];

        $total += $price * $item['quantity'];
    }
    $shipping = 2.5;
    $total += $shipping;

    $sql = "INSERT INTO orders
            (
                user_id,
                fullname,
                email,
                phone,
                address,
                city,
                zipcode,
                total,
                status,
                payment_method,
                created_at
            )
            VALUES
            (
                :user_id,
                :fullname,
                :email,
                :phone,
                :address,
                :city,
                :zipcode,
                :total,
                :status,
                :payment_method,
                 CURRENT_TIMESTAMP
            )";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        'user_id' => $_SESSION['user_id'],
        'fullname' => $fullname,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'city' => $city,
        'zipcode' => $zipcode,
        'total' => $total,
        'status' => 'pending',
        'payment_method' => $payment_method,
    ]);

    $order_id = $conn->lastInsertId();
    foreach ($_SESSION['cart'] as $product_id => $item) {

        $sql = "SELECT price, sale_price
                FROM products
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'id' => $product_id
        ]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $price = !empty($product['sale_price'])
            ? $product['sale_price']
            : $product['price'];


        $sql = "INSERT INTO order_items
                (
                    product_id,
                    order_id,
                    quantity,
                    price
                )
                VALUES
                (
                    :product_id,
                    :order_id,
                    :quantity,
                    :price
                )";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'product_id' => $product_id,
            'order_id' => $order_id,
            'quantity' => $item['quantity'],
            'price' => $price
        ]);

        $sql = "UPDATE products
                SET stock = stock - :quantity
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'quantity' => $item['quantity'],
            'id' => $product_id
        ]);
    }
    $conn->commit();
    $_SESSION['cart'] = [];
    header("Location: ordersuccess.php?order_id=" . $order_id);
    exit;


} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo "Order could not be completed.";
    echo "<br>";
    echo $e->getMessage();
}