<?php
require __DIR__ . '/config.php';
require_admin();

$allowedStatuses = ['pending', 'preparing', 'ready', 'completed', 'cancelled'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_status') {
    $orderId = (int)($_POST['order_id'] ?? 0);
    $newStatus = $_POST['status'] ?? '';

    if ($orderId > 0 && in_array($newStatus, $allowedStatuses, true)) {
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$newStatus, $orderId]);
    }

    header('Location: admin_orders.php');
    exit;
}

$stmt = $pdo->query('
  SELECT o.id, o.source, o.summary, o.payment_status, o.status, o.created_at, u.name AS customer_name
  FROM orders o
  LEFT JOIN users u ON o.user_id = u.id
  ORDER BY o.created_at DESC
');
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen 71 | Admin Orders</title>
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <header class="site-header">
      <div class="container header-inner">
        <div class="brand">
          <span class="brand-mark">71</span>
          <span class="brand-text">
            <span class="brand-title">Kitchen 71</span>
            <span class="brand-subtitle">Admin</span>
          </span>
        </div>
        <nav class="main-nav">
          <a href="index.php" class="nav-link">Site</a>
          <a href="admin.php" class="nav-link">Dashboard</a>
          <a href="admin_menu.php" class="nav-link">Menu</a>
          <a href="admin_orders.php" class="nav-link active">Orders</a>
          <a href="admin_announcements.php" class="nav-link">Announcements</a>
        </nav>
        <div class="header-actions">
          <span class="badge-dot"><span></span>Admin online</span>
          <a href="logout.php" class="btn btn-primary">Log out</a>
        </div>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
          <span></span><span></span><span></span>
        </button>
      </div>
    </header>

    <section class="page-hero">
      <div class="container">
        <h1>Manage orders</h1>
        <p>Update order workflow stages: Pending, Preparing, Ready, Completed, or Cancelled.</p>
      </div>
    </section>

    <main class="layout-main">
      <div class="container">
        <section class="form-card">
          <h2>Order status workflow</h2>
          <p>
            Use this page to model the business process by advancing or cancelling orders as needed.
          </p>

          <div style="overflow-x: auto; margin-top: 0.8rem">
            <table class="table">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Created</th>
                  <th>Customer</th>
                  <th>Source</th>
                  <th>Summary</th>
                  <th>Payment</th>
                  <th>Status</th>
                  <th>Update</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders): ?>
                  <?php foreach ($orders as $o): ?>
                    <tr>
                      <td>#<?php echo (int)$o['id']; ?></td>
                      <td><?php echo htmlspecialchars($o['created_at']); ?></td>
                      <td><?php echo htmlspecialchars($o['customer_name'] ?? 'Guest'); ?></td>
                      <td><?php echo htmlspecialchars(ucfirst($o['source'])); ?></td>
                      <td><?php echo htmlspecialchars($o['summary']); ?></td>
                      <td><?php echo htmlspecialchars(ucfirst($o['payment_status'])); ?></td>
                      <td>
                        <?php
                        $statusClass = 'status-pending';
                        if ($o['status'] === 'completed') $statusClass = 'status-approved';
                        if ($o['status'] === 'cancelled') $statusClass = 'status-denied';
                        ?>
                        <span class="status-pill <?php echo $statusClass; ?>">
                          <?php echo ucfirst($o['status']); ?>
                        </span>
                      </td>
                      <td>
                        <form method="post" action="admin_orders.php" style="display:flex; gap:0.4rem; align-items:center;">
                          <input type="hidden" name="action" value="update_status" />
                          <input type="hidden" name="order_id" value="<?php echo (int)$o['id']; ?>" />
                          <select class="form-select" name="status" style="min-width: 132px">
                            <?php foreach ($allowedStatuses as $status): ?>
                              <option value="<?php echo $status; ?>" <?php echo $o['status'] === $status ? 'selected' : ''; ?>>
                                <?php echo ucfirst($status); ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                          <button type="submit" class="btn btn-ghost">Save</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="meta-note">No orders yet.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </main>

    <footer class="site-footer">
      <div class="container footer-bottom-inner">
        <span>© 2026 Kitchen 71. Academic project website.</span>
      </div>
    </footer>

    <script src="assets/js/main.js"></script>
  </body>
</html>

