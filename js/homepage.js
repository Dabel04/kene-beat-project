document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. PREVENT CRASHES: Check if elements exist before using them ---
    const audioPlayer = document.querySelector('.audio-player');
    
    // Only run player logic if the player exists on this page
    if (audioPlayer) {
        const playPauseBtn = document.querySelector('.play-pause-btn');
        const prevBtn = document.querySelector('.previous-btn');
        const nextBtn = document.querySelector('.next-btn');
        const trackImage = document.querySelector('.player-track-image img');
        const trackTitle = document.querySelector('.player-track-title');
        const trackArtist = document.querySelector('.player-track-artist');
        const progressBar = document.querySelector('.progress');
        const currentTimeEl = document.querySelector('.current-time');
        const durationEl = document.querySelector('.duration');
        const progressContainer = document.querySelector('.progress-bar');
        
        let currentTrackIndex = 0;
        let isPlaying = false;
        let audio = new Audio();

        // Track Data (Static for Homepage)
        const tracks = [
            { id: 1, title: "Midnight Vibes", artist: "KentonTheProducer", duration: "3:45", audio: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3", image: "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=500&q=80" },
            { id: 2, title: "Urban Rhythm", artist: "KentonTheProducer", duration: "4:12", audio: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3", image: "https://images.unsplash.com/photo-1571330735066-03aaa9429d89?w=500&q=80" }
        ];

        function loadTrack(index) {
            currentTrackIndex = index;
            const track = tracks[index];
            if(!track) return;

            audio.src = track.audio;
            if(trackImage) trackImage.src = track.image;
            if(trackTitle) trackTitle.textContent = track.title;
            if(trackArtist) trackArtist.textContent = track.artist;
            if(durationEl) durationEl.textContent = track.duration;
            
            audio.load();
            audioPlayer.style.display = 'flex';
        }

        function playTrack() {
            audio.play().catch(e => console.error(e));
            isPlaying = true;
            if(playPauseBtn) playPauseBtn.innerHTML = '<i class="fa fa-pause"></i>';
        }

        function pauseTrack() {
            audio.pause();
            isPlaying = false;
            if(playPauseBtn) playPauseBtn.innerHTML = '<i class="fa fa-play"></i>';
        }

        if(playPauseBtn) {
            playPauseBtn.addEventListener('click', () => {
                isPlaying ? pauseTrack() : playTrack();
            });
        }

        if(nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentTrackIndex = (currentTrackIndex + 1) % tracks.length;
                loadTrack(currentTrackIndex);
                if(isPlaying) playTrack();
            });
        }

        if(prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentTrackIndex = (currentTrackIndex - 1 + tracks.length) % tracks.length;
                loadTrack(currentTrackIndex);
                if(isPlaying) playTrack();
            });
        }
        
        // Progress Bar
        audio.addEventListener('timeupdate', () => {
            if(progressBar) {
                const percent = (audio.currentTime / audio.duration) * 100;
                progressBar.style.width = `${percent}%`;
            }
            if(currentTimeEl) {
                let min = Math.floor(audio.currentTime / 60);
                let sec = Math.floor(audio.currentTime % 60);
                if(sec < 10) sec = '0' + sec;
                currentTimeEl.textContent = `${min}:${sec}`;
            }
        });
    }

    // --- 2. CARD PLAY BUTTONS ---
    const playButtons = document.querySelectorAll('.btn-play');
    playButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            // Your inline play logic handles this mostly, 
            // but we add this to prevent conflicting errors.
            const card = this.closest('.card');
            if(card && card.classList.contains('playing')) {
                 this.innerHTML = '<i class="fa fa-play"></i>';
                 card.classList.remove('playing');
                 // Pause logic here if needed
            } else if (card) {
                 this.innerHTML = '<i class="fa fa-pause"></i>';
                 card.classList.add('playing');
            }
        });
    });

    // --- 3. DUMMY BUY BUTTON (Do not let this interfere with Kits) ---
    // Only apply this alert to buttons that are NOT kit buttons
    const buyButtons = document.querySelectorAll('.btn-buy:not(.btn-buy-kit)');
    buyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.card');
            const title = card ? card.querySelector('h5').textContent : 'Item';
            alert(`Added "${title}" to your cart!`);
        });
    });
});