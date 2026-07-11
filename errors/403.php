<?php
require __DIR__ . '/layout.php';

smks3_render_error_page(
    403,
    'Akses Ditolak',
    'Anda tidak mempunyai kebenaran untuk melihat halaman ini.',
    'Jika anda rasa ini satu kesilapan, sila hubungi pentadbir sekolah.'
);
