<?php
include 'header.php';

// --- CONFIGURATION ---
$beatsPerPage = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filterTag = isset($_GET['tag']) ? trim($_GET['tag']) : '';

// --- BUILD QUERY ---
$whereClauses = [];
$params = [];
$types = '';

// 1. Search Filter
if ($search !== '') {
    $whereClauses[] = "(title LIKE ? OR tags LIKE ?)";
    $searchParam = '%' . $search . '%';
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= 'ss';
}

// 2. Tag Filter (Server-Side)
if ($filterTag !== '' && $filterTag !== 'all') {
    $whereClauses[] = "tags LIKE ?";
    $tagParam = '%' . $filterTag . '%';
    $params[] = $tagParam;
    $types .= 's';
}

$whereSQL = '';
if (count($whereClauses) > 0) {
    $whereSQL = "WHERE " . implode(' AND ', $whereClauses);
}

// --- PAGINATION LOGIC ---
$countSql = "SELECT COUNT(*) as total FROM tracks $whereSQL";
$countStmt = $conn->prepare($countSql);
if (!empty($types)) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$total = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $beatsPerPage);
$countStmt->close();

$offset = ($page - 1) * $beatsPerPage;

// --- FETCH TRACKS ---
$sql = "SELECT * FROM tracks $whereSQL ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

$bindParams = $params;
$bindParams[] = $beatsPerPage;
$bindParams[] = $offset;
$bindTypes = $types . 'ii';

$stmt->bind_param($bindTypes, ...$bindParams);
$stmt->execute();
$result = $stmt->get_result();

$tracksArray = [];
while ($row = $result->fetch_assoc()) {
    // SECURITY FIX: Send Tagged File
    $previewPath = !empty($row['tagged_file']) ? $row['tagged_file'] : $row['audio_file'];

    $tracksArray[] = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'bpm' => $row['bpm'],
        'price' => floatval($row['price']),
        'tags' => strtolower($row['tags']),
        'cover' => $row['cover_image'],
        'audio' => $previewPath,
        'producer' => 'KentonTheProducer'
    );
}
$stmt->close();

// --- FETCH ALL TAGS ---
$tagSql = "SELECT tags FROM tracks";
$tagResult = $conn->query($tagSql);
$allTags = [];
if($tagResult) {
    while($r = $tagResult->fetch_assoc()) {
        $parts = explode(',', $r['tags']);
        foreach($parts as $t) {
            $t = trim(strtolower($t));
            if(!empty($t) && !in_array($t, $allTags)) {
                $allTags[] = $t;
            }
        }
    }
}
sort($allTags);
?>

