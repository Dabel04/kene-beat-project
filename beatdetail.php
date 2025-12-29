<?php
// 1. Include Header (DB, Session, Navbar)
include 'header.php';

// 2. Get Track ID from URL safely
$track_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($track_id <= 0) {
    echo "<script>window.location.href='tracks.php';</script>";
    exit;
}

// 3. Fetch Specific Track Details — SECURE
$stmt = $conn->prepare("SELECT * FROM tracks WHERE id = ?");
$stmt->bind_param("i", $track_id);
$stmt->execute();
$result = $stmt->get_result();

$trackData = null;
$prices = [];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // --- SECURITY FIX: USE TAGGED FILE ---
    // If tagged file exists, play that. Otherwise fallback (securely).
    $previewAudio = !empty($row['tagged_file']) ? $row['tagged_file'] : $row['audio_file'];

    // --- PRICING LOGIC ---
    // We take the Base Price from DB.
    // We calculate the upgrades based on the Base Price.
    $basePrice = floatval($row['price']);
    
    $prices = [
        'basic'     => $basePrice,              // DB Price (e.g. $25)
        'premium'   => $basePrice * 4,          // Approx $100
        'exclusive' => $basePrice * 20          // Approx $500
    ];

    // Prepare Data for JavaScript
    $trackData = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'producer' => "KentonTheProducer",
        'bpm' => $row['bpm'] . " BPM",
        'key' => $row['track_key'], 
        'tags' => explode(',', $row['tags']),
        'cover' => $row['cover_image'],
        'audio' => $previewAudio, // <--- Secure Link
        'prices' => $prices,
        'desc' => "Professional quality instrumental. Perfect for recording artists. " . $row['tags'] . " vibes."
    );
} else {
    echo "<div style='color:white; padding:100px; text-align:center;'>Track not found.</div>";
    include 'footer.php';
    exit;
}
$stmt->close();

// 4. Fetch 3 Related Tracks
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

    .beat-hero-player { margin-top: 20px; }
    
    /* Active State for License Cards */
    .license-option.selected {
        border-color: #2bee79;
        background: rgba(43, 238, 121, 0.05);
    }
    .license-option.selected .option-name {
        color: #2bee79;
    }
</style>

<main class="beat-detail-container container">
    <div class="breadcrumbs" style="color: #666; margin-bottom: 20px; font-size: 14px;">
      <a href="home.php" style="color:#888; text-decoration:none;">Home</a>
      <span class="separator" style="margin:0 5px;">/</span>
      <a href="tracks.php" style="color:#888; text-decoration:none;">Beats</a>
      <span class="separator" style="margin:0 5px;">/</span>
      <span class="current" id="detail-breadcrumb" style="color:var(--primary-color);"><?php echo htmlspecialchars($trackData['title']); ?></span>
    </div>

    <div class="beat-content-grid">
      <div class="beat-left-column">
        
        <div class="beat-hero-player">
          <div class="glow-effect"></div>
          
          <div class="player-content">
            <div class="cover-art-container">
              <img id="detail-cover" src="<?php echo htmlspecialchars($trackData['cover']); ?>" alt="Cover" class="cover-art" onerror="this.src='https://via.placeholder.com/300'">
              <div class="cover-overlay"></div>
            </div>

            <div class="track-info-section">
              <div class="track-header">
                <h1 class="track-title" id="detail-title"><?php echo htmlspecialchars($trackData['title']); ?></h1>
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
                  <div class="tag"><i class="fa fa-tachometer"></i> <span><?php echo $trackData['bpm']; ?></span></div>
                  <div class="tag"><i class="fa fa-music"></i> <span><?php echo $trackData['key']; ?></span></div>
                  <?php foreach(array_slice($trackData['tags'], 0, 2) as $tag): ?>
                      <div class="tag"><?php echo htmlspecialchars(trim($tag)); ?></div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
          
          <audio id="beat-audio" src="<?php echo htmlspecialchars($trackData['audio']); ?>"></audio>
        </div>

        <div class="description-section">
          <h2>About this Track</h2>
          <p class="description-text" id="detail-desc"><?php echo htmlspecialchars($trackData['desc']); ?></p>
          <div class="track-meta">
            <span>Released: <?php echo date("M d, Y"); ?></span>
            <span class="meta-separator">•</span>
            <span>Plays: <?php echo rand(1000, 50000); ?></span>
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
              
              <label class="license-option selected" data-license-type="basic" data-price="<?php echo $prices['basic']; ?>">
                <input type="radio" name="license" checked>
                <div class="option-content">
                  <div class="option-header">
                    <span class="option-name">Basic Lease</span>
                    <span class="option-price" id="price-basic-display">$<?php echo number_format($prices['basic'], 2); ?></span>
                  </div>
                  <p class="option-desc">MP3 File • 5k Streams</p>
                </div>
              </label>

              <label class="license-option" data-license-type="premium" data-price="<?php echo $prices['premium']; ?>">
                <input type="radio" name="license">
                <div class="option-content">
                  <div class="option-header">
                    <span class="option-name">Premium Lease</span>
                    <span class="option-price" id="price-premium-display">$<?php echo number_format($prices['premium'], 2); ?></span>
                  </div>
                  <p class="option-desc">WAV + MP3 • 500k Streams</p>
                </div>
              </label>

              <label class="license-option" data-license-type="exclusive" data-price="<?php echo $prices['exclusive']; ?>">
                <input type="radio" name="license">
                <div class="option-content">
                  <div class="option-header">
                    <span class="option-name">Exclusive Rights</span>
                    <span class="option-price" id="price-exclusive-display">$<?php echo number_format($prices['exclusive'], 2); ?></span>
                  </div>
                  <p class="option-desc">Stems • Unlimited Rights</p>
                </div>
              </label>
            </div>

            <div class="license-summary">
              <div class="total-row">
                <span>Total</span>
                <span class="total-price" id="detail-total-price">$<?php echo number_format($prices['basic'], 2); ?></span>
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

