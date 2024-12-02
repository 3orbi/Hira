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