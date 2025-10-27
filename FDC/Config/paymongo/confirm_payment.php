<?php
session_start();

if (!isset($_SESSION['checkout_url'])) {
    header("Location: consultation.php");
    exit;
}

$checkoutUrl = $_SESSION['checkout_url'];
$doctorId = $_SESSION['doctor_id'];
$userId = $_SESSION['user_id'];
$customerName = $_SESSION['customer_name'];
$amount = $_SESSION['amount'];
$description = $_SESSION['description'];
$docName = $_SESSION['doctor_name'];
$docNum = $_SESSION['doctor_number'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Confirm Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h3 class="mb-4">Confirm Your Payment</h3>
            <h4 class="mb-4">User Information</h4>
            <p><strong>Name:</strong> <?= htmlspecialchars($customerName) ?></p>
            <p><strong>Amount:</strong> ₱<?= htmlspecialchars($amount) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($description) ?></p>
            <h4 class="mb-4">Recipients Info</h4>
            <p><strong>Name:</strong> <?= htmlspecialchars($docName) ?></p>
            <p><strong>Number:</strong> <?= htmlspecialchars($docNum) ?></p>

            <a href="<?= htmlspecialchars($checkoutUrl) ?>" class="btn btn-success mt-3">Proceed to GCash Payment</a>
        </div>
    </div>
</body>

</html>