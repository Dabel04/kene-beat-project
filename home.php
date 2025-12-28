<?php
// Just include the header. It handles Session, DB connection, and Navbar.
include 'header.php';

// --- FETCH DATA FOR THIS PAGE ---
$sql = "SELECT * FROM tracks ORDER BY id DESC LIMIT 8";
$result = $conn->query($sql);
$tracks = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tracks[] = $row;
    }
}
?>

<div class="hero">
    <div class="content">
        <div class="image">
            <img src="image/headphones1.png" alt="Headphones" />
        </div>
        <div class="header">
            <h1>Welcome</h1>
            <p><?php echo htmlspecialchars($username); ?></p>
        </div>
    </div>
</div>

<div class="section-box section-small">
    <div class="section-header">
        <h2>Latest Tracks</h2>
        <a href="tracks.php" class="btn-see-more">See More</a>
    </div>
    
    <div class="slider-container">
        <?php if (count($tracks) > 0): ?>
            <?php foreach ($tracks as $track): ?>
                <div class="card" data-track-id="beat<?php echo $track['id']; ?>">
                    <a href="beatdetail.php?id=<?php echo $track['id']; ?>">
                        <img src="<?php echo $track['cover_image']; ?>" alt="<?php echo $track['title']; ?>" onerror="this.src='https://via.placeholder.com/300'">
                    </a>
                    <div class="card-body">
                        <h5>
                            <a href="beatdetail.php?id=<?php echo $track['id']; ?>" style="color: inherit; text-decoration: none;">
                                <?php echo $track['title']; ?>
                            </a>
                        </h5>
                        <div class="btn-row">
                            <button class="btn-play"><i class="fa fa-play"></i></button>
                            <button class="btn-buy open-options-btn" 
                                data-id="<?php echo $track['id']; ?>" 
                                data-name="<?php echo $track['title']; ?>" 
                                data-producer="KentonTheProducer">
                                <i class="fa fa-shopping-cart"></i>
                            </button>
                        </div>
                        <div class="music-visualizer">
                            <div class="bar"></div><div class="bar"></div><div class="bar"></div><div class="bar"></div><div class="bar"></div>
                        </div>
                        <audio src="<?php echo $track['audio_file']; ?>"></audio>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:white; padding: 20px;">No tracks found.</p>
        <?php endif; ?>
    </div>
</div>

<section class="tracks-section">
    <h2 class="section-title">Featured Drops</h2>
    <div class="tracks-grid">
        <?php if (count($tracks) > 0): ?>
            <?php foreach ($tracks as $track): ?>
                <div class="track-card" data-track-id="grid<?php echo $track['id']; ?>">
                    <div class="track-image">
                        <a href="beatdetail.php?id=<?php echo $track['id']; ?>">
                            <img src="<?php echo $track['cover_image']; ?>" alt="<?php echo $track['title']; ?>" onerror="this.src='https://via.placeholder.com/300'">
                        </a>
                        <div class="track-overlay">
                            <div class="play-button" data-track="<?php echo $track['id']; ?>">
                                <i class="fa fa-play"></i>
                            </div>
                        </div>
                    </div>
                    <div class="track-info">
                        <h3 class="track-title">
                            <a href="beatdetail.php?id=<?php echo $track['id']; ?>" style="color: inherit; text-decoration: none;">
                                <?php echo $track['title']; ?>
                            </a>
                        </h3>
                        <p class="track-artist">KentonTheProducer</p>
                        <div class="track-meta">
                            <div class="track-stats">
                                <div class="stat"><i class="fa fa-money"></i><span>$<?php echo number_format($track['price'], 0); ?></span></div>
                                <div class="stat"><i class="fa fa-tachometer"></i><span><?php echo $track['bpm']; ?> BPM</span></div>
                            </div>
                            <button class="track-price open-options-btn" 
                                data-id="<?php echo $track['id']; ?>" 
                                data-name="<?php echo $track['title']; ?>" 
                                data-producer="KentonTheProducer">
                                <i class="fa fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                    <audio src="<?php echo $track['audio_file']; ?>"></audio>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php
// Include the footer. It has the player, scripts, and closing body tags.
include 'footer.php';
?>