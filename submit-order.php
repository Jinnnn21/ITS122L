<?php
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: order-online.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$orderDate = trim($_POST['order_date'] ?? '');
$orderTime = trim($_POST['order_time'] ?? '');
$orderType = trim($_POST['order_type'] ?? '');
$address = trim($_POST['address'] ?? '');
$orderDetails = trim($_POST['order_details'] ?? '');
$notes = trim($_POST['notes'] ?? '');

$errors = [];

if ($name === '') $errors[] = 'Name is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
if ($contact === '') $errors[] = 'Contact number is required.';
if ($orderDate === '') $errors[] = 'Order date is required.';
if ($orderTime === '') $errors[] = 'Order time is required.';
if ($orderType !== 'Pickup' && $orderType !== 'Delivery') $errors[] = 'Please select a valid order type.';
if ($orderDetails === '') $errors[] = 'Order details are required.';
if ($orderType === 'Delivery' && $address === '') $errors[] = 'Delivery address is required for delivery orders.';

if ($errors) {
    $_SESSION['order_error'] = implode(' ', $errors);
    header('Location: order-online.php#direct-order');
    exit;
}

$summaryParts = [
    'Customer: ' . $name,
    'Email: ' . $email,
    'Contact: ' . $contact,
    'Date: ' . $orderDate,
    'Time: ' . $orderTime,
    'Type: ' . $orderType,
];

if ($address !== '') {
    $summaryParts[] = 'Address: ' . $address;
}

$summaryParts[] = 'Order Details: ' . $orderDetails;

if ($notes !== '') {
    $summaryParts[] = 'Notes: ' . $notes;
}

$summary = implode(' | ', $summaryParts);

$user = current_user();
$userId = $user['id'] ?? null;

try {
    $stmt = $pdo->prepare('
      INSERT INTO orders (user_id, source, summary, payment_status, status)
      VALUES (?, ?, ?, ?, ?)
    ');
    $stmt->execute([$userId, 'website', $summary, 'pending', 'pending']);

    $_SESSION['order_success'] = 'Direct order submitted successfully. We will contact you soon.';
    header('Location: order-online.php#direct-order');
    exit;
} catch (PDOException $e) {
    $_SESSION['order_error'] = 'Failed to submit your order. Please try again.';
    header('Location: order-online.php#direct-order');
    exit;
}
