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

/**
 * Canonical site logo path (navbar, hero, login, favicon, lencana).
 * Prefers lencana_lagu_sekolah.image, then layout navbar_logo, then default.
 */
function smks3_site_logo_src(): string
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $fallback = 'images/hero-logo.png';
    try {
        require_once BASE_PATH . '/config/database.php';
        $pdo = getConnection();
        $raw = (string) ($pdo->query('SELECT image FROM lencana_lagu_sekolah WHERE id = 1 LIMIT 1')->fetchColumn() ?: '');
        $name = basename(str_replace('\\', '/', trim($raw, " \t\n\r\0\x0B\"'")));
        if ($name !== '' && !str_contains($name, '..')) {
            $candidate = str_starts_with($raw, 'images/') || str_starts_with($raw, 'uploads/')
                ? ltrim(str_replace('\\', '/', $raw), '/')
                : ('images/' . $name);
            $candidate = preg_replace('#^/+#', '', $candidate) ?: $candidate;
            if (is_file(BASE_PATH . '/' . $candidate)) {
                return $cached = $candidate;
            }
        }
    } catch (Throwable $e) {
        // fall through
    }

    try {
        $layout = smks3_get_layout_content();
        $fromLayout = smks3_layout_asset_src((string) ($layout['navbar_logo'] ?? ''));
        if ($fromLayout !== '' && is_file(BASE_PATH . '/' . $fromLayout)) {
            return $cached = $fromLayout;
        }
    } catch (Throwable $e) {
        // fall through
    }

    return $cached = $fallback;
}

/**
 * Sync site-wide logo path into layout chrome (navbar / SEO).
 */
function smks3_set_site_logo(string $relativePath): void
{
    $relativePath = trim(str_replace('\\', '/', $relativePath));
    if ($relativePath === '' || str_contains($relativePath, '..')) {
        return;
    }
    if (!str_starts_with($relativePath, 'images/') && !str_starts_with($relativePath, 'uploads/')) {
        $relativePath = 'images/' . basename($relativePath);
    }
    $layout = smks3_get_layout_content();
    $layout['navbar_logo'] = $relativePath;
    smks3_save_layout_content($layout);
}

/**
 * Favicon link attributes derived from the site logo (PNG/JPEG/WebP/ICO).
 *
 * @return array{href:string,type:string}
 */
function smks3_site_favicon(): array
{
    $logo = smks3_site_logo_src();
    $ext = strtolower(pathinfo($logo, PATHINFO_EXTENSION));
    $types = [
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
    ];
    if (isset($types[$ext]) && is_file(BASE_PATH . '/' . $logo)) {
        return ['href' => $logo, 'type' => $types[$ext]];
    }
    return ['href' => 'images/favicon-smks3.ico', 'type' => 'image/x-icon'];
}
