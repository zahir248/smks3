<?php
require __DIR__ . '/layout.php';

smks3_render_error_page(
    401,
    'Log Masuk Diperlukan',
    'Halaman ini memerlukan pengesahan sebelum boleh diakses.',
    'Sila log masuk melalui Portal Kakitangan di laman utama jika anda kakitangan sekolah.'
);
