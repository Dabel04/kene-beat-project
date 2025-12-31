document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. NAVIGATION MENU LOGIC ---
    const menuBtn = document.getElementById("menu-btn");
    const navLinks = document.getElementById("nav-links");
    const menuBtnIcon = menuBtn ? menuBtn.querySelector("i") : null;

    if (menuBtn && navLinks && menuBtnIcon) {
        menuBtn.addEventListener("click", (e) => {
            navLinks.classList.toggle("open");
            const isOpen = navLinks.classList.contains("open");
            menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
        });

        navLinks.addEventListener("click", (e) => {
            navLinks.classList.remove("open");
            menuBtnIcon.setAttribute("class", "ri-menu-line");
        });
    }

    // --- 2. ANIMATIONS (ScrollReveal) ---
    // We check if ScrollReveal exists to prevent errors on pages without it
    if (typeof ScrollReveal !== 'undefined') {
        const scrollRevealOption = {
            distance: "50px",
            origin: "bottom",
            duration: "1000",
        };

        ScrollReveal().reveal(".header__image img", { ...scrollRevealOption, origin: "right" });
        ScrollReveal().reveal(".header__container h1", { ...scrollRevealOption, delay: 500 });
        ScrollReveal().reveal(".header__content .section__description", { ...scrollRevealOption, delay: 1000 });
        ScrollReveal().reveal(".header__btns", { ...scrollRevealOption, delay: 1500 });
        ScrollReveal().reveal(".header__stats", { ...scrollRevealOption, delay: 2000 });
        ScrollReveal().reveal(".genre__card", { ...scrollRevealOption, interval: 500 });
        ScrollReveal().reveal(".feature__image img", { ...scrollRevealOption, origin: "right" });
        ScrollReveal().reveal(".feature__content .section__header", { ...scrollRevealOption, delay: 500 });
        ScrollReveal().reveal(".feature__list li", { ...scrollRevealOption, delay: 1000, interval: 500 });
    }

    // --- 3. SWIPER SLIDER ---
    if (typeof Swiper !== 'undefined') {
        const swiper = new Swiper(".swiper", {
            slidesPerView: "auto",
            spaceBetween: 20,
        });
    }

    // --- 4. CART SYSTEM INITIALIZATION ---
    updateCartCount(); // Update badge numbers on page load

    // Cart Sidebar Toggles
    const cartBtn = document.getElementById('cart-btn');
    const closeCartBtn = document.getElementById('close-cart');
    
    if (cartBtn) {
        cartBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openCart();
        });
    }

    if (closeCartBtn) {
        closeCartBtn.addEventListener('click', closeCart);
    }

    // --- 5. GLOBAL FUNCTIONS (Accessible by HTML onclick="") ---
    
    // Add To Cart Function
    window.addToCart = function(item) {
        let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
        
        // Prevent duplicates for Kits (Optional but recommended)
        if (item.type === 'kit') {
            const exists = cart.find(i => i.id === item.id);
            if (exists) {
                alert("This kit is already in your cart!");
                openCart();
                return;
            }
        }

        cart.push(item);
        localStorage.setItem('cartItems', JSON.stringify(cart));
        
        updateCartCount();
        renderCart();
        openCart();
        saveCartToSession(cart); // Sync with PHP session
    };

    // Remove From Cart Function
    window.removeFromCart = function(index) {
        let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
        cart.splice(index, 1);
        localStorage.setItem('cartItems', JSON.stringify(cart));
        
        updateCartCount();
        renderCart();
        saveCartToSession(cart);
    };
});

// --- HELPER FUNCTIONS ---

function openCart() {
    const sidebar = document.getElementById('cart-sidebar');
    if (sidebar) {
        sidebar.classList.add('open');
        renderCart(); // Refresh data when opening
    }
}

function closeCart() {
    const sidebar = document.getElementById('cart-sidebar');
    if (sidebar) sidebar.classList.remove('open');
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cartItems')) || [];
    const countElements = document.querySelectorAll('.cart-count'); 
    
    countElements.forEach(el => {
        el.innerText = cart.length;
        // Optional: Hide badge if empty
        el.style.display = cart.length > 0 ? 'flex' : 'none'; 
    });
}

function renderCart() {
    const cartContainer = document.querySelector('.cart-items');
    const totalEl = document.querySelector('.cart-total-price');
    
    if (!cartContainer) return;

    let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
    cartContainer.innerHTML = '';
    
    let total = 0;

    if (cart.length === 0) {
        cartContainer.innerHTML = '<div class="empty-cart-msg" style="padding:20px; text-align:center; color:#666;">Your cart is empty</div>';
    } else {
        cart.forEach((item, index) => {
            total += parseFloat(item.price);
            
            // Logic: Distinguish between Beats and Kits
            let metaHtml = '';
            
            if (item.type === 'kit') {
                metaHtml = `<span class="cart-item-license" style="color:#2bee79; font-size:11px; font-weight:bold;">Sound Kit (Royalty Free)</span>`;
            } else {
                // It's a beat
                const licenseName = item.licenseName || item.licenseKey || 'Basic License';
                metaHtml = `<span class="cart-item-license" style="color:#aaa; font-size:11px;">${licenseName}</span>`;
            }

            cartContainer.innerHTML += `
            <div class="cart-item" style="display:flex; gap:10px; padding:10px; border-bottom:1px solid #222; align-items:center;">
                <img src="${item.img}" alt="${item.name}" onerror="this.src='image/disk.png'" style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                <div class="cart-item-info" style="flex:1;">
                    <h4 style="color:white; font-size:14px; margin:0; font-weight:bold;">${item.name}</h4>
                    ${metaHtml}
                    <div class="cart-item-price" style="color:white; font-weight:bold; margin-top:2px;">$${parseFloat(item.price).toFixed(2)}</div>
                </div>
                <button class="remove-btn" onclick="removeFromCart(${index})" style="background:none; border:none; color:#ff4757; font-size:20px; cursor:pointer;">&times;</button>
            </div>`;
        });
    }

    if(totalEl) totalEl.innerText = '$' + total.toFixed(2);
    
    // Auto-save to PHP session so Checkout works immediately
    saveCartToSession(cart);
}

// Send Cart Data to PHP
function saveCartToSession(cart) {
    fetch('includes/save_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart: cart })
    })
    .catch(error => console.error('Error saving cart:', error));
}