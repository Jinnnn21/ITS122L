<?php
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: order-online.php');
    exit;
}

// 1. Check if the cart is empty (from Feature Branch)
$cartDetails = cart_build_details($pdo);
if (empty($cartDetails['items'])) {
    flash_set('checkout', 'Your cart is empty.', 'error');
    header('Location: order-online.php');
    exit;
}

$checkoutAction = $_POST['checkout_action'] ?? '';
$user = current_user();
$userId = $user['id'] ?? null;

// --- ACTION 1: PARTNER CHECKOUT (Foodpanda/Grab) ---
if ($checkoutAction === 'partner_checkout') {
    $channel = $_POST['channel'] ?? '';
    $channelUrls = [
        'foodpanda' => 'https://www.foodpanda.ph/',
        'grabfood' => 'https://food.grab.com/',
    ];

    if (!isset($channelUrls[$channel])) {
        flash_set('checkout', 'Invalid checkout channel selected.', 'error');
        header('Location: order-online.php');
        exit;
    }

    $summary = "Partner checkout request via " . ucfirst($channel) . "\n" . cart_summary_text($cartDetails);
    
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, source, summary, payment_status, status) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$userId, $channel, $summary, 'pending', 'received']);

    partner_checkout_set([
        'order_id' => (int)$pdo->lastInsertId(),
        'channel' => $channel,
        'created_at' => date('Y-m-d H:i:s'),
    ]);

    flash_set('checkout', 'Order recorded. Finish checkout on ' . ucfirst($channel) . ', then return here to clear your cart.', 'success');
    header('Location: ' . $channelUrls[$channel]);
    exit;
}

// --- ACTION 2: DIRECT ORDER ---
if ($checkoutAction === 'direct_order') {
    // Collect data (Merged from Main)
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $orderDate = trim($_POST['order_date'] ?? '');
    $orderTime = trim($_POST['order_time'] ?? '');
    $orderType = trim($_POST['order_type'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $orderDetails = trim($_POST['order_details'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    // Strict Validation (Merged from Main)
    $errors = [];
    if ($name === '') $errors[] = 'Name is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if ($contact === '') $errors[] = 'Contact number is required.';
    if ($orderDate === '') $errors[] = 'Order date is required.';
    if ($orderTime === '') $errors[] = 'Order time is required.';
    if ($orderType !== 'Pickup' && $orderType !== 'Delivery') $errors[] = 'Please select a valid order type.';
    if ($orderType === 'Delivery' && $address === '') $errors[] = 'Delivery address is required for delivery orders.';

    if ($errors) {
        $_SESSION['order_error'] = implode(' ', $errors);
        header('Location: order-online.php#direct-order');
        exit;
    }

    // Build the Summary (Combines Form data + Cart items)
    $summaryParts = [
        'Direct order via website',
        'Customer: ' . $name,
        'Email: ' . $email,
        'Contact: ' . $contact,
        'Order date: ' . $orderDate,
        'Order time: ' . $orderTime,
        'Order type: ' . $orderType,
    ];

    if ($address !== '') $summaryParts[] = 'Address: ' . $address;
    if ($orderDetails !== '') $summaryParts[] = 'Extra details: ' . $orderDetails;
    if ($notes !== '') $summaryParts[] = 'Notes: ' . $notes;

    $summaryParts[] = '--- CART ITEMS ---';
    $summaryParts[] = cart_summary_text($cartDetails);

    $summary = implode("\n", $summaryParts);

    try {
        $stmt = $pdo->prepare('INSERT INTO orders (user_id, source, summary, payment_status, status) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$userId, 'website', $summary, 'pending', 'received']);

        $_SESSION['order_success'] = 'Direct order submitted successfully. We will contact you soon.';
        cart_clear(); // Empty the cart on successful direct order
        header('Location: order-online.php#direct-order');
        exit;
    } catch (PDOException $e) {
        $_SESSION['order_error'] = 'Failed to submit your order. Please try again.';
        header('Location: order-online.php#direct-order');
        exit;
    }
}

// Fallback for unknown actions
flash_set('checkout', 'Unknown checkout action.', 'error');
header('Location: order-online.php');
exit;