<style>
    body { background-color: #000; }
    
    .catalog-header {
        margin-top: var(--nav-height);
        background: #050505;
        padding: 60px 0 30px;
        text-align: center;
        border-bottom: 1px solid #222;
    }
    .catalog-header h1 { color: white; font-weight: 800; text-transform: uppercase; letter-spacing: -1px; margin-bottom: 10px; }

    /* Filter Bar */
    .filter-container {
        background: #111;
        padding: 20px;
        border-bottom: 1px solid #222;
        position: sticky;
        top: var(--nav-height);
        z-index: 900;
    }
    .filter-row { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; max-width: 1200px; margin: 0 auto; align-items: center; }

    .search-box { background: #000; border: 1px solid #333; color: white; padding: 10px 20px; border-radius: 30px; width: 300px; outline: none; transition: 0.3s; }
    .search-box:focus { border-color: #2bee79; }

    .filter-link { 
        background: #000; border: 1px solid #333; color: #888; 
        padding: 8px 20px; border-radius: 30px; cursor: pointer; 
        transition: 0.3s; font-size: 14px; text-transform: uppercase; 
        font-weight: 600; text-decoration: none; display: inline-block;
    }
    .filter-link:hover, .filter-link.active { background: #2bee79; color: black; border-color: #2bee79; text-decoration: none; }

    .tracks-grid-container { padding: 50px 0; min-height: 60vh; }

    /* Pagination */
    .pagination-wrapper { display: flex; justify-content: center; gap: 10px; margin-top: 50px; }
    .page-link { 
        background: #111; color: white; padding: 10px 16px; 
        border-radius: 8px; text-decoration: none; font-weight: bold; border: 1px solid #333;
    }
    .page-link:hover, .page-link.active { background: #2bee79; color: black; border-color: #2bee79; }
    .page-link.disabled { opacity: 0.5; pointer-events: none; }

    /* Grid Items */
    .track-link { text-decoration: none; color: inherit; display: block; }
    .track-link:hover { text-decoration: none; color: #2bee79; }
    .track-tags-list { display: flex; gap: 5px; flex-wrap: wrap; margin: 8px 0; }
    .track-tag-badge { font-size: 10px; background: #1a1a1a; color: #888; padding: 3px 8px; border-radius: 4px; border: 1px solid #333; text-transform: uppercase; white-space: nowrap; }
</style>

<div class="catalog-header">
    <div class="container">
        <h1>Beat Catalog</h1>
        <p style="color: #666;">Browse. Listen. Create.</p>
    </div>
</div>

<div class="filter-container">
    <div class="filter-row">
        <form action="" method="GET" style="display:flex; align-items:center;">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="search-box" placeholder="Search beats...">
            <?php if($filterTag): ?><input type="hidden" name="tag" value="<?php echo htmlspecialchars($filterTag); ?>"><?php endif; ?>
        </form>

        <a href="?search=<?php echo urlencode($search); ?>" class="filter-link <?php echo ($filterTag == '') ? 'active' : ''; ?>">All</a>
        <?php foreach($allTags as $t): ?>
            <a href="?tag=<?php echo urlencode($t); ?>&search=<?php echo urlencode($search); ?>" 
               class="filter-link <?php echo ($filterTag == $t) ? 'active' : ''; ?>">
               <?php echo ucfirst($t); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="container tracks-grid-container">
    <div class="tracks-grid" id="tracks-grid"></div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination-wrapper">
            <a href="?page=<?php echo max(1, $page-1); ?>&search=<?php echo urlencode($search); ?>&tag=<?php echo urlencode($filterTag); ?>" 
               class="page-link <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
               &laquo;
            </a>
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&tag=<?php echo urlencode($filterTag); ?>" 
                   class="page-link <?php echo ($page == $i) ? 'active' : ''; ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            <a href="?page=<?php echo min($totalPages, $page+1); ?>&search=<?php echo urlencode($search); ?>&tag=<?php echo urlencode($filterTag); ?>" 
               class="page-link <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
               &raquo;
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. DATA FROM PHP
    const tracksData = <?php echo json_encode($tracksArray); ?>;
    const gridContainer = document.getElementById('tracks-grid');
    const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

    // 2. RENDER FUNCTION
    function renderTracks(data) {
        gridContainer.innerHTML = '';
        if(data.length === 0) {
            gridContainer.innerHTML = '<p style="color:#666; text-align:center; width:100%; margin-top:50px;">No tracks found matching your criteria.</p>';
            return;
        }

        data.forEach(track => {
            let tagsHTML = '';
            if(track.tags) {
                tagsHTML = '<div class="track-tags-list">' + 
                    track.tags.split(',').map(t => `<span class="track-tag-badge">${t.trim()}</span>`).join('') + 
                    '</div>';
            }

            const cardHTML = `
                <div class="track-card">
                    <div class="track-image">
                        <a href="beatdetail.php?id=${track.id}" class="track-link">
                            <img src="${track.cover}" alt="${track.title}" onerror="this.src='https://via.placeholder.com/300'">
                        </a>
                        <div class="track-overlay">
                            <div class="play-button js-play-track"><i class="fa fa-play"></i></div>
                        </div>
                    </div>
                    <div class="track-info">
                        <a href="beatdetail.php?id=${track.id}" class="track-link"><h3 class="track-title">${track.title}</h3></a>
                        <p class="track-artist">${track.producer}</p>
                        ${tagsHTML}
                        <div class="track-meta">
                            <div class="track-stats">
                                <div class="stat"><i class="fa fa-money"></i><span>$${track.price.toFixed(0)}</span></div>
                                <div class="stat"><i class="fa fa-tachometer"></i><span>${track.bpm} BPM</span></div>
                            </div>
                            <button class="track-price js-add-cart" 
                                data-id="${track.id}" 
                                data-name="${track.title}" 
                                data-producer="${track.producer}" 
                                data-img="${track.cover}" 
                                data-price="${track.price}">
                                <i class="fa fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                    <audio src="${track.audio}"></audio>
                </div>
            `;
            gridContainer.innerHTML += cardHTML;
        });
    }

    // 3. LISTENERS
    gridContainer.addEventListener('click', (e) => {
        // Play
        const playBtn = e.target.closest('.js-play-track');
        if (playBtn) {
            e.preventDefault(); e.stopPropagation();
            const card = playBtn.closest('.track-card');
            const audio = card.querySelector('audio');
            if(window.loadTrack) window.loadTrack(audio, card);
            return;
        }

        // Add to Cart
        const cartBtn = e.target.closest('.js-add-cart');
        if (cartBtn) {
            e.preventDefault(); e.stopPropagation();
            const trackData = {
                id: cartBtn.dataset.id, name: cartBtn.dataset.name, 
                producer: cartBtn.dataset.producer, img: cartBtn.dataset.img
            };
            openCartModal(trackData);
        }
    });

    // Helper: Open Cart Modal (Matching footer.php options)
    function openCartModal(trackData) {
        const modal = document.getElementById('options-modal-overlay');
        const modalTitle = document.getElementById('modal-track-name');
        const optionsContainer = document.getElementById('options-container');

        if(modal && optionsContainer) {
            modalTitle.innerHTML = `Select License: <span style="color:#2bee79">${trackData.name}</span>`;
            optionsContainer.innerHTML = ''; 

            // UPDATED LICENSES TO MATCH HOME/FOOTER
            const LICENSES = {
                'basic': { 
                    name: 'Basic Lease', 
                    price: 25.00, 
                    features: ['MP3 File (320kbps)', '5,000 Streams Cap', 'Non-Profit Use', '1 Commercial Video', 'Instant Download'],
                    recommended: false
                },
                'premium': { 
                    name: 'Premium Lease', 
                    price: 99.99, 
                    features: ['WAV + MP3 Files', '500,000 Streams Cap', 'For Profit Use', '10 Commercial Videos', 'Tracked Out Stems (+$50)'],
                    recommended: true
                },
                'exclusive': { 
                    name: 'Exclusive Rights', 
                    price: 500.00, 
                    features: ['MP3 + WAV + Stems', 'Unlimited Streams', 'Unlimited Profits', 'Radio Broadcasting', 'Ownership Transferred'],
                    recommended: false
                }
            };
            
            ['basic', 'premium', 'exclusive'].forEach(key => {
                const license = LICENSES[key];
                const div = document.createElement('div');
                div.className = 'license-option-card';
                div.innerHTML = `
                    ${license.recommended ? '<div class="recommended-badge">Best Value</div>' : ''}
                    <div class="license-name">${license.name}</div>
                    <div class="license-price">$${license.price.toFixed(0)}</div>
                    <ul class="license-features">${license.features.map(f => `<li><i class="fa fa-check"></i> ${f}</li>`).join('')}</ul>
                    <div class="select-label">Select Plan</div>
                `;
                div.onclick = () => {
                    // 1. Get Cart
                    let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
                    
                    // 2. Add Item
                    cart.push({ ...trackData, price: license.price, licenseKey: key, licenseName: license.name });
                    
                    // 3. Save Local
                    localStorage.setItem('cartItems', JSON.stringify(cart));
                    
                    // 4. Sync Server (If Logged In)
                    if(isLoggedIn) {
                        fetch('includes/save_cart.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ cart: cart })
                        }).catch(err => console.error("Sync failed"));
                    }

                    // 5. Update UI
                    document.getElementById('open-cart-btn').click(); 
                    modal.style.display = 'none';
                };
                optionsContainer.appendChild(div);
            });
            modal.style.display = 'flex';
        }
    }

    renderTracks(tracksData);
});
</script>