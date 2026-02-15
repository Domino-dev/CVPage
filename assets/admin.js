document.addEventListener('DOMContentLoaded', () => {
    initSkillCollection();
    addAnimation();
});


document.addEventListener('turbo:load', () => {
    initSkillCollection();
    addAnimation();
});



function initSkillCollection() {
    const collection = document.getElementById('skills');
    const addButton = document.getElementById('add-skill');
    if (!collection || !addButton) return;

    
    let index = collection.children.length;

    addButton.addEventListener('click', () => {
        const prototype = collection.dataset.prototype;
        const newForm = prototype.replace(/__name__/g, index);

        const div = document.createElement('div');
        div.classList.add('skill-item');
        div.innerHTML = newForm + '<button type="button" class="remove-skill">Remove skill</button>';

        collection.appendChild(div);
        index++;
    });

    collection.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-skill')) {
            e.target.closest('.skill-item').remove();
        }
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