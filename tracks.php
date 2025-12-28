<?php
include 'header.php';

$beatsPerPage = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$where = '';
$params = [];
$types = '';

if ($search !== '') {
    $where = "WHERE (title LIKE ? OR tags LIKE ?)";
    $searchParam = '%' . $search . '%';
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types = 'ss';
}

// Count total for pagination
$countSql = "SELECT COUNT(*) as total FROM tracks $where";
$countStmt = $conn->prepare($countSql);
if ($types) $countStmt->bind_param($types, ...$params);
$countStmt->execute();
$total = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $beatsPerPage);
$countStmt->close();

$offset = ($page - 1) * $beatsPerPage;

// Fetch current page
$sql = "SELECT * FROM tracks $where ORDER BY id DESC LIMIT ? OFFSET ?";
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
    $tracksArray[] = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'bpm' => $row['bpm'],
        'price' => floatval($row['price']),
        'tags' => strtolower($row['tags']),
        'cover' => $row['cover_image'],
        'audio' => $row['audio_file'],
        'producer' => 'KentonTheProducer'
    );
}
$stmt->close();
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

    .filter-btn { background: #000; border: 1px solid #333; color: #888; padding: 8px 20px; border-radius: 30px; cursor: pointer; transition: 0.3s; font-size: 14px; text-transform: uppercase; font-weight: 600; }
    .filter-btn:hover, .filter-btn.active { background: #2bee79; color: black; border-color: #2bee79; }

    .tracks-grid-container { padding: 50px 0; min-height: 60vh; }

    /* Tags & Links */
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
    <div class="filter-row" id="filter-buttons-container">
        <input type="text" id="searchInput" class="search-box" placeholder="Search beats...">
        <button class="filter-btn active" data-filter="all">All</button>
        </div>
</div>

<div class="container tracks-grid-container">
    <div class="tracks-grid" id="tracks-grid"></div>
  <?php if ($totalPages > 1): ?>
<div style="text-align:center; margin:50px 0;">
    <div style="display:inline-flex; gap:15px; align-items:center;">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
               style="background:#111; color:white; padding:12px 20px; border-radius:8px; text-decoration:none; font-weight:bold;">
                ← Previous
            </a>
        <?php endif; ?>

        <span style="color:#888;">
            Page <?php echo $page; ?> of <?php echo $totalPages; ?> 
            <?php if ($search): ?>(search: "<?php echo htmlspecialchars($search); ?>")<?php endif; ?>
        </span>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
               style="background:#2bee79; color:black; padding:12px 20px; border-radius:8px; text-decoration:none; font-weight:bold;">
                Next →
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tracksData = <?php echo json_encode($tracksArray); ?>;
    const gridContainer = document.getElementById('tracks-grid');
    const searchInput = document.getElementById('searchInput');
    const filterContainer = document.getElementById('filter-buttons-container');

    // 1. FILTER BUTTONS
    function generateFilterButtons() {
        const uniqueTags = new Set();
        tracksData.forEach(track => {
            if(track.tags) {
                track.tags.split(',').forEach(tag => {
                    if(tag.trim() !== "") uniqueTags.add(tag.trim());
                });
            }
        });
        uniqueTags.forEach(tag => {
            const btn = document.createElement('button');
            btn.className = 'filter-btn';
            btn.dataset.filter = tag;
            btn.textContent = tag.charAt(0).toUpperCase() + tag.slice(1);
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                filterTracks();
            });
            filterContainer.appendChild(btn);
        });
    }

    // 2. RENDER TRACKS
    function renderTracks(data) {
        gridContainer.innerHTML = '';
        if(data.length === 0) {
            gridContainer.innerHTML = '<p style="color:#666; text-align:center; width:100%; margin-top:50px;">No tracks found.</p>';
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

    function filterTracks() {
        const term = searchInput.value.toLowerCase();
        const activeBtn = document.querySelector('.filter-btn.active');
        const activeCat = activeBtn ? activeBtn.dataset.filter : 'all';
        const filtered = tracksData.filter(t => {
            return (t.title.toLowerCase().includes(term) || t.tags.includes(term)) && 
                   (activeCat === 'all' || t.tags.includes(activeCat));
        });
        renderTracks(filtered);
    }

    searchInput.addEventListener('input', filterTracks);

    // 3. CLICKS (Play & Cart)
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

        // Add to Cart (MODAL TRIGGER)
        const cartBtn = e.target.closest('.js-add-cart');
        if (cartBtn) {
            e.preventDefault(); e.stopPropagation();
            
            // Prepare Data
            const trackData = {
                id: cartBtn.dataset.id, name: cartBtn.dataset.name, 
                producer: cartBtn.dataset.producer, img: cartBtn.dataset.img
            };

            // Get Modal Elements
            const modal = document.getElementById('options-modal-overlay');
            const modalTitle = document.getElementById('modal-track-name');
            const optionsContainer = document.getElementById('options-container');

            if(modal && optionsContainer) {
                // Set Title
                modalTitle.innerHTML = `Select License: <span style="color:#2bee79">${trackData.name}</span>`;
                optionsContainer.innerHTML = ''; 

                // License Definitions (MATCHING FOOTER.PHP)
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
                
                // ORDER: Basic -> Premium -> Exclusive
                const keys = ['basic', 'premium', 'exclusive'];

                // GENERATE CARDS (Exact same HTML as footer.php)
                keys.forEach(key => {
                    const license = LICENSES[key];
                    const div = document.createElement('div');
                    div.className = 'license-option-card';
                    div.dataset.key = key; // For click listener
                    
                    // Features List
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

                // CLICK LOGIC FOR THE NEW CARDS
                optionsContainer.querySelectorAll('.license-option-card').forEach(card => {
                    card.addEventListener('click', (ev) => {
                        const key = card.dataset.key;
                        const selectedLicense = LICENSES[key];

                        let cart = JSON.parse(localStorage.getItem('cartItems')) || [];
                        cart.push({
                            id: trackData.id, name: trackData.name, producer: trackData.producer,
                            price: selectedLicense.price, licenseKey: key, licenseName: selectedLicense.name, img: trackData.img
                        });
                        localStorage.setItem('cartItems', JSON.stringify(cart));
                        
                        document.getElementById('open-cart-btn').click(); 
                        modal.style.display = 'none';
                    });
                });

                modal.style.display = 'flex';
            }
        }
    });

    generateFilterButtons(); 
    renderTracks(tracksData);
});
</script>
<script>
const searchInput = document.getElementById('search-input');

searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const query = encodeURIComponent(searchInput.value.trim());
        window.location.href = '?search=' + query + '&page=1';
    }
});

// Preserve search on pagination clicks
document.querySelectorAll('.pagination a').forEach(link => {
    link.addEventListener('click', function(e) {
        const url = new URL(this.href);
        const currentSearch = new URLSearchParams(window.location.search).get('search');
        if (currentSearch) {
            url.searchParams.set('search', currentSearch);
        }
        this.href = url.toString();
    });
});
</script>