<?php
include 'header.php';

// Force Login
if (!isset($_SESSION['user_id'])) {
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
  <title>Checkout - KentonTheProducer</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/homepage.css">
  <link rel="stylesheet" href="css/checkout.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
      body { background-color: #000; color: white; }
      
      /* --- PROFESSIONAL CARD INPUT STYLING --- */
      .card-input-container {
          border: 1px solid #333;
          border-radius: 6px;
          background: #0f0f0f;
          overflow: hidden;
          transition: border-color 0.3s;
      }
      .card-input-container:focus-within {
          border-color: #2bee79;
          box-shadow: 0 0 0 1px #2bee79;
      }
      
      .card-number-row {
          display: flex;
          align-items: center;
          padding: 12px 15px;
          border-bottom: 1px solid #333;
          background: #0f0f0f;
      }
      .card-number-row i { color: #888; margin-right: 10px; font-size: 18px; }
      .card-number-row input {
          border: none;
          background: transparent;
          width: 100%;
          color: white;
          outline: none;
          font-size: 16px;
          letter-spacing: 1px;
      }
      
      .card-details-row {
          display: flex;
      }
      .card-details-row input {
          width: 50%;
          border: none;
          background: transparent;
          color: white;
          padding: 12px 15px;
          outline: none;
          font-size: 16px;
          text-align: center;
      }
      .card-details-row input:first-child {
          border-right: 1px solid #333;
      }
      
      /* Readonly Inputs */
      input[readonly] { 
          background-color: #1a1a1a !important; 
          color: #888 !important; 
          border: 1px solid #333 !important; 
          cursor: not-allowed; 
      }

      /* Play/Delete Button Styles */
      .cart-item-image { position: relative; cursor: pointer; }
      .image-overlay {
          position: absolute; top: 0; left: 0; width: 100%; height: 100%;
          background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;
          opacity: 0; transition: 0.3s; border-radius: 6px;
      }
      .cart-item-image:hover .image-overlay { opacity: 1; }
      .image-overlay i { color: #2bee79; font-size: 20px; }
      
      .remove-item {
          background: none; border: none; color: #666; cursor: pointer; transition: 0.3s; font-size: 14px;
      }
      .remove-item:hover { color: #ff4757; }
  </style>
</head>
<body>
  
  <main class="checkout-container" style="padding-top: 120px; padding-bottom: 50px;">
    <div class="checkout-header">
      <h1>Secure Checkout</h1>
      <p class="subtitle">Review your tracks and complete your purchase.</p>
    </div>

    <div class="checkout-grid">
      <div class="checkout-left">
        <div class="cart-items-card">
          <div class="cart-header">
            <h3>Your Cart (<span id="cart-item-count">0</span> Items)</h3>
            <button class="clear-btn" id="clear-cart">Clear All</button>
          </div>
          
          <div class="cart-items-list" id="cart-items-list">
             <p style="padding:20px; color:#666;">Loading items...</p>
          </div>
        </div>

        <div class="promo-card">
          <div class="promo-content">
            <div class="promo-icon"><i class="fas fa-gift"></i></div>
            <div class="promo-text">
              <h4>Get 50% Off Your Next Beat?</h4>
              <p>Add 2 more tracks to your cart to unlock the bulk discount.</p>
            </div>
          </div>
          <button class="promo-btn" onclick="window.location.href='tracks.php'">Browse Beats</button>
        </div>
      </div>

      <div class="checkout-right">
        <div class="sticky-checkout">
            
          <div class="order-summary">
            <h3>Order Summary</h3>
            <div class="summary-details">
              <div class="summary-row"><span>Subtotal</span><span id="cart-subtotal">$0.00</span></div>
              <div class="summary-row"><span>Discount</span><span class="discount" id="cart-discount">-$0.00</span></div>
              <div class="summary-row"><span>Taxes</span><span id="cart-taxes">$0.00</span></div>
            </div>
            <div class="summary-total">
              <span>Total</span>
              <span id="cart-total" class="total-amount">$0.00</span>
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
                <input type="text" id="username" value="<?php echo htmlspecialchars($currentUser); ?>" readonly>
              </div>

              <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($currentEmail); ?>" readonly>
                <p class="helper-text">Your files will be sent to this email instantly.</p>
              </div>

              <div class="form-group">
                <label>Card Information</label>
                <div class="card-input-container">
                  <div class="card-number-row">
                    <i class="far fa-credit-card"></i>
                    <input type="text" id="card-number" placeholder="0000 0000 0000 0000" maxlength="19" required>
                  </div>
                  <div class="card-details-row">
                    <input type="text" id="card-expiry" placeholder="MM / YY" maxlength="7" required>
                    <input type="text" id="card-cvc" placeholder="CVC" maxlength="4" required>
                  </div>
                </div>
              </div>

              <button type="submit" class="pay-btn" id="pay-button" style="width:100%; padding:15px; background:#2bee79; color:black; font-weight:800; border:none; border-radius:5px; cursor:pointer;">
                <span>Pay <span id="pay-amount">$0.00</span></span>
                <i class="fas fa-arrow-right"></i>
              </button>

              <div class="security-badges">
                <div class="badge-item"><i class="fas fa-lock"></i><span>Secure 256-bit SSL</span></div>
                <div class="badge-item"><i class="fas fa-bolt"></i><span>Instant Delivery</span></div>
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
      // VARIABLES
      const cartList = document.getElementById('cart-items-list');
      const cartItemCount = document.getElementById('cart-item-count');
      const cartSubtotal = document.getElementById('cart-subtotal');
      const cartTotal = document.getElementById('cart-total');
      const payAmount = document.getElementById('pay-amount');
      const payButton = document.getElementById('pay-button');
      const clearCartBtn = document.getElementById('clear-cart');
      let cartData = [];

      // FETCH CART
      fetch('includes/get_cart.php')
      .then(res => res.json())
      .then(data => {
          if (data.success && data.items.length > 0) {
              cartData = data.items;
              renderCheckout();
          } else {
              handleEmptyCart();
          }
      })
      .catch(err => {
          console.error("Cart Error:", err);
          cartList.innerHTML = '<p style="padding:20px; color:red;">Error loading cart. Please refresh.</p>';
      });

      function handleEmptyCart() {
          cartList.innerHTML = `
            <div class="empty-cart" style="text-align:center; padding:30px;">
              <i class="fas fa-shopping-cart" style="font-size:30px; color:#333; margin-bottom:10px;"></i>
              <p>Your cart is empty</p>
              <a href="tracks.php" style="color:#2bee79;">Browse Beats</a>
            </div>`;
          payButton.disabled = true;
          payButton.style.opacity = '0.5';
          if(cartItemCount) cartItemCount.textContent = '0';
          updateTotals(0);
      }

      // RENDER
      window.renderCheckout = function() {
        cartList.innerHTML = '';
        let total = 0;

        if(cartData.length === 0) {
            handleEmptyCart();
            return;
        }

        cartData.forEach((item, index) => {
            total += parseFloat(item.price);
            const el = document.createElement('div');
            el.className = 'cart-item';
            el.innerHTML = `
                <div class="cart-item-content" style="display:flex; gap:15px; align-items:center; flex:1;">
                    <div class="cart-item-image" style="position:relative; width:60px; height:60px;">
                        <img src="${item.img || 'https://via.placeholder.com/60'}" style="width:100%; height:100%; object-fit:cover; border-radius:6px;">
                        <div class="image-overlay js-play-checkout" data-index="${index}">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="cart-item-info">
                        <h4 style="margin:0; color:white; font-size:16px;">${item.name || item.title}</h4>
                        <div class="item-meta" style="font-size:12px; color:#888;">
                            <span>${item.producer || 'Kenton'}</span> • 
                            <span style="color:#2bee79;">${item.licenseName}</span>
                        </div>
                    </div>
                </div>
                <div class="cart-item-actions" style="display:flex; align-items:center; gap:15px;">
                    <div class="item-price" style="font-weight:bold; color:white;">$${parseFloat(item.price).toFixed(2)}</div>
                    <button class="remove-item" onclick="removeItem(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            cartList.appendChild(el);
        });

        if(cartItemCount) cartItemCount.textContent = cartData.length;
        updateTotals(total);
      };

      function updateTotals(total) {
          const t = total.toFixed(2);
          if(cartSubtotal) cartSubtotal.textContent = `$${t}`;
          if(cartTotal) cartTotal.textContent = `$${t}`;
          if(payAmount) payAmount.textContent = `$${t}`;
      }

      // REMOVE ITEM
      window.removeItem = function(index) {
          cartData.splice(index, 1);
          fetch('includes/save_cart.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ cart: cartData })
          });
          renderCheckout();
      };

      // PLAY FUNCTION (Hooks into Footer Player)
      document.addEventListener('click', (e) => {
          const playBtn = e.target.closest('.js-play-checkout');
          if (playBtn) {
              const index = playBtn.dataset.index;
              const track = cartData[index];
              // Assuming 'loadTrack' is the function in your footer.php
              if (window.loadTrack && track) {
                  // We construct a track object that matches what footer expects
                  // get_cart.php returns 'name', 'img', 'producer'. 
                  // If 'audio' isn't in DB, we might default to demo or need to fix get_cart.
                  window.loadTrack({
                      title: track.name || track.title,
                      artist: track.producer || 'Kenton',
                      cover: track.img,
                      audio: track.audio || '#' // Needs audio link from DB
                  });
              } else {
                  console.log("Player not found or track missing audio");
              }
          }
      });
      
      if(clearCartBtn) {
          clearCartBtn.addEventListener('click', () => {
              if(confirm("Clear cart?")) {
                  cartData = [];
                  removeItem(0); 
              }
          });
      }

      // CARD FORMATTING
      const ccInput = document.getElementById('card-number');
      const expInput = document.getElementById('card-expiry');
      
      if(ccInput) {
          ccInput.addEventListener('input', function (e) {
              this.value = this.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
          });
      }
      if(expInput) {
          expInput.addEventListener('input', function (e) {
              this.value = this.value.replace(/\D/g, '').replace(/^(\d{2})(\d)/, '$1/$2').substr(0, 5);
          });
      }

      // SUBMIT
      const form = document.getElementById('payment-form');
      if (form) {
          form.addEventListener('submit', (e) => {
              e.preventDefault();
              if (cartData.length === 0) return alert("Cart is empty");

              const originalText = payButton.innerHTML;
              payButton.innerHTML = 'Processing...';
              payButton.disabled = true;

              const orderData = {
                  user: {
                      name: document.getElementById('username').value,
                      email: document.getElementById('email').value
                  },
                  cart: cartData
              };

              fetch('includes/place_order.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify(orderData)
              })
              .then(async response => {
                  const text = await response.text();
                  try { return JSON.parse(text); } 
                  catch (e) { throw new Error(text); }
              })
              .then(data => {
                  if(data.status === 'success') {
                      localStorage.removeItem('cartItems'); 
                      window.location.href = 'user-dashboard.php';
                  } else {
                      throw new Error(data.message);
                  }
              })
              .catch(err => {
                  console.error(err);
                  alert('Payment Failed: ' + err.message);
                  payButton.disabled = false;
                  payButton.innerHTML = originalText;
              });
          });
      }
    });
  </script>
</body>
</html>