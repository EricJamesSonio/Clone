class InventoryAPI {
  constructor() {
    if (!window.API_BASE_PATH) {
      throw new Error("API_BASE_PATH is not defined. Make sure config.js is loaded first.");
    }

    this.API = `${window.API_BASE_PATH.replace(/\/+$/, '')}/inventory`;
  }

  async getSetting() {
    try {
      const res = await fetch(this.API);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    } catch (err) {
      console.error('getSetting error', err);
      return { status: false, error: err.message };
    }
  }

  async setThreshold(threshold, updated_by) {
    const t = parseInt(threshold, 10);
    if (Number.isNaN(t) || t < 0) {
      return { status: false, error: 'Invalid threshold' };
    }
    try {
      const res = await fetch(this.API, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          global_threshold: t,
          updated_by: updated_by ? parseInt(updated_by, 10) : null
        })
      });
      if (!res.ok) {
        const txt = await res.text();
        throw new Error(`HTTP ${res.status}: ${txt}`);
      }
      return res.json();
    } catch (err) {
      console.error('setThreshold error', err);
      return { status: false, error: err.message };
    }
  }

  async getLowStock() {
    try {
      const res = await fetch(`${this.API}?action=low-stock`);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    } catch (err) {
      console.error('getLowStock error', err);
      return { status: false, error: err.message, data: [] };
    }
  }
}

class InventoryUI {
  constructor(api) {
    this.api = api;
    this.btnSet = document.getElementById('btnSet');
    this.thresholdInput = document.getElementById('threshold');
    this.lowStockList = document.getElementById('lowStockList');
    this.loggedInUser = JSON.parse(localStorage.getItem("loggedInUser") || "{}");

    this.init();
  }

  async init() {
    await this.loadSetting();
    await this.renderLowStock();
    this.bindEvents();
  }

  bindEvents() {
    if (this.btnSet) {
      this.btnSet.addEventListener('click', async () => {
        const t = this.thresholdInput?.value;
        const u = this.loggedInUser.id || null;

        this.btnSet.disabled = true;
        this.btnSet.textContent = 'Saving...';

        const result = await this.api.setThreshold(t, u);

        this.btnSet.disabled = false;
        this.btnSet.textContent = 'Set Threshold';

        if (result.status) {
          alert('Threshold set');
          await this.renderLowStock();
        } else {
          alert('Error: ' + (result.error || JSON.stringify(result)));
        }
      });
    }
  }

  async loadSetting() {
    const s = await this.api.getSetting();
    if (s && s.status) {
      if (this.thresholdInput) {
        this.thresholdInput.value = s.data.global_threshold ?? 0;
      }
    } else {
      console.warn('Could not fetch setting', s.error);
    }
  }

  async renderLowStock() {
    if (!this.lowStockList) return;

    this.lowStockList.innerHTML = '<li>Loading...</li>';
    const j = await this.api.getLowStock();
    this.lowStockList.innerHTML = '';

    if (!j || !j.data || j.data.length === 0) {
      this.lowStockList.innerHTML = '<li>No low-stock items (or threshold = 0)</li>';
      return;
    }

    j.data.forEach(it => {
      const li = document.createElement('li');
      li.textContent = `${it.name} — qty: ${it.quantity} — price: ${it.price}`;
      this.lowStockList.appendChild(li);
    });
  }
}

// ===== Initialization =====
document.addEventListener("DOMContentLoaded", () => {
  const inventoryAPI = new InventoryAPI(); // No need to pass basePath anymore
  new InventoryUI(inventoryAPI);
});
