document.addEventListener('DOMContentLoaded', () => {
    // Seamless Infinite Diagonal Marquee Logic
    const bgContainer = document.querySelector('.floating-bg-container');
    const bgTrack = document.querySelector('.bg-marquee-track');
    
    if (bgContainer && bgTrack) {
        // Randomize the slant angle (e.g., -30deg for bottom-left to top-right, 30deg for top-left to bottom-right)
        const angles = [-35, 35, -25, 25];
        const randomAngle = angles[Math.floor(Math.random() * angles.length)];
        
        // Randomize direction (normal or reverse)
        const directions = ['normal', 'reverse'];
        const randomDir = directions[Math.floor(Math.random() * directions.length)];
        
        // Apply to elements
        bgContainer.style.transform = `rotate(${randomAngle}deg)`;
        bgTrack.style.animationDirection = randomDir;
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
