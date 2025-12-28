<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/homepage.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <style>
    #cartItemsContainer {
      margin-top: 30px;
    }

    .remove-item {
      border: 1px solid var(--secondary-color);
      background: transparent;
      color: var(--secondary-color);
      padding: 6px 14px;
      border-radius: 20px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }

    .remove-item:hover {
      background: var(--secondary-color);
      color: var(--primary-color-dark);
    }

    #cartTotalContainer {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 20px;
      font-size: 1.3rem;
      text-align: right;
    }

    #totalPrice {
      color: var(--white);
      font-weight: bold;
    }

    .btn-checkout {
      display: inline-block;
      margin-top: 20px;
      background: var(--secondary-color);
      color: var(--primary-color-dark);
      padding: 10px 25px;
      border: none;
      border-radius: 30px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-checkout:hover {
      background: #e57a2d;
    }
  </style>
</head>
<body>

  <nav class="navbar">
    <a class="navbar-brand" href="home.php">
       <div class="logo"><i class="fa fa-grav"> Kenton<span>TheProducer</span></i></div>
     </a>
    <a href="cart.php" class="cart-container">
      🛒 <span id="cart-count">0</span>
    </a>
  </nav>

  <section class="section-box section-small">
    <h2>Your Cart Items</h2>
    <div id="cartItemsContainer" class="tracks-grid"></div>

    <div id="cartTotalContainer">
      <h3 style="color: var(--secondary-color); font-weight: 600;">
        Total: <span id="totalPrice">$0.00</span>
      </h3>
      <button class="btn-checkout" id="checkoutBtn">Checkout</button>
    </div>
  </section>

  <script>
// --- IMPROVED CART PAGE SCRIPT WITH SERVER SYNC ---
let cartItems = [];
const cartContainer = document.getElementById('cartItemsContainer');
const totalPriceElement = document.getElementById('totalPrice');
const cartCountElement = document.getElementById('cart-count');
const checkoutBtn = document.getElementById('checkoutBtn');
let isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

// Fetch cart from server if logged in, otherwise from LocalStorage
async function loadCart() {
    if (isLoggedIn) {
        try {
            const res = await fetch('includes/get_cart.php');
            const data = await res.json();
            if (data.success) {
                cartItems = data.items;
            }
        } catch (e) {
            console.error("Failed to load cart from server", e);
            cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        }
    } else {
        cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    }
    renderCart();
}

function saveCart() {
    if (isLoggedIn) {
        fetch('includes/save_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart: cartItems })
        });
    } else {
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
    }
    localStorage.setItem('cartCount', cartItems.length);
    if (cartCountElement) cartCountElement.textContent = cartItems.length;
}

function renderCart() {
    cartContainer.innerHTML = '';
    let totalPrice = 0;

    if (cartItems.length === 0) {
        cartContainer.innerHTML = '<p style="text-align:center; color:#888; padding:40px;">Your cart is empty.</p>';
        totalPriceElement.textContent = "$0.00";
        checkoutBtn.style.display = "none";
        return;
    }

    cartItems.forEach((item, index) => {
        totalPrice += item.price;

        const card = document.createElement('div');
        card.classList.add('track-card');
        card.innerHTML = `
            <div class="track-info">
                <h3 class="track-title">${item.name}</h3>
                <p class="track-artist">Producer: ${item.producer}</p>
                <p class="license-type">License: ${item.licenseName || 'Basic'}</p>
                <div class="track-meta">
                    <span class="track-price">$${item.price.toFixed(2)}</span>
                    <button class="btn-buy remove-item" data-index="${index}">Remove</button>
                </div>
            </div>
        `;
        cartContainer.appendChild(card);
    });

    totalPriceElement.textContent = `$${totalPrice.toFixed(2)}`;
    checkoutBtn.style.display = "inline-block";
}

// Remove item
document.addEventListener('click', async (e) => {
    if (e.target.classList.contains('remove-item')) {
        const index = e.target.dataset.index;
        cartItems.splice(index, 1);
        saveCart();
        renderCart();
    }
});

// Checkout
checkoutBtn.addEventListener('click', () => {
    if (cartItems.length === 0) return alert('Cart is empty!');
    if (isLoggedIn) {
        window.location.href = 'checkout.php';
    } else {
        alert('Please log in to checkout!');
        window.location.href = 'getstarted.php';
    }
});

// Initial load
loadCart();
</script>
</body>
</html>
