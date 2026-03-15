<?php
// Update these values for your local MySQL / MariaDB setup.
const DB_HOST = 'localhost';
const DB_NAME = 'kitchen71';
const DB_USER = 'root';
const DB_PASS = '';

session_start();

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    exit('Database connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function require_login()
{
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

function require_admin()
{
    $user = current_user();
    if (!$user || ($user['role'] ?? 'customer') !== 'admin') {
        header('Location: index.php');
        exit;
    }
}

function flash_set(string $key, string $message, string $type = 'info'): void
{
    $_SESSION['flash'][$key] = [
        'message' => $message,
        'type' => $type,
    ];
}

function flash_get(string $key): ?array
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $flash = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return $flash;
}

function cart_items(): array
{
    return $_SESSION['cart'] ?? [];
}

function cart_count(): int
{
    return array_sum(cart_items());
}

function cart_add_item(int $itemId, int $quantity = 1): void
{
    if ($itemId < 1 || $quantity < 1) {
        return;
    }

    $cart = cart_items();
    $cart[$itemId] = ($cart[$itemId] ?? 0) + $quantity;
    $_SESSION['cart'] = $cart;
}

function cart_update_item(int $itemId, int $quantity): void
{
    if ($itemId < 1) {
        return;
    }

    $cart = cart_items();
    if ($quantity < 1) {
        unset($cart[$itemId]);
    } else {
        $cart[$itemId] = $quantity;
    }
    $_SESSION['cart'] = $cart;
}

function cart_remove_item(int $itemId): void
{
    $cart = cart_items();
    unset($cart[$itemId]);
    $_SESSION['cart'] = $cart;
}

function cart_clear(): void
{
    unset($_SESSION['cart']);
    unset($_SESSION['partner_checkout_pending']);
}

function partner_checkout_set(array $details): void
{
    $_SESSION['partner_checkout_pending'] = $details;
}

function partner_checkout_get(): ?array
{
    return $_SESSION['partner_checkout_pending'] ?? null;
}

function partner_checkout_clear(): void
{
    unset($_SESSION['partner_checkout_pending']);
}

function cart_build_details(PDO $pdo): array
{
    $cart = cart_items();
    if (!$cart) {
        return [
            'items' => [],
            'subtotal' => 0.0,
            'count' => 0,
        ];
    }

    $itemIds = array_map('intval', array_keys($cart));
    $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
    $stmt = $pdo->prepare(
        "SELECT mi.id, mi.name, mi.price, mi.image_url, mc.name AS category_name
         FROM menu_items mi
         JOIN menu_categories mc ON mc.id = mi.category_id
         WHERE mi.id IN ($placeholders)"
    );
    $stmt->execute($itemIds);

    $menuItems = [];
    foreach ($stmt->fetchAll() as $item) {
        $menuItems[(int)$item['id']] = $item;
    }

    $details = [];
    $subtotal = 0.0;
    $count = 0;

    foreach ($cart as $itemId => $quantity) {
        $itemId = (int)$itemId;
        if (!isset($menuItems[$itemId])) {
            continue;
        }

        $item = $menuItems[$itemId];
        $lineTotal = (float)$item['price'] * (int)$quantity;
        $subtotal += $lineTotal;
        $count += (int)$quantity;

        $details[] = [
            'id' => $itemId,
            'name' => $item['name'],
            'category_name' => $item['category_name'],
            'image_url' => $item['image_url'],
            'price' => (float)$item['price'],
            'quantity' => (int)$quantity,
            'line_total' => $lineTotal,
        ];
    }

    return [
        'items' => $details,
        'subtotal' => $subtotal,
        'count' => $count,
    ];
}

function cart_summary_text(array $cartDetails): string
{
    if (empty($cartDetails['items'])) {
        return '';
    }

    $lines = [];
    foreach ($cartDetails['items'] as $item) {
        $lines[] = sprintf(
            '%dx %s - %s%.2f',
            $item['quantity'],
            $item['name'],
            'PHP ',
            $item['line_total']
        );
    }
    $lines[] = sprintf('Total: PHP %.2f', $cartDetails['subtotal']);

    return implode("\n", $lines);
}

