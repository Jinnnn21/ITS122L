<?php
require __DIR__ . '/config.php';

$cartCount = cart_count();

$stmtEvents = $pdo->prepare('SELECT * FROM announcements WHERE is_active = 1 AND category = ? ORDER BY created_at DESC');
$stmtEvents->execute(['event']);
$events = $stmtEvents->fetchAll();

$stmtNews = $pdo->prepare('SELECT * FROM announcements WHERE is_active = 1 AND category = ? ORDER BY created_at DESC');
$stmtNews->execute(['news']);
$news = $stmtNews->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen 71 | Announcements</title>
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
          <a href="announcements.php" class="nav-link active">Announcements</a>
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
        <h1>Announcements</h1>
        <p>
          Stay updated with Kitchen 71’s events, promotions, and news. Posts are managed by the
          site administrator.
        </p>
      </div>
    </section>

    <main class="layout-main">
      <div class="container two-column">
        <section>
          <div class="tabs" aria-label="Announcement categories">
            <button class="tab active" type="button">Events &amp; Promotions</button>
          </div>

          <div class="card-list">
            <?php if ($events): ?>
              <?php foreach ($events as $a): ?>
                <article class="announcement-card">
                  <div class="announcement-meta">
                    <span>Events &amp; Promotions</span>
                    <span><?php echo htmlspecialchars(date('M j, Y', strtotime($a['created_at']))); ?></span>
                  </div>
                  <h3><?php echo htmlspecialchars($a['title']); ?></h3>
                  <p><?php echo nl2br(htmlspecialchars($a['body'])); ?></p>
                </article>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="meta-note">
                No events or promotions have been posted yet. Admins can add announcements in the
                Manage Announcements page.
              </p>
            <?php endif; ?>
          </div>
        </section>

        <aside class="info-panel">
          <h3>News &amp; updates</h3>
          <p>
            This section highlights important announcements from Kitchen 71 outside of promos
            and events.
          </p>

          <div class="card-list">
            <?php if ($news): ?>
              <?php foreach ($news as $a): ?>
                <article class="announcement-card">
                  <div class="announcement-meta">
                    <span>News &amp; Updates</span>
                    <span><?php echo htmlspecialchars(date('M j, Y', strtotime($a['created_at']))); ?></span>
                  </div>
                  <h3><?php echo htmlspecialchars($a['title']); ?></h3>
                  <p><?php echo nl2br(htmlspecialchars($a['body'])); ?></p>
                </article>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="meta-note">
                No news posts have been added yet. Admins can publish updates in the Manage
                Announcements page.
              </p>
            <?php endif; ?>
          </div>

          <p class="meta-note" style="margin-top: 0.9rem">
            Each post is categorized as Events &amp; Promotions (<code>event</code>) or News
            &amp; Updates (<code>news</code>) in the <code>announcements</code> table.
          </p>
        </aside>
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
            <a href="about.php">About</a>
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
            <p>Kitchen 71, Angono, Rizal</p>
            <p>Facebook · Instagram · +63 917 329 7171</p>
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
