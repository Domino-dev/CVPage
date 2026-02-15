document.addEventListener('DOMContentLoaded', () => {

    initializeScrollScale()
    initializeObservers();
    initializeAnchors();
    runAnimationsManually();

    if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        addAnimation();
    }

    new Swiper('.swiper', {
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    GLightbox({
        selector: '.glightbox',
        zoomable: true,
        touchNavigation: true,
    });
});

document.addEventListener('turbo:load', () => {
    initializeObservers();
    runAnimationsManually();
    initializeAnchors();
    addAnimation();
    initializeScrollScale();

    new Swiper('.swiper', {
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    GLightbox({
        selector: '.glightbox',
        zoomable: true,
        touchNavigation: true,
    });
});

function initializeScrollScale() {
    window.addEventListener('scroll', function () {
        let scrollPosition = window.scrollY;
        let scale = 1 + scrollPosition / 5000;
        const img = document.querySelector('#photo-splitter img');
        if (img) img.style.transform = 'scale(' + scale + ')';
    });
}

// about me animation
function initializeObservers() {
    const elements = document.querySelectorAll('.scroll-reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); // šetří výkon
            }
        });
    }, { threshold: 0.5 });

    elements.forEach(el => observer.observe(el));
}

// about me animation
function runAnimationsManually() {
    const elements = document.querySelectorAll('.scroll-reveal');
    elements.forEach(el => {
        const rect = el.getBoundingClientRect();
        const inView = rect.top < window.innerHeight && rect.bottom > 0;
        if (inView && !el.classList.contains('visible')) {
            el.classList.add('visible');
        }
    });
}

function initializeAnchors() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const hash = this.getAttribute('href');
            const target = document.querySelector(hash);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
                setTimeout(runAnimationsManually, 500);
            }
        });
    });
}

function addAnimation() {
    const scrollers = document.querySelectorAll(".scroller");
    scrollers.forEach(scroller => {
        scroller.setAttribute('data-animated', true);
        const scrollerInner = scroller.querySelector('.scroller__inner');
        const scrollerContent = Array.from(scrollerInner.children);
        scrollerContent.forEach(item => {
            const duplicatedItem = item.cloneNode(true);
            duplicatedItem.setAttribute('aria-hidden', true);
            scrollerInner.appendChild(duplicatedItem);
        });
    });
}