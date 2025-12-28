document.addEventListener('DOMContentLoaded', function() {
// Audio player functionality
const playButtons = document.querySelectorAll('.btn-play');
let currentlyPlaying = null;

playButtons.forEach(button => {
button.addEventListener('click', function() {
    const card = this.closest('.card');
    const audio = card.querySelector('audio');
    const visualizer = card.querySelector('.music-visualizer');
    
    // If this audio is already playing, pause it
    if (card.classList.contains('playing')) {
    audio.pause();
    card.classList.remove('playing');
    this.innerHTML = '<i class="fa fa-play"></i>';
    currentlyPlaying = null;
    return;
    }
    
    // Pause any currently playing audio
    if (currentlyPlaying && currentlyPlaying !== audio) {
    currentlyPlaying.pause();
    const playingCard = currentlyPlaying.closest('.card');
    playingCard.classList.remove('playing');
    playingCard.querySelector('.btn-play').innerHTML = '<i class="fa fa-play"></i>';
    }
    
    // Play this audio
    audio.play();
    card.classList.add('playing');
    this.innerHTML = '<i class="fa fa-pause"></i>';
    currentlyPlaying = audio;
    
    // When audio ends
    audio.onended = function() {
    card.classList.remove('playing');
    button.innerHTML = '<i class="fa fa-play"></i>';
    currentlyPlaying = null;
    };
});
});

// Buy button functionality
const buyButtons = document.querySelectorAll('.btn-buy');
buyButtons.forEach(button => {
button.addEventListener('click', function() {
    const card = this.closest('.card');
    const title = card.querySelector('h5').textContent;
    alert(`Added "${title}" to your cart!`);
});
});
});

document.addEventListener('DOMContentLoaded', function() {
    // Track data
    const tracks = [
        {
            id: 1,
            title: "Midnight Vibes",
            artist: "KentonTheProducer",
            image: "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80",
            audio: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3",
            duration: "3:45"
        },
        {
            id: 2,
            title: "Urban Rhythm",
            artist: "KentonTheProducer",
            image: "https://images.unsplash.com/photo-1571330735066-03aaa9429d89?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1720&q=80",
            audio: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3",
            duration: "4:12"
        },
        {
            id: 3,
            title: "Deep Resonance",
            artist: "KentonTheProducer",
            image: "https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80",
            audio: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3",
            duration: "3:18"
        },
        {
            id: 4,
            title: "Echoes",
            artist: "KentonTheProducer",
            image: "https://images.unsplash.com/photo-1511735111819-9a3f7709049c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1674&q=80",
            audio: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3",
            duration: "4:32"
        },
        {
            id: 5,
            title: "Neon Dreams",
            artist: "KentonTheProducer",
            image: "https://images.unsplash.com/photo-1459749411175-04bf5292ceea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80",
            audio: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-5.mp3",
            duration: "3:56"
        },
        {
            id: 6,
            title: "Bass Theory",
            artist: "KentonTheProducer",
            image: "https://images.unsplash.com/photo-1511379938547-c1f69419868d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80",
            audio: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-6.mp3",
            duration: "3:24"
        }
    ];

    // Audio player functionality
    const audioPlayer = document.querySelector('.audio-player');
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
    const volumeBtn = document.querySelector('.volume-btn');
    const volumeLevel = document.querySelector('.volume-level');
    
    let currentTrack = null;
    let audio = new Audio();
    let isPlaying = false;
    let currentTrackIndex = 0;

    // Format time
    function formatTime(seconds) {
        let min = Math.floor(seconds / 60);
        let sec = Math.floor(seconds % 60);
        sec = sec < 10 ? `0${sec}` : sec;
        return `${min}:${sec}`;
    }

    // Load track
    function loadTrack(index) {
        currentTrackIndex = index;
        const track = tracks[index];
        
        audio.src = track.audio;
        trackImage.src = track.image;
        trackTitle.textContent = track.title;
        trackArtist.textContent = track.artist;
        durationEl.textContent = track.duration;
        
        audio.load();
        
        audio.addEventListener('loadedmetadata', () => {
            durationEl.textContent = formatTime(audio.duration);
        });
        
        audioPlayer.style.display = 'flex';
    }

    // Play track
    function playTrack() {
        audio.play();
        isPlaying = true;
        playPauseBtn.innerHTML = '<i class="fa fa-pause"></i>';
    }

    // Pause track
    function pauseTrack() {
        audio.pause();
        isPlaying = false;
        playPauseBtn.innerHTML = '<i class="fa fa-play"></i>';
    }

    // Previous track
    function prevTrack() {
        currentTrackIndex--;
        if (currentTrackIndex < 0) {
            currentTrackIndex = tracks.length - 1;
        }
        loadTrack(currentTrackIndex);
        if (isPlaying) playTrack();
    }

    // Next track
    function nextTrack() {
        currentTrackIndex++;
        if (currentTrackIndex > tracks.length - 1) {
            currentTrackIndex = 0;
        }
        loadTrack(currentTrackIndex);
        if (isPlaying) playTrack();
    }

    // Update progress bar
    function updateProgress() {
        const { currentTime, duration } = audio;
        const progressPercent = (currentTime / duration) * 100;
        progressBar.style.width = `${progressPercent}%`;
        currentTimeEl.textContent = formatTime(currentTime);
    }

    // Set progress
    function setProgress(e) {
        const width = this.clientWidth;
        const clickX = e.offsetX;
        const duration = audio.duration;
        audio.currentTime = (clickX / width) * duration;
    }

    // Event listeners
    playPauseBtn.addEventListener('click', () => {
        if (isPlaying) {
            pauseTrack();
        } else {
            playTrack();
        }
    });

    prevBtn.addEventListener('click', prevTrack);
    nextBtn.addEventListener('click', nextTrack);
    
    audio.addEventListener('timeupdate', updateProgress);
    audio.addEventListener('ended', nextTrack);
    
    progressContainer.addEventListener('click', setProgress);

    // Play button clicks on track cards
    document.querySelectorAll('.play-button').forEach(button => {
        button.addEventListener('click', function() {
            const trackId = parseInt(this.getAttribute('data-track'));
            const trackIndex = tracks.findIndex(track => track.id === trackId);
            
            if (currentTrackIndex !== trackIndex) {
                loadTrack(trackIndex);
            }
            
            playTrack();
        });
    });
});