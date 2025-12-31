<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../getstarted.php"); exit; }
include '../db_connect.php';
include 'includes/sidebar.php'; 
?>

<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight">Sound <span class="text-primary">Kits</span></h1>
        <p class="text-gray-400 text-lg">Manage your drum kits and sample packs.</p>
    </div>
    <button id="open-upload-modal" class="flex items-center gap-2 bg-primary hover:bg-primary-hover text-background-dark px-6 py-3 rounded-full font-bold shadow-glow transition-transform hover:scale-105">
        <span class="material-symbols-outlined text-xl">cloud_upload</span><span>New Kit</span>
    </button>
</div>

<div id="kits-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="col-span-full p-12 text-center text-gray-500">Loading kits...</div>
</div>

</main>
</div>

<div id="upload-modal" class="modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 invisible transition-all duration-300">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" id="modal-backdrop"></div>
    <div class="modal-content relative w-full max-w-2xl bg-[#162e21] border border-white/10 rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">
        
        <div class="p-6 border-b border-white/5 flex items-center justify-between">
            <h3 class="text-xl font-bold text-white">Upload Sound Kit</h3>
            <button id="close-modal-btn" class="text-gray-400 hover:text-white"><span class="material-symbols-outlined">close</span></button>
        </div>
        
        <div class="p-6 overflow-y-auto custom-scrollbar">
            <form id="upload-form" class="flex flex-col gap-6">
                
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase mb-2 block">Cover Art (Square)</label>
                    <div id="image-drop-zone" class="drop-zone relative h-40 w-full rounded-xl border-2 border-white/10 bg-black/20 flex flex-col items-center justify-center cursor-pointer hover:border-white/30 text-gray-400 hover:text-white group overflow-hidden transition-all">
                        <input type="file" id="image-input" accept="image/*" class="hidden">
                        <img id="image-preview" src="" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                        <div id="image-placeholder" class="flex flex-col items-center text-center p-2">
                            <span class="material-symbols-outlined text-3xl mb-1">image</span><span class="text-[10px] font-bold">Drop Image</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-primary uppercase mb-2 block">Audio Preview (MP3)</label>
                        <div id="audio-drop-zone" class="drop-zone h-32 rounded-xl border-2 border-white/10 bg-black/20 flex flex-col items-center justify-center cursor-pointer hover:border-primary/50 text-gray-400 hover:text-white group relative">
                            <input type="file" id="audio-input" accept="audio/*" class="hidden">
                            <span class="material-symbols-outlined text-2xl mb-1 text-primary">play_circle</span>
                            <div id="audio-file-info" class="text-xs font-bold text-primary truncate px-2 hidden"></div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-blue-400 uppercase mb-2 block">Product File (.ZIP)</label>
                        <div id="zip-drop-zone" class="drop-zone h-32 rounded-xl border-2 border-white/10 bg-black/20 flex flex-col items-center justify-center cursor-pointer hover:border-blue-400/50 text-gray-400 hover:text-white group relative">
                            <input type="file" id="zip-input" accept=".zip,.rar" class="hidden">
                            <span class="material-symbols-outlined text-2xl mb-1 text-blue-400">folder_zip</span>
                            <div id="zip-file-info" class="text-xs font-bold text-blue-400 truncate px-2 hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-gray-400 uppercase">Kit Title</label>
                        <input type="text" id="kit-title" required class="bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:ring-primary focus:border-primary outline-none">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-gray-400 uppercase">Price ($)</label>
                        <input type="number" id="kit-price" required value="29.99" class="bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:ring-primary focus:border-primary outline-none">
                    </div>
                </div>
                
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold text-gray-400 uppercase">Description</label>
                    <textarea id="kit-desc" rows="3" class="bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:ring-primary focus:border-primary outline-none" placeholder="What's inside? (e.g. 50 Kicks, 20 Loops...)"></textarea>
                </div>

                <div class="p-4 border-t border-white/5 flex justify-end gap-3 mt-2">
                    <button type="button" id="cancel-modal-btn" class="px-6 py-2 rounded-full text-gray-400 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 rounded-full bg-primary text-[#102217] font-bold hover:bg-primary-hover shadow-glow transition-transform hover:scale-105">Upload Kit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let files = { image: null, audio: null, zip: null };

    // --- DRAG & DROP UTILS ---
    function setupDrop(zoneId, inputId, type, key) {
        const zone = document.getElementById(zoneId);
        const input = document.getElementById(inputId);
        const prevent = e => { e.preventDefault(); e.stopPropagation(); };
        
        ['dragenter','dragover','dragleave','drop'].forEach(e => zone.addEventListener(e, prevent));
        ['dragenter','dragover'].forEach(e => zone.addEventListener(e, () => zone.classList.add('border-white/50')));
        ['dragleave','drop'].forEach(e => zone.addEventListener(e, () => zone.classList.remove('border-white/50')));
        
        zone.addEventListener('drop', e => { if(e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]); });
        zone.addEventListener('click', () => input.click());
        input.addEventListener('change', function() { if(this.files.length) handleFile(this.files[0]); });

        function handleFile(file) {
            files[key] = file;
            if(key === 'image') {
                const reader = new FileReader();
                reader.onload = e => { 
                    document.getElementById('image-preview').src = e.target.result; 
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('image-placeholder').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else if(key === 'audio') {
                document.getElementById('audio-file-info').innerText = file.name;
                document.getElementById('audio-file-info').classList.remove('hidden');
            } else if(key === 'zip') {
                document.getElementById('zip-file-info').innerText = file.name;
                document.getElementById('zip-file-info').classList.remove('hidden');
            }
        }
    }

    setupDrop('image-drop-zone', 'image-input', 'image/', 'image');
    setupDrop('audio-drop-zone', 'audio-input', 'audio/', 'audio');
    setupDrop('zip-drop-zone', 'zip-input', '', 'zip');

    // --- SUBMIT ---
    document.getElementById('upload-form').addEventListener('submit', (e) => {
        e.preventDefault();
        if(!files.image || !files.audio || !files.zip) { alert("Please upload all 3 files."); return; }

        const formData = new FormData();
        formData.append('title', document.getElementById('kit-title').value);
        formData.append('price', document.getElementById('kit-price').value);
        formData.append('desc', document.getElementById('kit-desc').value);
        formData.append('cover_image', files.image);
        formData.append('demo_audio', files.audio);
        formData.append('zip_file', files.zip);

        const btn = e.target.querySelector('button[type="submit"]');
        btn.innerText = "Uploading..."; btn.disabled = true;

        fetch('../includes/upload_kit.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if(data.success) { toggleModal(false); loadKits(); alert("Kit Uploaded!"); }
            else { alert("Error: " + data.message); }
        })
        .finally(() => { btn.innerText = "Upload Kit"; btn.disabled = false; });
    });

    // --- MODAL LOGIC ---
    const modal = document.getElementById('upload-modal');
    function toggleModal(show) { 
        modal.classList.toggle('invisible', !show); 
        modal.classList.toggle('opacity-0', !show);
        if(!show) document.getElementById('upload-form').reset();
    }
    document.getElementById('open-upload-modal').onclick = () => toggleModal(true);
    document.getElementById('close-modal-btn').onclick = () => toggleModal(false);
    document.getElementById('modal-backdrop').onclick = () => toggleModal(false);

    // --- LOAD KITS (FIXED) ---
    function loadKits() {
        console.log("Fetching kits...");
        fetch('../includes/get_kits.php')
        .then(r => r.json())
        .then(data => {
            console.log("Data received:", data);
            const grid = document.getElementById('kits-grid');
            
            if(!data.success || !data.kits || data.kits.length === 0) {
                grid.innerHTML = '<div class="col-span-full text-center text-gray-500 py-10">No kits found. Start uploading!</div>';
                return;
            }
            
            let html = '';
            data.kits.forEach(kit => {
                // Fix path if needed
                let imgPath = kit.cover_image;
                if (!imgPath.startsWith('../') && !imgPath.startsWith('http')) {
                    imgPath = '../' + imgPath;
                }

                html += `
                <div class="glass-panel rounded-xl overflow-hidden group">
                    <div class="relative h-48 w-full overflow-hidden">
                        <img src="${imgPath}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button onclick="deleteKit(${kit.id})" class="p-2 rounded-full bg-red-500 text-white hover:scale-110 transition-transform"><span class="material-symbols-outlined">delete</span></button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-white text-lg truncate">${kit.title}</h4>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-primary font-bold">$${parseFloat(kit.price).toFixed(2)}</span>
                            <span class="text-xs text-gray-400">${new Date(kit.created_at).toLocaleDateString()}</span>
                        </div>
                    </div>
                </div>`;
            });
            grid.innerHTML = html;
        })
        .catch(err => {
            console.error("Fetch Error:", err);
            document.getElementById('kits-grid').innerHTML = '<div class="col-span-full text-center text-red-500 py-10">Error loading kits. Check console.</div>';
        });
    }

    function deleteKit(id) {
        if(!confirm("Delete this kit?")) return;
        fetch('../includes/delete_kit.php', { 
            method: 'POST', 
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id}) 
        }).then(() => loadKits());
    }

    // Initial Load
    loadKits();
</script>
</body>
</html>