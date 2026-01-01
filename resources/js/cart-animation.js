// Add to Cart Animation
(function() {
    'use strict';

    // Find cart icon position
    function getCartIconPosition() {
        // Try multiple selectors to find cart icon
        const selectors = [
            'header a[href*="cart"]',
            'header a[href*="cart.index"]',
            'nav a[href*="cart"]'
        ];

        for (const selector of selectors) {
            const cartLink = document.querySelector(selector);
            if (cartLink) {
                const rect = cartLink.getBoundingClientRect();
                return {
                    x: rect.left + rect.width / 2,
                    y: rect.top + rect.height / 2
                };
            }
        }

        // Fallback to header center if cart not found
        const header = document.querySelector('header');
        if (header) {
            const rect = header.getBoundingClientRect();
            return {
                x: window.innerWidth - 100,
                y: rect.top + rect.height / 2
            };
        }

        return { x: window.innerWidth - 50, y: 50 };
    }

    // Create flying image animation
    function createFlyingImage(imgElement, targetPosition) {
        if (!imgElement || !imgElement.src) return;

        const rect = imgElement.getBoundingClientRect();
        const startX = rect.left + rect.width / 2;
        const startY = rect.top + rect.height / 2;

        // Create flying image element
        const flyingImg = document.createElement('img');
        flyingImg.src = imgElement.src;
        flyingImg.alt = imgElement.alt || '';
        flyingImg.style.cssText = `
            position: fixed;
            left: ${startX}px;
            top: ${startY}px;
            width: ${Math.min(rect.width, 100)}px;
            height: ${Math.min(rect.height, 100)}px;
            z-index: 9999;
            pointer-events: none;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transform: translate(-50%, -50%);
            opacity: 1;
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        `;

        document.body.appendChild(flyingImg);

        // Force reflow
        flyingImg.offsetHeight;

        // Animate to cart
        requestAnimationFrame(() => {
            flyingImg.style.left = `${targetPosition.x}px`;
            flyingImg.style.top = `${targetPosition.y}px`;
            flyingImg.style.width = '30px';
            flyingImg.style.height = '30px';
            flyingImg.style.opacity = '0.7';
        });

        // Remove after animation
        setTimeout(() => {
            flyingImg.style.opacity = '0';
            flyingImg.style.transform = 'translate(-50%, -50%) scale(0.5)';
            setTimeout(() => {
                if (flyingImg.parentNode) {
                    flyingImg.parentNode.removeChild(flyingImg);
                }
            }, 300);
        }, 600);
    }

    // Handle add to cart button clicks
    document.addEventListener('click', function(e) {
        const button = e.target.closest('button[type="submit"]');
        if (!button) return;

        const form = button.closest('form');
        if (!form || !form.action || !form.action.includes('cart/add')) return;

        // Get quantity from form
        const quantityInput = form.querySelector('input[name="quantity"]');
        const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;

        // Find product image
        const productCard = form.closest('.bg-gray-800');
        if (!productCard) return;

        const productImage = productCard.querySelector('img[src*="storage"], img[src*="products"]');
        if (!productImage) return;

        // Get cart position
        const targetPosition = getCartIconPosition();

        // Trigger cart count increment via custom event (Alpine.js will listen)
        window.dispatchEvent(new CustomEvent('cart-item-added', { 
            detail: { quantity: quantity },
            bubbles: true 
        }));

        // Create animation
        createFlyingImage(productImage, targetPosition);
    });
})();

