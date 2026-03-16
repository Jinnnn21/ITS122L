<?php
require __DIR__ . '/config.php';

$search = trim($_GET['q'] ?? '');
$categoryFilter = trim($_GET['category'] ?? '');
$sort = $_GET['sort'] ?? 'default';

$categoryStmt = $pdo->query('SELECT name FROM menu_categories ORDER BY name');
$categories = $categoryStmt->fetchAll();

$where = [];
$params = [];

if ($search !== '') {
    $where[] = '(mi.name LIKE ? OR mi.description LIKE ?)';
    $searchLike = '%' . $search . '%';
    $params[] = $searchLike;
    $params[] = $searchLike;
}

if ($categoryFilter !== '') {
    $where[] = 'mc.name = ?';
    $params[] = $categoryFilter;
}

$sql = '
  SELECT
    mi.id,
    mi.name,
    mi.description,
    mi.price,
    mi.is_available,
    mc.name AS category_name,
    (
      SELECT COUNT(*)
      FROM orders o
      WHERE o.summary LIKE CONCAT(\'%\', mi.name, \'%\')
    ) AS popularity_score
  FROM menu_items mi
  JOIN menu_categories mc ON mi.category_id = mc.id
';

if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

switch ($sort) {
    case 'price_asc':
        $sql .= ' ORDER BY mi.price ASC, mi.name ASC';
        break;
    case 'price_desc':
        $sql .= ' ORDER BY mi.price DESC, mi.name ASC';
        break;
    case 'popular':
        $sql .= ' ORDER BY popularity_score DESC, mi.name ASC';
        break;
    default:
        $sort = 'default';
        $sql .= ' ORDER BY mc.name ASC, mi.name ASC';
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
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
        <section class="form-card" style="margin-bottom: 1.2rem">
          <h2>Search and filter</h2>
          <p>Find dishes by keyword, category, and sorting option.</p>
          <form method="get" action="menu.php">
            <div class="form-grid">
              <div class="form-field">
                <label class="form-label" for="searchQuery">Search</label>
                <input
                  class="form-control"
                  id="searchQuery"
                  name="q"
                  type="text"
                  placeholder="e.g. Sisig"
                  value="<?php echo htmlspecialchars($search); ?>"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="categoryFilter">Category</label>
                <select class="form-select" id="categoryFilter" name="category">
                  <option value="">All categories</option>
                  <?php foreach ($categories as $c): ?>
                    <?php $catName = $c['name']; ?>
                    <option value="<?php echo htmlspecialchars($catName); ?>" <?php echo $categoryFilter === $catName ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($catName); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-field">
                <label class="form-label" for="sortFilter">Sort by</label>
                <select class="form-select" id="sortFilter" name="sort">
                  <option value="default" <?php echo $sort === 'default' ? 'selected' : ''; ?>>Category (Default)</option>
                  <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                  <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                  <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Popularity</option>
                </select>
              </div>
            </div>
            <div class="form-actions">
              <a href="menu.php" class="btn btn-ghost">Reset</a>
              <button class="btn btn-primary" type="submit">Apply</button>
            </div>
          </form>
        </section>

        <p class="meta-note" style="margin-bottom: 1rem">
          Showing <?php echo count($menuItems); ?> result<?php echo count($menuItems) === 1 ? '' : 's'; ?>.
        </p>

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

