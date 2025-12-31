<?php include 'header.php'; ?>
<?php
// Fetch Kits
$sql = "SELECT * FROM sound_kits ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<style>
    body { background-color: #000; color: #fff; }

    /* Hero */
    .kits-hero {
        padding: 120px 0 60px;
        text-align: center;
        background: radial-gradient(circle at center, #1a1a1a 0%, #000 70%);
        border-bottom: 1px solid #1a1a1a;
    }
    .kits-hero h1 { font-size: 3.5rem; font-weight: 800; text-transform: uppercase; letter-spacing: -2px; margin-bottom: 10px; }
    .kits-hero span { color: #2bee79; }
    .kits-hero p { color: #888; font-size: 1.1rem; max-width: 600px; margin: 0 auto; }

    /* Grid */
    .kits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 80px auto;
        padding: 0 20px;
    }

    /* Kit Card */
    .kit-card {
        background: #0f0f0f;
        border: 1px solid #222;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }
    .kit-card:hover { transform: translateY(-10px); border-color: #2bee79; box-shadow: 0 10px 30px rgba(43, 238, 121, 0.1); }

    .kit-image { width: 100%; height: 280px; object-fit: cover; border-bottom: 1px solid #222; }
    
    .kit-info { padding: 25px; }
    .kit-title { font-size: 1.2rem; font-weight: 700; color: white; margin-bottom: 5px; }
    .kit-price { color: #2bee79; font-size: 1.1rem; font-weight: 700; margin-bottom: 15px; display: block; }
    .kit-desc { color: #888; font-size: 0.9rem; line-height: 1.5; margin-bottom: 20px; min-height: 40px; }

    .kit-actions { display: flex; gap: 10px; }
    
    .btn-preview {
        flex: 1;
        background: #1a1a1a;
        color: white;
        border: 1px solid #333;
        padding: 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-preview:hover { background: #333; }

    .btn-buy-kit {
        flex: 2;
        background: #2bee79;
        color: #000;
        font-weight: 700;
        border: none;
        padding: 10px;
        border-radius: 6px;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-buy-kit:hover { background: white; }

</style>

<div class="kits-hero">
    <div class="container">
        <h1>Premium <span>Sound Kits</span></h1>
        <p>Professional drum samples, loops, and presets to level up your production.</p>
    </div>
</div>

<div class="kits-grid">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($kit = $result->fetch_assoc()): ?>
            <div class="kit-card">
                <img src="<?php echo htmlspecialchars($kit['cover_image']); ?>" alt="Kit Cover" class="kit-image">
                <div class="kit-info">
                    <div class="kit-title"><?php echo htmlspecialchars($kit['title']); ?></div>
                    <span class="kit-price">$<?php echo number_format($kit['price'], 2); ?></span>
                    <p class="kit-desc"><?php echo htmlspecialchars($kit['description']); ?></p>
                    
                    <div class="kit-actions">
                        <button class="btn-preview" onclick='window.loadTrack({
                            title: "<?php echo addslashes($kit['title']); ?> (Demo)",
                            audio: "<?php echo $kit['demo_audio']; ?>",
                            cover: "<?php echo $kit['cover_image']; ?>"
                        }, this)'>
                            <i class="fa fa-play"></i> Demo
                        </button>
                        
                        <button class="btn-buy-kit" onclick='addToCart({
                            id: "kit_<?php echo $kit['id']; ?>",
                            name: "<?php echo addslashes($kit['title']); ?>",
                            price: <?php echo $kit['price']; ?>,
                            img: "<?php echo $kit['cover_image']; ?>",
                            licenseName: "Sound Kit",
                            type: "kit"
                        })'>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; color:#666; grid-column: 1/-1;">No sound kits available yet.</p>
    <?php endif; ?>
</div>

<script>
    // Simple Add to Cart Wrapper (Assuming your main.js/header logic exists)
    function addToCart(item) {
        let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
        cart.push(item);
        localStorage.setItem('cartItems', JSON.stringify(cart));
        
        // Trigger update if function exists
        if(typeof updateCartCount === 'function') updateCartCount();
        if(typeof renderCart === 'function') renderCart();
        
        // Open sidebar
        const sidebar = document.getElementById('cart-sidebar');
        if(sidebar) sidebar.classList.add('open');
    }
</script>

<?php include 'footer.php'; ?>