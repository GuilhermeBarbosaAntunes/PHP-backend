document.addEventListener('DOMContentLoaded', () => {
    // Character counter for the contact form
    const txt = document.querySelector('.form-contato textarea');
    if (txt) {
        const contador = document.createElement('small');
        contador.style.color = '#94a3b8';
        txt.after(contador);
        const atualizar = () => {
            contador.textContent = `${txt.value.length} caracteres (mín. 10)`;
        };
        txt.addEventListener('input', atualizar);
        atualizar();
    }
});