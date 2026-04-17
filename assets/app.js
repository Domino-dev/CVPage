import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

function initializeTrix() {
    const textareas = document.querySelectorAll('textarea.trix-enabled');
    textareas.forEach(textarea => {
        const hiddenId = textarea.id;
        const trix = document.createElement('trix-editor');
        trix.setAttribute('input', hiddenId);
        textarea.insertAdjacentElement('afterend', trix);
    });
}

document.addEventListener('turbo:load', () => {
    initializeTrix();

    setTimeout(() => {
        const flashes = document.querySelectorAll('[class^="flash-"]');
        flashes.forEach(flash => flash.style.display = 'none');
    }, 10000); // 3000 ms = 3 sekundy

    window.toggleMenu = function () {
        const menu = document.querySelector(".center");
        menu.classList.toggle("active");
        document.body.classList.toggle("no-scroll");
    };

    document.querySelectorAll(".center a").forEach(link => {
        link.addEventListener("click", () => {
            const menu = document.querySelector(".center");
            menu.classList.remove("active");
            document.body.classList.remove("no-scroll");
        });
    });
});