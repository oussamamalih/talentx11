const TOAST_TYPES = ['success', 'error', 'warning', 'info'];

document.addEventListener('alpine:init', () => {
    Alpine.data('toastStack', (initial = [], duration = 4000) => ({
        toasts: [],
        duration: Number.isFinite(Number(duration)) ? Number(duration) : 4000,

        init() {
            const seed = Array.isArray(initial) ? initial : [];

            seed.forEach((toast, index) => {
                this.push(
                    toast.type,
                    toast.message,
                    index * 300,
                );
            });
        },

        push(type, message, stagger = 0) {
            const toast = {
                id: this.id(),
                type: TOAST_TYPES.includes(type) ? type : 'info',
                message,
                visible: true,
            };

            this.toasts.push(toast);

            if (this.duration > 0) {
                setTimeout(() => this.dismiss(toast), this.duration + stagger);
            }

            return toast;
        },

        dismiss(toast) {
            toast.visible = false;

            setTimeout(() => this.remove(toast), 250);
        },

        remove(toast) {
            const index = this.toasts.indexOf(toast);

            if (index !== -1) {
                this.toasts.splice(index, 1);
            }
        },

        id() {
            if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
                return crypto.randomUUID();
            }

            return `toast-${Date.now()}-${Math.random().toString(16).slice(2)}`;
        },
    }));
});