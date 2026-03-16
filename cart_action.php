<?php
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: menu.php');
    exit;
}

$action = $_POST['action'] ?? '';
$redirectTo = trim($_POST['redirect_to'] ?? 'menu.php');
$itemId = (int)($_POST['item_id'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 1);

switch ($action) {
    case 'add':
        if ($itemId > 0) {
            cart_add_item($itemId, max(1, $quantity));
            flash_set('cart', 'Item added to cart.', 'success');
        }
        break;

    case 'update':
        if ($itemId > 0) {
            cart_update_item($itemId, max(0, $quantity));
            flash_set('cart', 'Cart updated.', 'success');
        }
        break;

    case 'remove':
        if ($itemId > 0) {
            cart_remove_item($itemId);
            flash_set('cart', 'Item removed from cart.', 'success');
        }
        break;

    case 'clear':
        cart_clear();
        flash_set('cart', 'Cart cleared.', 'success');
        break;

    case 'complete_partner_checkout':
        cart_clear();
        flash_set('checkout', 'Partner checkout confirmed. Your cart has been cleared.', 'success');
        break;

    case 'cancel_partner_checkout':
        partner_checkout_clear();
        flash_set('checkout', 'Partner checkout reminder dismissed. Your cart is still saved.', 'info');
        break;
}

header('Location: ' . ($redirectTo !== '' ? $redirectTo : 'menu.php'));
exit;
