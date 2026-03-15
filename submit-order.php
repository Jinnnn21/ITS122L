<?php
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: order-online.php');
    exit;
}

$cartDetails = cart_build_details($pdo);
if (empty($cartDetails['items'])) {
    flash_set('checkout', 'Your cart is empty.', 'error');
    header('Location: order-online.php');
    exit;
}

$checkoutAction = $_POST['checkout_action'] ?? '';
$user = current_user();
$userId = $user['id'] ?? null;

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

if ($checkoutAction === 'direct_order') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $orderDate = trim($_POST['order_date'] ?? '');
    $orderTime = trim($_POST['order_time'] ?? '');
    $orderType = trim($_POST['order_type'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $orderDetails = trim($_POST['order_details'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($name === '' || $email === '' || $contact === '' || $orderDate === '' || $orderTime === '' || $orderType === '') {
        flash_set('checkout', 'Complete all required direct order fields.', 'error');
        header('Location: order-online.php#direct-order');
        exit;
    }

    $summaryParts = [
        'Direct order via website',
        'Customer: ' . $name,
        'Email: ' . $email,
        'Contact: ' . $contact,
        'Order date: ' . $orderDate,
        'Order time: ' . $orderTime,
        'Order type: ' . $orderType,
    ];

    if ($address !== '') {
        $summaryParts[] = 'Address: ' . $address;
    }

    if ($orderDetails !== '') {
        $summaryParts[] = 'Extra order details: ' . $orderDetails;
    }

    if ($notes !== '') {
        $summaryParts[] = 'Notes: ' . $notes;
    }

    $summaryParts[] = '---';
    $summaryParts[] = cart_summary_text($cartDetails);

    $summary = implode("\n", $summaryParts);
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, source, summary, payment_status, status) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$userId, 'website', $summary, 'pending', 'received']);

    flash_set('checkout', 'Direct order submitted successfully.', 'success');
    cart_clear();
    header('Location: order-online.php');
    exit;
}

flash_set('checkout', 'Unknown checkout action.', 'error');
header('Location: order-online.php');
exit;
