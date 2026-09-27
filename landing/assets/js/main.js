document.addEventListener('DOMContentLoaded', () => {
    // Floating Background Cards Logic
    const bgCards = document.querySelectorAll('.floating-bg-card');
    if (bgCards.length > 0) {
        bgCards.forEach((card, index) => {
            // Randomly position the cards across the screen
            const randomX = Math.floor(Math.random() * 85) + 5; // 5% to 90%
            const randomY = Math.floor(Math.random() * 85) + 5; // 5% to 90%
            
            // Randomize animation duration and delay for organic feel
            const randomDuration = Math.floor(Math.random() * 15) + 20; // 20s to 35s
            const randomDelay = Math.floor(Math.random() * 10);
            
            // Random base rotation
            const randomRotate = Math.floor(Math.random() * 60) - 30; // -30deg to 30deg

            card.style.left = `${randomX}%`;
            card.style.top = `${randomY}%`;
            card.style.transform = `rotate(${randomRotate}deg)`;
            card.style.animation = `floatDynamicCard ${randomDuration}s ease-in-out ${randomDelay}s infinite alternate`;
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
});
