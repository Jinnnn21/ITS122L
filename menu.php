<?php
require __DIR__ . '/config.php';

$stmt = $pdo->query('
  SELECT mi.id, mi.name, mi.description, mi.price, mi.is_available, mc.name AS category_name
  FROM menu_items mi
  JOIN menu_categories mc ON mi.category_id = mc.id
  ORDER BY mc.name, mi.name
');
$menuItems = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen 71 | Menu</title>
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
            <span class="brand-subtitle">Restaurant & Catering</span>
          </span>
        </div>
        <nav class="main-nav">
          <a href="index.php" class="nav-link">Home</a>
          <a href="menu.php" class="nav-link active">Menu</a>
          <a href="catering.php" class="nav-link">Catering</a>
          <a href="order-online.php" class="nav-link">Order Online</a>
          <a href="announcements.php" class="nav-link">Announcements</a>
          <a href="about.php" class="nav-link">About</a>
        </nav>
        <div class="header-actions">
          <?php if (current_user()): ?>
            <a href="profile.php" class="btn btn-ghost">My profile</a>
            <a href="logout.php" class="btn btn-primary">Log out</a>
          <?php else: ?>
            <a href="login.php" class="btn btn-ghost">Log in</a>
            <a href="register.php" class="btn btn-primary">Sign up</a>
          <?php endif; ?>
        </div>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
          <span></span><span></span><span></span>
        </button>
      </div>
    </header>

    <section class="page-hero">
      <div class="container">
        <h1>Menu</h1>
        <p>
          Explore Kitchen 71’s dine-in and catering dishes. Items below are loaded from the
          database and can be managed by the admin.
        </p>
      </div>
    </section>

    <main class="layout-main">
      <div class="container">
        <div class="pill-filter-group">
          <span class="pill-filter active">All items</span>
        </div>

        <div class="menu-grid">
          <?php if ($menuItems): ?>
            <?php foreach ($menuItems as $item): ?>
              <article class="menu-card">
                <div class="menu-card-header">
                  <h2 class="menu-card-title">
                    <?php echo htmlspecialchars($item['name']); ?>
                  </h2>
                  <span class="menu-card-price">
                    ₱<?php echo number_format((float)$item['price'], 2); ?>
                  </span>
                </div>
                <p><?php echo htmlspecialchars($item['description'] ?? ''); ?></p>
                <div class="menu-meta">
                  <span class="badge"><?php echo htmlspecialchars($item['category_name']); ?></span>
                  <span><?php echo $item['is_available'] ? 'Available' : 'Unavailable'; ?></span>
                </div>
              </article>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="meta-note">
              No menu items have been added yet. Log in as admin and use the Manage Menu Items
              page to create entries.
            </p>
          <?php endif; ?>
        </div>
      </div>
    </main>

    <footer class="site-footer">
      <div class="container footer-inner">
        <div>
          <div class="brand footer-brand">
            <span class="brand-mark">71</span>
            <span class="brand-text">
              <span class="brand-title">Kitchen 71</span>
              <span class="brand-subtitle">Restaurant & Catering</span>
            </span>
          </div>
          <p class="footer-text">
            Local comfort food and event catering, now easier to discover and manage online.
          </p>
        </div>
        <div class="footer-columns">
          <div>
            <h4>Explore</h4>
            <a href="menu.php">Menu</a>
            <a href="catering.php">Catering</a>
            <a href="order-online.php">Order Online</a>
            <a href="announcements.php">Announcements</a>
          </div>
          <div>
            <h4>Account</h4>
            <?php if (current_user()): ?>
              <a href="profile.php">My profile</a>
            <?php else: ?>
              <a href="login.php">Log in</a>
              <a href="register.php">Sign up</a>
            <?php endif; ?>
          </div>
          <div>
            <h4>Connect</h4>
            <p>Kitchen 71, [City/Location]</p>
            <p>Facebook · Instagram · Phone</p>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="container footer-bottom-inner">
          <span>© 2026 Kitchen 71. Academic project website.</span>
        </div>
      </div>
    </footer>

    <script src="assets/js/main.js"></script>
  </body>
</html>

