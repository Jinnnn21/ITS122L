<?php
require __DIR__ . '/config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id, name, email, phone, password_hash, role FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            sleep(1);
            $errors[] = 'Invalid email or password.';
        } else {
            $_SESSION['user'] = [
                'id' => (int)$user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role'],
            ];

            if ($user['role'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: index.php');
            }
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
    <title>Kitchen 71 | Login</title>
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
            <h1>Welcome back</h1>
            <p>Log in to manage your bookings and orders.</p>
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

        <form method="post" action="login.php" novalidate>
          <div class="form-field">
            <label class="form-label" for="loginEmail">Email address</label>
            <input
              class="form-control"
              id="loginEmail"
              name="email"
              type="email"
              value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
              placeholder="you@example.com"
            />
          </div>
          <div class="form-field" style="margin-top: 0.7rem">
            <label class="form-label" for="loginPassword">Password</label>
            <input
              class="form-control"
              id="loginPassword"
              name="password"
              type="password"
              placeholder="••••••••"
            />
          </div>

          <div
            class="form-actions"
            style="margin-top: 1.1rem; justify-content: space-between; align-items: center"
          >
            <span class="meta-note">Use your registered email and password.</span>
            <button type="submit" class="btn btn-primary">Log in</button>
          </div>
        </form>

        <p class="auth-footer-text">
          New to Kitchen 71?
          <a href="register.php">Create an account</a>.
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

