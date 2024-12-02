
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>


    
<div class="player-header">
        <div class="cover-container" id="main-cover">
            <img src="cover.jpg" alt="Album Cover" id="cover">
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

    <div id="fullscreen-player" class="fullscreen-player">
        <div class="fullscreen-content">
            <span class="close-fullscreen">&times;</span>
           
            <div class="player-fullscreen" id="fullscreen-player-content">
                <img src="cover.jpg" alt="Album Cover" id="fullscreen-cover">
                <div class="player-controls">
                    <i class="fa-solid fa-backward" title="Précédent" id="fullscreen-prev"></i>
                    <i class="fa-solid fa-play play-button" title="Lecture" id="fullscreen-play"></i>
                    <i class="fa-solid fa-forward" title="Suivant" id="fullscreen-next"></i>
                </div>
                <div class="fullscreen-progress-container">
                    <div class="fullscreen-progress-bar" id="fullscreen-progress"></div>
                </div>
                <div class="time-display">
                    <span id="fullscreen-current-time">0:00</span> / <span id="fullscreen-duration">0:00</span>
                </div>
                <div class="volume-control">
                    <i class="fa-solid fa-volume-high" id="fullscreen-volume-icon"></i>
                    <input type="range" id="fullscreen-volume-slider" min="0" max="1" step="0.01" value="1">
                </div>
            </div>
        </div>
    </div>

    <script>
        
        const audio = new Audio();

      
        const cover = document.getElementById('cover');
        const progressBar = document.getElementById('progress');
        const progressContainer = document.getElementById('progress-container');
        const currentTimeEl = document.getElementById('current-time');
        const durationEl = document.getElementById('duration');
        const playBtn = document.getElementById('play');
        const prevBtn = document.getElementById('prev');
        const nextBtn = document.getElementById('next');
        const volumeSlider = document.getElementById('volume-slider');
        const volumeIcon = document.getElementById('volume-icon');
        const mainCoverContainer = document.getElementById('main-cover');

       
        const fullscreenPlayer = document.getElementById('fullscreen-player');
        const closeFullscreenBtn = document.querySelector('.close-fullscreen');
        const fullscreenCover = document.getElementById('fullscreen-cover');
        const fullscreenPlayBtn = document.getElementById('fullscreen-play');
        const fullscreenPrevBtn = document.getElementById('fullscreen-prev');
        const fullscreenNextBtn = document.getElementById('fullscreen-next');
        const fullscreenProgressBar = document.getElementById('fullscreen-progress');
        const fullscreenProgressContainer = document.querySelector('.fullscreen-progress-container');
        const fullscreenCurrentTimeEl = document.getElementById('fullscreen-current-time');
        const fullscreenDurationEl = document.getElementById('fullscreen-duration');
        const fullscreenVolumeSlider = document.getElementById('fullscreen-volume-slider');
        const fullscreenVolumeIcon = document.getElementById('fullscreen-volume-icon');
        const fullscreenPlayerContent = document.getElementById('fullscreen-player-content');

        let isPlaying = false;

       
        const songs = [
            {
                src: "../public/Bahia.mp3",
                cover: "../public/cover.jpg",
            },
            {
                src: "../public/Nights.mp3",
                cover: "../public/cover2.jpg",
            }
           
        ];

        let currentIndex = 0;

        
        const loadSong = (index) => {
            const song = songs[index];
            audio.src = song.src;
            cover.src = song.cover;
            fullscreenCover.src = song.cover; 
        };

        // Formater le temps en minutes:secondes
        const formatTime = (time) => {
            const minutes = Math.floor(time / 60);
            const seconds = Math.floor(time % 60).toString().padStart(2, '0');
            return `${minutes}:${seconds}`;
        };

        // Mettre à jour l'icône de volume dans le lecteur principal
        const updateVolumeIcon = () => {
            if (audio.muted || audio.volume === 0) {
                volumeIcon.classList.remove('fa-volume-high', 'fa-volume-low');
                volumeIcon.classList.add('fa-volume-xmark');
            } else if (audio.volume < 0.5) {
                volumeIcon.classList.remove('fa-volume-high', 'fa-volume-xmark');
                volumeIcon.classList.add('fa-volume-low');
            } else {
                volumeIcon.classList.remove('fa-volume-low', 'fa-volume-xmark');
                volumeIcon.classList.add('fa-volume-high');
            }
        };

      
        const updateVolumeIconFullscreen = () => {
            if (audio.muted || audio.volume === 0) {
                fullscreenVolumeIcon.classList.remove('fa-volume-high', 'fa-volume-low');
                fullscreenVolumeIcon.classList.add('fa-volume-xmark');
            } else if (audio.volume < 0.5) {
                fullscreenVolumeIcon.classList.remove('fa-volume-high', 'fa-volume-xmark');
                fullscreenVolumeIcon.classList.add('fa-volume-low');
            } else {
                fullscreenVolumeIcon.classList.remove('fa-volume-low', 'fa-volume-xmark');
                fullscreenVolumeIcon.classList.add('fa-volume-high');
            }
        };

    
        const setVolumeSliderBackground = (value) => {
            const percentage = value * 100 + '%';
            volumeSlider.style.setProperty('--volume-level', percentage);
        };

        
        const setVolumeSliderBackgroundFullscreen = (value) => {
            const percentage = value * 100 + '%';
            fullscreenVolumeSlider.style.setProperty('--volume-level', percentage);
        };

        
        audio.addEventListener('timeupdate', () => {
            const currentTime = audio.currentTime;
            const duration = audio.duration || 0;

           
            currentTimeEl.textContent = formatTime(currentTime);
            durationEl.textContent = formatTime(duration);

            
            const progressPercent = (currentTime / duration) * 100;
            progressBar.style.width = `${progressPercent}%`;

            
            fullscreenCurrentTimeEl.textContent = formatTime(currentTime);
            fullscreenDurationEl.textContent = formatTime(duration);

            
            fullscreenProgressBar.style.width = `${progressPercent}%`;
        });

        
        progressContainer.addEventListener('click', (e) => {
            const width = progressContainer.offsetWidth;
            const clickX = e.offsetX;
            const duration = audio.duration;

            audio.currentTime = (clickX / width) * duration;
        });

        
        fullscreenProgressContainer.addEventListener('click', (e) => {
            const width = fullscreenProgressContainer.offsetWidth;
            const clickX = e.offsetX;
            const duration = audio.duration;

            audio.currentTime = (clickX / width) * duration;
        });

        
        playBtn.addEventListener('click', () => {
            if (isPlaying) {
                audio.pause();
            } else {
                audio.play();
            }
        });

        
        fullscreenPlayBtn.addEventListener('click', () => {
            if (isPlaying) {
                audio.pause();
            } else {
                audio.play();
            }
        });

        
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + songs.length) % songs.length;
            loadSong(currentIndex);
            audio.play();
        });

        
        fullscreenPrevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + songs.length) % songs.length;
            loadSong(currentIndex);
            audio.play();
        });

        
        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % songs.length;
            loadSong(currentIndex);
            audio.play();
        });

        
        fullscreenNextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % songs.length;
            loadSong(currentIndex);
            audio.play();
        });

        
        mainCoverContainer.addEventListener('click', () => {
            openFullscreenPlayer();
        });

        
        const openFullscreenPlayer = () => {
            fullscreenPlayer.style.display = 'block';
        
            fullscreenPlayerContent.classList.add('playing');
           
            if (isPlaying) {
                fullscreenPlayerContent.classList.add('playing');
            } else {
                fullscreenPlayerContent.classList.remove('playing');
            }
        };

        
        const closeFullscreenPlayer = () => {
            fullscreenPlayer.style.display = 'none';
        };

        
        closeFullscreenBtn.addEventListener('click', closeFullscreenPlayer);

        
        window.addEventListener('click', (event) => {
            if (event.target == fullscreenPlayer) {
                closeFullscreenPlayer();
            }
        });

        
        volumeSlider.addEventListener('input', (e) => {
            audio.volume = e.target.value;
            updateVolumeIcon();
            setVolumeSliderBackground(e.target.value);
            
            fullscreenVolumeSlider.value = e.target.value;
            setVolumeSliderBackgroundFullscreen(e.target.value);
            updateVolumeIconFullscreen();
        });

        
        fullscreenVolumeSlider.addEventListener('input', (e) => {
            audio.volume = e.target.value;
            updateVolumeIcon();
            setVolumeSliderBackground(e.target.value);
            
            volumeSlider.value = e.target.value;
            setVolumeSliderBackground(e.target.value);
            updateVolumeIconFullscreen();
        });

        
        volumeIcon.addEventListener('click', () => {
            audio.muted = !audio.muted;
            updateVolumeIcon();
            if (audio.muted) {
                setVolumeSliderBackground(0);
                fullscreenVolumeSlider.value = 0;
                setVolumeSliderBackgroundFullscreen(0);
                updateVolumeIconFullscreen();
            } else {
                setVolumeSliderBackground(volumeSlider.value);
                fullscreenVolumeSlider.value = volumeSlider.value;
                setVolumeSliderBackgroundFullscreen(volumeSlider.value);
                updateVolumeIconFullscreen();
            }
        });

        
        fullscreenVolumeIcon.addEventListener('click', () => {
            audio.muted = !audio.muted;
            updateVolumeIconFullscreen();
            if (audio.muted) {
                setVolumeSliderBackground(0);
                volumeSlider.value = 0;
                setVolumeSliderBackgroundFullscreen(0);
                updateVolumeIcon();
            } else {
                setVolumeSliderBackground(volumeSlider.value);
                fullscreenVolumeSlider.value = volumeSlider.value;
                setVolumeSliderBackgroundFullscreen(volumeSlider.value);
                updateVolumeIcon();
            }
        });

        
        updateVolumeIcon();
        updateVolumeIconFullscreen();

       
        loadSong(currentIndex);

        
        audio.addEventListener('play', () => {
            playBtn.classList.replace('fa-play', 'fa-pause');
            fullscreenPlayBtn.classList.replace('fa-play', 'fa-pause');
            isPlaying = true;
           
            fullscreenPlayerContent.classList.add('playing');
        });

        audio.addEventListener('pause', () => {
            playBtn.classList.replace('fa-pause', 'fa-play');
            fullscreenPlayBtn.classList.replace('fa-pause', 'fa-play');
           
            fullscreenPlayerContent.classList.remove('playing');
        });
    </script>

