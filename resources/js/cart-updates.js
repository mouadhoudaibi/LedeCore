// Cart Updates - Dispatch events when cart changes
document.addEventListener('DOMContentLoaded', function() {
    // Listen for form submissions that modify cart
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const action = form.action || '';
        
        // Check if this is a cart modification form
        if (action.includes('cart/update') || action.includes('cart/remove') || action.includes('cart/clear')) {
            // Dispatch event after a short delay to allow server to process
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('cart-updated'));
            }, 500);
        }
    });
});

