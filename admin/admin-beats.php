<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>My Beats - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#2bee79", "primary-hover": "#24c765", "background-light": "#f6f8f7", "background-dark": "#102217", "surface-dark": "#162e21" },
                    fontFamily: { "display": ["Spline Sans", "sans-serif"] },
                    boxShadow: { 'glow': '0 0 15px rgba(43, 238, 121, 0.2)' }
                },
            },
        }
    </script>
    <style>
        .glass-panel { background: rgba(22, 46, 33, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .modal { transition: opacity 0.3s ease, visibility 0.3s ease; opacity: 0; visibility: hidden; }
        .modal.active { opacity: 1; visibility: visible; }
        
        /* Drag & Drop Styles */
        .drop-zone { transition: all 0.3s ease; border-style: dashed; }
        .drop-zone.drag-over { border-color: #2bee79; background: rgba(43, 238, 121, 0.1); transform: scale(1.02); }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-900 dark:text-white">
<div class="relative flex min-h-screen w-full overflow-x-hidden">
    
    <aside class="hidden lg:flex w-72 flex-col fixed inset-y-0 left-0 z-50 border-r border-gray-200 dark:border-white/5 bg-white/50 dark:bg-[#102217]/95 backdrop-blur-xl">
        <div class="flex h-full flex-col justify-between p-6">
            <div class="flex flex-col gap-8">
                <div class="flex items-center gap-4 px-2">
                    <div class="bg-center bg-cover rounded-full h-12 w-12 border-2 border-primary/20" style='background-image: url("https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&auto=format&fit=crop&w=1740&q=80");'></div>
                    <div><h1 class="text-lg font-bold">Kenton</h1><p class="text-xs text-gray-500 uppercase">Pro Producer</p></div>
                </div>
                <nav class="flex flex-col gap-2">
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group text-gray-600 dark:text-gray-400" href="admin-dashboard.php">
                        <span class="material-symbols-outlined group-hover:text-primary">dashboard</span>
                        <p class="group-hover:text-white text-sm font-medium">Dashboard</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full bg-primary/10 dark:bg-primary/20 border border-primary/20" href="admin-beats.php">
                        <span class="material-symbols-outlined text-primary">library_music</span>
                        <p class="text-primary font-semibold text-sm">My Beats</p>
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group text-gray-600 dark:text-gray-400" href="customer.php">
                        <span class="material-symbols-outlined group-hover:text-primary">group</span>
                        <p class="group-hover:text-white text-sm font-medium">Customers</p>
                    </a>

                     <a class="flex items-center gap-4 px-4 py-3 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 group transition-all text-gray-600 dark:text-gray-400" href="order.php">
                        <span class="material-symbols-outlined group-hover:text-primary transition-colors">shopping_bag</span>
                        <p class="group-hover:text-white text-sm font-medium transition-colors">Orders</p>
                    </a>
                </nav>
            </div>
            <a href="../home.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-gray-400 hover:text-red-400"><span class="material-symbols-outlined">logout</span><span class="text-sm font-medium">Log Out</span></a>
        </div>
    </aside>

    <main class="flex-1 lg:ml-72 flex flex-col min-h-screen">
        <div class="flex-1 p-4 md:p-8 lg:p-12 max-w-7xl mx-auto w-full flex flex-col gap-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight">My <span class="text-primary">Beats</span></h1>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">Manage your catalog.</p>
                </div>
                <button id="open-upload-modal" class="flex items-center gap-2 bg-primary hover:bg-primary-hover text-background-dark px-6 py-3 rounded-full font-bold shadow-glow transition-transform hover:scale-105">
                    <span class="material-symbols-outlined text-xl">cloud_upload</span><span>Upload New Track</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="glass-panel p-4 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">Total Tracks</p>
                        <p class="text-2xl font-bold text-white" id="total-tracks-count">0</p>
                    </div>
                    <span class="material-symbols-outlined text-primary/50 text-3xl">library_music</span>
                </div>
            </div>

            <div class="glass-panel rounded-2xl overflow-hidden bg-white dark:bg-surface-dark">
                <div class="grid grid-cols-12 gap-4 p-4 border-b border-white/5 text-xs font-bold text-gray-400 uppercase bg-black/20">
                    <div class="col-span-5 md:col-span-4 pl-2">Track Details</div>
                    <div class="hidden md:flex col-span-2 justify-center">BPM / Key</div>
                    <div class="hidden md:flex col-span-2 justify-center">Price</div>
                    <div class="col-span-3 md:col-span-2 text-center">Status</div>
                    <div class="col-span-4 md:col-span-2 text-right pr-2">Actions</div>
                </div>
                <div id="beats-table-body">
                    <div class="p-8 text-center text-gray-500">Loading tracks...</div>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="upload-modal" class="modal fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" id="modal-backdrop"></div>
    <div class="modal-content relative w-full max-w-3xl bg-[#162e21] border border-white/10 rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">
        
        <div class="p-6 border-b border-white/5 flex items-center justify-between">
            <h3 class="text-xl font-bold text-white">Upload New Track</h3>
            <button id="close-modal-btn" class="text-gray-400 hover:text-white"><span class="material-symbols-outlined">close</span></button>
        </div>
        
        <div class="p-6 overflow-y-auto custom-scrollbar">
            <form id="upload-form" class="flex flex-col gap-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="text-xs font-bold text-gray-400 uppercase mb-2 block">Cover Art</label>
                        <div id="image-drop-zone" class="drop-zone relative h-40 w-full rounded-xl border-2 border-white/10 bg-black/20 flex flex-col items-center justify-center cursor-pointer hover:border-white/30 text-gray-400 hover:text-white group overflow-hidden">
                            <input type="file" id="image-input" accept="image/*" class="hidden">
                            <img id="image-preview" src="" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                            <div id="image-placeholder" class="flex flex-col items-center text-center p-2">
                                <span class="material-symbols-outlined text-3xl mb-1 group-hover:scale-110 transition-transform">image</span><span class="text-[10px] font-bold">Drop Image</span>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-400 uppercase mb-2 block">Audio File (MP3/WAV)</label>
                        <div id="audio-drop-zone" class="drop-zone h-40 w-full rounded-xl border-2 border-white/10 bg-black/20 flex flex-col items-center justify-center cursor-pointer hover:border-white/30 text-gray-400 hover:text-white group relative">
                            <input type="file" id="audio-input" accept="audio/*" class="hidden">
                            <div class="flex flex-col items-center text-center">
                                <span class="material-symbols-outlined text-4xl mb-2 text-primary group-hover:scale-110 transition-transform">audio_file</span><h4 class="text-sm font-bold text-white mb-1">Drag & Drop Audio</h4><p class="text-[10px] text-gray-500">or click to browse</p>
                            </div>
                            <div id="audio-file-info" class="absolute inset-0 bg-[#162e21] flex flex-col items-center justify-center z-20 hidden rounded-xl border border-primary/50">
                                <span class="material-symbols-outlined text-4xl text-primary mb-2">check_circle</span><p class="text-white text-sm font-bold" id="audio-filename"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2"><label class="text-xs font-bold text-gray-400 uppercase">Track Title</label><input type="text" id="track-title" required class="bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:ring-primary focus:border-primary"></div>
                    <div class="flex flex-col gap-2"><label class="text-xs font-bold text-gray-400 uppercase">BPM</label><input type="number" id="track-bpm" required class="bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:ring-primary focus:border-primary"></div>
                    <div class="flex flex-col gap-2"><label class="text-xs font-bold text-gray-400 uppercase">Key</label><input type="text" id="track-key" required placeholder="Cm" class="bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:ring-primary focus:border-primary"></div>
                    <div class="flex flex-col gap-2"><label class="text-xs font-bold text-gray-400 uppercase">Price ($)</label><input type="number" id="track-price" required value="25.00" class="bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:ring-primary focus:border-primary"></div>
                </div>
                <div class="flex flex-col gap-2"><label class="text-xs font-bold text-gray-400 uppercase">Tags</label><input type="text" id="track-tags" required placeholder="Trap, Dark" class="bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:ring-primary focus:border-primary"></div>
                <div class="p-4 border-t border-white/5 flex justify-end gap-3 mt-2">
                    <button type="button" id="cancel-modal-btn" class="px-6 py-2 rounded-full text-gray-400 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 rounded-full bg-primary text-[#102217] font-bold hover:bg-primary-hover shadow-glow transition-transform hover:scale-105">Publish Beat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let uploadedImageFile = null;
    let uploadedAudioFile = null;

    // --- 1. DRAG & DROP LOGIC ---
    function setupDragDrop(zoneId, inputId, type, callback) {
        const zone = document.getElementById(zoneId);
        const input = document.getElementById(inputId);
        if(!zone || !input) return;

        const prevent = (e) => { e.preventDefault(); e.stopPropagation(); };
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => zone.addEventListener(e, prevent));
        ['dragenter', 'dragover'].forEach(e => zone.addEventListener(e, () => zone.classList.add('drag-over')));
        ['dragleave', 'drop'].forEach(e => zone.addEventListener(e, () => zone.classList.remove('drag-over')));
        
        zone.addEventListener('drop', (e) => {
            if (e.dataTransfer.files.length) handleFiles(e.dataTransfer.files[0]);
        });
        zone.addEventListener('click', () => input.click());
        input.addEventListener('change', function() { if(this.files.length) handleFiles(this.files[0]); });

        function handleFiles(file) {
            if (!file.type.startsWith(type)) { alert('Invalid file type.'); return; }
            callback(file);
        }
    }

    setupDragDrop('image-drop-zone', 'image-input', 'image/', (file) => {
        uploadedImageFile = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('image-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });

    setupDragDrop('audio-drop-zone', 'audio-input', 'audio/', (file) => {
        uploadedAudioFile = file;
        document.getElementById('audio-filename').innerText = file.name;
        document.getElementById('audio-file-info').classList.remove('hidden');
        document.getElementById('audio-file-info').classList.add('flex');
    });

    // --- 2. UPLOAD TRACK ---
    const form = document.getElementById('upload-form');
    if(form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (!uploadedImageFile || !uploadedAudioFile) { alert("Please upload both files."); return; }

            const formData = new FormData();
            formData.append('title', document.getElementById('track-title').value);
            formData.append('bpm', document.getElementById('track-bpm').value);
            formData.append('track_key', document.getElementById('track-key').value);
            formData.append('price', document.getElementById('track-price').value);
            formData.append('tags', document.getElementById('track-tags').value);
            formData.append('cover_image', uploadedImageFile);
            formData.append('audio_file', uploadedAudioFile);

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerText = "Uploading...";
            submitBtn.disabled = true;

            fetch('../includes/upload_track.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    toggleModal(false);
                    form.reset();
                    resetUI();
                    loadBeats();
                    alert("Track Uploaded!");
                } else { alert("Error: " + data.message); }
            })
            .catch(e => alert("Upload failed. Check console."))
            .finally(() => {
                submitBtn.innerText = "Publish Beat";
                submitBtn.disabled = false;
            });
        });
    }

    function resetUI() {
        uploadedImageFile = null; uploadedAudioFile = null;
        document.getElementById('image-preview').classList.add('hidden');
        document.getElementById('image-placeholder').classList.remove('hidden');
        document.getElementById('audio-file-info').classList.add('hidden');
        document.getElementById('audio-file-info').classList.remove('flex');
    }

    // --- 3. DELETE TRACK FUNCTION ---
    function deleteTrack(id) {
        if(!confirm("Are you sure you want to delete this track? This cannot be undone.")) return;

        fetch('../includes/delete_track.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) {
                loadBeats(); // Refresh the table
            } else {
                alert("Error deleting: " + data.message);
            }
        })
        .catch(err => alert("Connection error while deleting."));
    }

    // --- 4. FETCH & DISPLAY BEATS ---
    function loadBeats() {
        fetch('../includes/get_tracks.php')
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Received HTML/Text instead of JSON.");
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.getElementById('beats-table-body');
            const countEl = document.getElementById('total-tracks-count');
            
            if (data.error) {
                if(tbody) tbody.innerHTML = `<div class="p-8 text-center text-red-400 font-bold">Error: ${data.error}</div>`;
                return;
            }

            if (countEl) countEl.innerText = data.length || 0;
            if (!tbody) return;
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = '<div class="p-8 text-center text-gray-500">No tracks found.</div>';
                return;
            }
            
            data.forEach(beat => {
                let imgPath = beat.cover_image;
                if (imgPath && !imgPath.startsWith('../') && !imgPath.startsWith('http')) {
                    imgPath = '../' + imgPath; 
                }

                // Added DELETE BUTTON below
                tbody.innerHTML += `
                <div class="grid grid-cols-12 gap-4 p-4 items-center border-b border-white/5 hover:bg-white/5 transition-colors group">
                    <div class="col-span-5 md:col-span-4 flex items-center gap-4">
                        <div class="relative w-12 h-12 rounded-lg overflow-hidden flex-shrink-0">
                            <img class="w-full h-full object-cover" src="${imgPath}" onerror="this.src='https://via.placeholder.com/50'"/>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <h4 class="text-white font-bold truncate">${beat.title}</h4>
                            <p class="text-xs text-gray-400">${beat.tags}</p>
                        </div>
                    </div>
                    <div class="hidden md:flex col-span-2 flex-col items-center justify-center">
                        <span class="text-white font-medium text-sm">${beat.bpm}</span><span class="text-xs text-gray-400">${beat.track_key}</span>
                    </div>
                    <div class="hidden md:flex col-span-2 items-center justify-center"><span class="text-primary font-bold">$${parseFloat(beat.price).toFixed(2)}</span></div>
                    <div class="col-span-3 md:col-span-2 flex items-center justify-center"><span class="px-2 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-bold border border-primary/20">PUBLISHED</span></div>
                    
                    <div class="col-span-4 md:col-span-2 flex items-center justify-end gap-2 pr-2">
                        <button class="p-2 rounded-full hover:bg-white/10 text-gray-400 hover:text-white" title="Edit">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </button>
                        <button onclick="deleteTrack(${beat.id})" class="p-2 rounded-full hover:bg-red-500/20 text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                </div>`;
            });
        })
        .catch(err => {
            console.error(err);
        });
    }

    // Modal Toggles
    const modal = document.getElementById('upload-modal');
    function toggleModal(s) { if(modal) { modal.classList.toggle('active', s); document.body.style.overflow = s ? 'hidden' : ''; } }
    const openBtn = document.getElementById('open-upload-modal');
    if(openBtn) openBtn.onclick = () => toggleModal(true);
    document.getElementById('close-modal-btn').onclick = () => toggleModal(false);
    document.getElementById('cancel-modal-btn').onclick = () => toggleModal(false);
    document.getElementById('modal-backdrop').onclick = () => toggleModal(false);

    loadBeats();
</script>
</body>
</html>