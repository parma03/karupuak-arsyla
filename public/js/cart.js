/* public/js/cart.js — KARUPUAK JANGEK ARSYLA */

const CART_URL = (window.APP_URL || "") + "/kaknani/karupuak-arsyla";

/* ── UPDATE QTY ──────────────────────────────────────── */
function updateCart(productId, newQty) {
  if (newQty < 1) {
    removeFromCart(productId);
    return;
  }

  fetch(APP_URL + "/cart/update", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: `product_id=${productId}&qty=${newQty}`,
  })
    .then((r) => r.json())
    .then((res) => {
      if (res.success) {
        // Update qty display
        const qtyEl = document.getElementById("qty-" + productId);
        if (qtyEl) qtyEl.textContent = newQty;

        // Recalculate subtotal for the row
        const priceEl = document.querySelector(
          `#cart-item-${productId} .ka-cart-price`,
        );
        if (priceEl) {
          const price = parseFloat(priceEl.dataset.price || 0);
          if (price) {
            const subEl = document.getElementById("sub-" + productId);
            if (subEl)
              subEl.textContent =
                "Rp " + (price * newQty).toLocaleString("id-ID");
          }
        }

        // Update totals
        recalcCartTotal();

        // Update badge
        if (typeof updateCartBadge === "function")
          updateCartBadge(res.cart_count);
      } else {
        showToast(res.message || "Gagal update keranjang", "danger");
      }
    })
    .catch(() => showToast("Terjadi kesalahan", "danger"));
}

/* ── REMOVE ITEM ─────────────────────────────────────── */
function removeFromCart(productId) {
  if (!confirm("Hapus produk dari keranjang?")) return;

  fetch(APP_URL + "/cart/remove", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: `product_id=${productId}`,
  })
    .then((r) => r.json())
    .then((res) => {
      if (res.success) {
        const row = document.getElementById("cart-item-" + productId);
        if (row) {
          row.style.transition = "opacity .3s, transform .3s";
          row.style.opacity = "0";
          row.style.transform = "translateX(20px)";
          setTimeout(() => {
            row.remove();
            recalcCartTotal();
            checkEmptyCart();
          }, 300);
        }
        if (typeof updateCartBadge === "function")
          updateCartBadge(res.cart_count);
        if (typeof showToast === "function")
          showToast("Produk dihapus dari keranjang", "success");
      }
    })
    .catch(() => showToast("Terjadi kesalahan", "danger"));
}

/* ── RECALC TOTAL ────────────────────────────────────── */
function recalcCartTotal() {
  let total = 0;
  document.querySelectorAll('[id^="sub-"]').forEach((el) => {
    const num = parseFloat(el.textContent.replace(/[^0-9]/g, ""));
    total += num;
  });

  const fmt = "Rp " + total.toLocaleString("id-ID");
  const subtotalEl = document.getElementById("cart-subtotal");
  const totalEl = document.getElementById("cart-total");
  if (subtotalEl) subtotalEl.textContent = fmt;
  if (totalEl) totalEl.textContent = fmt;
}

/* ── EMPTY STATE ─────────────────────────────────────── */
function checkEmptyCart() {
  const items = document.querySelectorAll('[id^="cart-item-"]');
  if (items.length === 0) {
    location.reload();
  }
}
