<?php
// 1. Include Header (DB, Session, Navbar)
include 'header.php';

// 2. Get Track ID from URL safely
$track_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($track_id <= 0) {
    header("Location: tracks.php");
    exit;
}

// 3. Fetch Specific Track Details — SECURE WITH PREPARED STATEMENT
$stmt = $conn->prepare("SELECT * FROM tracks WHERE id = ?");
$stmt->bind_param("i", $track_id);
$stmt->execute();
$result = $stmt->get_result();

$trackData = null;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $basePrice = floatval($row['price']);
    
    // Prepare Data for JavaScript (unchanged)
    $trackData = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'producer' => "KentonTheProducer",
        'bpm' => $row['bpm'] . " BPM",
        'key' => $row['track_key'], 
        'tags' => explode(',', $row['tags']),
        'cover' => $row['cover_image'],
        'audio' => $row['audio_file'],
        'priceBasic' => $basePrice,
        'pricePremium' => $basePrice * 2,  
        'priceUnlimited' => $basePrice * 4, 
        'desc' => "Professional quality instrumental. perfect for recording artists. " . $row['tags'] . " vibes."
    );
}
$stmt->close();

// 4. Fetch 3 Related Tracks — ALSO SECURE
$relatedStmt = $conn->prepare("SELECT * FROM tracks WHERE id != ? ORDER BY RAND() LIMIT 3");
$relatedStmt->bind_param("i", $track_id);
$relatedStmt->execute();
$relatedResult = $relatedStmt->get_result();
$relatedStmt->close();
?>

