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
});