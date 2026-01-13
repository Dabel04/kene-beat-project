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

// 2. Tag Filter
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

    /* --- FILTER BAR --- */
    .filter-container {
        background: #111;
        padding: 15px 20px;
        border-bottom: 1px solid #222;
        position: sticky;
        top: var(--nav-height);
        z-index: 900;
        width: 100%;
    }
    
    .filter-row { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; flex-wrap: wrap; gap: 15px; }
    .filter-group { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

    .search-box { background: #000; border: 1px solid #333; color: white; padding: 8px 20px; border-radius: 30px; width: 250px; outline: none; transition: 0.3s; font-size: 14px; }
    .search-box:focus { border-color: #2bee79; }

    .filter-link { 
        background: #000; border: 1px solid #333; color: #888; 
        padding: 6px 15px; border-radius: 30px; cursor: pointer; 
        transition: 0.3s; font-size: 13px; text-transform: uppercase; 
        font-weight: 600; text-decoration: none; display: inline-block;
    }
    .filter-link:hover, .filter-link.active { background: #2bee79; color: black; border-color: #2bee79; text-decoration: none; }

    .view-btn { background: #000; border: 1px solid #333; color: #888; width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; }
    .view-btn:hover, .view-btn.active { color: #2bee79; border-color: #2bee79; }

    .tracks-grid-container { padding: 40px 0; min-height: 60vh; }

    /* --- GRID VIEW --- */
    .tracks-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px; }
    
    /* Play Button Logic: Hidden by default, Visible on Hover OR Playing */
    .track-overlay { opacity: 0; transition: 0.3s; }
    .track-card:hover .track-overlay { opacity: 1; }
    .track-card.playing .track-overlay { opacity: 1 !important; background: rgba(0,0,0,0.5); }

    /* --- MOBILE GRID (2 Columns) --- */
    @media (max-width: 768px) {
        .filter-container { position: relative; top: 0; z-index: 10; padding: 10px 15px; }
        
        .tracks-grid:not(.list-view) {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .tracks-grid:not(.list-view) .track-image { height: 160px; }
        .tracks-grid:not(.list-view) .track-title { font-size: 13px; margin-bottom: 2px; }
        .tracks-grid:not(.list-view) .track-artist { font-size: 11px; }
        
        /* Stats (Price/BPM) visible on mobile grid */
        .tracks-grid:not(.list-view) .track-meta {
            display: flex; flex-direction: column; gap: 6px; margin-top: 5px;
        }
        .tracks-grid:not(.list-view) .track-stats {
            display: flex !important; justify-content: space-between; width: 100%; font-size: 11px; color: #ccc;
        }
        .tracks-grid:not(.list-view) .track-price {
            width: 100%; padding: 6px 0; font-size: 12px; justify-content: center; border-radius: 4px;
        }
    }

    /* --- LIST VIEW --- */
    .tracks-grid.list-view { display: flex; flex-direction: column; gap: 10px; }
    .tracks-grid.list-view .track-card { display: flex; flex-direction: row; align-items: center; padding: 10px 15px; height: auto; background: #111; border: 1px solid #222; }
    .tracks-grid.list-view .track-image { width: 60px; height: 60px; flex-shrink: 0; margin-right: 20px; border-radius: 6px; }
    .tracks-grid.list-view .track-info { padding: 0; flex-grow: 1; display: flex; align-items: center; justify-content: space-between; }
    .tracks-grid.list-view .track-info > div:first-child { display: flex; flex-direction: column; justify-content: center; margin-right: auto; }
    .tracks-grid.list-view .track-title { font-size: 16px; margin-bottom: 3px; }
    .tracks-grid.list-view .track-meta { margin-top: 0; padding-top: 0; border-top: none; display: flex; align-items: center; gap: 25px; }
    .tracks-grid.list-view .track-stats { gap: 15px; }
    .tracks-grid.list-view .track-stats .stat:first-child { display: none; } 
    .tracks-grid.list-view .track-price { width: auto; padding: 8px 20px; border-radius: 20px; display: flex; align-items: center; gap: 8px; font-size: 13px; }
    .tracks-grid.list-view .track-price::before { content: attr(data-price-formatted); font-weight: 700; }

    /* Pagination */
    .pagination-wrapper { display: flex; justify-content: center; gap: 8px; margin-top: 40px; }
    .page-link { background: #111; color: white; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-weight: bold; border: 1px solid #333; font-size: 14px; }
    .page-link:hover, .page-link.active { background: #2bee79; color: black; border-color: #2bee79; }
    .page-link.disabled { opacity: 0.5; pointer-events: none; }

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
        <div class="filter-group">
            <form action="" method="GET" style="display:flex; align-items:center;">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="search-box" placeholder="Search beats...">
                <?php if($filterTag): ?><input type="hidden" name="tag" value="<?php echo htmlspecialchars($filterTag); ?>"><?php endif; ?>
            </form>

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="?search=<?php echo urlencode($search); ?>" class="filter-link <?php echo ($filterTag == '') ? 'active' : ''; ?>">All</a>
                <?php foreach($allTags as $t): ?>
                    <a href="?tag=<?php echo urlencode($t); ?>&search=<?php echo urlencode($search); ?>" 
                       class="filter-link <?php echo ($filterTag == $t) ? 'active' : ''; ?>">
                       <?php echo ucfirst($t); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="filter-group">
            <button class="view-btn active" id="btn-grid-view" title="Grid View"><i class="fa fa-th-large"></i></button>
            <button class="view-btn" id="btn-list-view" title="List View"><i class="fa fa-list"></i></button>
        </div>
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
    const tracksData = <?php echo json_encode($tracksArray); ?>;
    const gridContainer = document.getElementById('tracks-grid');
    const btnGridView = document.getElementById('btn-grid-view');
    const btnListView = document.getElementById('btn-list-view');

    function setView(view) {
        if (view === 'list') {
            gridContainer.classList.add('list-view');
            btnListView.classList.add('active');
            btnGridView.classList.remove('active');
            localStorage.setItem('tracksView', 'list');
        } else {
            gridContainer.classList.remove('list-view');
            btnGridView.classList.add('active');
            btnListView.classList.remove('active');
            localStorage.setItem('tracksView', 'grid');
        }
    }

    const savedView = localStorage.getItem('tracksView') || 'grid';
    setView(savedView);

    if(btnGridView) btnGridView.addEventListener('click', () => setView('grid'));
    if(btnListView) btnListView.addEventListener('click', () => setView('list'));

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

            const priceFormatted = `$${track.price.toFixed(0)}`;

            const cardHTML = `
                <div class="track-card">
                    <div class="track-image js-play-track" style="cursor:pointer">
                        <img src="${track.cover}" alt="${track.title}" loading="lazy" onerror="this.src='https://via.placeholder.com/300'">
                        <div class="track-overlay">
                            <div class="play-button"><i class="fa fa-play"></i></div>
                        </div>
                    </div>
                    
                    <div class="track-info">
                        <div>
                            <a href="beatdetail.php?id=${track.id}" class="track-link"><h3 class="track-title">${track.title}</h3></a>
                            <p class="track-artist">${track.producer}</p>
                            ${tagsHTML}
                        </div>
                        <div class="track-meta">
                            <div class="track-stats">
                                <div class="stat"><i class="fa fa-money"></i><span>${priceFormatted}</span></div>
                                <div class="stat"><i class="fa fa-tachometer"></i><span>${track.bpm} BPM</span></div>
                            </div>
                            <button class="track-price js-add-cart" 
                                data-id="${track.id}" 
                                data-name="${track.title}" 
                                data-producer="${track.producer}" 
                                data-img="${track.cover}" 
                                data-price="${track.price}"
                                data-price-formatted="${priceFormatted}">
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

    gridContainer.addEventListener('click', (e) => {
        // Play Trigger
        const playBtn = e.target.closest('.js-play-track');
        if (playBtn) {
            e.preventDefault(); e.stopPropagation();
            const card = playBtn.closest('.track-card');
            const audio = card.querySelector('audio');
            if(window.loadTrack) window.loadTrack(audio, card);
            return;
        }

        // Cart Trigger
        const cartBtn = e.target.closest('.js-add-cart');
        if (cartBtn) {
            e.preventDefault(); e.stopPropagation();
            const trackData = {
                id: cartBtn.dataset.id, name: cartBtn.dataset.name, 
                producer: cartBtn.dataset.producer, img: cartBtn.dataset.img
            };

            if(typeof window.openOptionsModal === 'function') {
                 window.openOptionsModal(trackData);
            } else {
                 // Fallback
                 const footerBtn = document.querySelector(`.open-options-btn[data-id="${trackData.id}"]`);
                 if(footerBtn) footerBtn.click();
                 else if(typeof window.addToCart === 'function') {
                     window.addToCart({
                        ...trackData,
                        price: parseFloat(cartBtn.dataset.price),
                        licenseKey: 'basic', licenseName: 'Basic Lease', type: 'beat'
                     });
                 }
            }
        }
    });

    renderTracks(tracksData);
});
</script>