<link rel="stylesheet" href="css/beatproject.css">
<style>
    /* Fix layout to sit below fixed header */
    body { background-color: #000; }
    
    .beat-detail-container {
        margin-top: var(--nav-height);
        padding-top: 20px;
        min-height: 80vh;
    }

    /* Ensure the hero section doesn't overlap header */
    .beat-hero-player {
        margin-top: 20px;
    }
</style>

<main class="beat-detail-container container">
    <div class="breadcrumbs" style="color: #666; margin-bottom: 20px; font-size: 14px;">
      <a href="home.php" style="color:#888; text-decoration:none;">Home</a>
      <span class="separator" style="margin:0 5px;">/</span>
      <a href="tracks.php" style="color:#888; text-decoration:none;">Beats</a>
      <span class="separator" style="margin:0 5px;">/</span>
      <span class="current" id="detail-breadcrumb" style="color:var(--primary-color);">Loading...</span>
    </div>

    <div class="beat-content-grid">
      <div class="beat-left-column">
        
        <div class="beat-hero-player">
          <div class="glow-effect"></div>
          
          <div class="player-content">
            <div class="cover-art-container">
              <img id="detail-cover" src="" alt="Beat Cover" class="cover-art">
              <div class="cover-overlay"></div>
            </div>

            <div class="track-info-section">
              <div class="track-header">
                <h1 class="track-title" id="detail-title">Loading...</h1>
                <div class="producer-info">
                  <i class="fa fa-check-circle verified-badge"></i>
                  <span class="producer-name">Produced by KentonTheProducer</span>
                </div>
              </div>

              <div class="waveform-container">
                <div class="waveform-bars"></div>
              </div>

              <div class="controls-tags">
                <div class="player-controls">
                  <button class="play-btn-main" id="hero-play-btn">
                    <i class="fa fa-play"></i>
                  </button>
                  <div class="time-info">
                    <span class="current-time">0:00</span>
                    </div>
                </div>
                
                <div class="track-tags" id="detail-tags">
                  <div class="tag"><i class="fa fa-tachometer"></i> <span></span></div>
                  <div class="tag"><i class="fa fa-music"></i> <span></span></div>
                  <div class="tag"></div>
                  <div class="tag"></div>
                </div>
              </div>
            </div>
          </div>
          
          <audio id="beat-audio" src=""></audio>
        </div>

        <div class="description-section">
          <h2>About this Track</h2>
          <p class="description-text" id="detail-desc">Loading description...</p>
          <div class="track-meta">
            <span>Released: Oct 24, 2023</span>
            <span class="meta-separator">•</span>
            <span>Plays: 14.2k</span>
            <span class="meta-separator">•</span>
            <span>Files: MP3, WAV, Stems</span>
          </div>
        </div>

        <div class="related-tracks">
          <div class="related-header">
            <h2>More from KentonTheProducer</h2>
            <a href="tracks.php" class="view-all">View All</a>
          </div>
          
          <div class="related-list">
            <?php 
            if ($relatedResult && $relatedResult->num_rows > 0) {
                while($rel = $relatedResult->fetch_assoc()) {
            ?>
            <div class="related-track-item">
              <div class="related-track-image">
                <a href="beatdetail.php?id=<?php echo $rel['id']; ?>">
                  <img src="<?php echo $rel['cover_image']; ?>" alt="<?php echo $rel['title']; ?>" onerror="this.src='https://via.placeholder.com/300'">
                </a>
              </div>
              <div class="related-track-info">
                <h3><a href="beatdetail.php?id=<?php echo $rel['id']; ?>" style="color:inherit;text-decoration:none;"><?php echo $rel['title']; ?></a></h3>
                <p><?php echo $rel['bpm']; ?> BPM</p>
              </div>
              <div class="related-track-price">
                <a href="beatdetail.php?id=<?php echo $rel['id']; ?>" class="price-btn">$<?php echo floatval($rel['price']); ?></a>
              </div>
            </div>
            <?php 
                }
            } else {
                echo "<p style='color:#666;'>No related tracks found.</p>";
            }
            ?>
          </div>
        </div>
      </div>

      <div class="beat-right-column">
        <div class="sticky-sidebar">
          <div class="license-card">
            <div class="license-header">
              <h3>Select License</h3>
            </div>
            
            <div class="license-options">
              <label class="license-option selected" data-license-type="basic">
                <input type="radio" name="license" checked>
                <div class="option-content">
                  <div class="option-header">
                    <span class="option-name">Basic Lease</span>
                    <span class="option-price" id="price-basic-display">$0.00</span>
                  </div>
                  <p class="option-desc">MP3 File • 5k Streams</p>
                </div>
              </label>

              <label class="license-option" data-license-type="premium">
                <input type="radio" name="license">
                <div class="option-content">
                  <div class="option-header">
                    <span class="option-name">Premium Lease</span>
                    <span class="option-price" id="price-premium-display">$0.00</span>
                  </div>
                  <p class="option-desc">WAV + MP3 • 500k Streams</p>
                </div>
              </label>

              <label class="license-option" data-license-type="unlimited">
                <input type="radio" name="license">
                <div class="option-content">
                  <div class="option-header">
                    <span class="option-name">Unlimited</span>
                    <span class="option-price" id="price-unlimited-display">$0.00</span>
                  </div>
                  <p class="option-desc">Stems • Unlimited Rights</p>
                </div>
              </label>
            </div>

            <div class="license-summary">
              <div class="total-row">
                <span>Total</span>
                <span class="total-price" id="detail-total-price">$0.00</span>
              </div>
              
              <button class="buy-now-btn">
                <i class="fa fa-bolt"></i> Buy Now
              </button>
              
              <button class="add-to-cart-btn" id="detail-add-cart">
                <i class="fa fa-shopping-cart"></i> Add to Cart
              </button>
            </div>

            <div class="trust-badges">
              <div class="badge visa" title="Visa"></div>
              <div class="badge mastercard" title="Mastercard"></div>
              <div class="badge paypal" title="PayPal"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>

<?php 
// 5. Include Footer (Scripts, Cart Sidebar, Player)
include 'footer.php'; 
?>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // --- 1. DATA SOURCE ---
        const SERVER_DATA = <?php echo json_encode($trackData); ?>;

        if (!SERVER_DATA) {
            document.getElementById('detail-title').innerText = "Track Not Found";
            return;
        }

        // --- 2. STATE ---
        const data = SERVER_DATA;
        const trackId = data.id;
        let currentLicenseType = 'basic'; 
        let currentPrice = data.priceBasic;

        // --- 3. POPULATE UI ---
        document.title = `${data.title} - KentonTheProducer`;
        document.getElementById('detail-title').textContent = data.title;
        document.getElementById('detail-breadcrumb').textContent = data.title;
        document.getElementById('detail-desc').textContent = data.desc;
        
        // Prices
        document.getElementById('detail-total-price').textContent = `$${currentPrice.toFixed(2)}`;
        document.getElementById('price-basic-display').textContent = `$${data.priceBasic.toFixed(2)}`;
        document.getElementById('price-premium-display').textContent = `$${data.pricePremium.toFixed(2)}`;
        document.getElementById('price-unlimited-display').textContent = `$${data.priceUnlimited.toFixed(2)}`;

        // Tags
        const tagsContainer = document.getElementById('detail-tags');
        if(tagsContainer) {
            const children = tagsContainer.children;
            if(children[0]) children[0].querySelector('span').textContent = data.bpm;
            if(children[1]) children[1].querySelector('span').textContent = data.key;
            if(children[2]) children[2].textContent = data.tags[0] || 'Beat';
            if(children[3]) children[3].textContent = data.tags[1] || 'Instrumental';
        }

        // Images & Audio
        const coverEl = document.getElementById('detail-cover');
        if(coverEl) coverEl.src = data.cover;

        const audioEl = document.getElementById('beat-audio');
        if(audioEl) audioEl.src = data.audio;

        // --- 4. HERO PLAYER LOGIC ---
        // Note: This logic controls the specific "Hero" player on this page.
        // It operates independently of the sticky footer player for a better focused experience.
        const playBtnMain = document.getElementById('hero-play-btn');
        const waveformContainer = document.querySelector('.waveform-bars');

        // Fake Waveform Bars
        if (waveformContainer) {
            waveformContainer.innerHTML = ''; 
            for (let i = 0; i < 35; i++) {
                const bar = document.createElement('div');
                bar.className = 'waveform-bar';
                const height = Math.floor(Math.random() * 80) + 20;
                bar.style.height = `${height}%`;
                bar.style.opacity = (Math.random() * 0.5) + 0.3;
                bar.style.animationDelay = `${i * 0.05}s`;
                waveformContainer.appendChild(bar);
            }
        }
        
        if (playBtnMain && audioEl) {
            playBtnMain.addEventListener('click', () => {
                const icon = playBtnMain.querySelector('i');
                if (audioEl.paused) {
                    // Pause the global footer player if it's running
                    const footerAudio = document.querySelector('.audio-player audio'); 
                    if(footerAudio) footerAudio.pause();

                    audioEl.play();
                    icon.className = 'fa fa-pause';
                } else {
                    audioEl.pause();
                    icon.className = 'fa fa-play';
                }
            });
            audioEl.addEventListener('ended', () => {
                 playBtnMain.querySelector('i').className = 'fa fa-play';
            });
            audioEl.addEventListener('timeupdate', () => {
                 const m = Math.floor(audioEl.currentTime / 60);
                 const s = Math.floor(audioEl.currentTime % 60);
                 const timeDisplay = document.querySelector('.current-time');
                 if(timeDisplay) timeDisplay.textContent = `${m}:${s<10?'0':''}${s}`;
            });
        }

        // --- 5. LICENSE SELECTION ---
        const licenseOptions = document.querySelectorAll('.license-option');
        const totalPriceDisplay = document.getElementById('detail-total-price');

        licenseOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                // Reset styling
                licenseOptions.forEach(opt => {
                    opt.classList.remove('selected');
                    opt.querySelector('input').checked = false;
                });
                
                // Select clicked
                option.classList.add('selected');
                option.querySelector('input').checked = true;

                // Update State
                currentLicenseType = option.dataset.licenseType; 
                if(currentLicenseType === 'basic') currentPrice = data.priceBasic;
                if(currentLicenseType === 'premium') currentPrice = data.pricePremium;
                if(currentLicenseType === 'unlimited') currentPrice = data.priceUnlimited;

                // Update Price
                if(totalPriceDisplay) totalPriceDisplay.textContent = `$${currentPrice.toFixed(2)}`;
            });
        });

        // --- 6. CART LOGIC (Connected to Footer) ---
        function addToCartHandler() {
            if(!data) return;

            // Map license key to display name
            let licenseName = 'Basic Lease';
            if(currentLicenseType === 'premium') licenseName = 'Premium Lease';
            if(currentLicenseType === 'unlimited') licenseName = 'Unlimited';

            const item = {
                id: trackId,
                name: data.title,
                producer: data.producer,
                price: currentPrice,
                licenseKey: currentLicenseType,
                licenseName: licenseName,
                img: data.cover
            };

            // 1. Get existing cart
            let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
            
            // 2. Add new item
            cart.push(item);
            
            // 3. Save back to storage
            localStorage.setItem('cartItems', JSON.stringify(cart));
            
            // 4. Trigger Sidebar Update
            // Since the sidebar logic is in footer.php, clicking the "Open Cart" button
            // programmatically is the easiest way to refresh the cart UI and open it.
            const openCartBtn = document.getElementById('open-cart-btn');
            if(openCartBtn) openCartBtn.click();
        }

        const addToCartBtn = document.getElementById('detail-add-cart');
        const buyNowBtn = document.querySelector('.buy-now-btn');

        if(addToCartBtn) {
            addToCartBtn.addEventListener('click', addToCartHandler);
        }
        
        if(buyNowBtn) {
            buyNowBtn.addEventListener('click', () => {
                addToCartHandler(); // Add to cart
                window.location.href = 'checkout.php'; // Redirect immediately
            });
        }
    });
</script>