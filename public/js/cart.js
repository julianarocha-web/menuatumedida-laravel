(() => {
    const panel = document.getElementById('offcanvasWithBothOptions');
    if (!panel || !window.bootstrap) return;

    const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(panel);
    const feedback = panel.querySelector('[data-cart-feedback]');
    let busy = false;

    function showMessage(message, success) {
        feedback.replaceChildren();
        if (!message) return;
        const paragraph = document.createElement('p');
        paragraph.className = success ? 'alert alert-success' : 'alert alert-danger';
        paragraph.textContent = message;
        feedback.append(paragraph);
    }

    async function refresh() {
        const response = await fetch(panel.dataset.cartUrl, { headers: { Accept: 'application/json' } });
        if (!response.ok) throw new Error('No se pudo actualizar el carrito.');
        const data = await response.json();
        panel.querySelector('[data-cart-items]').innerHTML = data.html;
    }

    panel.addEventListener('show.bs.offcanvas', () => {
        if (!busy) refresh().catch(error => showMessage(error.message, false));
    });

    document.addEventListener('submit', async event => {
        const form = event.target.closest('[data-cart-form]');
        if (!form) return;
        event.preventDefault();
        if (busy) return;
        busy = true;
        const button = event.submitter;
        if (button) button.disabled = true;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { Accept: 'application/json' },
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                await refresh();
                throw new Error(Object.values(data.errors || {}).flat()[0] || 'No se pudo modificar el carrito. Recargá la página e intentá nuevamente.');
            }
            panel.querySelector('[data-cart-items]').innerHTML = data.html;
            showMessage(data.message, true);
        } catch (error) {
            showMessage(error.message, false);
        } finally {
            offcanvas.show();
            busy = false;
            if (button && document.contains(button)) button.disabled = false;
        }
    });

    if (panel.dataset.cartOpen === 'true') offcanvas.show();
})();
