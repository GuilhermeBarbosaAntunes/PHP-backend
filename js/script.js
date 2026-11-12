document.addEventListener('DOMContentLoaded', () => {
    // Character counter for the contact form
    const txt = document.querySelector('.form-contact textarea');
    if (txt) {
        const accountant = document.createElement('small');
        accountant.style.color = '#94a3b8';
        txt.after(accountant);
        const update = () => {
            accountant.textContent = `${txt.value.length} characters (mín. 10)`;
        };
        txt.addEventListener('input', update);
        update();
    }
});