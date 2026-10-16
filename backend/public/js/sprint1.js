document.querySelectorAll('[data-toggle-password]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.togglePassword);
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        button.textContent = show ? 'Sembunyikan' : 'Lihat';
        button.setAttribute('aria-pressed', String(show));
    });
});

document.querySelectorAll('[data-submit-form]').forEach((form) => {
    form.addEventListener('submit', () => {
        const button = form.querySelector('[data-submit-button]');
        if (!button || button.disabled) return;
        button.dataset.originalLabel = button.textContent;
        button.disabled = true;
        button.textContent = button.dataset.loadingLabel || 'Memproses…';
        form.setAttribute('aria-busy', 'true');
    });
});

window.addEventListener('pageshow', () => {
    document.querySelectorAll('[data-submit-button]').forEach((button) => {
        if (button.dataset.originalLabel) button.textContent = button.dataset.originalLabel;
        button.disabled = false;
        button.closest('form')?.removeAttribute('aria-busy');
    });
});
