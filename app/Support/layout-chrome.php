<?php

declare(strict_types=1);

function smks3_default_layout_content(): array
{
    return [
        'navbar_logo' => 'images/hero-logo.png',
        'footer_brand' => 'SMK Seremban 3',
        'footer_blurb' => 'Sekolah Menengah Kebangsaan dengan pendidikan berkualiti untuk masa depan pelajar.',
        'footer_contact_title' => 'Hubungi',
        'footer_social_title' => 'Ikuti Kami',
        'footer_copyright' => 'SMK Seremban 3. Hak Cipta Terpelihara.',
        'social' => [
            [
                'label' => 'Facebook',
                'icon' => 'bi-facebook',
                'href' => 'https://www.facebook.com/share/17rxCJHqUJ/',
            ],
            [
                'label' => 'TikTok',
                'icon' => 'bi-tiktok',
                'href' => 'https://www.tiktok.com/@smkseremban3?lang=en',
            ],
            [
                'label' => 'YouTube',
                'icon' => 'bi-youtube',
                'href' => 'https://www.youtube.com/@TVPSSSMKSEREMBAN3',
            ],
        ],
    ];
}

function smks3_normalize_social_link(array $link): array
{
    $label = trim((string) ($link['label'] ?? ''));
    $icon = trim((string) ($link['icon'] ?? 'bi-link-45deg')) ?: 'bi-link-45deg';
    if (!str_starts_with($icon, 'bi-')) {
        $icon = 'bi-' . ltrim($icon, '-');
    }
    $href = trim((string) ($link['href'] ?? '#')) ?: '#';
    return [
        'label' => $label !== '' ? $label : 'Pautan',
        'icon' => $icon,
        'href' => $href,
    ];
}

function smks3_parse_social_lines(string $text): array
{
    $out = [];
    foreach (preg_split('/\r\n|\n|\r/', $text) ?: [] as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $parts = array_map('trim', explode('|', $line));
        $out[] = smks3_normalize_social_link([
            'label' => $parts[0] ?? '',
            'icon' => $parts[1] ?? 'bi-link-45deg',
            'href' => $parts[2] ?? '#',
        ]);
    }
    return $out;
}

function smks3_format_social_lines(array $links): string
{
    $lines = [];
    foreach ($links as $link) {
        if (!is_array($link)) {
            continue;
        }
        $n = smks3_normalize_social_link($link);
        $lines[] = $n['label'] . ' | ' . $n['icon'] . ' | ' . $n['href'];
    }
    return implode("\n", $lines);
}

function smks3_get_layout_content(): array
{
    $defaults = smks3_default_layout_content();
    $stored = smks3_get_json_content('layout_chrome', []);
    if (!is_array($stored) || $stored === []) {
        return $defaults;
    }

    foreach (['navbar_logo', 'footer_brand', 'footer_blurb', 'footer_contact_title', 'footer_social_title', 'footer_copyright'] as $key) {
        $val = trim((string) ($stored[$key] ?? ''));
        if ($val !== '') {
            $defaults[$key] = $val;
        }
    }

    if (isset($stored['social'])) {
        if (is_string($stored['social'])) {
            $list = smks3_parse_social_lines($stored['social']);
        } elseif (is_array($stored['social'])) {
            $list = [];
            foreach ($stored['social'] as $link) {
                if (is_array($link)) {
                    $list[] = smks3_normalize_social_link($link);
                }
            }
        } else {
            $list = [];
        }
        if ($list !== []) {
            $defaults['social'] = $list;
        }
    }

    return $defaults;
}

function smks3_save_layout_content(array $content): bool
{
    $defaults = smks3_default_layout_content();
    $payload = [];
    foreach (['navbar_logo', 'footer_brand', 'footer_blurb', 'footer_contact_title', 'footer_social_title', 'footer_copyright'] as $key) {
        $val = trim((string) ($content[$key] ?? $defaults[$key]));
        $payload[$key] = $val !== '' ? $val : $defaults[$key];
    }

    $social = $content['social'] ?? $defaults['social'];
    if (is_string($social)) {
        $social = smks3_parse_social_lines($social);
    }
    if (!is_array($social)) {
        $social = $defaults['social'];
    }
    $payload['social'] = [];
    foreach ($social as $link) {
        if (is_array($link)) {
            $payload['social'][] = smks3_normalize_social_link($link);
        }
    }
    if ($payload['social'] === []) {
        $payload['social'] = $defaults['social'];
    }

    return smks3_save_site_content(
        'layout_chrome',
        json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}'
    );
}

function smks3_layout_asset_src(string $path): string
{
    $path = trim($path);
    if ($path === '') {
        return 'images/hero-logo.png';
    }
    if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'uploads/') || str_starts_with($path, 'images/')) {
        return $path;
    }
    return 'uploads/layout/' . ltrim($path, '/');
}
