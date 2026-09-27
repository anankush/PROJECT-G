document.addEventListener('DOMContentLoaded', () => {
    // Seamless Infinite Diagonal Marquee Logic
    const bgContainer = document.querySelector('.floating-bg-container');
    const bgTracks = document.querySelectorAll('.bg-marquee-track');
    
    if (bgContainer && bgTracks.length > 0) {
        // Randomize the slant angle (e.g., -30deg for bottom-left to top-right, 30deg for top-left to bottom-right)
        const angles = [-35, 35, -25, 25];
        const randomAngle = angles[Math.floor(Math.random() * angles.length)];
        
        // Pick a base direction
        const directions = ['normal', 'reverse'];
        const baseDir = directions[Math.floor(Math.random() * directions.length)];
        
        // Apply rotation to the container
        bgContainer.style.transform = `rotate(${randomAngle}deg)`;
        
        // Apply alternating directions to the tracks
        bgTracks.forEach((track, index) => {
            if (index % 2 === 0) {
                // Track 0 and 2 (Top and Bottom) go the base direction
                track.style.animationDirection = baseDir;
            } else {
                // Track 1 (Middle) goes the opposite direction
                track.style.animationDirection = (baseDir === 'normal') ? 'reverse' : 'normal';
            }
        });
    }

    // Scroll Reveal Animation using Intersection Observer
    const revealElements = document.querySelectorAll('.reveal');

    const revealOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealOnScroll = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                return;
            } else {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, revealOptions);

    revealElements.forEach(el => {
        revealOnScroll.observe(el);
    });

    // Simple sticky header background change
    const header = document.querySelector('.header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        } else {
            header.style.boxShadow = 'none';
        }
    });

    // Mobile Menu Toggle
    const mobileBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');
    if (mobileBtn && navLinks) {
        mobileBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
        
        // Close menu when clicking a link
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
            });
        });
    }
});
