<?php
require __DIR__ . '/config.php';

$cartCount = cart_count();
$cartFlash = flash_get('cart');

$stmt = $pdo->query('
  SELECT
    mi.id,
    mi.name,
    mi.description,
    mi.price,
    mi.is_available,
    mi.image_url,
    mc.name AS category_name,
    mc.description AS category_description
  FROM menu_items mi
  JOIN menu_categories mc ON mi.category_id = mc.id
  ORDER BY mc.name, mi.id
');
$menuItems = $stmt->fetchAll();

$menuByCategory = [];
foreach ($menuItems as $item) {
    $categoryName = $item['category_name'];
    if (!isset($menuByCategory[$categoryName])) {
        $menuByCategory[$categoryName] = [
            'description' => $item['category_description'] ?? '',
            'items' => [],
        ];
    }
    $menuByCategory[$categoryName]['items'][] = $item;
}
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
          <a href="order-online.php" class="btn btn-ghost cart-link"><span class="cart-icon" aria-hidden="true">&#128722;</span><span>Cart</span><span class="cart-count"><?php echo $cartCount; ?></span></a>
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
          database and can be added to your cart for direct or partner checkout.
        </p>
      </div>
    </section>

    <main class="layout-main">
      <div class="container">
        <div class="pill-filter-group">
          <span class="pill-filter active">All items</span>
          <a href="order-online.php" class="pill-filter">Cart: <?php echo $cartCount; ?> item<?php echo $cartCount === 1 ? '' : 's'; ?></a>
        </div>

        <?php if ($cartFlash): ?>
          <div class="status-alert status-<?php echo htmlspecialchars($cartFlash['type']); ?>">
            <?php echo htmlspecialchars($cartFlash['message']); ?>
          </div>
        <?php endif; ?>

        <?php if ($menuByCategory): ?>
          <?php foreach ($menuByCategory as $categoryName => $categoryData): ?>
            <section class="menu-category-block">
              <div class="menu-category-header">
                <h2><?php echo htmlspecialchars($categoryName); ?></h2>
                <?php if (!empty($categoryData['description'])): ?>
                  <p><?php echo nl2br(htmlspecialchars($categoryData['description'])); ?></p>
                <?php endif; ?>
              </div>

              <div class="menu-grid">
                <?php foreach ($categoryData['items'] as $item): ?>
                  <article class="menu-card" id="menu-item-<?php echo (int)$item['id']; ?>">
                    <?php if (!empty($item['image_url'])): ?>
                      <div class="menu-card-media">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy" />
                      </div>
                    <?php endif; ?>
                    <div class="menu-card-header">
                      <h3 class="menu-card-title">
                        <?php echo htmlspecialchars($item['name']); ?>
                      </h3>
                      <span class="menu-card-price">
                        ₱<?php echo number_format((float)$item['price'], 2); ?>
                      </span>
                    </div>
                    <p><?php echo htmlspecialchars($item['description'] ?? ''); ?></p>
                    <div class="menu-meta">
                      <span class="badge"><?php echo htmlspecialchars($categoryName); ?></span>
                      <span><?php echo $item['is_available'] ? 'Available' : 'Unavailable'; ?></span>
                    </div>

                    <?php if ($item['is_available']): ?>
                      <form action="cart_action.php" method="post" class="menu-card-actions">
                        <input type="hidden" name="action" value="add" />
                        <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>" />
                        <input type="hidden" name="redirect_to" value="menu.php#menu-item-<?php echo (int)$item['id']; ?>" />
                        <label class="sr-only" for="qty-<?php echo (int)$item['id']; ?>">Quantity</label>
                        <input
                          class="qty-input"
                          id="qty-<?php echo (int)$item['id']; ?>"
                          type="number"
                          name="quantity"
                          min="1"
                          value="1"
                        />
                        <button type="submit" class="btn btn-primary">Add to cart</button>
                      </form>
                    <?php else: ?>
                      <div class="menu-card-actions menu-card-actions-disabled">
                        <span class="meta-note">This item is currently unavailable.</span>
                      </div>
                    <?php endif; ?>
                  </article>
                <?php endforeach; ?>
              </div>
            </section>
          <?php endforeach; ?>
          <?php else: ?>
            <p class="meta-note">
              No menu items have been added yet. Log in as admin and use the Manage Menu Items
              page to create entries.
            </p>
          <?php endif; ?>
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

