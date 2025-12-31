<style>
/* --- FOOTER STYLES --- */
.site-footer { background-color: #050505; color: #a0a0a0; font-size: 14px; padding-top: 60px; border-top: 1px solid #222; margin-top: auto; }
.footer-newsletter { border-bottom: 1px solid #1a1a1a; padding-bottom: 50px; margin-bottom: 50px; }
.newsletter-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
.newsletter-text h3 { color: white; margin-bottom: 5px; font-weight: 700; }
.newsletter-text p { margin: 0; color: #666; }
.newsletter-form { display: flex; gap: 10px; flex-grow: 1; max-width: 500px; }
.newsletter-form input { width: 100%; background: #111; border: 1px solid #333; padding: 12px 20px; color: white; border-radius: 5px; outline: none; }
.newsletter-form input:focus { border-color: #2bee79; }
.newsletter-form button { background: #2bee79; color: #000; font-weight: 700; border: none; padding: 0 25px; border-radius: 5px; cursor: pointer; transition: 0.3s; }
.newsletter-form button:hover { background: white; }
.footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 60px; }
.footer-col h4 { color: white; font-size: 16px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 25px; font-weight: 700; }
.footer-links-list { list-style: none; padding: 0; margin: 0; }
.footer-links-list li { margin-bottom: 12px; }
.footer-links-list a { color: #a0a0a0; text-decoration: none; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
.footer-links-list a:hover { color: #2bee79; padding-left: 5px; }
.social-icons { display: flex; gap: 15px; }
.social-icons a { width: 35px; height: 35px; background: #111; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; text-decoration: none; transition: 0.3s; }
.social-icons a:hover { background: #2bee79; color: black; transform: translateY(-3px); }
.footer-bottom { background: #020202; padding: 25px 0; border-top: 1px solid #1a1a1a; }
.footer-bottom-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
.payment-methods i { font-size: 24px; color: #555; margin-left: 10px; }
@media (max-width: 991px) { .footer-grid { grid-template-columns: 1fr 1fr; } .footer-newsletter { text-align: center; } .newsletter-content { justify-content: center; } }
@media (max-width: 576px) { .footer-grid { grid-template-columns: 1fr; text-align: center; } .newsletter-form { width: 100%; flex-direction: column; } .footer-bottom-content { flex-direction: column; text-align: center; } }

/* --- CART SIDEBAR --- */
.slide-out-cart { position: fixed; top: 0; right: -450px; width: 420px; height: 100%; background: #080808; border-left: 1px solid #222; box-shadow: -10px 0 30px rgba(0,0,0,0.8); z-index: 2000; transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1); display: flex; flex-direction: column; }
.slide-out-cart.open { right: 0; }
.cart-header { padding: 25px; background: #0a0a0a; border-bottom: 1px solid #222; display: flex; justify-content: space-between; align-items: center; }
.cart-header h3 { margin: 0; color: white; font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
.close-btn { background: none; border: none; color: #666; font-size: 28px; cursor: pointer; transition: 0.3s; line-height: 1; }
.close-btn:hover { color: white; }
.cart-items { flex-grow: 1; overflow-y: auto; padding: 20px; }
.cart-item { display: flex; align-items: center; gap: 15px; background: #111; padding: 15px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #222; transition: 0.2s; }
.cart-item:hover { border-color: #333; }
.cart-item-img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
.cart-item-info { flex-grow: 1; }
.cart-item-title { color: white; font-weight: 700; font-size: 14px; margin: 0 0 4px 0; }
.cart-item-license { display: block; color: #888; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.cart-item-price { color: #2bee79; font-weight: 700; font-size: 14px; }
.remove-item-btn { background: none; border: none; color: #444; cursor: pointer; font-size: 16px; transition: 0.3s; padding: 5px; }
.remove-item-btn:hover { color: #ff4757; }
.cart-footer { padding: 25px; background: #0a0a0a; border-top: 1px solid #222; }
.cart-total { display: flex; justify-content: space-between; align-items: center; color: white; font-size: 18px; font-weight: 700; margin-bottom: 20px; }
.cart-total span { color: #2bee79; }
.btn-checkout { width: 100%; background: #2bee79; color: #000; font-weight: 800; text-transform: uppercase; padding: 15px; border: none; border-radius: 4px; letter-spacing: 1px; cursor: pointer; transition: 0.3s; }
.btn-checkout:hover { background: white; box-shadow: 0 0 15px rgba(43, 238, 121, 0.4); }
.empty-cart-message { color: #666; text-align: center; margin-top: 50px; font-style: italic; }
@media (max-width: 500px) { .slide-out-cart { width: 100%; right: -100%; } }

/* --- MODAL STYLES --- */
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.9); backdrop-filter: blur(5px); z-index: 3000; display: none; justify-content: center; align-items: center; padding: 20px; }
.options-modal { background: #0a0a0a; width: 100%; max-width: 1000px; border-radius: 12px; border: 1px solid #333; overflow: hidden; animation: slideUp 0.3s ease-out; display: flex; flex-direction: column; max-height: 90vh; }
@keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.options-header { background: #111; padding: 20px 30px; border-bottom: 1px solid #222; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
.options-header h3 { margin: 0; color: white; font-size: 18px; font-weight: 700; }
.options-body { padding: 30px; background: #080808; overflow-y: auto; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
@media (max-width: 991px) { .modal-overlay { align-items: flex-end; padding: 0; } .options-modal { max-width: 100%; border-radius: 15px 15px 0 0; max-height: 85vh; border-bottom: none; } .options-body { display: flex; flex-direction: column; padding: 20px; gap: 15px; } .license-option-card { padding: 20px; } }
.license-option-card { background: #131313; border: 1px solid #222; border-radius: 10px; padding: 25px; display: flex; flex-direction: column; align-items: center; text-align: center; transition: all 0.2s ease-in-out; position: relative; cursor: pointer; height: 100%; }
.license-option-card:hover, .license-option-card:active { border-color: #2bee79; background: #181818; transform: translateY(-2px); }
.license-name { color: #fff; font-size: 18px; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 1px; }
.license-price { color: #2bee79; font-size: 28px; font-weight: 700; margin-bottom: 20px; }
.license-features { list-style: none; padding: 0; margin: 0 0 20px 0; width: 100%; text-align: left; flex-grow: 1; }
.license-features li { color: #999; font-size: 13px; padding: 8px 0; border-bottom: 1px solid #222; display: flex; align-items: center; gap: 8px; }
.license-features li i { color: #2bee79; font-size: 10px; }
.license-features li:last-child { border-bottom: none; }
.select-label { margin-top: auto; font-size: 12px; font-weight: 700; color: #555; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; border: 1px solid #333; padding: 8px 20px; border-radius: 20px; }
.license-option-card:hover .select-label { background: #2bee79; color: black; border-color: #2bee79; }
.recommended-badge { position: absolute; top: -10px; background: #2bee79; color: black; font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; z-index: 10; }
</style>

<footer class="site-footer">
    <div class="container">
        <div class="footer-newsletter">
            <div class="newsletter-content">
                <div class="newsletter-text">
                    <h3>Join the Inner Circle</h3>
                    <p>Get exclusive free beats, discounts, and industry tips.</p>
                </div>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email address..." required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        
        <div class="footer-grid">
            <div class="footer-col footer-brand">
                <a href="#" class="brand-logo" style="font-size: 22px; margin-bottom: 15px; display:block;">Kenton<span>.</span></a>
                <p>Professional music production for serious artists. High-quality instrumentals, mixing services, and custom sound design.</p>
                <div class="social-icons">
                    <a href="#"><i class="fa fa-instagram"></i></a>
                    <a href="#"><i class="fa fa-youtube-play"></i></a>
                    <a href="#"><i class="fa fa-soundcloud"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Explore</h4>
                <ul class="footer-links-list">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="tracks.php">Beat Catalog</a></li>
                    <li><a href="kits.php">Sound Kits</a></li>
                    <li><a href="services.php">Services</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Support</h4>
                <ul class="footer-links-list">
                    <li><a href="about.php">About Me</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="licensing.php">Licensing Info</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <ul class="footer-links-list">
                    <li><a href="#"><i class="fa fa-envelope"></i> info@kenton.com</a></li>
                    <li><a href="#"><i class="fa fa-map-marker"></i> Los Angeles, CA</a></li>
                </ul>
                <div style="margin-top: 20px;">
                    <button class="btn btn-outline-light btn-sm" onclick="window.location.href='contact.php'">Work With Me</button>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container footer-bottom-content">
            <div class="copyright">&copy; 2024 <strong>KentonTheProducer</strong>. All Rights Reserved.</div>
            <div class="payment-methods">
                <i class="fa fa-cc-visa"></i>
                <i class="fa fa-cc-mastercard"></i>
                <i class="fa fa-cc-paypal"></i>
                <i class="fa fa-cc-stripe"></i>
            </div>
        </div>
    </div>
</footer>

<div class="audio-player" style="display: none;">
    <div class="player-controls">
        <button class="player-btn previous-btn"><i class="fa fa-step-backward"></i></button>
        <button class="player-btn play-pause-btn"><i class="fa fa-play"></i></button>
        <button class="player-btn next-btn"><i class="fa fa-step-forward"></i></button>
    </div>
    <div class="track-details">
        <div class="player-track-image"><img src="" alt="Track"></div>
        <div class="player-track-info">
            <div class="player-track-title">Track Title</div>
            <div class="player-track-artist">KentonTheProducer</div>
        </div>
    </div>
    <div class="progress-container">
        <div class="progress-bar"><div class="progress"></div></div>
        <div class="time"><span class="current-time">0:00</span><span class="duration">0:00</span></div>
    </div>
    <div class="volume-control">
        <button class="player-btn volume-btn"><i class="fa fa-volume-up"></i></button>
        <div class="volume-bar"><div class="volume-level"></div></div>
    </div>
</div>

<div id="cart-sidebar" class="slide-out-cart">
    <div class="cart-header">
        <h3>Your Cart</h3>
        <button id="close-cart-btn" class="close-btn">&times;</button>
    </div>
    <div id="cart-items-container" class="cart-items"><p class="empty-cart-message">Your cart is empty.</p></div>
    <div class="cart-footer">
        <div class="cart-total">Total: <span id="cart-total-price">$0.00</span></div>
        <button class="btn-checkout">Checkout Securely <i class="fa fa-lock ml-1"></i></button>
    </div>
</div>

<div id="options-modal-overlay" class="modal-overlay">
    <div class="options-modal">
        <div class="options-header">
            <h3 id="modal-track-name">Select License</h3>
            <button class="close-btn" id="close-options-modal">&times;</button>
        </div>
        <div class="options-body" id="options-container"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // --- 1. MOBILE MENU ---
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const navLinks = document.getElementById('nav-links');
    if(mobileBtn){
        mobileBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            const icon = mobileBtn.querySelector('i');
            if(navLinks.classList.contains('active')){ icon.classList.remove('fa-bars'); icon.classList.add('fa-times'); } 
            else { icon.classList.remove('fa-times'); icon.classList.add('fa-bars'); }
        });
    }

    // --- 2. AUDIO PLAYER ---
    const audioPlayer = document.querySelector('.audio-player');
    const playPauseBtn = audioPlayer.querySelector('.play-pause-btn');
    const playPauseIcon = playPauseBtn ? playPauseBtn.querySelector('i') : null;
    const playerTitle = audioPlayer.querySelector('.player-track-title');
    const playerImage = audioPlayer.querySelector('.player-track-image img');
    const progress = audioPlayer.querySelector('.progress');
    const progressBar = audioPlayer.querySelector('.progress-bar');
    
    let genericAudio = new Audio();
    let currentAudio = null;
    let currentCard = null;

    function resetTrackState(card) {
        if(card) {
            card.classList.remove('playing');
            const icon = card.querySelector('.btn-play i') || card.querySelector('.play-button i');
            if(icon) icon.className = 'fa fa-play';
        }
    }

    window.loadTrack = function(input, context) {
        if(currentAudio && currentAudio !== input && currentAudio !== genericAudio) {
            currentAudio.pause();
            resetTrackState(currentCard);
        }

        if (typeof input === 'object' && !(input instanceof Element)) {
            genericAudio.src = input.audio;
            currentAudio = genericAudio;
            currentCard = null; 
            audioPlayer.style.display = 'flex';
            if(playerTitle) playerTitle.textContent = input.title;
            if(playerImage) playerImage.src = input.cover;
            currentAudio.play().catch(e => console.error("Playback error", e));
            if(playPauseIcon) playPauseIcon.className = 'fa fa-pause';
            attachPlayerListeners(currentAudio, null);
            return;
        }

        currentAudio = input;
        currentCard = context;
        audioPlayer.style.display = 'flex';
        const title = context.querySelector('.card-body h5 a, .track-title, .track-title a')?.textContent || 'Unknown';
        const img = context.querySelector('img')?.src || '';
        if(playerTitle) playerTitle.textContent = title;
        if(playerImage) playerImage.src = img;
        currentAudio.play().catch(e => console.error(e));
        if(playPauseIcon) playPauseIcon.className = 'fa fa-pause';
        context.classList.add('playing');
        const icon = context.querySelector('.btn-play i') || context.querySelector('.play-button i');
        if(icon) icon.className = 'fa fa-pause';
        attachPlayerListeners(currentAudio, context);
    }

    function attachPlayerListeners(audio, card) {
        audio.ontimeupdate = () => {
            const percent = (audio.currentTime / audio.duration) * 100;
            if(progress) progress.style.width = `${percent}%`;
        };
        audio.onended = () => {
            if(playPauseIcon) playPauseIcon.className = 'fa fa-play';
            if(card) resetTrackState(card);
        };
    }

    document.querySelectorAll('.card, .track-card').forEach(card => {
        const btn = card.querySelector('.btn-play') || card.querySelector('.play-button');
        const audio = card.querySelector('audio');
        if(btn && audio) {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if(currentAudio === audio) {
                    if(audio.paused) { audio.play(); if(playPauseIcon) playPauseIcon.className = 'fa fa-pause'; }
                    else { audio.pause(); if(playPauseIcon) playPauseIcon.className = 'fa fa-play'; }
                } else { window.loadTrack(audio, card); }
            });
        }
    });

    if(playPauseBtn) {
        playPauseBtn.addEventListener('click', () => {
            if(!currentAudio) return;
            if(currentAudio.paused) { currentAudio.play(); playPauseIcon.className = 'fa fa-pause'; }
            else { currentAudio.pause(); playPauseIcon.className = 'fa fa-play'; }
        });
    }

    if(progressBar) {
        progressBar.addEventListener('click', (e) => {
            if(!currentAudio) return;
            const width = progressBar.clientWidth;
            const clickX = e.offsetX;
            currentAudio.currentTime = (clickX / width) * currentAudio.duration;
        });
    }

    // --- 3. GLOBAL ADD TO CART (Fix for Sound Kits) ---
    // This allows kits.php to call window.addToCart({...})
    window.addToCart = function(item) {
        let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
        
        // Prevent duplicate kits (optional)
        if (item.type === 'kit') {
            const exists = cart.find(i => i.id === item.id);
            if (exists) {
                alert("This kit is already in your cart!");
                // Open Sidebar
                cartSidebar.classList.add('open');
                return;
            }
        }

        cart.push(item);
        saveCartState(cart);
        renderCart();
        cartSidebar.classList.add('open');
    };

    // --- 4. CART SYSTEM (Internal) ---
    let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
    const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    
    const cartCountElement = document.getElementById('cart-count');
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartItemsContainer = document.getElementById('cart-items-container');
    const cartTotalPrice = document.getElementById('cart-total-price');
    const openCartBtn = document.getElementById('open-cart-btn');
    const closeCartBtn = document.getElementById('close-cart-btn');
    const checkoutBtn = document.querySelector('.btn-checkout');

    function saveCartState(updatedCart) {
        if(updatedCart) cart = updatedCart;
        localStorage.setItem('cartItems', JSON.stringify(cart));
        updateCartCount();
        
        // Sync with PHP
        fetch('includes/save_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart: cart })
        }).catch(err => console.error("Sync failed", err));
    }

    function initCart() {
        if (isLoggedIn) {
            fetch('includes/get_cart.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const serverItems = data.items || [];
                        cart = serverItems.map(item => ({
                            ...item,
                            licenseKey: item.licenseKey || item.license_type || 'basic'
                        }));
                        localStorage.setItem('cartItems', JSON.stringify(cart));
                        renderCart();
                    }
                })
                .catch(err => console.log('Cart fetch skipped/failed'));
        } else {
            renderCart();
        }
        updateCartCount();
    }

    function updateCartCount() { if(cartCountElement) cartCountElement.textContent = cart.length; }
    function calculateCartTotal() { return cart.reduce((sum, item) => sum + parseFloat(item.price), 0).toFixed(2); }

    function renderCart() {
        if (!cartItemsContainer) return;
        cartItemsContainer.innerHTML = '';
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="empty-cart-message">Your cart is empty.</p>';
        } else {
            cart.forEach((item, index) => {
                const itemEl = document.createElement('div');
                itemEl.classList.add('cart-item');
                const imgUrl = item.img || item.cover || 'https://via.placeholder.com/60?text=Item';
                
                // LICENSE DISPLAY LOGIC
                let licenseHtml = '';
                if(item.type === 'kit') {
                    licenseHtml = `<span class="cart-item-license" style="color:#2bee79; font-weight:bold;">Sound Kit (Royalty Free)</span>`;
                } else {
                    licenseHtml = `<span class="cart-item-license">${item.licenseName || 'Standard Lease'}</span>`;
                }

                itemEl.innerHTML = `
                    <img src="${imgUrl}" class="cart-item-img" alt="Cover" onerror="this.src='https://via.placeholder.com/60'">
                    <div class="cart-item-info">
                        <h4 class="cart-item-title">${item.name || item.title}</h4>
                        ${licenseHtml}
                        <span class="cart-item-price">$${parseFloat(item.price).toFixed(2)}</span>
                    </div>
                    <button class="remove-item-btn" data-index="${index}"><i class="fa fa-trash"></i></button>
                `;
                cartItemsContainer.appendChild(itemEl);
            });
            
            cartItemsContainer.querySelectorAll('.remove-item-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const idx = parseInt(e.currentTarget.dataset.index);
                    cart.splice(idx, 1);
                    saveCartState(); 
                    renderCart();
                });
            });
        }
        if (cartTotalPrice) cartTotalPrice.textContent = `$${calculateCartTotal()}`;
    }

    if (openCartBtn) openCartBtn.addEventListener('click', () => { cartSidebar.classList.add('open'); renderCart(); });
    if (closeCartBtn) closeCartBtn.addEventListener('click', () => cartSidebar.classList.remove('open'));
    if (checkoutBtn) checkoutBtn.addEventListener('click', () => { cart.length > 0 ? window.location.href = 'checkout.php' : alert("Cart is empty!"); });
    
    initCart();

    // --- 5. BEAT LICENSE MODAL (Specific to Beats) ---
    const LICENSES = {
        'basic': { name: 'Basic Lease', price: 25.00, features: ['MP3 File (320kbps)', '5,000 Streams Cap', 'Non-Profit Use', '1 Commercial Video', 'Instant Download'], recommended: false },
        'premium': { name: 'Premium Lease', price: 99.99, features: ['WAV + MP3 Files', '500,000 Streams Cap', 'For Profit Use', '10 Commercial Videos', 'Tracked Out Stems (+$50)'], recommended: true },
        'exclusive': { name: 'Exclusive Rights', price: 500.00, features: ['MP3 + WAV + Stems', 'Unlimited Streams', 'Unlimited Profits', 'Radio Broadcasting', 'Ownership Transferred'], recommended: false }
    };
    const optionsModalOverlay = document.getElementById('options-modal-overlay');
    const optionsContainer = document.getElementById('options-container');
    const modalTrackName = document.getElementById('modal-track-name');
    const closeOptionsModalBtn = document.getElementById('close-options-modal');
    let currentTrackData = {};

    // Only attach to buttons that are NOT kits (kits usually have onclick="addToCart")
    document.querySelectorAll('.open-options-btn').forEach(button => {
        button.addEventListener('click', () => {
            currentTrackData = {
                id: button.dataset.id, name: button.dataset.name, producer: button.dataset.producer,
                img: button.closest('.card, .track-card')?.querySelector('img')?.src || ''
            };
            if(modalTrackName) modalTrackName.innerHTML = `Select License: <span style="color:#2bee79">${currentTrackData.name}</span>`;
            renderOptions();
            if(optionsModalOverlay) optionsModalOverlay.style.display = 'flex';
        });
    });

    if(closeOptionsModalBtn) closeOptionsModalBtn.addEventListener('click', () => optionsModalOverlay.style.display = 'none');

    function renderOptions() {
        if (!optionsContainer) return;
        optionsContainer.innerHTML = '';
        const keys = ['basic', 'premium', 'exclusive'];
        keys.forEach(key => {
            const license = LICENSES[key];
            const div = document.createElement('div');
            div.className = 'license-option-card';
            div.dataset.key = key; 
            let featuresHTML = '';
            license.features.forEach(feat => { featuresHTML += `<li><i class="fa fa-check"></i> ${feat}</li>`; });
            const badge = license.recommended ? '<div class="recommended-badge">Best Value</div>' : '';
            div.innerHTML = `
                ${badge}
                <div class="license-name">${license.name}</div>
                <div class="license-price">$${license.price.toFixed(0)}</div>
                <ul class="license-features">${featuresHTML}</ul>
                <div class="select-label">Select Plan</div>
            `;
            optionsContainer.appendChild(div);
        });
        
        optionsContainer.querySelectorAll('.license-option-card').forEach(card => {
            card.addEventListener('click', (e) => {
                const key = card.dataset.key;
                
                const existingIndex = cart.findIndex(item => item.id === currentTrackData.id);
                if (existingIndex !== -1) {
                    cart[existingIndex].licenseKey = key;
                    cart[existingIndex].price = LICENSES[key].price;
                    cart[existingIndex].licenseName = LICENSES[key].name;
                } else {
                    cart.push({
                        id: currentTrackData.id, 
                        name: currentTrackData.name, 
                        producer: currentTrackData.producer,
                        price: LICENSES[key].price, 
                        licenseKey: key, 
                        licenseName: LICENSES[key].name, 
                        img: currentTrackData.img
                    });
                }
                
                saveCartState(); 
                optionsModalOverlay.style.display = 'none';
                cartSidebar.classList.add('open'); 
                renderCart();
            });
        });
    }
});
</script>