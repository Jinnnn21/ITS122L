<?php
require __DIR__ . '/config.php';

$errors = [];
$cartCount = cart_count();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if ($password === '' || strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($password !== $password_confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, phone, password_hash) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $email, $phone, $hash]);

            $userId = (int)$pdo->lastInsertId();
            $_SESSION['user'] = [
                'id' => $userId,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'role' => 'customer',
            ];

            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen 71 | Register</title>
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
  </head>
  <body class="auth-layout">
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
          <a href="order-online.php" class="btn btn-ghost cart-link"><span class="cart-icon" aria-hidden="true">&#128722;</span><span>Cart</span><span class="cart-count"><?php echo $cartCount; ?></span></a>
          <a href="login.php" class="btn btn-ghost">Log in</a>
          <a href="register.php" class="btn btn-primary">Sign up</a>
        </div>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
          <span></span><span></span><span></span>
        </button>
      </div>
    </header>

    <main class="auth-main">
      <section class="auth-card">
        <header class="auth-card-header">
          <div>
            <h1>Create an account</h1>
            <p>Register to submit catering bookings and track your orders.</p>
          </div>
        </header>

        <?php if ($errors): ?>
          <div class="form-card" style="margin-bottom: 0.9rem; padding: 0.7rem 0.8rem">
            <ul class="info-list">
              <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form method="post" action="register.php" novalidate>
          <div class="form-grid">
            <div class="form-field">
              <label class="form-label" for="regName">Full name</label>
              <input
                class="form-control"
                id="regName"
                name="name"
                type="text"
                value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                placeholder="Juan Dela Cruz"
              />
            </div>
            <div class="form-field">
              <label class="form-label" for="regPhone">Contact number</label>
              <input
                class="form-control"
                id="regPhone"
                name="phone"
                type="tel"
                value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                placeholder="+63 9XX XXX XXXX"
              />
            </div>
          </div>

          <div class="form-field" style="margin-top: 0.7rem">
            <label class="form-label" for="regEmail">Email address</label>
            <input
              class="form-control"
              id="regEmail"
              name="email"
              type="email"
              value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
              placeholder="you@example.com"
            />
          </div>

          <div class="form-grid" style="margin-top: 0.7rem">
            <div class="form-field">
              <label class="form-label" for="regPassword">Password</label>
              <input
                class="form-control"
                id="regPassword"
                name="password"
                type="password"
                placeholder="••••••••"
              />
            </div>
            <div class="form-field">
              <label class="form-label" for="regPasswordConfirm">Confirm password</label>
              <input
                class="form-control"
                id="regPasswordConfirm"
                name="password_confirm"
                type="password"
                placeholder="••••••••"
              />
            </div>
          </div>

          <div
            class="form-actions"
            style="margin-top: 1.1rem; justify-content: space-between; align-items: center"
          >
            <span class="meta-note">Passwords are stored securely using hashes.</span>
            <button type="submit" class="btn btn-primary">Create account</button>
          </div>
        </form>

        <p class="auth-footer-text">
          Already have an account?
          <a href="login.php">Log in</a>.
        </p>
      </section>
    </main>

    <footer class="site-footer">
      <div class="container footer-bottom-inner">
        <span>© 2026 Kitchen 71. Academic project website.</span>
      </div>
    </footer>

    <script src="assets/js/main.js"></script>
  </body>
</html>

