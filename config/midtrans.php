<?php
// config/midtrans.php

// Ambil dari: https://dashboard.sandbox.midtrans.com/settings/config_info
define('MIDTRANS_SERVER_KEY',    'SB-Mid-server-yZbU_u1NCKEyGDsZs_UVEmzn');
define('MIDTRANS_CLIENT_KEY',    'SB-Mid-client-qz4dsSPdnxp1Ztqd');
define('MIDTRANS_IS_PRODUCTION', false); // true = live, false = sandbox

define(
    'MIDTRANS_SNAP_URL',
    MIDTRANS_IS_PRODUCTION
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js'
);

define(
    'MIDTRANS_API_URL',
    MIDTRANS_IS_PRODUCTION
        ? 'https://api.midtrans.com/v2'
        : 'https://api.sandbox.midtrans.com/v2'
);

define(
    'MIDTRANS_SNAP_API_URL',
    MIDTRANS_IS_PRODUCTION
        ? 'https://app.midtrans.com/snap/v1'
        : 'https://app.sandbox.midtrans.com/snap/v1'
);
