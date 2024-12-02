
<head>
    <meta charset="UTF-8">
    <title>Lecteur Audio avec Contrôle du Volume Personnalisé</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Ubuntu', sans-serif;
            background-color: #121212;
            color: #fff;
        }

        .player-header {
            position: fixed;
            bottom: 0;
            left: 250px;
            width: calc(100% - 250px);
            background-color: rgba(28, 28, 28, 0.9);
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .cover-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            margin-right: 20px;
            overflow: hidden;
            border-radius: 8px;
        }

        .cover-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .player-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .player-controls i {
            font-size: 20px;
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .player-controls i:hover {
            color: #e50914;
        }

        .progress-container {
            flex: 1;
            display: flex;
            align-items: center;
            margin-left: 20px;
            height: 5px;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            position: relative;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background-color: #e50914;
            transition: width 0.1s linear;
        }

        .time-display {
            font-size: 12px;
            color: #ccc;
            margin-left: 20px;
            user-select: none;
        }

        .volume-control {
            display: flex;
            align-items: center;
            margin-left: 20px;
        }

        .volume-control i {
            font-size: 20px;
            color: #fff;
            cursor: pointer;
            margin-right: 10px;
            transition: color 0.3s ease;
        }

        .volume-control i:hover {
            color: #e50914;
        }

        .volume-control input[type="range"] {
            width: 100px;
            appearance: none;
            background: transparent;
            cursor: pointer;
            margin: 0;
            padding: 0;
        }

        .volume-control input[type="range"]::-webkit-slider-runnable-track {
            width: 100%;
            height: 5px;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
        }

        .volume-control input[type="range"]::-moz-range-track {
            width: 100%;
            height: 5px;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
        }

        .volume-control input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 12px;
            width: 12px;
            background: #e50914;
            border-radius: 50%;
            cursor: pointer;
            margin-top: -4px;
        }

        .volume-control input[type="range"]::-moz-range-thumb {
            height: 12px;
            width: 12px;
            background: #e50914;
            border-radius: 50%;
            cursor: pointer;
        }

        .volume-control input[type="range"]::-ms-thumb {
            height: 12px;
            width: 12px;
            background: #e50914;
            border-radius: 50%;
            cursor: pointer;
        }

        .volume-control input[type="range"]:focus {
            outline: none;
        }

        .volume-control input[type="range"]::-webkit-slider-runnable-track {
            background: linear-gradient(to right, #e50914 0%, #e50914 var(--volume-level), #fff var(--volume-level), #fff 100%);
        }

        .volume-control input[type="range"]::-moz-range-track {
            background: linear-gradient(to right, #e50914 0%, #e50914 var(--volume-level), #fff var(--volume-level), #fff 100%);
        }
    </style>
</head>
<body>

    <div class="player-header">
        <div class="cover-container">
            <img src="cover.jpg" alt="Album Cover" id="cover">
        </div>

        <div class="player-controls">
            <i class="fa-solid fa-backward" title="Précédent" id="prev"></i>
            <i class="fa-solid fa-play play-button" title="Lecture" id="play"></i>
            <i class="fa-solid fa-forward" title="Suivant" id="next"></i>
        </div>

        <div class="progress-container">
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
        const audio = new Audio();

        const cover = document.getElementById('cover');
        const progressBar = document.getElementById('progress');
        const currentTimeEl = document.getElementById('current-time');
        const durationEl = document.getElementById('duration');
        const playBtn = document.getElementById('play');

        const volumeSlider = document.getElementById('volume-slider');
        const volumeIcon = document.getElementById('volume-icon');

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
        };

        const formatTime = (time) => {
            const minutes = Math.floor(time / 60);
            const seconds = Math.floor(time % 60).toString().padStart(2, '0');
            return `${minutes}:${seconds}`;
        };

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

        const setVolumeSliderBackground = (value) => {
            const percentage = value * 100 + '%';
            volumeSlider.style.setProperty('--volume-level', percentage);
        };

        audio.addEventListener('timeupdate', () => {
            const currentTime = audio.currentTime;
            const duration = audio.duration || 0;

            currentTimeEl.textContent = formatTime(currentTime);
            durationEl.textContent = formatTime(duration);

            const progressPercent = (currentTime / duration) * 100;
            progressBar.style.width = `${progressPercent}%`;
        });

        document.querySelector('.progress-container').addEventListener('click', (e) => {
            const width = e.currentTarget.offsetWidth;
            const clickX = e.offsetX;
            const duration = audio.duration;

            audio.currentTime = (clickX / width) * duration;
        });

        playBtn.addEventListener('click', () => {
            if (isPlaying) {
                audio.pause();
                playBtn.classList.replace('fa-pause', 'fa-play');
            } else {
                audio.play();
                playBtn.classList.replace('fa-play', 'fa-pause');
            }
            isPlaying = !isPlaying;
        });

        document.getElementById('prev').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + songs.length) % songs.length;
            loadSong(currentIndex);
            audio.play();
            playBtn.classList.replace('fa-play', 'fa-pause');
            isPlaying = true;
        });

        document.getElementById('next').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % songs.length;
            loadSong(currentIndex);
            audio.play();
            playBtn.classList.replace('fa-play', 'fa-pause');
            isPlaying = true;
        });

        volumeSlider.addEventListener('input', (e) => {
            audio.volume = e.target.value;
            updateVolumeIcon();
            setVolumeSliderBackground(e.target.value);
        });

        volumeIcon.addEventListener('click', () => {
            audio.muted = !audio.muted;
            updateVolumeIcon();
            if (audio.muted) {
                setVolumeSliderBackground(0);
            } else {
                setVolumeSliderBackground(volumeSlider.value);
            }
        });

        audio.volume = volumeSlider.value;
        setVolumeSliderBackground(volumeSlider.value);
        loadSong(currentIndex);
    </script>

</body>
