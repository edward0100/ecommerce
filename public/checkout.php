<?php 
session_start();
include '../includes/navbar.php';
include '../config/db.php';

// Check if the cart is not empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo "<div class='container'><h2>Your cart is empty!</h2></div>";
    exit;
}

// Calculate total amount for the checkout
$checkout_total = 0;
foreach ($_SESSION['cart'] as $product) {
    $checkout_total += $product['price'] * $product['quantity'];
}
$_SESSION['checkout_total'] = $checkout_total;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - E-Commerce</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .checkout-container {
            margin-top: 50px;
        }
        .order-summary {
            margin-top: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            text-align: center;
        }
        .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
        }
        #paypal-button-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container checkout-container">
    <h2 class="my-4 text-center">Checkout</h2>

    <div class="order-summary">
        <h4>Order Summary</h4>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo $product['quantity']; ?></td>
                    <td>$<?php echo number_format($product['price'] * $product['quantity'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3 class="text-right total-amount">Total: $<?php echo number_format($checkout_total, 2); ?></h3>
    </div>

    <!-- PayPal Button Container -->
    <div id="paypal-button-container" class="text-center"></div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo number_format($checkout_total, 2); ?>' 
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                    window.location.href = 'paypal_success.php?orderID=' + data.orderID;
                });
            }
        }).render('#paypal-button-container'); 
    </script>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>