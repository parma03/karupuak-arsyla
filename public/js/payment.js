/* public/js/payment.js — KARUPUAK Kulit ARSYLA */

/**
 * Utility untuk halaman payment — digunakan bersama Midtrans Snap.js
 * Snap.js sendiri di-load dari CDN Midtrans di view payment.php
 */

function formatRupiah(amount) {
  return "Rp " + parseFloat(amount).toLocaleString("id-ID");
}

function showPaymentLoading(btn) {
  btn.disabled = true;
  btn.dataset.originalText = btn.innerHTML;
  btn.innerHTML =
    '<span class="spinner-border spinner-border-sm me-2"></span>Membuka pembayaran...';
}

function resetPaymentBtn(btn) {
  btn.disabled = false;
  btn.innerHTML = btn.dataset.originalText || "Bayar Sekarang";
}
