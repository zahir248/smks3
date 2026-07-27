<?php

declare(strict_types=1);

function smks3_drive_folder_url(string $folderId): string
{
    $folderId = trim($folderId);
    if ($folderId === '') {
        return '#';
    }
    return 'https://drive.google.com/drive/folders/' . $folderId;
}

function smks3_kurikulum_defaults(): array
{
    static $defaults = null;
    if ($defaults === null) {
        $file = __DIR__ . '/kurikulum-defaults.php';
        $defaults = is_file($file) ? (require $file) : [];
        if (!is_array($defaults)) {
            $defaults = [];
        }
    }
    return $defaults;
}

function smks3_ensure_kurikulum_cards_table(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS kurikulum_card (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            page_key VARCHAR(80) NOT NULL,
            section_key VARCHAR(80) NOT NULL DEFAULT \'main\',
            title VARCHAR(255) NOT NULL,
            description TEXT NULL,
            icon VARCHAR(80) NOT NULL DEFAULT \'bi-folder2-open\',
            href VARCHAR(500) NOT NULL DEFAULT \'\',
            is_external TINYINT(1) NOT NULL DEFAULT 0,
            btn_label VARCHAR(120) NOT NULL DEFAULT \'\',
            links_json LONGTEXT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            KEY idx_kurikulum_page (page_key, section_key, sort_order, id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );
}

function smks3_is_external_kurikulum_url(string $href): bool
{
    return (bool) preg_match('#^https?://#i', trim($href));
}

/** Google Sheets / Drive / Docs — staff resource links (not Looker Studio). */
function smks3_is_staff_doc_url(string $href): bool
{
    $href = trim($href);
    if (!smks3_is_external_kurikulum_url($href)) {
        return false;
    }
    $host = strtolower((string) (parse_url($href, PHP_URL_HOST) ?: ''));
    $path = strtolower((string) (parse_url($href, PHP_URL_PATH) ?: ''));

    if ($host === 'sheets.google.com' || str_ends_with($host, '.sheets.google.com')) {
        return true;
    }
    if ($host === 'drive.google.com' || str_ends_with($host, '.drive.google.com')) {
        return true;
    }
    if ($host === 'docs.google.com' || str_ends_with($host, '.docs.google.com')) {
        // Sheets + Docs; exclude other docs.google.com products if needed later
        return str_starts_with($path, '/spreadsheets')
            || str_starts_with($path, '/document')
            || str_starts_with($path, '/file');
    }
    return false;
}

/** When true, public visitors may open staff Google/Drive links. Default: off. */
function smks3_public_external_docs_enabled(): bool
{
    return smks3_get_html_content('public_external_docs', '0') === '1';
}

function smks3_set_public_external_docs(bool $enabled): bool
{
    return smks3_save_site_content('public_external_docs', $enabled ? '1' : '0');
}

/** Admin/superadmin always; public only if setting enabled. */
function smks3_can_open_staff_external_url(): bool
{
    if (smks3_is_editor()) {
        return true;
    }
    return smks3_public_external_docs_enabled();
}

/**
 * Resolve href/attrs for kurikulum external staff docs (hide real URL when locked).
 *
 * @return array{href:string,attrs:string,locked:bool,class:string}
 */
function smks3_staff_external_link_meta(string $href): array
{
    $href = trim($href);
    $isExt = smks3_is_external_kurikulum_url($href);
    $isStaff = $isExt && smks3_is_staff_doc_url($href);
    if (!$isStaff || smks3_can_open_staff_external_url()) {
        return [
            'href' => $href !== '' ? $href : '#',
            'attrs' => $isExt ? 'target="_blank" rel="noopener noreferrer"' : '',
            'locked' => false,
            'class' => '',
        ];
    }
    return [
        'href' => '#',
        'attrs' => 'data-smks3-login-required="1" aria-disabled="true" title="Log masuk kakitangan diperlukan untuk membuka fail ini"',
        'locked' => true,
        'class' => 'smks3-external-locked',
    ];
}

function smks3_normalize_kurikulum_links($links): array
{
    if (is_string($links)) {
        $decoded = json_decode($links, true);
        $links = is_array($decoded) ? $decoded : [];
    }
    if (!is_array($links)) {
        return [];
    }
    $out = [];
    foreach ($links as $link) {
        if (!is_array($link)) {
            continue;
        }
        $title = trim((string) ($link['title'] ?? ''));
        $href = trim((string) ($link['href'] ?? ''));
        if ($title === '') {
            continue;
        }
        $out[] = ['title' => $title, 'href' => $href !== '' ? $href : '#'];
    }
    return $out;
}

function smks3_parse_kurikulum_links_text(string $text): array
{
    $out = [];
    foreach (preg_split('/\r\n|\n|\r/', $text) ?: [] as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        if (str_contains($line, '|')) {
            [$title, $href] = array_map('trim', explode('|', $line, 2));
        } else {
            $title = $line;
            $href = '#';
        }
        if ($title === '') {
            continue;
        }
        $out[] = ['title' => $title, 'href' => $href !== '' ? $href : '#'];
    }
    return $out;
}

function smks3_format_kurikulum_links_text(array $links): string
{
    $lines = [];
    foreach (smks3_normalize_kurikulum_links($links) as $link) {
        $lines[] = $link['title'] . ' | ' . $link['href'];
    }
    return implode("\n", $lines);
}

function smks3_seed_kurikulum_page(PDO $pdo, string $pageKey): void
{
    $all = smks3_kurikulum_defaults();
    if (!isset($all[$pageKey]) || !is_array($all[$pageKey])) {
        return;
    }
    $cards = $all[$pageKey]['cards'] ?? [];
    if (!is_array($cards) || $cards === []) {
        return;
    }
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM kurikulum_card WHERE page_key = ?');
    $stmt->execute([$pageKey]);
    if ((int) $stmt->fetchColumn() > 0) {
        return;
    }

    $ins = $pdo->prepare(
        'INSERT INTO kurikulum_card
        (page_key, section_key, title, description, icon, href, is_external, btn_label, links_json, sort_order)
        VALUES (?,?,?,?,?,?,?,?,?,?)'
    );
    $order = 0;
    foreach ($cards as $card) {
        if (!is_array($card)) {
            continue;
        }
        $title = trim((string) ($card['title'] ?? ''));
        if ($title === '') {
            continue;
        }
        $links = smks3_normalize_kurikulum_links($card['links'] ?? []);
        $ins->execute([
            $pageKey,
            trim((string) ($card['section_key'] ?? 'main')) ?: 'main',
            $title,
            trim((string) ($card['description'] ?? '')),
            trim((string) ($card['icon'] ?? 'bi-folder2-open')) ?: 'bi-folder2-open',
            trim((string) ($card['href'] ?? '')),
            !empty($card['is_external']) ? 1 : 0,
            trim((string) ($card['btn_label'] ?? '')),
            $links === [] ? null : json_encode($links, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ++$order,
        ]);
    }
}

function smks3_kurikulum_href_is_placeholder(string $href): bool
{
    $href = trim($href);
    return $href === '' || $href === '#';
}

/**
 * Merge link lists: keep editor values, fill placeholder hrefs from defaults,
 * and append default links that are missing by title.
 *
 * @param list<array{title:string,href:string}> $existing
 * @param list<array{title:string,href:string}> $defaults
 * @return list<array{title:string,href:string}>
 */
function smks3_merge_kurikulum_links_with_defaults(array $existing, array $defaults): array
{
    $defaults = smks3_normalize_kurikulum_links($defaults);
    $existing = smks3_normalize_kurikulum_links($existing);

    if ($defaults === []) {
        return $existing;
    }

    // Replace placeholder-only lists (e.g. "Empty | #") with real defaults.
    $onlyPlaceholders = true;
    foreach ($existing as $link) {
        $title = strtoupper(trim((string) ($link['title'] ?? '')));
        if ($title !== 'EMPTY' && !smks3_kurikulum_href_is_placeholder((string) ($link['href'] ?? ''))) {
            $onlyPlaceholders = false;
            break;
        }
    }
    if ($existing === [] || $onlyPlaceholders) {
        return $defaults;
    }

    $defaultByTitle = [];
    foreach ($defaults as $link) {
        $defaultByTitle[strtoupper($link['title'])] = $link;
    }

    $out = [];
    $have = [];
    foreach ($existing as $link) {
        $key = strtoupper($link['title']);
        if ($key === 'EMPTY') {
            continue;
        }
        $have[$key] = true;
        if (smks3_kurikulum_href_is_placeholder($link['href']) && isset($defaultByTitle[$key])) {
            $defHref = $defaultByTitle[$key]['href'];
            if (!smks3_kurikulum_href_is_placeholder($defHref)) {
                $link['href'] = $defHref;
            }
        }
        $out[] = $link;
    }

    foreach ($defaults as $link) {
        $key = strtoupper($link['title']);
        if (isset($have[$key])) {
            continue;
        }
        $out[] = $link;
        $have[$key] = true;
    }

    return $out;
}

/**
 * Soft-sync defaults into DB without wiping CMS edits:
 * - insert missing cards (matched by title)
 * - fill empty / "#" hrefs from defaults
 * - fill placeholder link hrefs + append missing links
 * - fill empty btn_label from defaults
 */
function smks3_sync_kurikulum_page_from_defaults(PDO $pdo, string $pageKey): void
{
    $all = smks3_kurikulum_defaults();
    if (!isset($all[$pageKey]) || !is_array($all[$pageKey])) {
        return;
    }
    $defaults = $all[$pageKey]['cards'] ?? [];
    if (!is_array($defaults) || $defaults === []) {
        return;
    }

    smks3_ensure_kurikulum_cards_table($pdo);
    smks3_seed_kurikulum_page($pdo, $pageKey);

    $stmt = $pdo->prepare(
        'SELECT * FROM kurikulum_card WHERE page_key = ? ORDER BY sort_order ASC, id ASC'
    );
    $stmt->execute([$pageKey]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    $byTitle = [];
    $maxOrder = 0;
    foreach ($rows as $row) {
        $title = strtoupper(trim((string) ($row['title'] ?? '')));
        if ($title !== '') {
            $byTitle[$title] = $row;
        }
        $maxOrder = max($maxOrder, (int) ($row['sort_order'] ?? 0));
    }

    $ins = $pdo->prepare(
        'INSERT INTO kurikulum_card
        (page_key, section_key, title, description, icon, href, is_external, btn_label, links_json, sort_order)
        VALUES (?,?,?,?,?,?,?,?,?,?)'
    );
    $upd = $pdo->prepare(
        'UPDATE kurikulum_card
         SET href = ?, is_external = ?, btn_label = ?, links_json = ?
         WHERE id = ?'
    );

    foreach ($defaults as $card) {
        if (!is_array($card)) {
            continue;
        }
        $title = trim((string) ($card['title'] ?? ''));
        if ($title === '') {
            continue;
        }
        $titleKey = strtoupper($title);
        $defLinks = smks3_normalize_kurikulum_links($card['links'] ?? []);
        $defHref = trim((string) ($card['href'] ?? ''));
        $defBtn = trim((string) ($card['btn_label'] ?? ''));
        $defExternal = !empty($card['is_external']) ? 1 : 0;
        if (!$defExternal && smks3_is_external_kurikulum_url($defHref)) {
            $defExternal = 1;
        }

        if (!isset($byTitle[$titleKey])) {
            $ins->execute([
                $pageKey,
                trim((string) ($card['section_key'] ?? 'main')) ?: 'main',
                $title,
                trim((string) ($card['description'] ?? '')),
                trim((string) ($card['icon'] ?? 'bi-folder2-open')) ?: 'bi-folder2-open',
                $defHref,
                $defExternal,
                $defBtn,
                $defLinks === [] ? null : json_encode($defLinks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ++$maxOrder,
            ]);
            continue;
        }

        $row = $byTitle[$titleKey];
        $id = (int) ($row['id'] ?? 0);
        if ($id <= 0) {
            continue;
        }

        $href = trim((string) ($row['href'] ?? ''));
        $btn = trim((string) ($row['btn_label'] ?? ''));
        $external = !empty($row['is_external']) ? 1 : 0;
        $links = smks3_normalize_kurikulum_links($row['links_json'] ?? []);

        $changed = false;

        if (smks3_kurikulum_href_is_placeholder($href) && !smks3_kurikulum_href_is_placeholder($defHref)) {
            $href = $defHref;
            $external = $defExternal;
            $changed = true;
        }

        if ($btn === '' && $defBtn !== '') {
            $btn = $defBtn;
            $changed = true;
        } elseif ($btn === 'Buka Folder' && $defBtn !== '' && $defBtn !== $btn) {
            $btn = $defBtn;
            $changed = true;
        }

        $mergedLinks = smks3_merge_kurikulum_links_with_defaults($links, $defLinks);
        if ($mergedLinks !== $links) {
            $links = $mergedLinks;
            $changed = true;
        }

        if (!$changed) {
            continue;
        }

        $upd->execute([
            $href,
            $external,
            $btn,
            $links === [] ? null : json_encode($links, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $id,
        ]);
    }
}

function smks3_get_kurikulum_cards(PDO $pdo, string $pageKey, ?string $sectionKey = null): array
{
    smks3_ensure_kurikulum_cards_table($pdo);
    smks3_seed_kurikulum_page($pdo, $pageKey);
    smks3_sync_kurikulum_page_from_defaults($pdo, $pageKey);

    if ($sectionKey !== null) {
        $stmt = $pdo->prepare(
            'SELECT * FROM kurikulum_card WHERE page_key = ? AND section_key = ? ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute([$pageKey, $sectionKey]);
    } else {
        $stmt = $pdo->prepare(
            'SELECT * FROM kurikulum_card WHERE page_key = ? ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute([$pageKey]);
    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    foreach ($rows as &$row) {
        $row['links'] = smks3_normalize_kurikulum_links($row['links_json'] ?? []);
        $row['is_external'] = !empty($row['is_external']);
        $row['links_text'] = smks3_format_kurikulum_links_text($row['links']);
    }
    unset($row);
    return $rows;
}

function smks3_get_kurikulum_meta(string $pageKey): array
{
    $defaults = smks3_kurikulum_defaults()[$pageKey]['meta'] ?? [
        'intro' => '',
        'sections' => ['main' => ['title' => '', 'subtitle' => '']],
    ];
    $intro = (string) ($defaults['intro'] ?? '');
    $sections = is_array($defaults['sections'] ?? null) ? $defaults['sections'] : [];

    $stored = smks3_get_json_content('kurikulum_meta_' . $pageKey, []);
    if (isset($stored['intro']) && is_string($stored['intro']) && trim($stored['intro']) !== '') {
        $intro = trim($stored['intro']);
    }
    if (isset($stored['sections']) && is_array($stored['sections'])) {
        foreach ($stored['sections'] as $key => $sec) {
            if (!is_array($sec)) {
                continue;
            }
            $key = (string) $key;
            if (!isset($sections[$key]) || !is_array($sections[$key])) {
                $sections[$key] = ['title' => '', 'subtitle' => ''];
            }
            if (isset($sec['title']) && trim((string) $sec['title']) !== '') {
                $sections[$key]['title'] = trim((string) $sec['title']);
            }
            if (array_key_exists('subtitle', $sec)) {
                $sections[$key]['subtitle'] = trim((string) $sec['subtitle']);
            }
        }
    }

    return ['intro' => $intro, 'sections' => $sections];
}

function smks3_save_kurikulum_meta(string $pageKey, array $meta): bool
{
    $payload = [
        'intro' => trim((string) ($meta['intro'] ?? '')),
        'sections' => [],
    ];
    $sections = $meta['sections'] ?? [];
    if (is_array($sections)) {
        foreach ($sections as $key => $sec) {
            if (!is_array($sec)) {
                continue;
            }
            $payload['sections'][(string) $key] = [
                'title' => trim((string) ($sec['title'] ?? '')),
                'subtitle' => trim((string) ($sec['subtitle'] ?? '')),
            ];
        }
    }
    return smks3_save_site_content(
        'kurikulum_meta_' . $pageKey,
        json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}'
    );
}

/** Load cards + meta for a kurikulum page controller. */
function smks3_kurikulum_page_vars(string $pageKey): array
{
    $pdo = getConnection();
    $is_editor = smks3_can_edit_page($pageKey);
    $kurikulum_page_key = $pageKey;
    $kurikulum_meta = smks3_get_kurikulum_meta($pageKey);
    $kurikulum_cards = smks3_get_kurikulum_cards($pdo, $pageKey);
    $kurikulum_by_section = [];
    foreach ($kurikulum_cards as $card) {
        $sec = (string) ($card['section_key'] ?? 'main');
        $kurikulum_by_section[$sec][] = $card;
    }
    return compact('pdo', 'is_editor', 'kurikulum_page_key', 'kurikulum_meta', 'kurikulum_cards', 'kurikulum_by_section');
}