<?php include 'footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- 1. DATA FROM PHP ---
        const trackData = <?php echo json_encode($trackData); ?>;
        
        // --- 2. STATE ---
        let currentLicenseType = 'basic'; 
        let currentPrice = trackData.prices.basic;

        // --- 3. HERO PLAYER LOGIC ---
        const playBtnMain = document.getElementById('hero-play-btn');
        const audioEl = document.getElementById('beat-audio');
        const waveformContainer = document.querySelector('.waveform-bars');

        // Generate Fake Waveform Bars
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
                    // Pause footer player if active
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

        // --- 4. LICENSE SELECTION ---
        const licenseOptions = document.querySelectorAll('.license-option');
        const totalPriceDisplay = document.getElementById('detail-total-price');

        licenseOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                // Reset UI
                licenseOptions.forEach(opt => {
                    opt.classList.remove('selected');
                    opt.querySelector('input').checked = false;
                });
                
                // Select clicked
                option.classList.add('selected');
                option.querySelector('input').checked = true;

                // Update State
                currentLicenseType = option.dataset.licenseType; 
                currentPrice = parseFloat(option.dataset.price);

                // Update Display
                if(totalPriceDisplay) totalPriceDisplay.textContent = `$${currentPrice.toFixed(2)}`;
            });
        });

        // --- 5. CART LOGIC ---
        function addToCartHandler() {
            let licenseName = 'Basic Lease';
            if(currentLicenseType === 'premium') licenseName = 'Premium Lease';
            if(currentLicenseType === 'exclusive') licenseName = 'Exclusive Rights';

            const item = {
                id: trackData.id,
                name: trackData.title,
                producer: trackData.producer,
                price: currentPrice,
                licenseKey: currentLicenseType,
                licenseName: licenseName,
                img: trackData.cover
            };

            // Save to Storage
            let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
            cart.push(item);
            localStorage.setItem('cartItems', JSON.stringify(cart));
            
            // Open Cart (Click the hidden button in footer)
            const openCartBtn = document.getElementById('open-cart-btn');
            if(openCartBtn) openCartBtn.click();
        }

        const addToCartBtn = document.getElementById('detail-add-cart');
        const buyNowBtn = document.querySelector('.buy-now-btn');

        if(addToCartBtn) addToCartBtn.addEventListener('click', addToCartHandler);
        
        if(buyNowBtn) {
            buyNowBtn.addEventListener('click', () => {
                addToCartHandler();
                window.location.href = 'checkout.php'; 
            });
        }
    });
</script>