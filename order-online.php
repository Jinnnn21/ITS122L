<?php
require __DIR__ . '/config.php';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen 71 | Order Online</title>
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
          <a href="order-online.php" class="nav-link active">Order Online</a>
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
        <h1>Order Online</h1>
        <p>
          Choose how you want to order from Kitchen 71. Order directly through our
          website or through our delivery partners.
        </p>
      </div>
    </section>

    <main class="layout-main">
      <section class="order-channels">
        <div class="container">
          <div class="cards-grid three-col">
            <article class="card">
              <h3 class="order-channel-title">
                <img
                  class="order-logo-image"
                  src="assets/img/foodpanda.png"
                  alt="Foodpanda logo"
                />
                <span>Foodpanda</span>
              </h3>
              <p>Order Kitchen 71 through Foodpanda for fast and familiar delivery.</p>
              <ul class="order-channel-list">
                <li>Fast app-based ordering</li>
                <li>Live rider tracking</li>
                <li>Best for regular meal delivery</li>
              </ul>
              <a href="https://www.foodpanda.ph/" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                Open Foodpanda
              </a>
            </article>

            <article class="card">
              <h3 class="order-channel-title">
                <img
                  class="order-logo-image"
                  src="assets/img/grabfood.png"
                  alt="GrabFood logo"
                />
                <span>GrabFood</span>
              </h3>
              <p>Order through GrabFood for quick delivery and app-based payment options.</p>
              <ul class="order-channel-list">
                <li>Easy checkout</li>
                <li>Delivery tracking</li>
                <li>Great for nearby customers</li>
              </ul>
              <a href="https://food.grab.com/" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                Open GrabFood
              </a>
            </article>

            <article class="card">
              <h3 class="order-channel-title">
                <span class="brand-mark order-brand-mark">71</span>
                <span>Direct Order</span>
              </h3>
              <p>
                For bulk orders, bilao trays, and custom requests, send your order directly
                through Kitchen 71.
              </p>
              <ul class="order-channel-list">
                <li>Best for bilao and tray orders</li>
                <li>Custom requests allowed</li>
                <li>Advance ordering available</li>
              </ul>
              <div class="order-channel-actions">
                <a href="#direct-order" class="btn btn-primary">Place Direct Order</a>
              </div>
            </article>
          </div>
        </div>
      </section>
<br>
      <section id="direct-order" class="order-direct">
        <div class="container two-column">
          <section class="form-card">
            <h2>Direct Order Request</h2>
            <p>
              Submit your direct order request. Our team will review and confirm your order
              shortly.
            </p>

            <form action="submit-order.php" method="post">
              <div class="form-grid">
                <div class="form-field">
                  <label class="form-label" for="orderName">Full name</label>
                  <input class="form-control" id="orderName" type="text" name="name" required />
                </div>
                <div class="form-field">
                  <label class="form-label" for="orderEmail">Email address</label>
                  <input class="form-control" id="orderEmail" type="email" name="email" required />
                </div>
                <div class="form-field">
                  <label class="form-label" for="orderContact">Contact number</label>
                  <input class="form-control" id="orderContact" type="text" name="contact" required />
                </div>
                <div class="form-field">
                  <label class="form-label" for="orderDate">Order date</label>
                  <input class="form-control" id="orderDate" type="date" name="order_date" required />
                </div>
                <div class="form-field">
                  <label class="form-label" for="orderTime">Order time</label>
                  <input class="form-control" id="orderTime" type="time" name="order_time" required />
                </div>
                <div class="form-field">
                  <label class="form-label" for="orderType">Order type</label>
                  <select class="form-select" id="orderType" name="order_type" required>
                    <option value="">Order type</option>
                    <option value="Pickup">Pickup</option>
                    <option value="Delivery">Delivery</option>
                  </select>
                </div>
                <div class="form-field">
                  <label class="form-label" for="orderAddress">Delivery address</label>
                  <input class="form-control" id="orderAddress" type="text" name="address" />
                </div>
              </div>

              <div class="form-field" style="margin-top: 0.9rem">
                <label class="form-label" for="orderDetails">Order details</label>
                <textarea
                  class="form-textarea"
                  id="orderDetails"
                  name="order_details"
                  rows="4"
                  placeholder="Example: 2 Bilao Pancit, 1 Pork Sisig tray"
                ></textarea>
              </div>

              <div class="form-field" style="margin-top: 0.9rem">
                <label class="form-label" for="orderNotes">Special instructions / notes</label>
                <textarea
                  class="form-textarea"
                  id="orderNotes"
                  name="notes"
                  rows="3"
                  placeholder="Example: Separate the BBQ sauce"
                ></textarea>
              </div>

              <div class="form-actions">
                <span class="meta-note order-note">
                  Your request will be reviewed by Kitchen 71. Final confirmation depends on item
                  availability.
                </span>
                <button type="submit" class="btn btn-primary">Submit Direct Order</button>
              </div>
            </form>
          </section>

          <aside class="info-panel">
            <h3>Ordering reminders</h3>
            <p>Important details to help your direct order request go smoothly.</p>
            <ul class="info-list">
              <li>Delivery app prices may vary depending on the platform.</li>
              <li>Direct orders are ideal for advance bookings and large quantities.</li>
              <li>Kitchen 71 may contact you to verify order details.</li>
              <li>Payment confirmation may be required for large orders.</li>
            </ul>
            <br>
            <div class="chip-row order-tags">
              <span class="chip">Fast Delivery</span>
              <span class="chip">Custom Orders</span>
              <span class="chip">Advance Booking</span>
            </div>
          </aside>
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
