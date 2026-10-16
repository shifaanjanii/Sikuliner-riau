(() => {
    'use strict';
    const $ = (selector, root = document) => root.querySelector(selector);
    const $$ = (selector, root = document) => Array.from(root.querySelectorAll(selector));
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    // Every photograph, including backend uploads, has a local fallback.
    $$('img[data-image-fallback]').forEach(img => {
        const fallback = () => {
            if (img.dataset.fallbackApplied === 'true') return;
            img.dataset.fallbackApplied = 'true';
            img.src = img.dataset.imageFallback;
            img.classList.remove('is-photo');
            img.alt = 'Foto makanan belum tersedia';
        };
        img.addEventListener('error', fallback);
        if (img.complete && img.naturalWidth === 0) fallback();
    });

    const backdrop = $('[data-sidebar-backdrop]');
    let activeMenu = null;
    let menuTrigger = null;
    const closeMenu = (restoreFocus = true) => {
        if (!activeMenu) return;
        activeMenu.classList.remove('is-open');
        menuTrigger?.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('sidebar-is-open');
        if (backdrop) backdrop.hidden = true;
        if (restoreFocus) menuTrigger?.focus();
        activeMenu = null;
    };
    $$('[data-menu-toggle]').forEach(button => {
        button.addEventListener('click', () => {
            const menu = document.getElementById(button.dataset.menuToggle);
            if (!menu) return;
            const open = !menu.classList.contains('is-open');
            closeMenu(false);
            if (!open) return;
            activeMenu = menu;
            menuTrigger = button;
            menu.classList.add('is-open');
            button.setAttribute('aria-expanded', 'true');
            if (menu.id === 'admin-sidebar') {
                document.body.classList.add('sidebar-is-open');
                if (backdrop) backdrop.hidden = false;
                $('a,button', menu)?.focus();
            }
        });
    });
    $$('[data-menu-close]').forEach(button => button.addEventListener('click', () => closeMenu()));
    backdrop?.addEventListener('click', () => closeMenu());
    document.addEventListener('click', event => {
        if (activeMenu?.id === 'public-menu' && !activeMenu.contains(event.target) && !menuTrigger.contains(event.target)) closeMenu(false);
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closeMenu();
        if (event.key !== 'Tab' || activeMenu?.id !== 'admin-sidebar') return;
        const items = $$('a[href],button:not([disabled]),input', activeMenu).filter(el => el.getClientRects().length);
        if (!items.length) return;
        const first = items[0], last = items[items.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    });
    window.matchMedia('(min-width: 851px)').addEventListener('change', event => { if (event.matches) closeMenu(false); });

    $$('[data-password-toggle]').forEach(button => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);
            if (!input) return;
            const visible = input.type === 'password';
            input.type = visible ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(visible));
            button.setAttribute('aria-label', visible ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            $('.sr-only', button).textContent = button.getAttribute('aria-label');
        });
    });

    const setFieldError = (form, input, message) => {
        const error = $$('[data-error-for]', form).find(el => el.dataset.errorFor === input.name);
        if (error) error.textContent = message;
        if (message) input.setAttribute('aria-invalid', 'true');
        else input.removeAttribute('aria-invalid');
    };
    const validate = form => {
        let firstInvalid = null;
        $$('input:not([type=hidden]),select,textarea', form).forEach(input => {
            let message = '';
            if (input.validity.valueMissing) message = 'Bagian ini perlu diisi dulu, ya.';
            else if (input.validity.typeMismatch) message = 'Masukkan alamat email yang valid.';
            else if (input.validity.tooLong) message = 'Isian ini terlalu panjang.';
            if (input.name === 'password_confirmation' && input.value !== form.elements.password?.value) message = 'Konfirmasi kata sandi belum sama.';
            if (input.type === 'file' && input.dataset.uploadError) message = input.dataset.uploadError;
            setFieldError(form, input, message);
            if (message && !firstInvalid) firstInvalid = input;
        });
        if (firstInvalid) { firstInvalid.focus(); return false; }
        return true;
    };
    const previewFeedback = form => {
        const feedback = $('.form-feedback', form);
        if (!feedback) return;
        feedback.textContent = form.closest('.auth-content')
            ? 'Layanan akun belum tersedia. Tidak ada proses masuk atau pendaftaran yang dilakukan.'
            : 'Ini pratinjau tampilan. Perubahan belum dapat disimpan atau dihapus.';
        feedback.focus();
    };
    $$('form[data-validate]').forEach(form => { form.noValidate = true; });
    $$('form[data-validate],form[data-preview-form]').forEach(form => {
        form.addEventListener('submit', event => {
            if (form.hasAttribute('data-delete-form')) return;
            if (form.hasAttribute('data-validate') && !validate(form)) { event.preventDefault(); return; }
            if (form.hasAttribute('data-preview-form')) { event.preventDefault(); previewFeedback(form); }
        });
    });

    // Native GET submissions keep filters in the URL and use the backend when connected.
    $$('[data-filter-form]').forEach(form => {
        $('select', form)?.addEventListener('change', () => form.requestSubmit());
    });

    const hero = $('[data-hero]');
    if (hero) {
        let items = [];
        try { items = JSON.parse($('[data-featured-data]', hero).textContent); } catch (_) { /* Keep the server-rendered first slide. */ }
        const plate = $('[data-main-plate]', hero);
        const image = $('img', plate || hero);
        const art = $('.hero-art', hero);
        const pauseButton = $('[data-hero-autoplay]', hero);
        let current = 0, changeVersion = 0, userPaused = false, pointerStart = null;
        const change = direction => {
            if (items.length < 2 || !image || !plate) return;
            current = (current + direction + items.length) % items.length;
            const target = current, version = ++changeVersion;
            const item = items[target];
            plate.classList.add('is-changing');
            const preload = new Image();
            const loaded = new Promise(resolve => { preload.onload = resolve; preload.onerror = resolve; });
            preload.src = item.photo;
            const delay = new Promise(resolve => setTimeout(resolve, reducedMotion.matches ? 0 : 220));
            Promise.all([loaded, delay]).then(() => {
                if (version !== changeVersion) return;
                image.dataset.fallbackApplied = 'false';
                image.src = preload.naturalWidth ? item.photo : image.dataset.imageFallback;
                image.alt = preload.naturalWidth ? `Ilustrasi ${item.name}` : 'Foto makanan belum tersedia';
                $('[data-featured-name]', hero).textContent = item.name;
                $('[data-featured-category]', hero).textContent = item.category;
                $('[data-featured-caption]', hero).textContent = item.caption;
                $$('[data-slide-dot]', hero).forEach((dot, index) => {
                    dot.classList.toggle('is-active', index === target);
                    dot.setAttribute('aria-pressed', String(index === target));
                });
                plate.classList.remove('is-changing');
                if (!reducedMotion.matches) {
                    $('[data-featured-name]', hero).animate([{ opacity: 0, transform: 'translateY(7px)' }, { opacity: 1, transform: 'translateY(0)' }], { duration: 380, easing: 'ease-out' });
                }
            });
        };
        $('[data-hero-prev]', hero)?.addEventListener('click', () => change(-1));
        $('[data-hero-next]', hero)?.addEventListener('click', () => change(1));
        $$('[data-slide-dot]', hero).forEach(dot => dot.addEventListener('click', () => {
            const target = Number(dot.dataset.slideDot);
            if (target !== current) change(target - current);
        }));
        hero.addEventListener('keydown', event => {
            if (!event.target.closest('.carousel-dots,.carousel-buttons')) return;
            if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                event.preventDefault(); change(event.key === 'ArrowRight' ? 1 : -1);
            }
        });
        const updateMotion = () => {
            hero.dataset.motionPaused = String(userPaused || reducedMotion.matches);
            if (!pauseButton) return;
            pauseButton.hidden = reducedMotion.matches || items.length < 2;
            pauseButton.textContent = userPaused ? 'Putar otomatis' : 'Jeda animasi';
            pauseButton.setAttribute('aria-pressed', String(userPaused));
        };
        pauseButton?.addEventListener('click', () => { userPaused = !userPaused; updateMotion(); });
        reducedMotion.addEventListener('change', updateMotion);
        updateMotion();
        const autoTimer = window.setInterval(() => {
            if (userPaused || reducedMotion.matches || document.hidden || hero.matches(':hover,:focus-within')) return;
            const bounds = hero.getBoundingClientRect();
            if (bounds.bottom > 120 && bounds.top < window.innerHeight * .7) change(1);
        }, 7000);
        window.addEventListener('pagehide', () => window.clearInterval(autoTimer), { once: true });

        let parallaxFrame = 0;
        const resetParallax = () => { art?.style.setProperty('--parallax-x', '0px'); art?.style.setProperty('--parallax-y', '0px'); };
        hero.addEventListener('pointermove', event => {
            if (event.pointerType !== 'mouse' || reducedMotion.matches || userPaused || !window.matchMedia('(pointer:fine)').matches) return;
            window.cancelAnimationFrame(parallaxFrame);
            parallaxFrame = window.requestAnimationFrame(() => {
                const box = hero.getBoundingClientRect();
                art?.style.setProperty('--parallax-x', `${((event.clientX - box.left) / box.width - .5) * 12}px`);
                art?.style.setProperty('--parallax-y', `${((event.clientY - box.top) / box.height - .5) * 10}px`);
            });
        });
        hero.addEventListener('pointerleave', resetParallax);
        art?.addEventListener('pointerdown', event => { if (event.pointerType === 'touch') pointerStart = { x: event.clientX, y: event.clientY }; });
        art?.addEventListener('pointerup', event => {
            if (!pointerStart) return;
            const dx = event.clientX - pointerStart.x, dy = event.clientY - pointerStart.y;
            if (Math.abs(dx) > 45 && Math.abs(dy) < 40) change(dx < 0 ? 1 : -1);
            pointerStart = null;
        });
        art?.addEventListener('pointercancel', () => { pointerStart = null; });
    }

    $$('[data-upload]').forEach(input => {
        const panel = input.closest('[data-upload-panel]');
        const image = $('[data-upload-preview]', panel);
        const filename = $('[data-upload-name]', panel);
        const clear = $('[data-upload-clear]', panel);
        const original = image?.getAttribute('src');
        let objectUrl = null;
        const revoke = () => { if (objectUrl) URL.revokeObjectURL(objectUrl); objectUrl = null; };
        const reset = () => {
            revoke(); input.value = ''; delete input.dataset.uploadError;
            if (image) { image.src = original; image.hidden = !original; }
            if (filename) filename.textContent = '';
            if (clear) clear.hidden = true;
        };
        input.addEventListener('change', () => {
            revoke(); delete input.dataset.uploadError;
            const file = input.files?.[0];
            if (!file) { reset(); return; }
            const allowed = (input.dataset.accept || '').split(',').filter(Boolean);
            const limit = Number(input.dataset.maxBytes || 0);
            let error = '';
            if (!file.type.startsWith('image/') || (allowed.length && !allowed.includes(file.type))) error = 'Pilih format gambar yang diizinkan.';
            else if (limit && file.size > limit) error = `Ukuran foto melebihi ${limit / 1024 / 1024} MB.`;
            const showError = message => {
                input.dataset.uploadError = message;
                setFieldError(input.form, input, message);
                if (image) { image.src = original; image.hidden = !original; }
            };
            setFieldError(input.form, input, error);
            if (error) { showError(error); return; }
            objectUrl = URL.createObjectURL(file);
            image.hidden = false;
            image.onload = () => {};
            image.onerror = () => { image.onerror = null; showError('Foto ini tidak dapat dibaca. Pilih gambar lain.'); };
            image.src = objectUrl;
            if (filename) filename.textContent = file.name;
            if (clear) clear.hidden = false;
        });
        clear?.addEventListener('click', () => { reset(); setFieldError(input.form, input, ''); input.focus(); });
        window.addEventListener('pagehide', revoke);
    });

    const dialog = $('#delete-dialog');
    let pendingDelete = null, deleteTrigger = null;
    $$('form[data-delete-form]').forEach(form => {
        form.addEventListener('submit', event => {
            if (form.dataset.confirmed === 'true') return;
            event.preventDefault();
            pendingDelete = form;
            deleteTrigger = $('button[type=submit]', form);
            if (dialog?.showModal) {
                $('[data-delete-name]', dialog).textContent = form.dataset.deleteName;
                dialog.showModal();
                $('[data-dialog-cancel]', dialog).focus();
            } else if (window.confirm(`Hapus ${form.dataset.deleteName}?`)) {
                if (form.hasAttribute('data-preview-form')) previewFeedback(form);
                else { form.dataset.confirmed = 'true'; form.requestSubmit(); }
            }
        });
    });
    $('[data-dialog-cancel]', dialog || document)?.addEventListener('click', () => dialog.close());
    $('[data-dialog-confirm]', dialog || document)?.addEventListener('click', () => {
        dialog.close();
        if (!pendingDelete) return;
        if (pendingDelete.hasAttribute('data-preview-form')) previewFeedback(pendingDelete);
        else { pendingDelete.dataset.confirmed = 'true'; pendingDelete.requestSubmit(); }
    });
    dialog?.addEventListener('close', () => deleteTrigger?.focus());
    dialog?.addEventListener('click', event => { if (event.target === dialog) { const r = dialog.getBoundingClientRect(); if (event.clientX < r.left || event.clientX > r.right || event.clientY < r.top || event.clientY > r.bottom) dialog.close(); } });

    if ('IntersectionObserver' in window && !reducedMotion.matches) {
        const observer = new IntersectionObserver(entries => entries.forEach(entry => {
            if (entry.isIntersecting) { entry.target.classList.add('is-revealed'); observer.unobserve(entry.target); }
        }), { threshold: .06 });
        $$('.reveal').forEach(element => {
            element.classList.add('is-reveal-ready'); observer.observe(element);
        });
        window.addEventListener('pageshow', event => { if (event.persisted) $$('.reveal').forEach(element => element.classList.add('is-revealed')); });
    }
})();
