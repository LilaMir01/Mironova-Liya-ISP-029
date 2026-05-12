import './bootstrap';

// Анимация появления при прокрутке
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.about-section, .about-feature').forEach(el => {
        el.classList.add('animate-on-scroll');
        observer.observe(el);
    });

    initProductsCreatorPage();

    const deleteForms = document.querySelectorAll('.js-confirm-delete');
    if (deleteForms.length > 0) {
        const backdrop = document.createElement('div');
        backdrop.className = 'confirm-modal-backdrop';
        backdrop.innerHTML = `
            <div class="confirm-modal">
                <p id="confirmDeleteText">Вы точно хотите удалить?</p>
                <div class="confirm-modal-buttons">
                    <button type="button" class="btn-modal-confirm" id="confirmDeleteYes">Да</button>
                    <button type="button" class="btn-modal-cancel" id="confirmDeleteNo">Нет</button>
                </div>
            </div>
        `;
        document.body.appendChild(backdrop);

        const confirmText = document.getElementById('confirmDeleteText');
        const btnYes = document.getElementById('confirmDeleteYes');
        const btnNo = document.getElementById('confirmDeleteNo');
        let activeForm = null;

        deleteForms.forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                activeForm = form;
                const entity = form.dataset.entityLabel || 'элемент';
                confirmText.textContent = `Вы точно хотите удалить ${entity}?`;
                backdrop.classList.add('open');
            });
        });

        btnNo?.addEventListener('click', () => {
            activeForm = null;
            backdrop.classList.remove('open');
        });

        btnYes?.addEventListener('click', () => {
            if (activeForm) {
                activeForm.submit();
            }
        });
    }

    initPriceRangeDual();
    initHomeSlider();
    initCatalogProductModal();
    initDirectorCredentialsConfirm();

    if (window.location.hash === '#creator-product-form') {
        requestAnimationFrame(() => {
            document.getElementById('creator-product-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    initQtyRestrictedInputs();
    initCheckoutPhoneField();
    initCartQtyAutoUpdate();
});

function initProductsCreatorPage() {
    const root = document.querySelector('[data-products-creator]');
    if (!root) {
        return;
    }

    root.querySelectorAll('.js-creator-file-input').forEach((input) => {
        const wrap = input.closest('.creator-file');
        const nameEl = wrap?.querySelector('.js-creator-file-name');
        const photoField = input.closest('.js-product-photo-field');
        const previewImg = photoField?.querySelector('.js-product-photo-preview');
        input.addEventListener('change', () => {
            const f = input.files?.[0];
            if (nameEl) {
                nameEl.textContent = f ? f.name : 'Файл не выбран';
            }
            if (previewImg && f) {
                if (previewImg.dataset.blobUrl) {
                    URL.revokeObjectURL(previewImg.dataset.blobUrl);
                }
                const url = URL.createObjectURL(f);
                previewImg.dataset.blobUrl = url;
                previewImg.src = url;
                previewImg.hidden = false;
            }
            const slideGroup = input.closest('.home-slide-file-group');
            const slideImg = slideGroup?.querySelector('.js-home-slide-preview-img');
            const slideEmpty = slideGroup?.querySelector('.js-home-slide-preview-empty');
            if (input.classList.contains('js-home-slide-input') && slideImg && f) {
                if (slideImg.dataset.blobUrl) {
                    URL.revokeObjectURL(slideImg.dataset.blobUrl);
                }
                const slideUrl = URL.createObjectURL(f);
                slideImg.dataset.blobUrl = slideUrl;
                slideImg.src = slideUrl;
                slideImg.hidden = false;
                if (slideEmpty) {
                    slideEmpty.hidden = true;
                }
            }
        });
    });

    const typesJson = document.getElementById('creator-json-material-types');
    const manufsJson = document.getElementById('creator-json-manufacturers');
    if (!typesJson || !manufsJson) {
        return;
    }

    let types = [];
    let manufs = [];
    try {
        types = JSON.parse(typesJson.textContent || '[]');
        manufs = JSON.parse(manufsJson.textContent || '[]');
    } catch {
        return;
    }

    root.querySelectorAll('.js-creator-delete-with-action').forEach((form) => {
        form.addEventListener('submit', (e) => {
            const id = form.dataset.selectedId;
            if (!id) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return;
            }
            const base = (form.dataset.urlBase || '').replace(/\/$/, '');
            form.action = `${base}/${id}`;
        }, true);
    });

    setupCreatorCombo(root.querySelector('[data-combo-role="material-type"]'), types);
    setupCreatorCombo(root.querySelector('[data-combo-role="manufacturer"]'), manufs);

    root.querySelectorAll('[data-add-combo="material"]').forEach((combo) => {
        const hidden = combo.closest('.form-group')?.querySelector('.js-add-product-type-id');
        setupPickerCombo(combo, types, { hiddenInput: hidden });
    });
    root.querySelectorAll('[data-add-combo="manufacturer"]').forEach((combo) => {
        const hidden = combo.closest('.form-group')?.querySelector('.js-add-product-manufacturer-id');
        setupPickerCombo(combo, manufs, { hiddenInput: hidden });
    });

    root.querySelectorAll('.creator-add-product-form, .creator-add-product-form-inner').forEach((form) => {
        form.addEventListener('submit', (e) => {
            const tid = form.querySelector('.js-add-product-type-id')?.value;
            const mid = form.querySelector('.js-add-product-manufacturer-id')?.value;
            if (!tid || !mid) {
                e.preventDefault();
                alert('Выберите материал и производителя из выпадающего списка.');
            }
        });
    });
}

function setupCreatorCombo(comboEl, items) {
    if (!comboEl || !Array.isArray(items)) {
        return;
    }
    const col = comboEl.closest('.creator-delete-col');
    const form = col?.querySelector('.js-creator-delete-with-action');
    const btn = form?.querySelector('button[type="submit"]');
    if (!form || !btn) {
        return;
    }
    setupPickerCombo(comboEl, items, { deleteForm: form, deleteBtn: btn });
}

/**
 * @param {HTMLElement} comboEl
 * @param {Array<{id: string|number, name: string}>} items
 * @param {{ hiddenInput?: HTMLInputElement|null, deleteForm?: HTMLFormElement, deleteBtn?: HTMLButtonElement }} config
 */
function setupPickerCombo(comboEl, items, config) {
    const { hiddenInput, deleteForm, deleteBtn } = config || {};
    const input = comboEl.querySelector('.creator-combo__input');
    const list = comboEl.querySelector('.creator-combo__list');
    if (!input || !list) {
        return;
    }
    const useDelete = Boolean(deleteForm && deleteBtn);
    const useHidden = Boolean(hiddenInput);
    if (!useDelete && !useHidden) {
        return;
    }

    function escapeHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function render(filter) {
        const q = (filter || '').trim().toLowerCase();
        const matched = !q
            ? items
            : items.filter((i) => String(i.name).toLowerCase().includes(q));
        list.innerHTML = matched
            .slice(0, 50)
            .map(
                (i) =>
                    `<li role="option" tabindex="-1" data-id="${escapeHtml(String(i.id))}">${escapeHtml(String(i.name))}</li>`
            )
            .join('');
        list.hidden = matched.length === 0;
        input.setAttribute('aria-expanded', matched.length === 0 ? 'false' : 'true');
    }

    function clearSelection() {
        if (hiddenInput) {
            hiddenInput.value = '';
        }
        if (deleteForm) {
            deleteForm.dataset.selectedId = '';
        }
        if (deleteBtn) {
            deleteBtn.disabled = true;
        }
    }

    input.addEventListener('input', () => {
        clearSelection();
        render(input.value);
    });

    input.addEventListener('focus', () => {
        render(input.value);
    });

    list.addEventListener('click', (e) => {
        const li = e.target.closest('li[data-id]');
        if (!li) {
            return;
        }
        const id = li.getAttribute('data-id');
        const label = li.textContent || '';
        input.value = label;
        if (hiddenInput) {
            hiddenInput.value = id || '';
        }
        if (deleteForm) {
            deleteForm.dataset.selectedId = id || '';
        }
        if (deleteBtn) {
            deleteBtn.disabled = !id;
        }
        list.hidden = true;
        input.setAttribute('aria-expanded', 'false');
    });

    document.addEventListener('click', (e) => {
        if (!comboEl.contains(e.target)) {
            list.hidden = true;
            input.setAttribute('aria-expanded', 'false');
        }
    });
}

function initHomeSlider() {
    const root = document.querySelector('[data-home-slider]');
    if (!root) {
        return;
    }

    const track = root.querySelector('[data-home-slider-track]');
    const slides = track ? track.querySelectorAll('.home-slider__slide') : [];
    const prevBtn = root.querySelector('[data-home-slider-prev]');
    const nextBtn = root.querySelector('[data-home-slider-next]');
    const dotsWrap = document.querySelector('[data-home-slider-dots]');

    if (!track || slides.length === 0) {
        return;
    }

    let n = parseInt(track.dataset.slideCount || String(slides.length), 10);
    if (!Number.isFinite(n) || n < 1) {
        n = slides.length;
    }

    let index = 0;
    let autoplayTimer = null;

    function setTransform() {
        const pct = (100 / n) * index;
        track.style.transform = `translateX(-${pct}%)`;
    }

    function go(to) {
        index = (to + n) % n;
        setTransform();
        if (dotsWrap) {
            dotsWrap.querySelectorAll('.home-slider__dot').forEach((dot, di) => {
                dot.classList.toggle('is-active', di === index);
            });
        }
    }

    function restartAutoplay() {
        clearInterval(autoplayTimer);
        autoplayTimer = setInterval(() => {
            go(index + 1);
        }, 3000);
    }

    if (dotsWrap && n > 0) {
        dotsWrap.innerHTML = '';
        for (let k = 0; k < n; k += 1) {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = `home-slider__dot${k === 0 ? ' is-active' : ''}`;
            b.setAttribute('aria-label', `Перейти к слайду ${k + 1}`);
            b.addEventListener('click', () => {
                go(k);
                restartAutoplay();
            });
            dotsWrap.appendChild(b);
        }
    }

    setTransform();
    restartAutoplay();

    prevBtn?.addEventListener('click', () => {
        go(index - 1);
        restartAutoplay();
    });
    nextBtn?.addEventListener('click', () => {
        go(index + 1);
        restartAutoplay();
    });
}

const CATALOG_PRODUCT_PLACEHOLDER_IMG = 'https://via.placeholder.com/480x360?text=Alta+Design';

function initCheckoutPhoneField() {
    document.querySelectorAll('.js-checkout-phone').forEach((input) => {
        input.addEventListener('keydown', (e) => {
            const allowedNav = ['Backspace', 'Delete', 'Tab', 'Escape', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
            if (allowedNav.includes(e.key)) {
                return;
            }
            if (e.ctrlKey || e.metaKey || e.altKey) {
                return;
            }
            if (e.key.length === 1 && !/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
        input.addEventListener('input', () => {
            input.value = String(input.value).replace(/\D/g, '').slice(0, 20);
        });
    });
}

function formatRubIntForCart(n) {
    return new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 }).format(Math.round(Number(n)));
}

function recalcCartTotalsFromDom() {
    let grand = 0;
    document.querySelectorAll('[data-cart-row]').forEach((row) => {
        const input = row.querySelector('input[name="qty"]');
        let q = parseInt(input?.value, 10);
        if (!Number.isFinite(q) || input.value === '') {
            q = 1;
        }
        const unit = Number(row.dataset.unitPrice);
        const lineAmount = q * unit;
        grand += lineAmount;
        const sumEl = row.querySelector('.js-cart-line-sum');
        if (sumEl) {
            sumEl.textContent = formatRubIntForCart(lineAmount);
        }
    });
    const grandSpan = document.querySelector('.js-cart-grand-total');
    if (grandSpan) {
        grandSpan.textContent = formatRubIntForCart(grand);
    }
}

async function syncCartQtyToServer(form) {
    const input = form.querySelector('input[name="qty"]');
    if (!input) {
        return;
    }
    let q = parseInt(input.value, 10);
    if (!Number.isFinite(q) || input.value === '') {
        q = 1;
    }
    const token = form.querySelector('input[name="_token"]')?.value;
    if (!token) {
        return;
    }
    const fd = new FormData();
    fd.append('_token', token);
    fd.append('_method', 'PUT');
    fd.append('qty', String(q));
    const res = await fetch(form.action, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: fd,
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
        window.location.reload();
        return;
    }
    const row = form.closest('[data-cart-row]');
    const sumEl = row?.querySelector('.js-cart-line-sum');
    if (sumEl && typeof data.lineTotal === 'number') {
        sumEl.textContent = formatRubIntForCart(data.lineTotal);
    }
    const grandSpan = document.querySelector('.js-cart-grand-total');
    if (grandSpan && typeof data.grandTotal === 'number') {
        grandSpan.textContent = formatRubIntForCart(data.grandTotal);
    }
}

function initCartQtyAutoUpdate() {
    const root = document.querySelector('[data-cart-page]');
    if (!root) {
        return;
    }

    const debounceTimers = new Map();

    root.querySelectorAll('.js-cart-qty-form').forEach((form) => {
        const input = form.querySelector('input[name="qty"]');
        if (!input) {
            return;
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();
        });

        const scheduleSync = () => {
            if (debounceTimers.has(form)) {
                clearTimeout(debounceTimers.get(form));
            }
            const t = setTimeout(() => {
                debounceTimers.delete(form);
                syncCartQtyToServer(form).catch(() => {
                    window.location.reload();
                });
            }, 400);
            debounceTimers.set(form, t);
        };

        input.addEventListener('input', () => {
            recalcCartTotalsFromDom();
            scheduleSync();
        });

        input.addEventListener('blur', () => {
            recalcCartTotalsFromDom();
            if (debounceTimers.has(form)) {
                clearTimeout(debounceTimers.get(form));
                debounceTimers.delete(form);
            }
            syncCartQtyToServer(form).catch(() => {
                window.location.reload();
            });
        });
    });
}

function initQtyRestrictedInputs() {
    document.querySelectorAll('.js-qty-restricted').forEach((input) => {
        input.setAttribute('min', '1');
        input.setAttribute('max', '9999');

        input.addEventListener('keydown', (e) => {
            const allowedNav = ['Backspace', 'Delete', 'Tab', 'Escape', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
            if (allowedNav.includes(e.key)) {
                return;
            }
            if (e.ctrlKey || e.metaKey || e.altKey) {
                return;
            }
            if (e.key.length === 1 && !/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        input.addEventListener('input', () => {
            const raw = String(input.value).replace(/\D/g, '');
            if (raw === '') {
                input.value = '';
                return;
            }
            let n = parseInt(raw, 10);
            if (n < 1) {
                n = 1;
            }
            if (n > 9999) {
                n = 9999;
            }
            input.value = String(n);
        });

        input.addEventListener('blur', () => {
            if (input.value === '' || parseInt(input.value, 10) < 1) {
                input.value = '1';
            }
        });
    });
}

function initDirectorCredentialsConfirm() {
    const forms = document.querySelectorAll('form.js-director-credentials-form');
    if (forms.length === 0) {
        return;
    }

    const backdrop = document.createElement('div');
    backdrop.className = 'confirm-modal-backdrop';
    backdrop.innerHTML = `
        <div class="confirm-modal">
            <p id="directorCredentialsConfirmText"></p>
            <div class="confirm-modal-buttons">
                <button type="button" class="btn-modal-confirm" id="directorCredentialsConfirmYes">Да</button>
                <button type="button" class="btn-modal-cancel" id="directorCredentialsConfirmNo">Нет</button>
            </div>
        </div>
    `;
    document.body.appendChild(backdrop);

    const textEl = document.getElementById('directorCredentialsConfirmText');
    const btnYes = document.getElementById('directorCredentialsConfirmYes');
    const btnNo = document.getElementById('directorCredentialsConfirmNo');
    let pendingForm = null;

    forms.forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            pendingForm = form;
            const label = form.dataset.staffRoleLabel || 'сотрудника';
            if (textEl) {
                textEl.textContent = `Вы точно хотите изменить логин и пароль у ${label}?`;
            }
            backdrop.classList.add('open');
        });
    });

    btnNo?.addEventListener('click', () => {
        pendingForm = null;
        backdrop.classList.remove('open');
    });

    btnYes?.addEventListener('click', () => {
        if (pendingForm) {
            const f = pendingForm;
            pendingForm = null;
            backdrop.classList.remove('open');
            f.submit();
        }
    });
}

function initCatalogProductModal() {
    const page = document.querySelector('[data-catalog-products-page]');
    const dataEl = document.getElementById('catalog-products-modal-data');
    const modal = document.getElementById('product-detail-modal');
    if (!page || !dataEl || !modal) {
        return;
    }

    let items = [];
    try {
        items = JSON.parse(dataEl.textContent || '[]');
    } catch {
        return;
    }

    const byId = new Map(items.map((p) => [String(p.id), p]));

    const img = modal.querySelector('.js-product-detail-modal-img');
    const title = modal.querySelector('.js-product-detail-modal-title');
    const priceEl = modal.querySelector('.js-product-detail-modal-price');
    const manufacturerEl = modal.querySelector('.js-product-detail-modal-manufacturer');
    const colorEl = modal.querySelector('.js-product-detail-modal-color');
    const dimensionsEl = modal.querySelector('.js-product-detail-modal-dimensions');
    const descriptionEl = modal.querySelector('.js-product-detail-modal-description');
    const descBlock = modal.querySelector('.product-detail-modal__description-block');
    const form = modal.querySelector('.js-product-detail-modal-form');
    const qtyInput = form?.querySelector('input[name="qty"]');

    function setMeta(el, text) {
        if (!el) {
            return;
        }
        const v = (text ?? '').trim();
        el.textContent = v || '—';
    }

    function openModal(product) {
        if (!img || !title || !priceEl || !form) {
            return;
        }
        img.src = product.image || CATALOG_PRODUCT_PLACEHOLDER_IMG;
        img.alt = product.name || '';
        title.textContent = product.name || '';
        priceEl.textContent = product.priceFormatted || '';

        setMeta(manufacturerEl, product.manufacturer);
        setMeta(colorEl, product.color);
        setMeta(dimensionsEl, product.dimensions);

        const desc = (product.description || '').trim();
        if (descBlock && descriptionEl) {
            if (desc) {
                descBlock.hidden = false;
                descriptionEl.textContent = desc;
            } else {
                descBlock.hidden = true;
                descriptionEl.textContent = '';
            }
        }

        form.action = product.cartUrl || '';
        if (qtyInput) {
            qtyInput.value = '1';
        }

        modal.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
    }

    function openById(id) {
        const product = byId.get(String(id));
        if (product) {
            openModal(product);
        }
    }

    page.querySelectorAll('.js-catalog-product-card').forEach((card) => {
        card.addEventListener('click', (e) => {
            if (e.target.closest('.js-catalog-product-card-cart')) {
                return;
            }
            openById(card.dataset.materialId);
        });
        card.addEventListener('keydown', (e) => {
            if (e.target.closest('.js-catalog-product-card-cart')) {
                return;
            }
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openById(card.dataset.materialId);
            }
        });
    });

    modal.querySelectorAll('.js-product-detail-modal-close').forEach((el) => {
        el.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });
}

function initPriceRangeDual() {
    const root = document.querySelector('.js-price-range');
    if (!root) {
        return;
    }

    const minBound = Number(root.dataset.min || 0);
    const maxBound = Number(root.dataset.max || 100000);
    const inputFrom = root.querySelector('input[name="price_from"]');
    const inputTo = root.querySelector('input[name="price_to"]');
    const rangeFrom = root.querySelector('.js-range-from');
    const rangeTo = root.querySelector('.js-range-to');
    const clearBtn = document.querySelector('.js-price-clear');

    if (!inputFrom || !inputTo || !rangeFrom || !rangeTo) {
        return;
    }

    rangeFrom.min = String(minBound);
    rangeFrom.max = String(maxBound);
    rangeTo.min = String(minBound);
    rangeTo.max = String(maxBound);
    inputFrom.min = minBound;
    inputFrom.max = maxBound;
    inputTo.min = minBound;
    inputTo.max = maxBound;

    function clamp(n, a, b) {
        return Math.max(a, Math.min(b, n));
    }

    function syncFromInputs() {
        let f = Math.round(Number(inputFrom.value));
        let t = Math.round(Number(inputTo.value));
        f = clamp(f, minBound, maxBound);
        t = clamp(t, minBound, maxBound);
        if (f > t) {
            const x = f;
            f = t;
            t = x;
        }
        inputFrom.value = f;
        inputTo.value = t;
        rangeFrom.value = String(f);
        rangeTo.value = String(t);
    }

    syncFromInputs();

    rangeFrom.addEventListener('input', () => {
        let f = Math.round(Number(rangeFrom.value));
        let t = Math.round(Number(rangeTo.value));
        if (f > t) {
            rangeFrom.value = String(t);
            f = t;
        }
        inputFrom.value = f;
    });

    rangeTo.addEventListener('input', () => {
        let f = Math.round(Number(rangeFrom.value));
        let t = Math.round(Number(rangeTo.value));
        if (t < f) {
            rangeTo.value = String(f);
            t = f;
        }
        inputTo.value = t;
    });

    inputFrom.addEventListener('input', () => {
        let f = Math.round(Number(inputFrom.value));
        f = clamp(f, minBound, maxBound);
        let t = Math.round(Number(inputTo.value));
        if (f > t) {
            f = t;
        }
        inputFrom.value = f;
        rangeFrom.value = String(f);
    });

    inputTo.addEventListener('input', () => {
        let t = Math.round(Number(inputTo.value));
        t = clamp(t, minBound, maxBound);
        let f = Math.round(Number(inputFrom.value));
        if (t < f) {
            t = f;
        }
        inputTo.value = t;
        rangeTo.value = String(t);
    });

    clearBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        inputFrom.value = minBound;
        inputTo.value = maxBound;
        rangeFrom.value = String(minBound);
        rangeTo.value = String(maxBound);
    });
}
