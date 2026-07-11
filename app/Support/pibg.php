<?php

declare(strict_types=1);

function smks3_default_pibg_content(): array
{
    return [
        'title' => 'Jawatankuasa PIBG',
        'subtitle' => 'Senarai Ahli Jawatankuasa PIBG SMK Seremban 3.',
        'button_label' => 'Buka PDF di Tab Baru',
        'pdf' => 'images/SENARAI AJK PIBG SESI 2026.docx.pdf',
    ];
}

function smks3_get_pibg_content(): array
{
    $defaults = smks3_default_pibg_content();
    $stored = smks3_get_json_content('jawatankuasa_pibg', []);
    if (!is_array($stored) || $stored === []) {
        return $defaults;
    }

    foreach (['title', 'subtitle', 'button_label', 'pdf'] as $key) {
        $val = trim((string) ($stored[$key] ?? ''));
        if ($val !== '') {
            $defaults[$key] = $val;
        }
    }
    return $defaults;
}

function smks3_save_pibg_content(array $content): bool
{
    $defaults = smks3_default_pibg_content();
    $payload = [];
    foreach (['title', 'subtitle', 'button_label', 'pdf'] as $key) {
        $val = trim((string) ($content[$key] ?? $defaults[$key]));
        $payload[$key] = $val !== '' ? $val : $defaults[$key];
    }
    return smks3_save_site_content(
        'jawatankuasa_pibg',
        json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}'
    );
}

function smks3_pibg_pdf_src(string $path): string
{
    $path = trim($path);
    if ($path === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'uploads/') || str_starts_with($path, 'images/') || str_starts_with($path, 'files/')) {
        return $path;
    }
    return 'uploads/pibg/' . ltrim($path, '/');
}
