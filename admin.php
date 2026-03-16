<?php
require __DIR__ . '/config.php';
require_admin();

// Simple counts for dashboard
$userCount = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$menuCount = (int)$pdo->query('SELECT COUNT(*) FROM menu_items')->fetchColumn();
$bookingCount = (int)$pdo->query('SELECT COUNT(*) FROM bookings')->fetchColumn();
$orderCount = (int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$announcementCount = (int)$pdo->query('SELECT COUNT(*) FROM announcements')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen 71 | Admin Dashboard</title>
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
          <a href="admin.php" class="nav-link active">Dashboard</a>
          <a href="admin_menu.php" class="nav-link">Menu</a>
          <a href="admin_orders.php" class="nav-link">Orders</a>
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
        <h1>Admin dashboard</h1>
        <p>
          Overview of key entities from the database and navigation to CRUD pages.
        </p>
      </div>
    </section>

    <main class="layout-main">
      <div class="container">
        <section class="admin-grid">
          <article class="admin-card">
            <h3>Users</h3>
            <p>Registered customers and administrators (from the <code>users</code> table).</p>
            <p class="admin-stat">Total: <?php echo $userCount; ?></p>
          </article>

          <article class="admin-card">
            <h3>Menu items</h3>
            <p>
              Manage Kitchen 71’s dine-in and catering items with full CRUD operations.
            </p>
            <p class="admin-stat">
              Total items: <?php echo $menuCount; ?> ·
              <a href="admin_menu.php">Open Manage Menu Items</a>
            </p>
          </article>

          <article class="admin-card">
            <h3>Bookings</h3>
            <p>
              Review catering bookings submitted by users, and update their status.
            </p>
            <p class="admin-stat">Total bookings: <?php echo $bookingCount; ?></p>
          </article>

          <article class="admin-card">
            <h3>Orders</h3>
            <p>
              Orders from the website and other channels consolidated in the
              <code>orders</code> table.
            </p>
            <p class="admin-stat">Total orders: <?php echo $orderCount; ?></p>
            <p class="admin-stat"><a href="admin_orders.php">Manage order workflow</a></p>
          </article>

          <article class="admin-card">
            <h3>Announcements</h3>
            <p>
              Events &amp; Promotions and News &amp; Updates displayed on the Announcements page.
            </p>
            <p class="admin-stat">
              Total active posts: <?php echo $announcementCount; ?> ·
              <a href="admin_announcements.php">Manage</a>
            </p>
          </article>
        </section>

        <p class="admin-stat" style="margin-top: 1.4rem">
          Use the Menu and Announcements admin pages to demonstrate CRUD (create, read, update,
          delete) with the database.
        </p>
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

