/* public/js/app.js — KARUPUAK Kulit ARSYLA */

const APP_URL =
  document.querySelector('meta[name="app-url"]')?.content ||
  window.location.origin + "/karupuak-arsyla";

/* ── INTERCEPT SEMUA FORM DENGAN data-confirm ────────── */
document.addEventListener("submit", function (e) {
  const form = e.target;
  const msg = form.dataset.confirm;
  if (!msg) return;

  e.preventDefault();
  confirmDialog(
    msg,
    () => {
      form.removeAttribute("data-confirm");
      form.submit();
    },
    {
      title: form.dataset.confirmTitle || "Konfirmasi",
      confirmText: form.dataset.confirmOk || "Ya, Lanjutkan",
      cancelText: form.dataset.confirmCancel || "Batal",
      confirmColor: form.dataset.confirmColor || "#dc3545",
    },
  );
});

/* ── ADD TO CART ─────────────────────────────────────── */
function addToCart(productId, btn) {
  if (btn.dataset.loginRequired) {
    window.location.href = APP_URL + "/login";
    return;
  }

  const original = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

  fetch(APP_URL + "/cart/add", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: `product_id=${productId}&qty=1`,
  })
    .then((r) => r.json())
    .then((res) => {
      if (res.redirect) {
        window.location.href = res.redirect;
        return;
      }
      if (res.success) {
        updateCartBadge(res.cart_count);
        btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Ditambah!';
        btn.style.background = "#2c7a4b";
        btn.style.color = "#fff";
        setTimeout(() => {
          btn.innerHTML = original;
          btn.disabled = false;
          btn.style = "";
        }, 1800);
        showToast(res.message, "success");
      } else {
        showToast(res.message || "Gagal ditambahkan", "danger");
        btn.innerHTML = original;
        btn.disabled = false;
      }
    })
    .catch(() => {
      btn.innerHTML = original;
      btn.disabled = false;
    });
}

function updateCartBadge(count) {
  let badge = document.getElementById("cart-badge");
  if (!badge && count > 0) {
    const cartLink = document.querySelector('a[href$="/cart"]');
    if (cartLink) {
      badge = document.createElement("span");
      badge.id = "cart-badge";
      badge.className =
        "position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger";
      cartLink.style.position = "relative";
      cartLink.appendChild(badge);
    }
  }
  if (badge) {
    badge.textContent = count;
    badge.style.display = count > 0 ? "" : "none";
  }
}

/* ── TOAST ───────────────────────────────────────────── */
function showToast(msg, type = "success") {
  const id = "toast-" + Date.now();
  const icon =
    type === "success" ? "check-circle-fill" : "exclamation-circle-fill";
  const html = `
    <div id="${id}" class="toast align-items-center text-bg-${type} border-0 mb-2" role="alert" style="min-width:260px">
      <div class="d-flex">
        <div class="toast-body"><i class="bi bi-${icon} me-2"></i>${msg}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>`;

  let wrap = document.getElementById("toast-wrap");
  if (!wrap) {
    wrap = document.createElement("div");
    wrap.id = "toast-wrap";
    wrap.style.cssText = "position:fixed;bottom:24px;right:24px;z-index:9999";
    document.body.appendChild(wrap);
  }
  wrap.insertAdjacentHTML("beforeend", html);
  const el = document.getElementById(id);
  new bootstrap.Toast(el, { delay: 3000 }).show();
  el.addEventListener("hidden.bs.toast", () => el.remove());
}

/* ── PASSWORD TOGGLE ─────────────────────────────────── */
function togglePassword(inputId, btn) {
  const input = document.getElementById(inputId);
  const icon = btn.querySelector("i");
  if (input.type === "password") {
    input.type = "text";
    icon.className = "bi bi-eye-slash";
  } else {
    input.type = "password";
    icon.className = "bi bi-eye";
  }
}

/* ── AUTO-DISMISS ALERTS ─────────────────────────────── */
document.querySelectorAll(".alert:not(.alert-permanent)").forEach((el) => {
  setTimeout(() => {
    const bsAlert = new bootstrap.Alert(el);
    bsAlert.close();
  }, 4500);
});

/* ── SWEETALERT2 ──── */
function confirmDialog(message, onConfirm, options = {}) {
  const defaults = {
    title: options.title || "Konfirmasi",
    text: message,
    icon: options.icon || "warning",
    showCancelButton: true,
    confirmButtonText: options.confirmText || "Ya, Lanjutkan",
    cancelButtonText: options.cancelText || "Batal",
    confirmButtonColor: options.confirmColor || "#dc3545",
    cancelButtonColor: options.cancelColor || "#6c757d",
    reverseButtons: true,
    focusCancel: true,
    customClass: {
      popup: "rounded-4 shadow-sm",
      confirmButton: "btn btn-sm px-4",
      cancelButton: "btn btn-sm px-4 me-2",
    },
    buttonsStyling: false,
  };

  Swal.fire(defaults).then((result) => {
    if (result.isConfirmed && typeof onConfirm === "function") {
      onConfirm();
    }
  });
}
