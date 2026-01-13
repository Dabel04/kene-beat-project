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

    /* Grid Layout - The Fix */
    .kits-grid {
        display: grid;
        /* Laptop/Desktop: 4 items per row */
        grid-template-columns: repeat(4, 1fr); 
        gap: 25px;
        max-width: 1400px;
        margin: 60px auto;
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
        display: flex;
        flex-direction: column;
    }
    .kit-card:hover { transform: translateY(-10px); border-color: #2bee79; box-shadow: 0 10px 30px rgba(43, 238, 121, 0.1); }

    .kit-image { width: 100%; height: 250px; object-fit: cover; border-bottom: 1px solid #222; }
    
    .kit-info { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .kit-title { font-size: 1.1rem; font-weight: 700; color: white; margin-bottom: 5px; line-height: 1.3; }
    .kit-price { color: #2bee79; font-size: 1rem; font-weight: 700; margin-bottom: 10px; display: block; }
    .kit-desc { color: #888; font-size: 0.85rem; line-height: 1.5; margin-bottom: 15px; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

    .kit-actions { display: flex; gap: 8px; margin-top: auto; }
    
    .btn-preview {
        flex: 1;
        background: #1a1a1a;
        color: white;
        border: 1px solid #333;
        padding: 10px 0;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
    .btn-preview:hover { background: #333; }

    .btn-buy-kit {
        flex: 2;
        background: #2bee79;
        color: #000;
        font-weight: 700;
        border: none;
        padding: 10px 0;
        border-radius: 6px;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.3s;
        font-size: 12px;
    }
    .btn-buy-kit:hover { background: white; }

    /* --- RESPONSIVE MEDIA QUERIES --- */
    
    /* Tablet (Less than 1024px) */
    @media (max-width: 1024px) {
        .kits-grid {
            grid-template-columns: repeat(3, 1fr); /* 3 cols on tablet */
            gap: 20px;
        }
    }

    /* Phone (Less than 768px) - The "2 Rows" Request */
    @media (max-width: 768px) {
        .kits-hero { padding: 80px 0 40px; }
        .kits-hero h1 { font-size: 2rem; }
        
        .kits-grid {
            grid-template-columns: repeat(2, 1fr); /* 2 items per row */
            gap: 10px;
            padding: 0 10px;
        }

        /* Adjust card size for small screens */
        .kit-image { height: 160px; }
        .kit-info { padding: 12px; }
        .kit-title { font-size: 0.9rem; margin-bottom: 2px; }
        .kit-price { font-size: 0.9rem; margin-bottom: 8px; }
        .kit-desc { font-size: 0.75rem; -webkit-line-clamp: 2; margin-bottom: 10px; }
        
        /* Adjust Buttons */
        .kit-actions { flex-direction: column; gap: 5px; } /* Stack buttons on phone for better clickability */
        .btn-preview, .btn-buy-kit { width: 100%; padding: 8px 0; font-size: 11px; }
    }

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
                        
                        <button class="btn-buy-kit" onclick='window.addToCart({
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

<?php include 'footer.php'; ?>