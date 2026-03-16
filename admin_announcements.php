<?php
require __DIR__ . '/config.php';
require_admin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create' || $action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $category = $_POST['category'] ?? 'event';

        if ($title === '') $errors[] = 'Title is required.';
        if ($body === '') $errors[] = 'Body is required.';

        if (!$errors) {
            if ($action === 'create') {
                $stmt = $pdo->prepare('INSERT INTO announcements (title, body, category, is_active) VALUES (?, ?, ?, 1)');
                $stmt->execute([$title, $body, $category]);
            } else {
                $stmt = $pdo->prepare('UPDATE announcements SET title = ?, body = ?, category = ? WHERE id = ?');
                $stmt->execute([$title, $body, $category, $id]);
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM announcements WHERE id = ?');
            $stmt->execute([$id]);
        }
    }
}

$stmt = $pdo->query('SELECT * FROM announcements ORDER BY created_at DESC');
$announcements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen 71 | Admin Announcements</title>
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
          <a href="admin_orders.php" class="nav-link">Orders</a>
          <a href="admin_announcements.php" class="nav-link active">Announcements</a>
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
        <h1>Manage announcements</h1>
        <p>CRUD interface for the <code>announcements</code> table.</p>
      </div>
    </section>

    <main class="layout-main">
      <div class="container two-column">
        <section class="form-card">
          <h2>Create announcement</h2>

          <?php if ($errors): ?>
            <div class="form-card" style="margin-bottom: 0.9rem; padding: 0.7rem 0.8rem">
              <ul class="info-list">
                <?php foreach ($errors as $e): ?>
                  <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" action="admin_announcements.php">
            <input type="hidden" name="action" value="create" />
            <div class="form-field">
              <label class="form-label" for="title">Title</label>
              <input class="form-control" id="title" name="title" type="text" required />
            </div>
            <div class="form-field" style="margin-top: 0.7rem">
              <label class="form-label" for="category">Category</label>
              <select class="form-select" id="category" name="category">
                <option value="event">Events &amp; Promotions</option>
                <option value="news">News &amp; Updates</option>
              </select>
            </div>
            <div class="form-field" style="margin-top: 0.7rem">
              <label class="form-label" for="body">Body</label>
              <textarea
                class="form-textarea"
                id="body"
                name="body"
                placeholder="Announcement details..."
                required
              ></textarea>
            </div>
            <div class="form-actions">
              <button type="reset" class="btn btn-ghost">Clear</button>
              <button type="submit" class="btn btn-primary">Publish</button>
            </div>
          </form>
        </section>

        <section class="form-card">
          <h2>Existing announcements</h2>
          <p class="meta-note">
            Delete to remove announcements. For update, you can adapt this page to load values
            into an edit form.
          </p>

          <div style="overflow-x: auto; margin-top: 0.8rem">
            <table class="table">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Category</th>
                  <th>Created</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($announcements): ?>
                  <?php foreach ($announcements as $a): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($a['title']); ?></td>
                      <td><?php echo $a['category'] === 'event' ? 'Events & Promotions' : 'News & Updates'; ?></td>
                      <td><?php echo htmlspecialchars($a['created_at']); ?></td>
                      <td>
                        <form method="post" action="admin_announcements.php" style="display:inline-block">
                          <input type="hidden" name="action" value="delete" />
                          <input type="hidden" name="id" value="<?php echo (int)$a['id']; ?>" />
                          <button
                            type="submit"
                            class="btn btn-ghost"
                            onclick="return confirm('Delete this announcement?');"
                          >
                            Delete
                          </button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="meta-note">No announcements yet.</td>
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

