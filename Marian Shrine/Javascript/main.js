// Marian Shrine General JavaScript

document.addEventListener('DOMContentLoaded', function () {
    console.log("Marian Shrine JS Loaded");

    // --- DOM Elements ---
    const galleryContainer = document.getElementById('gallery-container');
    const videoContainer = document.getElementById('video-container');
    const navbar = document.querySelector('.navbar');

    // --- Data Fetching & Rendering ---

    function loadGallery() {
        if (!galleryContainer) return;

        fetch('api/gallery.php')
            .then(response => response.json())
            .then(data => {
                galleryContainer.innerHTML = '';
                if (data.length === 0) {
                    galleryContainer.innerHTML = '<p class="text-center w-100">No images found.</p>';
                    return;
                }

                data.forEach(img => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 col-sm-6 mb-4';
                    col.innerHTML = `
                        <div class="gallery-item">
                            <img src="${img.url}" class="gallery-img" alt="${img.title}" onerror="this.src='https://via.placeholder.com/400x300?text=Image+Error'">
                            <div class="gallery-overlay">
                                <h5>${img.title}</h5>
                                ${img.date ? `<small>${formatDate(img.date)}</small>` : ''}
                            </div>
                        </div>
                    `;
                    galleryContainer.appendChild(col);
                });
            })
            .catch(error => console.error('Error loading gallery:', error));
    }

    function loadVideos() {
        if (!videoContainer) return;

        fetch('api/videos.php')
            .then(response => response.json())
            .then(data => {
                videoContainer.innerHTML = '';
                if (data.length === 0) {
                    videoContainer.innerHTML = '<p class="text-center w-100">No videos found.</p>';
                    return;
                }

                data.forEach(vid => {
                    const col = document.createElement('div');
                    col.className = 'col-lg-6 mb-4';
                    col.innerHTML = `
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="ratio ratio-16x9">
                                    <iframe src="${vid.url}" 
                                            title="video player" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen></iframe>
                                </div>
                                <div class="p-3">
                                    <h5>${vid.title}</h5>
                                    <p class="text-muted">${formatDate(vid.date)}</p>
                                    ${vid.description ? `<p class="small">${vid.description}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    videoContainer.appendChild(col);
                });
            })
            .catch(error => console.error('Error loading videos:', error));
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }

    // --- UI Effects ---

    // Navbar Scroll Effect
    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            navbar.classList.add('shadow');
            navbar.style.padding = '10px 0';
        } else {
            navbar.classList.remove('shadow');
            navbar.style.padding = '15px 0';
        }
    });

    // Initial Load
    loadGallery();
    loadVideos();

    // Active Link Highlighting (Scroll Spy)
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight; // Unused but part of standard spy logic
            if (pageYOffset >= (sectionTop - 200)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').includes(current)) {
                link.classList.add('active');
            }
        });
    });
});
