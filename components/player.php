<div class="player-header">
    <div class="cover-container" id="main-cover">
        <img src="/public/img/default-song.jpg" alt="Album Cover" id="cover">
    </div>

    <div class="player-controls">
        <i class="fa-solid fa-backward" title="Précédent" id="prev"></i>
        <i class="fa-solid fa-play play-button" title="Lecture" id="play"></i>
        <i class="fa-solid fa-forward" title="Suivant" id="next"></i>
    </div>

    <div class="progress-container" id="progress-container">
        <div class="progress-bar" id="progress"></div>
    </div>

    <div class="time-display">
        <span id="current-time">0:00</span> / <span id="duration">0:00</span>
    </div>

    <div class="volume-control">
        <i class="fa-solid fa-volume-high" id="volume-icon"></i>
        <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="1">
    </div>
</div>

<script>
    let songs = [];
    const audio = new Audio();
    const playButton = document.getElementById('play');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const cover = document.getElementById('cover');
    const progressBar = document.getElementById('progress');
    const currentTimeEl = document.getElementById('current-time');
    const durationEl = document.getElementById('duration');

    let isPlaying = false;
    let currentSongIndex = 0;

    function savePlayerState() {
        localStorage.setItem('playerState', JSON.stringify({
            currentSongIndex,
            currentTime: audio.currentTime,
            isPlaying
        }));
    }

    function loadPlayerState() {
        const state = JSON.parse(localStorage.getItem('playerState'));
        if (state && songs.length > 0) {
            currentSongIndex = state.currentSongIndex;
            loadSong(songs[currentSongIndex]);
            audio.currentTime = state.currentTime;
            if (state.isPlaying) {
                audio.play().catch(e => console.error("Erreur lors de la reprise de la lecture:", e));
            }
        }
    }

    const loadSong = (song, autoplay = false) => {
        if (!song) return;
        
        audio.src = song.src;
        cover.src = song.cover || "/public/img/default-song.jpg";
        document.querySelector('.player-header .time-display').innerText = `${song.title} - ${song.artist}`;
        
        if (autoplay) {
            audio.play().catch(error => {
                console.error('Erreur lors de la lecture:', error);
            });
        }
        savePlayerState();
    };

    const togglePlay = () => {
        if (isPlaying) {
            audio.pause();
        } else {
            audio.play().catch(error => {
                console.error('Erreur lors de la lecture:', error);
            });
        }
    };

    audio.addEventListener('play', () => {
        isPlaying = true;
        playButton.classList.replace('fa-play', 'fa-pause');
        updateParentPage();
        savePlayerState();
    });

    audio.addEventListener('pause', () => {
        isPlaying = false;
        playButton.classList.replace('fa-pause', 'fa-play');
        updateParentPage();
        savePlayerState();
    });

    audio.addEventListener('timeupdate', () => {
        if (audio.duration) {
            const progressPercent = (audio.currentTime / audio.duration) * 100;
            progressBar.style.width = `${progressPercent}%`;
            currentTimeEl.textContent = formatTime(audio.currentTime);
            durationEl.textContent = formatTime(audio.duration);
            updateParentPage();
            savePlayerState();
        }
    });

    const formatTime = (time) => {
        const minutes = Math.floor(time / 60);
        const seconds = Math.floor(time % 60).toString().padStart(2, '0');
        return `${minutes}:${seconds}`;
    };

    prevButton.addEventListener('click', () => {
        currentSongIndex = (currentSongIndex - 1 + songs.length) % songs.length;
        loadSong(songs[currentSongIndex], true);
    });

    nextButton.addEventListener('click', () => {
        currentSongIndex = (currentSongIndex + 1) % songs.length;
        loadSong(songs[currentSongIndex], true);
    });

    document.getElementById('progress-container').addEventListener('click', (e) => {
        const width = e.target.offsetWidth;
        const clickX = e.offsetX;
        const duration = audio.duration;
        audio.currentTime = (clickX / width) * duration;
    });

    const volumeSlider = document.getElementById('volume-slider');
    volumeSlider.addEventListener('input', (e) => {
        audio.volume = e.target.value;
        localStorage.setItem('playerVolume', e.target.value);
    });

    playButton.addEventListener('click', togglePlay);

    window.addEventListener('message', function(event) {
        if (event.data.type === 'LOAD_SONGS') {
            songs = event.data.songs;
            if (songs.length > 0) {
                loadPlayerState();
            }
        } else if (event.data.type === 'PLAY_SONG') {
            const songIndex = event.data.songIndex;
            if (songs[songIndex]) {
                currentSongIndex = songIndex;
                loadSong(songs[currentSongIndex], true);
            }
        } else if (event.data.type === 'RESTORE_STATE') {
            const state = event.data.state;
            if (state && songs.length > 0) {
                currentSongIndex = state.currentSongIndex;
                loadSong(songs[currentSongIndex]);
                audio.currentTime = state.currentTime;
                if (state.isPlaying) {
                    audio.play().catch(e => console.error("Erreur lors de la reprise de la lecture:", e));
                }
            }
        }
    });

    function updateParentPage() {
        window.parent.postMessage({
            type: 'PLAYER_STATE',
            isPlaying: isPlaying,
            currentSongIndex: currentSongIndex,
            currentTime: audio.currentTime,
            duration: audio.duration
        }, '*');
    }

    // Restaurer le volume
    const savedVolume = localStorage.getItem('playerVolume');
    if (savedVolume !== null) {
        audio.volume = parseFloat(savedVolume);
        volumeSlider.value = savedVolume;
    }

    // Sauvegarder l'état avant de quitter la page
    window.addEventListener('beforeunload', savePlayerState);
</script>