document.addEventListener('alpine:init', () => {
    Alpine.store('favorites', {
        ids: [],
        storageAvailable: true,
        notice: '',
        init() {
            try {
                const saved = JSON.parse(localStorage.getItem('mc-property-favorites') || '[]');
                this.ids = Array.isArray(saved) ? [...new Set(saved.filter(id => Number.isSafeInteger(id) && id > 0))].slice(0, 100) : [];
            } catch { this.storageAvailable = false; }
            window.addEventListener('storage', event => {
                if (event.key === 'mc-property-favorites') {
                    try {
                        const saved = JSON.parse(event.newValue || '[]');
                        this.ids = Array.isArray(saved) ? [...new Set(saved.filter(id => Number.isSafeInteger(id) && id > 0))].slice(0, 100) : [];
                        window.dispatchEvent(new CustomEvent('favorites-changed'));
                    } catch { /* Preserve the current selection if storage is malformed. */ }
                }
            });
        },
        has(id) { return this.ids.includes(id); },
        toggle(id) {
            if (this.has(id)) this.ids = this.ids.filter(value => value !== id);
            else if (this.ids.length < 100) this.ids = [...this.ids, id];
            else { this.notice = 'Puedes guardar hasta 100 propiedades.'; return; }
            this.notice = this.has(id) ? 'Propiedad guardada en favoritas.' : 'Propiedad eliminada de favoritas.';
            try { localStorage.setItem('mc-property-favorites', JSON.stringify(this.ids)); }
            catch { this.storageAvailable = false; }
            window.dispatchEvent(new CustomEvent('favorites-changed'));
        }
    });
});
