<?php
// 1. Include Header to start Session and DB
include 'header.php';

// 2. FETCH USER DETAILS
// If not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='getstarted.php';</script>";
    exit;
}

$currentUser = $_SESSION['username'];
$currentEmail = isset($_SESSION['email']) ? $_SESSION['email'] : 'user@example.com';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - KentonTheProducer</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/homepage.css">
  <link rel="stylesheet" href="css/checkout.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
  <style>
      /* Ensure Pre-filled inputs look read-only */
      input[readonly] {
          background-color: #1a1a1a !important;
          color: #888 !important;
          cursor: not-allowed;
          border-color: #333 !important;
      }
  </style>
</head>
<body>
  <main class="checkout-container" style="padding-top: 100px;">
    <div class="checkout-header">
      <h1>Secure Checkout</h1>
      <p class="subtitle">Review your tracks and complete your purchase securely.</p>
    </div>

    <div class="checkout-grid">
      <div class="checkout-left">
        <div class="cart-items-card">
          <div class="cart-header">
            <h3>Your Cart (<span id="cart-item-count">0</span> Items)</h3>
            <button class="clear-btn" id="clear-cart">Clear All</button>
          </div>
          
          <div class="cart-items-list" id="cart-items-list">
            </div>
        </div>

        <div class="promo-card">
          <div class="promo-content">
            <div class="promo-icon">
              <i class="fas fa-gift"></i>
            </div>
            <div class="promo-text">
              <h4>Get 50% Off Your Next Beat?</h4>
              <p>Add 2 more tracks to your cart to unlock the bulk discount.</p>
            </div>
          </div>
          <button class="promo-btn">Browse Beats</button>
        </div>
      </div>

      <div class="checkout-right">
        <div class="sticky-checkout">
          <div class="order-summary">
            <h3>Order Summary</h3>
            <div class="summary-details">
              <div class="summary-row">
                <span>Subtotal</span>
                <span id="cart-subtotal">$0.00</span>
              </div>
              <div class="summary-row">
                <span>Discount</span>
                <span class="discount" id="cart-discount">-$0.00</span>
              </div>
              <div class="summary-row">
                <span>Taxes</span>
                <span id="cart-taxes">$0.00</span>
              </div>
            </div>
            <div class="summary-total">
              <span>Total</span>
              <span id="cart-total" class="total-amount">$0.00</span>
            </div>
            
            <div class="promo-input-container">
              <input type="text" id="promo-code" placeholder="Promo code">
              <button id="apply-promo">APPLY</button>
            </div>
          </div>

          <div class="checkout-form-card">
            <div class="form-header">
              <i class="fas fa-lock"></i>
              <h3>Payment Details</h3>
            </div>
            
            <form id="payment-form">
              <div class="form-group">
                <label>Account Name</label>
                <input type="text" id="username" value="<?php echo htmlspecialchars($currentUser); ?>" readonly required>
              </div>

              <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($currentEmail); ?>" readonly required>
                <p class="helper-text">Your files will be sent to this email instantly.</p>
              </div>

              <div class="form-group">
                <label>Card Information</label>
                <div class="card-input-container">
                  <div class="card-number-row">
                    <i class="far fa-credit-card"></i>
                    <input type="text" id="card-number" placeholder="Card number" maxlength="19" required>
                  </div>
                  <div class="card-details-row">
                    <input type="text" id="card-expiry" placeholder="MM / YY" maxlength="7" required>
                    <input type="text" id="card-cvc" placeholder="CVC" maxlength="4" required>
                  </div>
                </div>
              </div>

              <button type="submit" class="pay-btn" id="pay-button">
                <span>Pay <span id="pay-amount">$0.00</span></span>
                <i class="fas fa-arrow-right"></i>
              </button>

              <div class="security-badges">
                <div class="badge-item">
                  <i class="fas fa-lock"></i>
                  <span>Secure 256-bit SSL</span>
                </div>
                <div class="badge-item">
                  <i class="fas fa-bolt"></i>
                  <span>Instant Delivery</span>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Cart data from localStorage
      let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
      const cartCountElement = document.getElementById('cart-count');
      const cartItemsList = document.getElementById('cart-items-list');
      const cartItemCount = document.getElementById('cart-item-count');
      const cartSubtotal = document.getElementById('cart-subtotal');
      const cartTotal = document.getElementById('cart-total');
      const payAmount = document.getElementById('pay-amount');
      const clearCartBtn = document.getElementById('clear-cart');
      
      const LICENSES = {
        'exclusive': { name: 'Exclusive Rights', price: 500.00 },
        'premium': { name: 'Non-Exclusive (Premium)', price: 99.99 },
        'basic': { name: 'Non-Exclusive (Basic)', price: 25.00 }
      };

      // Initialize cart display
      function updateCartCount() {
        if(cartCountElement) {
          cartCountElement.textContent = cart.length;
        }
        if(cartItemCount) {
          cartItemCount.textContent = cart.length;
        }
      }

      function calculateCartTotals() {
        const subtotal = cart.reduce((sum, item) => sum + item.price, 0);
        const discount = 0; 
        const taxes = 0; 
        const total = subtotal - discount + taxes;
        
        return {
          subtotal: subtotal.toFixed(2),
          discount: discount.toFixed(2),
          taxes: taxes.toFixed(2),
          total: total.toFixed(2)
        };
      }

      function renderCartItems() {
        if (!cartItemsList) return;
        
        cartItemsList.innerHTML = '';
        
        if (cart.length === 0) {
          cartItemsList.innerHTML = `
            <div class="empty-cart">
              <i class="fas fa-shopping-cart"></i>
              <p>Your cart is empty</p>
              <a href="tracks.php" class="btn btn-signup">Browse Beats</a>
            </div>
          `;
        } else {
          cart.forEach((item, index) => {
            const itemEl = document.createElement('div');
            itemEl.className = 'cart-item';
            itemEl.innerHTML = `
              <div class="cart-item-content">
                <div class="cart-item-image">
                  <img src="${item.img || 'https://via.placeholder.com/70'}" alt="${item.name}">
                  <div class="image-overlay">
                    <i class="fas fa-play"></i>
                  </div>
                </div>
                <div class="cart-item-info">
                  <h4>${item.name}</h4>
                  <div class="item-meta">
                    <span class="bpm-tag">${item.producer || 'Kenton'}</span>
                    <span class="key">${item.licenseName || 'License'}</span>
                  </div>
                </div>
              </div>
              
              <div class="cart-item-actions">
                <div class="item-price">$${item.price.toFixed(2)}</div>
                <button class="remove-item" data-index="${index}">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            `;
            cartItemsList.appendChild(itemEl);
          });
        }
        
        const totals = calculateCartTotals();
        if (cartSubtotal) cartSubtotal.textContent = `$${totals.subtotal}`;
        if (cartTotal) cartTotal.textContent = `$${totals.total}`;
        if (payAmount) payAmount.textContent = `$${totals.total}`;
      }

      // Clear Cart Listener
      if (clearCartBtn) {
        clearCartBtn.addEventListener('click', () => {
          if (confirm('Are you sure you want to clear your cart?')) {
            cart = [];
            localStorage.setItem('cartItems', JSON.stringify(cart));
            updateCartCount();
            renderCartItems();
          }
        });
      }

      // Remove item listener
      document.addEventListener('click', (e) => {
        if (e.target.closest('.remove-item')) {
          const index = parseInt(e.target.closest('.remove-item').dataset.index);
          cart.splice(index, 1);
          localStorage.setItem('cartItems', JSON.stringify(cart));
          updateCartCount();
          renderCartItems();
        }
      });

      // Promo code logic (Keep original)
      const applyPromoBtn = document.getElementById('apply-promo');
      if (applyPromoBtn) {
        applyPromoBtn.addEventListener('click', () => {
          const promoCode = document.getElementById('promo-code').value;
          if (promoCode === 'SAVE20') {
            alert('Promo code applied! 20% discount will be calculated on the next page.');
          } else if (promoCode) {
            alert('Invalid promo code');
          }
        });
      }

      // Format card inputs
      function formatCardNumber(value) {
        const v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        const matches = v.match(/\d{4,16}/g);
        const match = (matches && matches[0]) || '';
        const parts = [];
        for (let i = 0, len = match.length; i < len; i += 4) { parts.push(match.substring(i, i + 4)); }
        return parts.length ? parts.join(' ') : value;
      }

      function formatExpiry(value) {
        const v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        if (v.length >= 2) return v.substring(0, 2) + ' / ' + v.substring(2, 4);
        return v;
      }

      const cardNumberInput = document.getElementById('card-number');
      if (cardNumberInput) {
        cardNumberInput.addEventListener('input', (e) => { e.target.value = formatCardNumber(e.target.value); });
      }

      const cardExpiryInput = document.getElementById('card-expiry');
      if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', (e) => { e.target.value = formatExpiry(e.target.value); });
      }

      // Initialize
      updateCartCount();
      renderCartItems();
    });

    // --- PASTE THIS AT THE BOTTOM OF checkout.php (DEBUG MODE) ---
    
    const paymentForm = document.getElementById('payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', (e) => {
            e.preventDefault();

            let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
            if (cart.length === 0) { alert('Cart is empty'); return; }

            const payButton = document.getElementById('pay-button');
            const originalText = payButton.innerHTML; 
            payButton.innerHTML = 'Processing...';
            payButton.disabled = true;

            const nameInput = document.getElementById('username') || document.getElementById('fname');
            const emailInput = document.getElementById('email');

            const orderData = {
                user: {
                    name: nameInput ? nameInput.value : 'Guest', 
                    email: emailInput ? emailInput.value : 'guest@example.com'
                },
                cart: cart
            };

            // Send to Database
            fetch('includes/place_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            })
            .then(async response => {
                // 1. Get the raw text from the server
                const text = await response.text();
                
                // 2. Try to read it as JSON
                try {
                    const data = JSON.parse(text);
                    return data;
                } catch (err) {
                    // 3. If it fails, THROW the raw text so we can see the PHP error
                    throw new Error("Server Error: " + text);
                }
            })
            .then(data => {
                if(data.status === 'success') {
                    const library = JSON.parse(localStorage.getItem('myLibrary')) || [];
                    const newItems = cart.map(item => ({...item, purchaseDate: new Date().toLocaleDateString()}));
                    localStorage.setItem('myLibrary', JSON.stringify([...newItems, ...library]));
                    
                    localStorage.removeItem('cartItems');
                    alert('Payment Successful!');
                    window.location.href = 'user-dashboard.php';
                } else {
                    alert('Database Error: ' + (data.message || 'Unknown error'));
                    payButton.disabled = false;
                    payButton.innerHTML = originalText;
                }
            })
            .catch(err => {
                // --- THIS IS THE IMPORTANT PART ---
                console.error(err);
                // This will alert the ACTUAL error (like "404 Not Found" or PHP error text)
                alert("Debug Error:\n" + err.message.substring(0, 400)); 
                payButton.disabled = false;
                payButton.innerHTML = originalText;
            });
        });
    }
  </script>
</body>
</html>