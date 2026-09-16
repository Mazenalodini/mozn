document.addEventListener('alpine:init', () => {
    // Cart Store
    Alpine.store('cart', {
        items: [],
        count: 0,
        total: 0,

        init() {
            // Load from local storage if available, usually you'd sync with backend here
            // For now, we'll keep it simple or strictly session based
            this.updateCount();
        },

        add(product) {
            // AJAX call to add to session cart would go here
            console.log('Adding product', product);
            // Simulate for UI
            this.count++;
            // Show toast (simulated)
            alert('Added to cart!');
        },

        updateCount() {
            // Fetch current count from server
        }
    });
});

// Simple Toast function (vanilla JS)
function showToast(message, type = 'success') {
    // Implementation for a simple toast notification
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 z-50 px-6 py-3 rounded shadow-lg text-white transform transition-all duration-300 translate-y-10 opacity-0 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    toast.innerText = message;
    document.body.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-10', 'opacity-0');
    });

    // Remove
    setTimeout(() => {
        toast.classList.add('translate-y-10', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
