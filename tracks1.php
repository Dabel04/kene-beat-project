
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beat Library - Modern Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/tracks.css">
</head>
<body>

    <div class="alert-bar text-white py-2">
        <div class="container d-flex justify-content-between align-items-center px-3">
            <small>⚠️ Get 50% off all beats for a limited time! ⚠️</small>
            <button class="btn btn-sm btn-accent text-uppercase fw-bold">Shop Now</button>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg py-3">
        <div class="container-fluid px-3">
            <a class="navbar-brand logo-text me-4" href="#">
                <div class="logo"><i class="fa fa-grav"></i> Kenton<span>TheProducer</span></div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars text-accent"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="#">Library</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Licensing</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                </ul>

                <form class="d-none d-lg-flex mx-auto col-lg-4 my-2 my-lg-0">
                    <div class="input-group">
                        <input class="form-control bg-secondary-main text-white border-0" type="search" placeholder="Search for artists, genres, or moods..." aria-label="Search">
                        <button class="btn btn-accent" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-primary-text" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user me-1"></i> <span id="user-display-name">Account</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark bg-secondary-main" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Purchases</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-sm btn-accent ms-lg-3 mt-2 mt-lg-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                            <i class="fa-solid fa-shopping-cart"></i> Cart (<span id="cart-count-new">0</span>)
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid px-3 d-lg-none mobile-search-container py-2">
        <form class="d-flex">
            <div class="input-group w-100">
                <input class="form-control bg-secondary-main text-white border-0" type="search" placeholder="Search for artists, genres, or moods..." aria-label="Search">
                <button class="btn btn-accent" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>
    </div>
    <div class="container welcome-container">
            <h1 class="welcome-greeting" id="welcome-greeting-text">Welcome</h1>
    </div>
    <div class="container py-5">
        
        <section class="mb-5 p-4 rounded-4 bg-secondary-main position-relative">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary-text mb-0">🔥 Recommended for You</h2>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Sort by
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark bg-secondary-main">
                        <li><a class="dropdown-item" href="#">Newest</a></li>
                        <li><a class="dropdown-item" href="#">Trending</a></li>
                    </ul>
                </div>
            </div>

            <div class="skeleton-container position-relative overflow-hidden">
                <div class="skeleton-row mb-3"></div>
                <div class="skeleton-row mb-3 w-75"></div>
                <div class="skeleton-row w-50"></div>
                
                <button class="btn btn-accent btn-lg position-absolute top-50 start-50 translate-middle">
                    Discover Top Beats
                </button>
            </div>
        </section>

        <hr class="section-divider my-5">

        <section class="mb-5">
            <h2 class="text-primary-text mb-4 text-center">Browse Categories</h2>
            <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-6">
                <div class="col">
                    <div class="category-card p-2 text-center">
                        <div class="category-img-container rounded-3">
                            <img src="https://picsum.photos/300/300?random=1" alt="Category Image" class="img-fluid rounded-3">
                        </div>
                        <h6 class="text-primary-text mt-2 mb-0">Hip Hop</h6>
                        <small class="text-secondary-text">320 Items</small>
                    </div>
                </div>
                <div class="col">
                    <div class="category-card p-2 text-center">
                        <div class="category-img-container rounded-3">
                            <img src="https://picsum.photos/300/300?random=2" alt="Category Image" class="img-fluid rounded-3">
                        </div>
                        <h6 class="text-primary-text mt-2 mb-0">Trap</h6>
                        <small class="text-secondary-text">180 Items</small>
                    </div>
                </div>
                <div class="col">
                    <div class="category-card p-2 text-center">
                        <div class="category-img-container rounded-3">
                            <img src="https://picsum.photos/300/300?random=3" alt="Category Image" class="img-fluid rounded-3">
                        </div>
                        <h6 class="text-primary-text mt-2 mb-0">R&B</h6>
                        <small class="text-secondary-text">95 Items</small>
                    </div>
                </div>
                <div class="col">
                    <div class="category-card p-2 text-center">
                        <div class="category-img-container rounded-3">
                            <img src="https://picsum.photos/300/300?random=4" alt="Category Image" class="img-fluid rounded-3">
                        </div>
                        <h6 class="text-primary-text mt-2 mb-0">Pop</h6>
                        <small class="text-secondary-text">210 Items</small>
                    </div>
                </div>
                <div class="col">
                    <div class="category-card p-2 text-center">
                        <div class="category-img-container rounded-3">
                            <img src="https://picsum.photos/300/300?random=5" alt="Category Image" class="img-fluid rounded-3">
                        </div>
                        <h6 class="text-primary-text mt-2 mb-0">Lofi</h6>
                        <small class="text-secondary-text">150 Items</small>
                    </div>
                </div>
                <div class="col">
                    <div class="category-card p-2 text-center">
                        <div class="category-img-container rounded-3">
                            <img src="https://picsum.photos/300/300?random=6" alt="Category Image" class="img-fluid rounded-3">
                        </div>
                        <h6 class="text-primary-text mt-2 mb-0">Afrobeat</h6>
                        <small class="text-secondary-text">110 Items</small>
                    </div>
                </div>
                </div>
        </section>

        <hr class="section-divider my-5">

        <section class="mb-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-5 rounded-4 h-100 d-flex flex-column justify-content-center bg-secondary-main text-center text-md-start">
                        <span class="text-accent fw-bold mb-2">NEW FEATURE</span>
                        <h2 class="text-primary-text mb-3">Get Custom Production</h2>
                        <p class="text-secondary-text mb-4">
                            Need a unique sound tailored specifically for your project? Book a one-on-one session with Kenton for exclusive, bespoke production starting next week.
                        </p>
                        <button class="btn btn-lg btn-accent w-auto mx-auto mx-md-0">Learn More &rarr;</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-4 rounded-4 h-100 bg-accent d-flex justify-content-center align-items-center">
                        <img src="https://picsum.photos/400/300?random=7" alt="Custom Production Image" class="img-fluid rounded-3" style="max-height: 300px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </section>

        <hr class="section-divider my-5">

        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary-text mb-0">All Tracks</h2>
                <a href="#" class="text-accent text-decoration-none fw-bold">View More &rarr;</a>
            </div>
            <div id="item-list-container">
                </div>
            
            <nav aria-label="Track list pagination" class="mt-5">
                <ul class="pagination justify-content-center pagination-dark-theme">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            </section>

    </div> <footer class="mt-auto py-5 bg-secondary-main">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-4 g-4 pb-4">
                <div class="col">
                    <a class="navbar-brand logo-text mb-3 d-block" href="#">
                        <i class="fa-solid fa-compact-disc me-2"></i>Kenton<span>Beats</span>
                    </a>
                    <p class="text-secondary-text">High-Quality beats for modern artists.</p>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle text-primary-text" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-globe"></i> English
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark bg-secondary-main">
                            <li><a class="dropdown-item" href="#">Spanish</a></li>
                            <li><a class="dropdown-item" href="#">French</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col">
                    <h5 class="text-primary-text mb-3">Company</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-secondary-text">About Us</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary-text">Careers</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary-text">Press</a></li>
                    </ul>
                </div>
                <div class="col">
                    <h5 class="text-primary-text mb-3">Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-secondary-text">Contact</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary-text">FAQ</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary-text">Help Center</a></li>
                    </ul>
                </div>
                <div class="col">
                    <h5 class="text-primary-text mb-3">Legal</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-secondary-text">Terms of Use</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary-text">Privacy Policy</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary-text">Licenses</a></li>
                    </ul>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary-subtle">
                <small class="text-secondary-text">&copy; 2024 KentonBeats. All rights reserved.</small>
                <div>
                    <a href="#" class="text-primary-text me-3"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="text-primary-text me-3"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="text-primary-text"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <div class="modal fade" id="licensingModal" tabindex="-1" aria-labelledby="licensingModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-secondary-main text-primary-text">
          <div class="modal-header border-bottom border-secondary-subtle">
            <h5 class="modal-title" id="licensingModalLabel">Choose License for <span id="modal-track-name" class="text-accent"></span></h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="modal-track-id">
            <div class="licensing-options">
              
              <div class="form-check p-3 mb-2 rounded border track-license-option selected" data-price="25.00" data-license="MP3 Lease">
                <input class="form-check-input" type="radio" name="licensingRadio" id="radioMp3" value="MP3 Lease" checked>
                <label class="form-check-label w-100" for="radioMp3">
                  <strong class="d-block">MP3 Lease ($25.00)</strong>
                  <small class="text-secondary-text">Perfect for mixtapes and demos. Limited streams.</small>
                </label>
              </div>
              
              <div class="form-check p-3 mb-2 rounded border track-license-option" data-price="50.00" data-license="WAV Lease">
                <input class="form-check-input" type="radio" name="licensingRadio" id="radioWav" value="WAV Lease">
                <label class="form-check-label w-100" for="radioWav">
                  <strong class="d-block">WAV Lease ($50.00)</strong>
                  <small class="text-secondary-text">High quality file for commercial use. Unlimited streams.</small>
                </label>
              </div>
              
              <div class="form-check p-3 mb-2 rounded border track-license-option" data-price="150.00" data-license="Trackout Stems">
                <input class="form-check-input" type="radio" name="licensingRadio" id="radioStems" value="Trackout Stems">
                <label class="form-check-label w-100" for="radioStems">
                  <strong class="d-block">Trackout Stems ($150.00)</strong>
                  <small class="text-secondary-text">All instruments separated for professional mixing. Exclusive rights option available.</small>
                </label>
              </div>
            </div>
          </div>
          <div class="modal-footer border-top border-secondary-subtle">
            <span class="text-primary-text me-auto fw-bold">Total: <span id="modal-total-price" class="text-accent">$25.00</span></span>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-accent" id="addToCartFromModal">Add to Cart</button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="offcanvas offcanvas-end bg-secondary-main text-primary-text" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
      <div class="offcanvas-header border-bottom border-secondary-subtle">
        <h5 class="offcanvas-title" id="offcanvasCartLabel"><i class="fa-solid fa-shopping-bag text-accent me-2"></i> Your Cart</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body d-flex flex-column">
        <div id="cart-items-display" class="flex-grow-1">
          <p class="text-secondary-text p-3">Loading cart...</p>
        </div>
        <div class="mt-auto pt-3 border-top border-secondary-subtle">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary-text fw-bold">Subtotal:</span>
                <span id="cart-subtotal" class="text-accent fs-5 fw-bold">$0.00</span>
            </div>
            <button class="btn btn-lg btn-accent w-100">Proceed to Checkout</button>
            <small class="d-block text-center text-secondary-text mt-2">Taxes and shipping calculated at checkout.</small>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Define the user's name at the top for dynamic insertion
            const CURRENT_USER_NAME = "<?php echo htmlspecialchars($username); ?>"; 
            
            // Update Welcome Greeting and Navbar Display
            document.getElementById('welcome-greeting-text').textContent = `Welcome, ${CURRENT_USER_NAME}!`;
            document.getElementById('user-display-name').textContent = CURRENT_USER_NAME;


            // Dynamic List Data (10 tracks)
            const MOCK_TRACKS = [
                { id: 1, title: "Midnight Rush", subtitle: "Kenton", duration: "3:45", tags: ["Trap", "Dark", "808"], image: "https://picsum.photos/50/50?random=10", basePrice: 25.00 },
                { id: 2, title: "Urban Pulse", subtitle: "Kenton", duration: "4:12", tags: ["Hip Hop", "Smooth"], image: "https://picsum.photos/50/50?random=11", basePrice: 25.00 },
                { id: 3, title: "Deep Resonance", subtitle: "Guest Prod", duration: "3:18", tags: ["Ambient", "Chill", "Lofi"], image: "https://picsum.photos/50/50?random=12", basePrice: 25.00 },
                { id: 4, title: "Solar Flare", subtitle: "Kenton", duration: "2:59", tags: ["Pop", "Uplifting"], image: "https://picsum.photos/50/50?random=13", basePrice: 25.00 },
                { id: 5, title: "Bass Theory 2.0", subtitle: "Kenton", duration: "4:32", tags: ["Dubstep", "Aggressive"], image: "https://picsum.photos/50/50?random=14", basePrice: 25.00 },
                { id: 6, title: "Neon City", subtitle: "Kenton", duration: "3:55", tags: ["Synthwave", "Retro"], image: "https://picsum.photos/50/50?random=15", basePrice: 25.00 },
                { id: 7, title: "Lost Temple", subtitle: "Guest Prod", duration: "4:01", tags: ["World", "Cinematic"], image: "https://picsum.photos/50/50?random=16", basePrice: 25.00 },
                { id: 8, title: "Smooth Operator", subtitle: "Kenton", duration: "3:22", tags: ["R&B", "Neo-Soul"], image: "https://picsum.photos/50/50?random=17", basePrice: 25.00 },
                { id: 9, title: "Grind Time", subtitle: "Kenton", duration: "2:48", tags: ["Drill", "Grime"], image: "https://picsum.photos/50/50?random=18", basePrice: 25.00 },
                { id: 10, title: "Echoes", subtitle: "Kenton", duration: "5:05", tags: ["Ambient", "Experimental"], image: "https://picsum.photos/50/50?random=19", basePrice: 25.00 }
            ];

            const listContainer = document.getElementById('item-list-container');
            let currentlyPlayingId = null;
            
            // --- Cart Logic (Updated) ---
            let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
            const newCartCountElement = document.getElementById('cart-count-new');
            let currentlyPlayingCartId = null; // New state for cart playback

            function updateCartCount() {
                if(newCartCountElement) {
                    newCartCountElement.textContent = cart.length;
                }
                localStorage.setItem('cartItems', JSON.stringify(cart));
            }
            updateCartCount(); // Initial load

            // Function to handle the Play/Pause logic inside the Offcanvas
            function toggleCartPlayback(e) {
                const cartId = e.currentTarget.dataset.cartId;
                const icon = e.currentTarget.querySelector('i');

                // Stop any playback in the main track list
                if (currentlyPlayingId !== null) {
                    const prevRow = document.querySelector(`.track-row[data-track-id="${currentlyPlayingId}"]`);
                    if (prevRow) {
                        prevRow.classList.remove('playing');
                        prevRow.querySelector('.btn-play-toggle i').className = 'fa-solid fa-play';
                    }
                    currentlyPlayingId = null;
                }

                if (currentlyPlayingCartId === cartId) {
                    // Pause the currently playing track
                    currentlyPlayingCartId = null;
                    icon.className = 'fa-solid fa-play';
                } else {
                    // Stop previous playing track in cart if any
                    if (currentlyPlayingCartId !== null) {
                        const prevButton = document.querySelector(`.cart-play-toggle[data-cart-id="${currentlyPlayingCartId}"]`);
                        if (prevButton) {
                             prevButton.querySelector('i').className = 'fa-solid fa-play';
                        }
                    }
                    
                    // Play the new track
                    currentlyPlayingCartId = cartId;
                    icon.className = 'fa-solid fa-pause';
                }
            }

            // Function to render cart items inside the Offcanvas
            function displayCartItems() {
                const displayArea = document.getElementById('cart-items-display');
                const subtotalElement = document.getElementById('cart-subtotal');
                let subtotal = 0;
                
                if (cart.length === 0) {
                    displayArea.innerHTML = '<p class="text-secondary-text p-3">No items in cart yet. Add a license to begin!</p>';
                    subtotalElement.textContent = '$0.00';
                    return;
                }
                
                let html = '<div class="list-group list-group-flush">';
                
                cart.forEach(item => {
                    subtotal += item.price;
                    
                    // Parse the name string: "Track Name (License Option)"
                    const parts = item.name.match(/(.*) \((.*)\)/);
                    const trackTitle = parts ? parts[1] : item.name;
                    const licenseOption = parts ? parts[2] : 'License Unknown';
                    
                    // Determine the current play state icon
                    const playIconClass = currentlyPlayingCartId === item.id ? 'fa-pause' : 'fa-play';
                    
                    html += `
                        <div class="list-group-item bg-secondary-main text-primary-text d-flex align-items-start py-3 px-0 border-bottom border-secondary-subtle" data-cart-id="${item.id}">
                            
                            <button data-cart-id="${item.id}" class="btn btn-sm btn-action me-2 mt-1 cart-play-toggle" title="Play">
                                <i class="fa-solid ${playIconClass}"></i>
                            </button>

                            <div class="flex-grow-1">
                                <strong class="d-block">${trackTitle}</strong>
                                <small class="d-block text-accent fw-bold mb-1">${licenseOption}</small>
                                <small class="text-secondary-text">${item.producer}</small>
                            </div>

                            <div class="text-end d-flex flex-column align-items-end ms-3">
                                <span class="fw-bold text-accent mb-2">$${item.price.toFixed(2)}</span>
                                <button data-item-id="${item.id}" class="btn btn-sm btn-action remove-from-cart" title="Remove"><i class="fa-solid fa-trash-alt"></i></button>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                displayArea.innerHTML = html;
                subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
                
                // Add listeners
                document.querySelectorAll('.remove-from-cart').forEach(button => {
                    button.addEventListener('click', removeItemFromCart);
                });
                document.querySelectorAll('.cart-play-toggle').forEach(button => {
                    button.addEventListener('click', toggleCartPlayback);
                });
            }

            function removeItemFromCart(e) {
                const itemId = e.currentTarget.dataset.itemId;
                cart = cart.filter(item => item.id !== itemId);
                
                // If the removed item was playing, reset the state
                if (currentlyPlayingCartId === itemId) {
                    currentlyPlayingCartId = null;
                }

                updateCartCount();
                displayCartItems(); // Re-render the offcanvas
            }
            
            // Event listener for when the offcanvas is shown (to populate cart)
            const offcanvasCart = document.getElementById('offcanvasCart');
            if (offcanvasCart) {
                offcanvasCart.addEventListener('show.bs.offcanvas', function () {
                    displayCartItems();
                });
            }
            // ----------------------------------------------------

            // Function to generate and inject the HTML markup for Section 4
            function renderItemList() {
                if (!listContainer) return;

                const html = MOCK_TRACKS.map(track => {
                    const tagsHtml = track.tags.map(tag => 
                        `<span class="badge rounded-pill bg-accent-light text-dark">${tag}</span>`
                    ).join(' ');

                    // Ensure the main list's play button reflects its own state
                    const playIconClass = currentlyPlayingId === track.id ? 'fa-pause' : 'fa-play';


                    return `
                        <div class="track-row p-3 mb-2 d-flex align-items-center" data-track-id="${track.id}">
                            
                            <div class="track-play-col me-3">
                                <div class="track-thumbnail position-relative me-3">
                                    <img src="${track.image}" alt="${track.title} cover" class="rounded">
                                    <button class="btn-play-toggle" data-track-id="${track.id}">
                                        <i class="fa-solid ${playIconClass}"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="track-details flex-grow-1 me-3 text-truncate">
                                
                                <div class="title-action-row d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="text-primary-text mb-0 fw-bold text-truncate me-2">${track.title}</h6>
                                    
                                    <div class="track-actions text-nowrap d-flex align-items-center ms-2">
                                        <div class="hover-actions d-flex me-2">
                                            <button class="btn btn-sm btn-action" title="Find Similar Track"><i class="fa-solid fa-magnifying-glass-chart"></i></button>
                                            <button class="btn btn-sm btn-action" title="Like/Favorite"><i class="fa-regular fa-heart"></i></button>
                                            <button class="btn btn-sm btn-action" title="Download Demo"><i class="fa-solid fa-download"></i></button>
                                        </div>

                                        <button 
                                            class="btn btn-sm btn-accent btn-add-to-cart" 
                                            title="Add to Cart"
                                            data-track-id="${track.id}"
                                            data-track-name="${track.title}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#licensingModal"
                                        >
                                            <i class="fa-solid fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <small class="text-secondary-text">${track.subtitle}</small>

                            </div>
                            <div class="track-visualization d-none d-md-block flex-grow-1 mx-3">
                                <div class="visualizer-bars">
                                    <div class="bar"></div><div class="bar"></div><div class="bar"></div><div class="bar"></div><div class="bar"></div><div class="bar"></div><div class="bar"></div>
                                </div>
                            </div>
                            
                            <div class="track-duration me-3 text-secondary-text d-none d-sm-block">
                                ${track.duration}
                            </div>

                            <div class="track-tags me-3 d-none d-lg-block text-nowrap">
                                ${tagsHtml}
                            </div>
                            </div>
                    `;
                }).join('');

                listContainer.innerHTML = html;
                addPlayToggleListeners();
                addModalTriggerListeners(); // New listener for the modal
            }

            // Function to add click event listeners and handle the play toggle state (Main List)
            function addPlayToggleListeners() {
                document.querySelectorAll('.btn-play-toggle').forEach(button => {
                    button.addEventListener('click', (e) => {
                        const trackId = parseInt(e.currentTarget.dataset.trackId);
                        const icon = e.currentTarget.querySelector('i');
                        const row = e.currentTarget.closest('.track-row');
                        
                        // Stop any playback in the cart list
                         if (currentlyPlayingCartId !== null) {
                            const prevButton = document.querySelector(`.cart-play-toggle[data-cart-id="${currentlyPlayingCartId}"]`);
                            if (prevButton) {
                                prevButton.querySelector('i').className = 'fa-solid fa-play';
                            }
                            currentlyPlayingCartId = null;
                        }


                        if (currentlyPlayingId === trackId) {
                            currentlyPlayingId = null;
                            icon.className = 'fa-solid fa-play';
                            row.classList.remove('playing');
                        } else {
                            if (currentlyPlayingId !== null) {
                                const prevRow = document.querySelector(`.track-row[data-track-id="${currentlyPlayingId}"]`);
                                if (prevRow) {
                                    prevRow.classList.remove('playing');
                                    prevRow.querySelector('.btn-play-toggle i').className = 'fa-solid fa-play';
                                }
                            }
                            currentlyPlayingId = trackId;
                            icon.className = 'fa-solid fa-pause';
                            row.classList.add('playing');
                        }
                    });
                });
            }

            // --- Multi-option Modal Logic ---
            function addModalTriggerListeners() {
                document.querySelectorAll('.btn-add-to-cart').forEach(button => {
                    button.addEventListener('click', (e) => {
                        const trackId = e.currentTarget.dataset.trackId;
                        const trackName = e.currentTarget.dataset.trackName;

                        document.getElementById('modal-track-id').value = trackId;
                        document.getElementById('modal-track-name').textContent = trackName;
                        
                        const defaultOption = document.querySelector('.track-license-option');
                        if (defaultOption) {
                             document.querySelectorAll('.track-license-option').forEach(opt => opt.classList.remove('selected'));
                            defaultOption.classList.add('selected');
                            document.querySelector('#modal-total-price').textContent = `$${parseFloat(defaultOption.dataset.price).toFixed(2)}`;
                            defaultOption.querySelector('input[type="radio"]').checked = true;
                        }
                    });
                });
            }

            document.querySelectorAll('.track-license-option').forEach(option => {
                option.addEventListener('click', (e) => {
                    const target = e.currentTarget.closest('.track-license-option');
                    if (!target) return;

                    document.querySelectorAll('.track-license-option').forEach(opt => opt.classList.remove('selected'));
                    target.classList.add('selected');
                    target.querySelector('input[type="radio"]').checked = true;

                    const price = parseFloat(target.dataset.price).toFixed(2);
                    document.querySelector('#modal-total-price').textContent = `$${price}`;
                });
            });

            document.getElementById('addToCartFromModal').addEventListener('click', () => {
                const trackId = document.getElementById('modal-track-id').value;
                const trackName = document.getElementById('modal-track-name').textContent;
                
                const selectedOption = document.querySelector('.track-license-option input:checked').closest('.track-license-option');
                
                const price = parseFloat(selectedOption.dataset.price);
                const license = selectedOption.dataset.license;

                const item = {
                    // Create unique ID, e.g., "1-MP3Lease"
                    id: `${trackId}-${license.replace(/\s/g, '')}`, 
                    // Store license in the name for easy display in cart
                    name: `${trackName} (${license})`, 
                    producer: "Kenton", 
                    price: price
                };

                cart.push(item);
                updateCartCount();
                
                const modalElement = document.getElementById('licensingModal');
                const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                if (modal) {
                    modal.hide();
                }
                
                // Open the offcanvas right after adding the item for immediate feedback
                const cartOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasCart) || new bootstrap.Offcanvas(offcanvasCart);
                cartOffcanvas.show();
            });
            // ----------------------------------------------------

            // Initialize the list rendering
            renderItemList();
        });
    </script>

</body>
</html>