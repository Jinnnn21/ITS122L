<?php
    require __DIR__ . '/config.php';
    $cartCount = cart_count();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen 71 | About</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            <a href="about.php" class="nav-link active">About</a>
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
        <button class="nav-toggle" id="navToggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<section class="page-hero">
    <div class="container">
        <h1>About Kitchen 71</h1>
        <p>
            Discover our story, our mission, and how Kitchen 71 brings people
            together through great food and catering services.
        </p>
    </div>
</section>

<main>

<section class="section">
    <div class="container">
        <header class="section-header">
            <h2>Our Story</h2>
            <p>
                Kitchen 71 started as a local restaurant focused on bringing
                Filipino comfort food to the community. Today we serve both
                dine-in customers and provide catering services for special events.
            </p>
        </header>
        <div class="cards-grid three-col">
            <article class="card">
                <h3>Quality Food</h3>
                <p>
                    Our dishes are prepared with fresh ingredients and traditional
                    Filipino recipes that customers love.
                </p>
            </article>
            <article class="card">
                <h3>Event Catering</h3>
                <p>
                    From birthdays to corporate events, our catering services
                    make celebrations easier and more memorable.
                </p>
            </article>
            <article class="card">
                <h3>Community Focus</h3>
                <p>
                    Kitchen 71 aims to be more than a restaurant — we want to be
                    a place where families and friends enjoy meals together.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <header class="section-header">
            <h2>What We Offer</h2>
            <p>
                Kitchen 71 provides a complete dining and catering experience
                for families, offices, and special celebrations.
            </p>
        </header>
        <div class="cards-grid three-col">
            <article class="card">
                <h3>Dine-In Experience</h3>
                <p>
                    Enjoy freshly prepared Filipino comfort food in our
                    welcoming restaurant environment.
                </p>
            </article>
            <article class="card">
                <h3>Online Ordering</h3>
                <p>
                    Order your favorite meals online through our website,
                    Foodpanda, or GrabFood.
                </p>
            </article>
            <article class="card">
                <h3>Catering Packages</h3>
                <p>
                    Perfect for birthdays, corporate events, and celebrations
                    with customizable catering trays and packages.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <header class="section-header">
            <h2>Our Mission</h2>
            <p>
                At Kitchen 71, our mission is to bring people together through
                delicious food, warm hospitality, and reliable catering services.
            </p>
        </header>
        <div class="highlight-panel">
            <p>
                We strive to create memorable dining experiences while providing
                convenient ways for customers to browse menus, order online,
                and book catering events through our integrated website.
            </p>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container split">
        <div>
            <h2>Visit Kitchen 71</h2>
            <p>
                Whether you are dining in, ordering online, or planning an event,
                Kitchen 71 is ready to serve delicious meals for every occasion.
            </p>
            <ul class="checklist">
                <li>Dine-in restaurant meals</li>
                <li>Catering packages for events</li>
                <li>Delivery through Foodpanda and GrabFood</li>
                <li>Direct orders for bilao trays and party food</li>
            </ul>
        </div>
        <div class="highlight-panel">
            <h3>Contact Information</h3>
            <p>Email: kitchen71@email.com</p>
            <p>Phone: +63 900 000 0000</p>
            <p>Location: Manila, Philippines</p>

            <h3 style="margin-top:20px;">Order Through</h3>
            
            <div style="display:flex; gap:20px; margin-top:12px; align-items:center;">
                <a href="https://www.foodpanda.ph" target="_blank">
                    <img src="assets/img/foodpanda.png" width="110">
                </a>
                <a href="https://food.grab.com" target="_blank">
                    <img src="assets/img/grabfood.png" width="110">
                </a>
                <a href="order-online.php" class="btn btn-primary">
                    Order Online Through:
                </a>
            </div>
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
