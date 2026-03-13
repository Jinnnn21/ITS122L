<?php
require __DIR__ . '/config.php';
require_admin();

$errors = [];

// Handle create / update / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create' || $action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $categoryName = trim($_POST['category'] ?? 'Uncategorized');

        if ($name === '') {
            $errors[] = 'Name is required.';
        }

        if (!$errors) {
            // Ensure category exists or create it (default type dine-in)
            $stmt = $pdo->prepare('SELECT id FROM menu_categories WHERE name = ? LIMIT 1');
            $stmt->execute([$categoryName]);
            $category = $stmt->fetch();

            if ($category) {
                $categoryId = (int)$category['id'];
            } else {
                $stmt = $pdo->prepare('INSERT INTO menu_categories (name, type) VALUES (?, ?)');
                $stmt->execute([$categoryName, 'dine-in']);
                $categoryId = (int)$pdo->lastInsertId();
            }

            if ($action === 'create') {
                $stmt = $pdo->prepare('
                  INSERT INTO menu_items (category_id, name, description, price, is_available)
                  VALUES (?, ?, ?, ?, 1)
                ');
                $stmt->execute([$categoryId, $name, $description, $price]);
            } else {
                $stmt = $pdo->prepare('
                  UPDATE menu_items
                  SET category_id = ?, name = ?, description = ?, price = ?
                  WHERE id = ?
                ');
                $stmt->execute([$categoryId, $name, $description, $price, $id]);
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM menu_items WHERE id = ?');
            $stmt->execute([$id]);
        }
    }
}

// Fetch items for display
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
    <title>Kitchen 71 | Admin Menu</title>
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
          <a href="admin_menu.php" class="nav-link active">Menu</a>
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
        <h1>Manage menu items</h1>
        <p>Demonstration of full CRUD for the <code>menu_items</code> table.</p>
      </div>
    </section>

    <main class="layout-main">
      <div class="container two-column">
        <section class="form-card">
          <h2>Add / edit item</h2>
          <?php if ($errors): ?>
            <div class="form-card" style="margin-bottom: 0.9rem; padding: 0.7rem 0.8rem">
              <ul class="info-list">
                <?php foreach ($errors as $e): ?>
                  <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" action="admin_menu.php">
            <input type="hidden" name="action" value="create" />
            <div class="form-grid">
              <div class="form-field">
                <label class="form-label" for="name">Name</label>
                <input class="form-control" id="name" name="name" type="text" required />
              </div>
              <div class="form-field">
                <label class="form-label" for="category">Category</label>
                <input
                  class="form-control"
                  id="category"
                  name="category"
                  type="text"
                  placeholder="Bilao Platters, Pancit, Pork & Pulutan..."
                  required
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="price">Price (₱)</label>
                <input
                  class="form-control"
                  id="price"
                  name="price"
                  type="number"
                  min="0"
                  step="0.01"
                  required
                />
              </div>
            </div>
            <div class="form-field" style="margin-top: 0.9rem">
              <label class="form-label" for="description">Description</label>
              <textarea
                class="form-textarea"
                id="description"
                name="description"
                placeholder="Short description of the dish..."
              ></textarea>
            </div>
            <div class="form-actions">
              <button type="reset" class="btn btn-ghost">Clear</button>
              <button type="submit" class="btn btn-primary">Add item</button>
            </div>
          </form>
        </section>

        <section class="form-card">
          <h2>Existing items</h2>
          <p class="meta-note">
            Use the database-backed table below for read, update, and delete operations.
          </p>
          <div style="overflow-x: auto; margin-top: 0.8rem">
            <table class="table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($menuItems): ?>
                  <?php foreach ($menuItems as $item): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($item['name']); ?></td>
                      <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                      <td>₱<?php echo number_format((float)$item['price'], 2); ?></td>
                      <td>
                        <form method="post" action="admin_menu.php" style="display:inline-block">
                          <input type="hidden" name="action" value="delete" />
                          <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>" />
                          <button
                            type="submit"
                            class="btn btn-ghost"
                            onclick="return confirm('Delete this item?');"
                          >
                            Delete
                          </button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="meta-note">No items yet.</td>
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

