document.addEventListener('DOMContentLoaded', () => {
    // Floating Background Cards Logic (Diagonal Infinite Scroll)
    const bgCards = document.querySelectorAll('.floating-bg-card');
    if (bgCards.length > 0) {
        const totalDuration = 50; // Super slow animation (50 seconds per cycle)
        
        // Pick a random animation path and direction for this page load
        const animations = ['diagonalScroll1', 'diagonalScroll2'];
        const directions = ['normal', 'reverse'];
        const chosenAnim = animations[Math.floor(Math.random() * animations.length)];
        const chosenDir = directions[Math.floor(Math.random() * directions.length)];
        
        bgCards.forEach((card, index) => {
            // Create a track offset so they don't all follow the exact same line
            // This spreads them across 3 different parallel tracks (-35vw, 0vw, +35vw)
            const trackOffset = (index % 3) * 35 - 35; 
            
            // Negative delay ensures they are already spread out on the screen when the page loads
            const delay = -1 * (index / bgCards.length) * totalDuration;

            // Pass the track offset to CSS via custom property
            card.style.setProperty('--track-x', `${trackOffset}vw`);
            
            // Apply the randomized diagonal scroll animation
            card.style.animation = `${chosenAnim} ${totalDuration}s linear ${delay}s infinite ${chosenDir}`;
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
