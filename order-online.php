<?php
require __DIR__ . '/config.php';

$cartDetails = cart_build_details($pdo);
$cartItems = $cartDetails['items'];
$cartSubtotal = $cartDetails['subtotal'];
$cartCount = $cartDetails['count'];
$cartFlash = flash_get('cart');
$checkoutFlash = flash_get('checkout');
$pendingPartnerCheckout = partner_checkout_get();

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
        <h1>Order Online</h1>
        <p>
          Choose how you want to order from Kitchen 71. Order directly through our
          website or record your cart first before continuing to our delivery partners.
        </p>
      </div>
    </section>

    <main class="layout-main">
      <section class="order-cart-overview">
        <div class="container two-column">
          <section class="form-card">
            <div class="section-heading-inline">
              <div>
                <h2>Your cart</h2>
                <p><?php echo $cartCount; ?> item<?php echo $cartCount === 1 ? '' : 's'; ?> selected.</p>
              </div>
              <a href="menu.php" class="btn btn-ghost">Add more items</a>
            </div>

            <?php if ($cartFlash): ?>
              <div class="status-alert status-<?php echo htmlspecialchars($cartFlash['type']); ?>">
                <?php echo htmlspecialchars($cartFlash['message']); ?>
              </div>
            <?php endif; ?>

            <?php if ($checkoutFlash): ?>
              <div class="status-alert status-<?php echo htmlspecialchars($checkoutFlash['type']); ?>">
                <?php echo htmlspecialchars($checkoutFlash['message']); ?>
              </div>
            <?php endif; ?>

            <?php if ($pendingPartnerCheckout): ?>
              <div class="status-alert status-info">
                <strong>Pending <?php echo htmlspecialchars(ucfirst($pendingPartnerCheckout['channel'])); ?> checkout</strong>
                <p class="status-alert-text">
                  Order #<?php echo (int)$pendingPartnerCheckout['order_id']; ?> was recorded on
                  <?php echo htmlspecialchars($pendingPartnerCheckout['created_at']); ?>. Clear the cart only after you finish the order in the partner app.
                </p>
                <div class="status-alert-actions">
                  <form action="cart_action.php" method="post">
                    <input type="hidden" name="action" value="complete_partner_checkout" />
                    <input type="hidden" name="redirect_to" value="order-online.php" />
                    <button type="submit" class="btn btn-primary btn-small">I completed the partner order</button>
                  </form>
                  <form action="cart_action.php" method="post">
                    <input type="hidden" name="action" value="cancel_partner_checkout" />
                    <input type="hidden" name="redirect_to" value="order-online.php" />
                    <button type="submit" class="btn btn-ghost btn-small">Keep cart, hide reminder</button>
                  </form>
                </div>
              </div>
            <?php endif; ?>

            <?php if ($cartItems): ?>
              <div class="cart-table-wrap">
                <table class="table cart-table">
                  <thead>
                    <tr>
                      <th>Item</th>
                      <th>Price</th>
                      <th>Qty</th>
                      <th>Total</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($cartItems as $item): ?>
                      <tr>
                        <td>
                          <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                          <div class="meta-note"><?php echo htmlspecialchars($item['category_name']); ?></div>
                        </td>
                        <td>₱<?php echo number_format((float)$item['price'], 2); ?></td>
                        <td>
                          <form action="cart_action.php" method="post" class="cart-inline-form">
                            <input type="hidden" name="action" value="update" />
                            <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>" />
                            <input type="hidden" name="redirect_to" value="order-online.php" />
                            <input
                              class="qty-input"
                              type="number"
                              name="quantity"
                              min="0"
                              value="<?php echo (int)$item['quantity']; ?>"
                            />
                            <button type="submit" class="btn btn-ghost btn-small">Update</button>
                          </form>
                        </td>
                        <td>₱<?php echo number_format((float)$item['line_total'], 2); ?></td>
                        <td>
                          <form action="cart_action.php" method="post" class="cart-inline-form">
                            <input type="hidden" name="action" value="remove" />
                            <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>" />
                            <input type="hidden" name="redirect_to" value="order-online.php" />
                            <button type="submit" class="btn btn-ghost btn-small">Remove</button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

              <div class="cart-summary-bar">
                <div>
                  <strong>Subtotal</strong>
                  <p class="meta-note">Final delivery and service fees depend on the checkout channel.</p>
                </div>
                <div class="cart-summary-total">₱<?php echo number_format((float)$cartSubtotal, 2); ?></div>
              </div>

              <form action="cart_action.php" method="post" class="cart-clear-form">
                <input type="hidden" name="action" value="clear" />
                <input type="hidden" name="redirect_to" value="order-online.php" />
                <button type="submit" class="btn btn-ghost">Clear cart</button>
              </form>
            <?php else: ?>
              <div class="empty-state-block">
                <p class="meta-note">Your cart is empty. Add items from the menu before checkout.</p>
                <a href="menu.php" class="btn btn-primary">Browse menu</a>
              </div>
            <?php endif; ?>
          </section>

          <aside class="info-panel">
            <h3>Checkout flow</h3>
            <p>Choose the channel that matches how you want to finish this order.</p>
            <ul class="info-list">
              <li>Direct order sends your cart and customer details to Kitchen 71.</li>
              <li>Foodpanda and GrabFood record the cart here first, then open the partner site.</li>
              <li>You can still edit quantities before submitting.</li>
            </ul>
            <br>
            <div class="chip-row order-tags">
              <span class="chip">Cart Tracking</span>
              <span class="chip">Partner Checkout</span>
              <span class="chip">Direct Request</span>
            </div>
          </aside>
        </div>
      </section>

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
              <form action="submit-order.php" method="post" class="order-channel-form">
                <input type="hidden" name="checkout_action" value="partner_checkout" />
                <input type="hidden" name="channel" value="foodpanda" />
                <button type="submit" class="btn btn-primary" <?php echo $cartItems ? '' : 'disabled'; ?>>
                  Record and open Foodpanda
                </button>
              </form>
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
              <form action="submit-order.php" method="post" class="order-channel-form">
                <input type="hidden" name="checkout_action" value="partner_checkout" />
                <input type="hidden" name="channel" value="grabfood" />
                <button type="submit" class="btn btn-primary" <?php echo $cartItems ? '' : 'disabled'; ?>>
                  Record and open GrabFood
                </button>
              </form>
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

      <section id="direct-order" class="order-direct">
        <div class="container two-column">
          <section class="form-card">
            <h2>Direct Order Request</h2>
            <p>
              Submit your cart as a direct order request. Our team will review and confirm your order
              shortly.
            </p>

            <form action="submit-order.php" method="post">
              <input type="hidden" name="checkout_action" value="direct_order" />
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
                  Your cart will be attached to this request. Final confirmation depends on item
                  availability.
                </span>
                <button type="submit" class="btn btn-primary" <?php echo $cartItems ? '' : 'disabled'; ?>>Submit Direct Order</button>
              </div>
            </form>
          </section>

          <aside class="info-panel">
            <h3>Current order summary</h3>
            <?php if ($cartItems): ?>
              <ul class="info-list cart-summary-list">
                <?php foreach ($cartItems as $item): ?>
                  <li>
                    <strong><?php echo (int)$item['quantity']; ?>x <?php echo htmlspecialchars($item['name']); ?></strong><br>
                    ₱<?php echo number_format((float)$item['line_total'], 2); ?>
                  </li>
                <?php endforeach; ?>
              </ul>
              <br>
              <p><strong>Total:</strong> ₱<?php echo number_format((float)$cartSubtotal, 2); ?></p>
            <?php else: ?>
              <p>Add items from the menu to build your order summary.</p>
            <?php endif; ?>
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
