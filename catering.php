<?php
require __DIR__ . '/config.php';

$user = current_user();
$cartCount = cart_count();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user) {
        header('Location: login.php');
        exit;
    }

    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $eventDate = $_POST['event_date'] ?? '';
    $eventTime = $_POST['event_time'] ?? '';
    $guests = (int)($_POST['guests'] ?? 0);
    $location = trim($_POST['location'] ?? '');
    $package = trim($_POST['package_name'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($fullName === '') $errors[] = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if ($phone === '') $errors[] = 'Contact number is required.';
    if ($eventDate === '') $errors[] = 'Event date is required.';
    if ($eventTime === '') $errors[] = 'Event time is required.';
    if ($guests <= 0) $errors[] = 'Number of guests must be greater than zero.';
    if ($location === '') $errors[] = 'Event location is required.';
    if ($package === '') $errors[] = 'Please select a package.';

    if (!$errors) {
        $stmt = $pdo->prepare('
          INSERT INTO bookings
          (user_id, full_name, email, phone, event_date, event_time, guests, location, package_name, notes)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $user['id'],
            $fullName,
            $email,
            $phone,
            $eventDate,
            $eventTime,
            $guests,
            $location,
            $package,
            $notes
        ]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen 71 | Catering</title>
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
          <a href="catering.php" class="nav-link active">Catering</a>
          <a href="order-online.php" class="nav-link">Order Online</a>
          <a href="announcements.php" class="nav-link">Announcements</a>
          <a href="about.php" class="nav-link">About</a>
        </nav>
        <div class="header-actions">
          <a href="order-online.php" class="btn btn-ghost cart-link"><span class="cart-icon" aria-hidden="true">&#128722;</span><span>Cart</span><span class="cart-count"><?php echo $cartCount; ?></span></a>
          <?php if ($user): ?>
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
        <h1>Catering services</h1>
        <p>
          Plan birthdays, corporate gatherings, weddings, and private celebrations with Kitchen
          71’s catering packages and event booking.
        </p>
      </div>
    </section>

    <main class="layout-main">
      <div class="container two-column">
        <section class="form-card" id="book-event">
          <h2>Book an event</h2>
          <p>
            <?php if ($user): ?>
              Submit your catering booking request. Your booking status will appear in "My bookings".
            <?php else: ?>
              You need to log in or register before submitting a booking request.
            <?php endif; ?>
          </p>

          <?php if ($success): ?>
            <div class="form-card" style="margin-bottom: 0.9rem; padding: 0.7rem 0.8rem">
              <p class="meta-note">
                Booking submitted successfully. You can review the status in your profile under
                "My bookings".
              </p>
            </div>
          <?php endif; ?>

          <?php if ($errors): ?>
            <div class="form-card" style="margin-bottom: 0.9rem; padding: 0.7rem 0.8rem">
              <ul class="info-list">
                <?php foreach ($errors as $e): ?>
                  <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" action="catering.php" novalidate>
            <div class="form-grid">
              <div class="form-field">
                <label class="form-label" for="fullName">Full name</label>
                <input
                  class="form-control"
                  id="fullName"
                  name="full_name"
                  type="text"
                  value="<?php echo htmlspecialchars($_POST['full_name'] ?? ($user['name'] ?? '')); ?>"
                  <?php echo $user ? '' : 'readonly'; ?>
                  placeholder="Juan Dela Cruz"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="email">Email address</label>
                <input
                  class="form-control"
                  id="email"
                  name="email"
                  type="email"
                  value="<?php echo htmlspecialchars($_POST['email'] ?? ($user['email'] ?? '')); ?>"
                  <?php echo $user ? '' : 'readonly'; ?>
                  placeholder="you@example.com"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="phone">Contact number</label>
                <input
                  class="form-control"
                  id="phone"
                  name="phone"
                  type="tel"
                  value="<?php echo htmlspecialchars($_POST['phone'] ?? ($user['phone'] ?? '')); ?>"
                  <?php echo $user ? '' : 'readonly'; ?>
                  placeholder="+63 9XX XXX XXXX"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="eventDate">Event date</label>
                <input
                  class="form-control"
                  id="eventDate"
                  name="event_date"
                  type="date"
                  value="<?php echo htmlspecialchars($_POST['event_date'] ?? ''); ?>"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="eventTime">Event time</label>
                <input
                  class="form-control"
                  id="eventTime"
                  name="event_time"
                  type="time"
                  value="<?php echo htmlspecialchars($_POST['event_time'] ?? ''); ?>"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="guests">Number of guests</label>
                <input
                  class="form-control"
                  id="guests"
                  name="guests"
                  type="number"
                  min="1"
                  value="<?php echo htmlspecialchars($_POST['guests'] ?? ''); ?>"
                  placeholder="80"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="location">Event location</label>
                <input
                  class="form-control"
                  id="location"
                  name="location"
                  type="text"
                  value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>"
                  placeholder="Venue name / full address"
                />
              </div>
              <div class="form-field">
                <label class="form-label" for="package">Preferred package</label>
                <select class="form-select" id="package" name="package_name">
                  <?php $val = $_POST['package_name'] ?? ''; ?>
                  <option value="">Select a package</option>
                  <option <?php echo $val === 'Intimate Gathering (30–50 guests)' ? 'selected' : ''; ?>>
                    Intimate Gathering (30–50 guests)
                  </option>
                  <option <?php echo $val === 'Celebration Package (80–100 guests)' ? 'selected' : ''; ?>>
                    Celebration Package (80–100 guests)
                  </option>
                  <option <?php echo $val === 'Corporate Package (120+ guests)' ? 'selected' : ''; ?>>
                    Corporate Package (120+ guests)
                  </option>
                </select>
              </div>
            </div>

            <div class="form-field" style="margin-top: 0.9rem">
              <label class="form-label" for="notes">Special requests / notes</label>
              <textarea
                class="form-textarea"
                id="notes"
                name="notes"
                placeholder="Allergies, program flow, additional requests..."
              ><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
            </div>

            <div class="form-actions">
              <span class="meta-note">
                <?php echo $user ? 'Your request will be reviewed by Kitchen 71.' : 'Log in to enable the form.'; ?>
              </span>
              <button type="submit" class="btn btn-primary" <?php echo $user ? '' : 'disabled'; ?>>
                Submit booking request
              </button>
            </div>
          </form>
        </section>

        <aside class="info-panel" id="packages">
          <h3>Catering packages</h3>
          <p>
            Kitchen 71 offers flexible packages designed around guest count and event type.
            Final details will be coordinated with you by the team.
          </p>

          <div class="card-list">
            <article class="menu-card">
              <div class="menu-card-header">
                <h2 class="menu-card-title">Intimate Gathering</h2>
                <span class="menu-card-price">Est. 30–50 guests</span>
              </div>
              <p>
                Ideal for family birthdays and small celebrations. Includes main dish selections,
                noodles, rice, and drinks.
              </p>
              <div class="menu-meta">
                <span class="badge">Buffet-style</span>
                <span>Customizable menu</span>
              </div>
            </article>

            <article class="menu-card">
              <div class="menu-card-header">
                <h2 class="menu-card-title">Celebration Package</h2>
                <span class="menu-card-price">Est. 80–100 guests</span>
              </div>
              <p>
                For weddings, debuts, and milestone events. Includes premium viands and full
                catering service.
              </p>
              <div class="menu-meta">
                <span class="badge-soft">Most booked</span>
                <span>On-site staff</span>
              </div>
            </article>

            <article class="menu-card">
              <div class="menu-card-header">
                <h2 class="menu-card-title">Corporate Package</h2>
                <span class="menu-card-price">Est. 120+ guests</span>
              </div>
              <p>
                Designed for company gatherings and large functions with menu options fit for
                teams and clients.
              </p>
              <div class="menu-meta">
                <span class="badge">Corporate</span>
                <span>Custom quote</span>
              </div>
            </article>
          </div>

          <ul class="info-list">
            <li><strong>Lead time:</strong> Recommended at least 7–10 days before event.</li>
            <li><strong>Availability:</strong> Subject to schedule confirmation from Kitchen 71.</li>
            <li><strong>Payments:</strong> Down payment and final payment handled outside the site.</li>
          </ul>
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
          </div>
          <div>
            <h4>Account</h4>
            <?php if ($user): ?>
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

