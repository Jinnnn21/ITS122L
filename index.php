<?php require __DIR__ . '/config.php'; ?>
<?php $cartCount = cart_count(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen 71 | Home</title>
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
          <a href="index.php" class="nav-link active">Home</a>
          <a href="menu.php" class="nav-link">Menu</a>
          <a href="catering.php" class="nav-link">Catering</a>
          <a href="order-online.php" class="nav-link">Order Online</a>
          <a href="announcements.php" class="nav-link">Announcements</a>
          <a href="about.php" class="nav-link">About</a>
        </nav>
        <div class="header-actions">
          <a href="order-online.php" class="btn btn-ghost cart-link"><span class="cart-icon" aria-hidden="true">&#128722;</span><span>Cart</span><span class="cart-count"><?php echo $cartCount; ?></span></a>
          <?php if (current_user()): ?>
            <a href="profile.php" class="btn btn-ghost">Hi, <?php echo htmlspecialchars(current_user()['name']); ?></a>
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

    <main>
      <section class="hero">
        <div class="container hero-inner">
          <div class="hero-content">
            <h1>Comfort food, crafted for every occasion.</h1>
            <p>
              Kitchen 71 is your neighborhood restaurant and catering partner for birthdays,
              corporate events, weddings, and everyday cravings.
            </p>
            <div class="hero-actions">
              <a href="catering.php" class="btn btn-primary">Book a catering</a>
              <a href="menu.php" class="btn btn-ghost">Browse dine-in menu</a>
            </div>
            <div class="hero-meta">
              <div>
                <span class="meta-label">Dine-in & Catering</span>
                <span class="meta-value">Bilao platters · Pancit · Pork Sisig</span>
              </div>
              <div>
                <span class="meta-label">Order channels</span>
                <span class="meta-value">Restaurant · Foodpanda · GrabFood · Social media</span>
              </div>
            </div>
          </div>
          <div class="hero-card">
            <div class="hero-tag">Featured catering trays</div>
            <div class="hero-grid">
              <div class="hero-item">
                <h3>Bilao Platter</h3>
                <p>Perfect for sharing with family or team gatherings.</p>
                <span class="pill">Good for 8–10</span>
              </div>
              <div class="hero-item">
                <h3>Pancit</h3>
                <p>Classic Filipino favorite, cooked the Kitchen 71 way.</p>
                <span class="pill">Event staple</span>
              </div>
              <div class="hero-item">
                <h3>Pork Sisig</h3>
                <p>Sizzling, savory, and best enjoyed together.</p>
                <span class="pill">Crowd favorite</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="section" id="highlights">
        <div class="container">
          <header class="section-header">
            <h2>One platform for dine-in, delivery, and events</h2>
            <p>
              Explore our menus, book catering, and track your orders—everything in one place
              for a smoother Kitchen 71 experience.
            </p>
          </header>
          <div class="cards-grid three-col">
            <article class="card">
              <h3>Dine-In & Catering Menus</h3>
              <p>
                View detailed menus with descriptions, prices, and availability for both
                restaurant and catering items.
              </p>
              <a href="menu.php" class="card-link">View menus</a>
            </article>
            <article class="card">
              <h3>Catering Packages</h3>
              <p>
                Choose from curated packages with recommended guest capacities for any type
                of celebration.
              </p>
              <a href="catering.php#packages" class="card-link">See packages</a>
            </article>
            <article class="card">
              <h3>Order Management</h3>
              <p>
                Keep track of your website orders, while we consolidate orders from
                Foodpanda, GrabFood, and walk-ins.
              </p>
              <a href="profile.php#orders" class="card-link">My orders</a>
            </article>
          </div>
        </div>
      </section>

      <section class="section section-alt" id="how-it-works">
        <div class="container split">
          <div>
            <h2>How it works</h2>
            <ol class="steps">
              <li>
                <h4>1. Create your account</h4>
                <p>Sign up to manage your catering bookings and view your order history.</p>
              </li>
              <li>
                <h4>2. Choose a service</h4>
                <p>
                  Browse dine-in favorites, explore catering menus, or check out delivery
                  partners and direct order options.
                </p>
              </li>
              <li>
                <h4>3. Submit your request</h4>
                <p>
                  Fill in event details or place your orders. We’ll confirm via your preferred
                  communication channel.
                </p>
              </li>
            </ol>
          </div>
          <div class="highlight-panel">
            <h3>For busy organizers & hungry teams</h3>
            <p>
              Whether it’s a birthday, corporate gathering, or intimate celebration, Kitchen 71
              helps you coordinate food and bookings without the back-and-forth.
            </p>
            <ul class="checklist">
              <li>Centralized booking requests</li>
              <li>Transparent booking status (Pending, Approved, Denied)</li>
              <li>Consolidated order records</li>
            </ul>
          </div>
        </div>
      </section>
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
              <?php if ((current_user()['role'] ?? 'customer') === 'admin'): ?>
                <a href="admin.php">Admin dashboard</a>
              <?php endif; ?>
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

