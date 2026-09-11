<?php
session_start();
include "includes/database.php";
include "includes/header.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = (int) $_GET['id'];
    $quantity = 1; 

    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["id" => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['sale_price'] ?: $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }
    }

    header("Location: cart.php");
    exit;
}

if (isset($_POST['add_to_cart'])) {

    $product_id = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "id" => $product_id
    ]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['sale_price'] ?: $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }
    }
}

if (isset($_POST['update_cart'])) {

    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    if (isset($_SESSION['cart'][$product_id])) {
        $sql = "SELECT stock FROM products WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "id" => $product_id
        ]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $stock = (int) $product['stock'];
            if ($quantity > 0 && $quantity <= $stock) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } elseif ($quantity > $stock) {
                $_SESSION['cart'][$product_id]['quantity'] = $stock;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
}

if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $product_total = $item['price'] * $item['quantity'];
    $subtotal += $product_total;
}
$shipping = 2.5;
$total = $subtotal + $shipping;
?>
<div class="container" style="margin-top: 140px; margin-bottom: 80px;">
    <h2 class="fw-bold mb-4">Shopping Cart</h2>
    <?php if (empty($_SESSION['cart'])): ?>
        <div class="card border-0 shadow-sm text-center py-5 rounded-4">
            <div class="card-body">
                <i class="bi bi-cart-x text-muted display-1 mb-3"></i>
                <h3 class="fw-semibold">Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
                <a href="index.php" class="btn btn-primary px-4 py-2 rounded-pill fw-medium">
                    Continue Shopping
                </a>
            </div>
        </div>

    <?php else: ?>

        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5">
            <div class="card-body p-0">
                <div class="row fw-semibold text-muted border-bottom pb-3 px-2 d-none d-md-flex" style="font-size: 1rem;">
                    <div class="col-md-6">Product</div>
                    <div class="col-md-2 text-center">Price</div>
                    <div class="col-md-2 text-center">Quantity</div>
                    <div class="col-md-2 text-end">Total</div>
                </div>

                <?php foreach ($_SESSION['cart'] as $item): ?>
                 <?php
                $stock_sql = "SELECT stock FROM products WHERE id = :id";
                $stock_stmt = $conn->prepare($stock_sql);
                $stock_stmt->execute([
                "id" => $item['id']
                ]);
                $stock_data = $stock_stmt->fetch(PDO::FETCH_ASSOC);
                $stock = $stock_data ? (int) $stock_data['stock'] : 0;
                $product_total = $item['price'] * $item['quantity'];
                ?>

                    <div class="row align-items-center py-4 px-2 border-bottom text-center text-md-start">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
                                    <img src="images/<?= htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>"  
                                         class="img-fluid rounded" 
                                         style="max-height: 100%; object-fit: contain;" 
                                         alt="<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>"  >
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2 text-dark fs-5"><?= htmlspecialchars($item['name']); ?></h5>
                                    <form action="cart.php" method="POST" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?= $item['id']; ?>">
                                        <button type="submit" name="remove_product" class="btn btn-link p-0 text-danger text-decoration-none border-0 bg-transparent" style="font-size: 1rem;">
                                            <i class="bi bi-trash3 me-1"></i>Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mb-2 mb-md-0 text-md-center">
                            <span class="text-muted fw-medium">$<?= number_format($item['price'], 2); ?></span>
                        </div>

                        <div class="col-md-2 mb-2 mb-md-0 d-flex justify-content-center">
                            <form action="cart.php" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="product_id" value="<?= $item['id']; ?>">
                                <div class="input-group input-group-sm" style="width: 110px;">
                                  <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1" max="<?= $stock; ?>" class="form-control text-center rounded-start-2 border-end-0">
                                    <button type="submit" name="update_cart" class="btn btn-outline-secondary rounded-end-2" title="Update Quantity">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-2 text-md-end fw-bold text-dark fs-6">
                            $<?= number_format($product_total, 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <div class="row align-items-start justify-content-between g-4">
            <div class="col-md-6">
                <a href="index.php" class="btn btn-link text-decoration-none text-secondary p-0 fw-medium">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>

            <div class="col-md-5 col-lg-4 ms-auto">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4">Cart Summary</h5>

                    <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold text-dark">Subtotal</span>
                    <span class="fw-bold text-dark">
                    $<?= number_format($subtotal, 2); ?>
                    </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Shipping</span>
                    <span class="fw-medium">
                    $<?= number_format($shipping, 2); ?>
                    </span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5 text-dark">Total</span>
                    <span class="fw-bold fs-4 text-dark">
                    $<?= number_format($total, 2); ?>
                    </span>
                    </div>

                    <a href="checkout.php" class="btn btn-primary w-100 py-2 rounded-pill fw-semibold shadow-sm">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>