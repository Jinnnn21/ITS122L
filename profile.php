<?php
require __DIR__ . '/config.php';
require_login();

$user = current_user();

// Update basic profile info
$profileMessage = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_profile') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name !== '') {
        $stmt = $pdo->prepare('UPDATE users SET name = ?, phone = ? WHERE id = ?');
        $stmt->execute([$name, $phone, $user['id']]);
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;
        $user = current_user();
        $profileMessage = 'Profile updated.';
    }
}

// Bookings for this user
$stmtBookings = $pdo->prepare('SELECT * FROM bookings WHERE user_id = ? ORDER BY created_at DESC');
$stmtBookings->execute([$user['id']]);
$bookings = $stmtBookings->fetchAll();

// Orders for this user (website orders only for now)
$stmtOrders = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmtOrders->execute([$user['id']]);
$orders = $stmtOrders->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen 71 | My Profile</title>
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
          <a href="menu.php" class="nav-link">Menu</a>
          <a href="catering.php" class="nav-link">Catering</a>
          <a href="order-online.php" class="nav-link">Order Online</a>
          <a href="announcements.php" class="nav-link">Announcements</a>
          <a href="about.php" class="nav-link">About</a>
        </nav>
        <div class="header-actions">
          <a href="profile.php" class="btn btn-ghost">My profile</a>
          <a href="logout.php" class="btn btn-primary">Log out</a>
        </div>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
          <span></span><span></span><span></span>
        </button>
      </div>
    </header>

    <section class="page-hero">
      <div class="container">
        <h1>My profile</h1>
        <p>
          View and update your details, track the status of catering bookings, and review your
          order history.
        </p>
      </div>
    </section>

    <main class="layout-main">
      <div class="container profile-layout">
        <section class="profile-card">
          <h2>Profile information</h2>
          <p>These details are stored in the <code>users</code> table.</p>

          <?php if ($profileMessage): ?>
            <p class="meta-note"><?php echo htmlspecialchars($profileMessage); ?></p>
          <?php endif; ?>

          <form method="post" action="profile.php#profile">
            <input type="hidden" name="action" value="update_profile" />
            <div class="form-grid" id="profile">
              <div class="form-field">
                <label class="form-label" for="profName">Full name</label>
                <input
                  class="form-control"
                  id="profName"
                  name="name"
                  type="text"
                  value="<?php echo htmlspecialchars($user['name']); ?>"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="profPhone">Contact number</label>
                <input
                  class="form-control"
                  id="profPhone"
                  name="phone"
                  type="tel"
                  value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="profEmail">Email address</label>
                <input
                  class="form-control"
                  id="profEmail"
                  type="email"
                  value="<?php echo htmlspecialchars($user['email']); ?>"
                  readonly
                />
              </div>
            </div>

            <div class="form-actions">
              <button class="btn btn-ghost" type="reset">Cancel</button>
              <button class="btn btn-primary" type="submit">Save changes</button>
            </div>
          </form>
        </section>

        <section>
          <div class="tabs" aria-label="Profile sections">
            <button class="tab active" type="button">My bookings</button>
            <button class="tab" type="button">My orders</button>
          </div>

          <div class="form-card" id="bookings">
            <h2 style="margin-bottom: 0.4rem">My bookings</h2>
            <p class="meta-note">
              Records from the <code>bookings</code> table associated with your account.
            </p>

            <div style="overflow-x: auto; margin-top: 0.8rem">
              <table class="table">
                <thead>
                  <tr>
                    <th>Event date</th>
                    <th>Package</th>
                    <th>Guests</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($bookings): ?>
                    <?php foreach ($bookings as $b): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($b['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($b['package_name']); ?></td>
                        <td><?php echo (int)$b['guests']; ?></td>
                        <td>
                          <?php
                          $statusClass = 'status-pending';
                          if ($b['status'] === 'approved') $statusClass = 'status-approved';
                          if ($b['status'] === 'denied') $statusClass = 'status-denied';
                          ?>
                          <span class="status-pill <?php echo $statusClass; ?>">
                            <?php echo ucfirst($b['status']); ?>
                          </span>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="4" class="meta-note">No bookings yet.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="form-card" id="orders" style="margin-top: 1.2rem">
            <h2 style="margin-bottom: 0.4rem">My orders</h2>
            <p class="meta-note">
              Orders from the <code>orders</code> table, including those placed on the website and
              linked to your account.
            </p>

            <div style="overflow-x: auto; margin-top: 0.8rem">
              <table class="table">
                <thead>
                  <tr>
                    <th>Order date</th>
                    <th>Source</th>
                    <th>Summary</th>
                    <th>Payment</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($orders): ?>
                    <?php foreach ($orders as $o): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($o['created_at']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($o['source'])); ?></td>
                        <td><?php echo htmlspecialchars($o['summary']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($o['payment_status'])); ?></td>
                        <td>
                          <?php
                          $statusClass = 'status-pending';
                          if ($o['status'] === 'completed') $statusClass = 'status-approved';
                          ?>
                          <span class="status-pill <?php echo $statusClass; ?>">
                            <?php echo ucfirst($o['status']); ?>
                          </span>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="meta-note">No orders yet.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
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

