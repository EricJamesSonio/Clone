// modal.js
import { getSizes, ensureGuestToken } from './session.js';
import { API_BASE_PATH } from './config.js';

class Modal {
    constructor(apiBasePath) {
        this.apiBasePath = apiBasePath;
        this.currentItem = null;

        this.modalElement = document.getElementById('itemModal');
        this.nameElement = document.getElementById('modalItemName');
        this.quantityInput = document.getElementById('modalQuantity');
        this.sizeSelect = document.getElementById('modalSize');
    }

    open(item) {
        this.currentItem = item;
        this.nameElement.textContent = item.name;
        this.quantityInput.value = 1;

        this.populateSizes();
        this.show();
    }

    close() {
        this.hide();
    }

    populateSizes() {
        this.sizeSelect.innerHTML = '';
        getSizes().forEach(size => {
            const opt = document.createElement('option');
            opt.value = size.id;
            opt.textContent = `${size.name} (+₱${parseFloat(size.price_modifier).toFixed(2)})`;
            opt.dataset.modifier = size.price_modifier;
            this.sizeSelect.appendChild(opt);
        });
    }

    async addToCart() {
        const qty = parseInt(this.quantityInput.value, 10);
        if (qty < 1) return;

        const selectedOption = this.sizeSelect.options[this.sizeSelect.selectedIndex];
        const sizeId = selectedOption.value;
        const mod = parseFloat(selectedOption.dataset.modifier);
        const unitPrice = parseFloat(this.currentItem.price) + mod;

        const payload = {
            item_id: this.currentItem.id,
            size_id: sizeId,
            quantity: qty,
            guest_token: ensureGuestToken()
        };

        try {
            const res = await fetch(`${this.apiBasePath}/cart`, {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.error || data.message || res.statusText);

            alert(`✅ Added ${this.currentItem.name} ×${qty} to your cart.`);
        } catch (err) {
            console.error("Cart sync failed:", err);
            alert(`❌ Could not add to cart: ${err.message}`);
        }

        this.close();
    }

    show() {
        this.modalElement.style.display = 'flex';
    }

    hide() {
        this.modalElement.style.display = 'none';
    }
}

// ===== Singleton Export =====
export const modal = new Modal(API_BASE_PATH);

// Keep old function names for compatibility with existing code
export function openModal(item) {
    modal.open(item);
}
export function closeModal() {
    modal.close();
}
export async function addToCart() {
    await modal.addToCart();
}
