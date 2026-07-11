<?php
require __DIR__ . '/layout.php';

smks3_render_error_page(
    503,
    'Perkhidmatan Tidak Tersedia',
    'Portal sedang dalam penyelenggaraan atau tidak dapat diakses buat sementara waktu.',
    'Sila cuba lagi sebentar lagi. Terima kasih atas kesabaran anda.'
);
