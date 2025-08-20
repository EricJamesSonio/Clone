class ItemManager {
  constructor() {
    if (!window.API_BASE_PATH) {
      throw new Error("API_BASE_PATH is not defined. Ensure config.js is loaded before item_management.js");
    }

    this.basePath = window.API_BASE_PATH.replace(/\/+$/, '');
    this.API_ITEMS = `${this.basePath}/items`;

    this.categorySelect = document.getElementById("categorySelect");
    this.subcategorySelect = document.getElementById("subcategorySelect");
    this.itemTableBody = document.querySelector("#itemTable tbody");
    this.addItemForm = document.getElementById("addItemForm");
    this.searchInput = document.getElementById("searchInput");
    this.suggestionsBox = document.getElementById("suggestionsBox");

    this.searchTimeout = null;
  }

  async init() {
    await this.loadCategories();
    await this.loadItems();
    this.bindEvents();
  }

  async loadCategories() {
    try {
      const res = await fetch(`${this.basePath}/categories`, { credentials: 'include' });
      const result = await res.json();

      if (!result.status || !Array.isArray(result.data)) {
        throw new Error("Invalid categories data");
      }

      this.categorySelect.innerHTML = result.data
        .map(cat => `<option value="${cat.id}">${cat.name}</option>`)
        .join("");
    } catch (err) {
      console.error("Error loading categories:", err);
    }
  }

  async loadSubcategories(categoryId) {
    try {
      const res = await fetch(`${this.basePath}/subcategories?category_id=${categoryId}`, { credentials: 'include' });
      const result = await res.json();

      if (!result || result.status === false || !Array.isArray(result.data)) {
        throw new Error('Invalid subcategories data');
      }

      this.subcategorySelect.innerHTML = result.data
        .map(sc => `<option value="${sc.id}">${sc.name}</option>`)
        .join("");
    } catch (err) {
      console.error("Error loading subcategories:", err);
    }
  }

  async loadItems() {
    try {
      const res = await fetch(`${this.API_ITEMS}?action=getAll`);
      const result = await res.json();

      if (!result.status || !Array.isArray(result.data)) {
        throw new Error("Invalid items data");
      }

      this.renderItems(result.data);
    } catch (err) {
      console.error("Error loading items:", err);
    }
  }

  renderItems(items) {
    this.itemTableBody.innerHTML = items.map(item => `
      <tr data-id="${item.id}">
        <td><input value="${item.name}" class="edit-name"></td>
        <td><input type="number" value="${item.price ?? 0}" step="0.01" class="edit-price"></td>
        <td>${item.category_name || ''}</td>
        <td>${item.subcategory_name || ''}</td>
        <td><textarea class="edit-desc">${item.description || ""}</textarea></td>
        <td>
          <button class="btnUpdate">Update</button>
          <button class="btnDelete">Delete</button>
        </td>
      </tr>
    `).join("");
  }

  async addItem(data) {
    try {
      const res = await fetch(`${this.API_ITEMS}?action=add`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });
      const result = await res.json();

      if (result.status) {
        this.showMessage("Item added successfully!", "success");
        this.addItemForm.reset();
        this.loadItems();
      } else {
        this.showMessage(result.message || "Failed to add item", "error");
      }
    } catch (err) {
      console.error("Error adding item:", err);
      this.showMessage("Error adding item", "error");
    }
  }

  async updateItem(data) {
    try {
      const res = await fetch(`${this.API_ITEMS}?action=update`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });
      const result = await res.json();

      if (result.status) {
        this.showMessage("Item updated successfully!", "success");
        this.loadItems();
      } else {
        this.showMessage(result.message || "Failed to update item", "error");
      }
    } catch (err) {
      console.error("Error updating item:", err);
      this.showMessage("Error updating item", "error");
    }
  }

  async deleteItem(id) {
    try {
      await fetch(`${this.API_ITEMS}?action=delete&id=${id}`, { method: "DELETE" });
      this.loadItems();
    } catch (err) {
      console.error("Error deleting item:", err);
    }
  }

  async searchInventory(query) {
    try {
      const res = await fetch(`${this.API_ITEMS}?action=searchInventory&query=${encodeURIComponent(query)}`, {
        credentials: 'include'
      });
      const response = await res.json();

      if (response.status && Array.isArray(response.data) && response.data.length > 0) {
        this.renderItems(response.data);
      } else {
        this.itemTableBody.innerHTML = `<tr><td colspan="6">No results found</td></tr>`;
      }
    } catch (err) {
      console.error('Search error:', err);
    }
  }

  showMessage(text, type = "success") {
    let msg = document.createElement("div");
    msg.className = `alert ${type}`;
    msg.textContent = text;
    document.body.appendChild(msg);
    setTimeout(() => msg.remove(), 3000);
  }

  bindEvents() {
    this.categorySelect.addEventListener("change", (e) => {
      this.loadSubcategories(e.target.value);
    });

    this.addItemForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const newItem = {
        name: document.getElementById("itemName").value,
        price: document.getElementById("itemPrice").value,
        category_id: this.categorySelect.value,
        subcategory_id: this.subcategorySelect.value,
        description: document.getElementById("itemDescription").value
      };
      this.addItem(newItem);
    });

    document.querySelector("#itemTable").addEventListener("click", (e) => {
      const row = e.target.closest("tr");
      if (!row) return;
      const id = row.dataset.id;

      if (e.target.classList.contains("btnUpdate")) {
        const updated = {
          id,
          name: row.querySelector(".edit-name").value,
          price: row.querySelector(".edit-price").value,
          description: row.querySelector(".edit-desc").value
        };
        this.updateItem(updated);
      }

      if (e.target.classList.contains("btnDelete")) {
        if (confirm("Delete this item?")) {
          this.deleteItem(id);
        }
      }
    });

    this.searchInput?.addEventListener('input', () => {
      const query = this.searchInput.value.trim();
      if (query.length < 1) {
        this.loadItems();
        return;
      }
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        this.searchInventory(query);
      }, 300);
    });

    document.getElementById("btnBack").addEventListener("click", () => {
      window.location.href = "../inventory.html";
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const manager = new ItemManager();
  manager.init();
});
