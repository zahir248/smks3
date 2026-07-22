<?php

declare(strict_types=1);

function smks3_default_pibg_content(): array
{
    return [
        'title' => 'Jawatankuasa PIBG',
        'subtitle' => 'Senarai Ahli Jawatankuasa PIBG SMK Seremban 3.',
        'button_label' => 'Buka / Muat Turun PDF',
        'pdfs' => ['images/SENARAI AJK PIBG SESI 2026.docx.pdf'],
    ];
}

/**
 * Parse PIBG PDF field (legacy single path or list) into relative path list.
 *
 * @return list<string>
 */
function smks3_pibg_parse_pdfs(mixed $raw): array
{
    if (is_array($raw)) {
        $list = $raw;
    } else {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return [];
        }
        if (str_starts_with($raw, '[')) {
            $decoded = json_decode($raw, true);
            $list = is_array($decoded) ? $decoded : [$raw];
        } else {
            $list = [$raw];
        }
    }

    $out = [];
    $seen = [];
    foreach ($list as $item) {
        $path = trim(str_replace('\\', '/', (string) $item));
        if ($path === '' || str_contains($path, '..') || isset($seen[$path])) {
            continue;
        }
        $seen[$path] = true;
        $out[] = $path;
    }
    return $out;
}

/**
 * @return list<string> Existing public PDF paths for display/edit.
 */
function smks3_pibg_pdf_srcs(mixed $raw): array
{
    $out = [];
    foreach (smks3_pibg_parse_pdfs($raw) as $path) {
        $src = smks3_pibg_pdf_src($path);
        if ($src === '') {
            continue;
        }
        if (preg_match('#^https?://#i', $src) || is_file(BASE_PATH . '/' . $src)) {
            $out[] = $src;
        }
    }
    return $out;
}

function smks3_get_pibg_content(): array
{
    $defaults = smks3_default_pibg_content();
    $stored = smks3_get_json_content('jawatankuasa_pibg', []);
    if (!is_array($stored) || $stored === []) {
        return $defaults;
    }

    foreach (['title', 'subtitle', 'button_label'] as $key) {
        $val = trim((string) ($stored[$key] ?? ''));
        if ($val !== '') {
            $defaults[$key] = $val;
        }
    }

    if (array_key_exists('pdfs', $stored)) {
        $defaults['pdfs'] = smks3_pibg_parse_pdfs($stored['pdfs']);
    } elseif (array_key_exists('pdf', $stored)) {
        // Migrate legacy single PDF into list (allow empty once cleared).
        $defaults['pdfs'] = smks3_pibg_parse_pdfs($stored['pdf']);
    }

    return $defaults;
}

function smks3_save_pibg_content(array $content): bool
{
    $defaults = smks3_default_pibg_content();
    $payload = [
        'title' => trim((string) ($content['title'] ?? $defaults['title'])) ?: $defaults['title'],
        'subtitle' => trim((string) ($content['subtitle'] ?? $defaults['subtitle'])),
        'button_label' => trim((string) ($content['button_label'] ?? $defaults['button_label'])) ?: $defaults['button_label'],
        'pdfs' => smks3_pibg_parse_pdfs($content['pdfs'] ?? []),
    ];
    // Keep a legacy `pdf` mirror (first file) for older readers.
    $payload['pdf'] = $payload['pdfs'][0] ?? '';

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
