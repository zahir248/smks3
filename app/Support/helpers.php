<?php
/**
 * Helper functions for School Website
 */

function smks3_ensure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        smks3_enforce_idle_editor_session();
        return;
    }
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443)
        || (strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
    smks3_enforce_idle_editor_session();
}

/** Idle timeout for logged-in editors (seconds). */
function smks3_session_idle_ttl(): int
{
    return 30 * 60; // 30 minit
}

/** Clear portal editor auth keys from the current session. */
function smks3_clear_editor_session(): void
{
    unset(
        $_SESSION['username'],
        $_SESSION['role'],
        $_SESSION['unit_id'],
        $_SESSION['unit_name'],
        $_SESSION['edit_preview'],
        $_SESSION['rbac_permissions'],
        $_SESSION['last_activity']
    );
}

/**
 * Auto log out editors after idle timeout.
 * Updates last_activity on each request while still logged in.
 */
function smks3_enforce_idle_editor_session(): void
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;
    if (empty($_SESSION['username'])) {
        return;
    }
    $ttl = smks3_session_idle_ttl();
    $now = time();
    $last = (int) ($_SESSION['last_activity'] ?? 0);
    if ($last > 0 && ($now - $last) > $ttl) {
        smks3_clear_editor_session();
        return;
    }
    $_SESSION['last_activity'] = $now;
}

/** Current portal route slug (e.g. index, profil-sekolah). */
function smks3_current_route(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($scriptDir !== '/' && $scriptDir !== '' && str_starts_with($uri, $scriptDir)) {
        $uri = substr($uri, strlen($scriptDir)) ?: '/';
    }
    $uri = trim(rawurldecode($uri), '/');
    if ($uri === '' || $uri === 'index.php' || $uri === 'index') {
        return 'index';
    }
    $uri = preg_replace('/\.php$/i', '', $uri) ?: 'index';
    return basename($uri);
}

/** Logged-in admin or superadmin (portal edit mode). */
function smks3_is_editor(): bool
{
    smks3_ensure_session();
    smks3_enforce_active_editor_session();
    if (empty($_SESSION['username'])) {
        return false;
    }
    $role = (string) ($_SESSION['role'] ?? '');
    return in_array($role, ['admin', 'superadmin'], true);
}

function smks3_editor_role(): string
{
    smks3_ensure_session();
    $role = (string) ($_SESSION['role'] ?? '');
    if ($role === 'superadmin') {
        return 'superadmin';
    }
    return 'admin';
}

/** Ensure users.is_active exists (1 = aktif, 0 = tidak aktif). */
function smks3_ensure_users_is_active_column(?PDO $pdo = null): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;
    try {
        $pdo = $pdo ?? getConnection();
        $col = $pdo->query("SHOW COLUMNS FROM users LIKE 'is_active'")->fetch(PDO::FETCH_ASSOC);
        if (!$col) {
            $pdo->exec('ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1');
        }
    } catch (Throwable $e) {
        // ignore
    }
}

/**
 * Whether a user row is allowed to access the portal.
 * Superadmin is always treated as active.
 */
function smks3_user_row_is_active(array $user): bool
{
    $role = (string) ($user['role'] ?? '');
    if ($role === 'superadmin') {
        return true;
    }
    if (!array_key_exists('is_active', $user)) {
        return true;
    }
    return (int) $user['is_active'] === 1;
}

/** If the current session user was deactivated, clear the session. */
function smks3_enforce_active_editor_session(): void
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;
    smks3_ensure_session();
    $username = (string) ($_SESSION['username'] ?? '');
    if ($username === '') {
        return;
    }
    $role = (string) ($_SESSION['role'] ?? '');
    if ($role === 'superadmin') {
        return;
    }
    try {
        $pdo = getConnection();
        smks3_ensure_users_is_active_column($pdo);
        $stmt = $pdo->prepare('SELECT role, is_active FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || !smks3_user_row_is_active($row)) {
            smks3_clear_editor_session();
        }
    } catch (Throwable $e) {
        // ignore — do not lock out on schema/db blips
    }
}

/** Ensure users.edit_preview exists (1 = pratonton, 0 = suntingan aktif). */
function smks3_ensure_users_edit_preview_column(?PDO $pdo = null): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;
    try {
        $pdo = $pdo ?? getConnection();
        $col = $pdo->query("SHOW COLUMNS FROM users LIKE 'edit_preview'")->fetch(PDO::FETCH_ASSOC);
        if (!$col) {
            $pdo->exec('ALTER TABLE users ADD COLUMN edit_preview TINYINT(1) NOT NULL DEFAULT 0');
        }
    } catch (Throwable $e) {
        // ignore — preference falls back to session / default
    }
}

/** Whether the logged-in editor prefers visitor preview (edit UI off). */
function smks3_get_edit_preview(): bool
{
    smks3_ensure_session();
    if (!smks3_is_editor()) {
        return false;
    }
    if (array_key_exists('edit_preview', $_SESSION)) {
        return !empty($_SESSION['edit_preview']);
    }
    try {
        $pdo = getConnection();
        smks3_ensure_users_edit_preview_column($pdo);
        $username = (string) ($_SESSION['username'] ?? '');
        if ($username === '') {
            $_SESSION['edit_preview'] = 0;
            return false;
        }
        $stmt = $pdo->prepare('SELECT edit_preview FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $val = (int) ($stmt->fetchColumn() ?: 0);
        $_SESSION['edit_preview'] = $val ? 1 : 0;
        return $val === 1;
    } catch (Throwable $e) {
        $_SESSION['edit_preview'] = 0;
        return false;
    }
}

/** Persist editor preview preference for the current user. */
function smks3_set_edit_preview(bool $preview): bool
{
    smks3_ensure_session();
    if (!smks3_is_editor()) {
        return false;
    }
    $username = (string) ($_SESSION['username'] ?? '');
    if ($username === '') {
        return false;
    }
    $flag = $preview ? 1 : 0;
    $_SESSION['edit_preview'] = $flag;
    try {
        $pdo = getConnection();
        smks3_ensure_users_edit_preview_column($pdo);
        $stmt = $pdo->prepare('UPDATE users SET edit_preview = ? WHERE username = ?');
        return $stmt->execute([$flag, $username]);
    } catch (Throwable $e) {
        return false;
    }
}

/** Icon class for FPK / visi / misi cards. */
function smks3_fpk_icon(string $kategori): string
{
    $kategori = strtolower($kategori);
    return match (true) {
        str_contains($kategori, 'visi') => 'bi-eye',
        str_contains($kategori, 'misi') => 'bi-gear',
        str_contains($kategori, 'motto') => 'bi-lightbulb',
        str_contains($kategori, 'pelan') => 'bi-journal-text',
        default => 'bi-journal-text',
    };
}

function smks3_default_settings(): array
{
    return [
        'school_name' => 'Sekolah Menengah Kebangsaan Seremban 3',
        'tagline' => '',
        'address' => 'Jalan Seremban Tiga 3 25, Seremban 3, 70300 Seremban, Negeri Sembilan',
        'phone' => '011-65732533',
        'email' => 'nea4117@moe.edu.my',
        'about_summary' => 'Sekolah Menengah Kebangsaan Seremban 3 ialah sekolah menengah yang komited menyediakan pendidikan berkualiti.',
    ];
}

function getSettings() {
    $defaults = smks3_default_settings();
    try {
        require_once BASE_PATH . '/config/database.php';
        $pdo = getConnection();
        $stmt = $pdo->query('SELECT school_name, tagline, address, phone, email, about_summary FROM settings WHERE id = 1 LIMIT 1');
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
        if (!$row) {
            return $defaults;
        }
        foreach ($defaults as $key => $fallback) {
            $val = trim((string) ($row[$key] ?? ''));
            $defaults[$key] = $val !== '' ? $val : $fallback;
        }
        return $defaults;
    } catch (Throwable $e) {
        return $defaults;
    }
}

/** Default homepage text blocks (overridable via site_content). */
function smks3_default_home_content(): array
{
    return [
        'hero_welcome' => 'Selamat Datang Ke Portal',
        'hero_subtitle' => 'Pusat maklumat digital untuk komuniti sekolah — berita, akademik, kokurikulum dan banyak lagi.',
        'nav_section_title' => 'Navigasi Portal',
        'slideshow_section_title' => 'Berita & Acara',
        'news_section_title' => 'Berita Terkini',
        'cta_title' => 'Sedia Menyertai Kami?',
        'cta_text' => 'Daftar sekarang dan capai impian anda di {school_name}.',
        'sidebar_title' => 'Maklumat Sekolah',
    ];
}

function smks3_get_home_content(): array
{
    $defaults = smks3_default_home_content();
    try {
        require_once BASE_PATH . '/config/database.php';
        $pdo = getConnection();
        $keys = array_keys($defaults);
        $placeholders = implode(',', array_fill(0, count($keys), '?'));
        $stmt = $pdo->prepare("SELECT content_key, content_value FROM site_content WHERE content_key IN ($placeholders)");
        $stmt->execute($keys);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $key = (string) $row['content_key'];
            $val = trim((string) $row['content_value']);
            if ($val !== '' && isset($defaults[$key])) {
                $defaults[$key] = $val;
            }
        }
    } catch (Throwable $e) {
        // keep defaults
    }
    return $defaults;
}

function smks3_save_site_content(string $key, string $value): bool
{
    if (!smks3_is_safe_content_key($key)) {
        return false;
    }
    try {
        require_once BASE_PATH . '/config/database.php';
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO site_content (content_key, content_value) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE content_value = VALUES(content_value)'
        );
        return $stmt->execute([$key, $value]);
    } catch (Throwable $e) {
        return false;
    }
}

function smks3_save_settings(array $fields): bool
{
    $allowed = ['school_name', 'tagline', 'address', 'phone', 'email', 'about_summary'];
    $sets = [];
    $params = [];
    foreach ($allowed as $col) {
        if (!array_key_exists($col, $fields)) {
            continue;
        }
        $sets[] = "$col = ?";
        $params[] = trim((string) $fields[$col]);
    }
    if (!$sets) {
        return false;
    }
    try {
        require_once BASE_PATH . '/config/database.php';
        $pdo = getConnection();
        $sql = 'UPDATE settings SET ' . implode(', ', $sets) . ' WHERE id = 1';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    } catch (Throwable $e) {
        return false;
    }
}

function smks3_default_quick_links(): array
{
    return [
        ['href' => 'profil-sekolah', 'icon' => 'bi-diagram-3', 'title' => 'Pengurusan', 'subtitle' => 'Pentadbiran', 'external' => false],
        ['href' => 'pentaksiran-peperiksaan', 'icon' => 'bi-book', 'title' => 'Kurikulum', 'subtitle' => 'Akademik', 'external' => false],
        ['href' => 'enrolmen-murid', 'icon' => 'bi-people-fill', 'title' => 'Hal Ehwal', 'subtitle' => 'Murid', 'external' => false],
        ['href' => 'unit-badan-beruniform', 'icon' => 'bi-trophy', 'title' => 'Kokurikulum', 'subtitle' => 'Aktiviti', 'external' => false],
        ['href' => 'jawatankuasa-pibg', 'icon' => 'bi-bank', 'title' => 'PIBG', 'subtitle' => 'Kerjasama', 'external' => false],
        ['href' => 'https://www.tiktok.com/@smkseremban3?lang=en', 'icon' => 'bi-camera-video', 'title' => 'Media', 'subtitle' => 'Sekolah', 'external' => true],
        ['href' => 'pusat-sumber', 'icon' => 'bi-book-half', 'title' => 'NILAM', 'subtitle' => 'Bacaan', 'external' => false],
        ['href' => 'https://delima.moe-dl.edu.my/', 'icon' => 'bi-award', 'title' => 'DELIMA', 'subtitle' => 'Digital', 'external' => true],
        ['href' => 'https://laporan-sukan-permainan-s3.my.canva.site/', 'icon' => 'bi-trophy-fill', 'title' => 'Sukan', 'subtitle' => 'Aktiviti', 'external' => true],
        ['href' => '#', 'icon' => 'bi-person-badge', 'title' => 'IDME', 'subtitle' => 'Sistem', 'external' => false],
    ];
}

function smks3_default_slideshow(): array
{
    return [
        [
            'image' => 'images/POSTER FUN RUN 2026.jpg',
            'alt' => 'Poster Fun Run 2026',
            'href' => 'https://sites.google.com/moe-dl.edu.my/vfr/kategori?authuser=0',
            'external' => true,
        ],
        [
            'image' => 'images/slide2.jpg',
            'alt' => 'Slaid 2',
            'href' => '',
            'external' => false,
        ],
        [
            'image' => 'images/slide3.jpg',
            'alt' => 'Slaid 3',
            'href' => '',
            'external' => false,
        ],
    ];
}

function smks3_normalize_quick_link(array $link): array
{
    return [
        'href' => trim((string) ($link['href'] ?? '#')),
        'icon' => trim((string) ($link['icon'] ?? 'bi-link-45deg')),
        'title' => trim((string) ($link['title'] ?? '')),
        'subtitle' => trim((string) ($link['subtitle'] ?? '')),
        'external' => !empty($link['external']),
    ];
}

function smks3_normalize_slide(array $slide): array
{
    return [
        'image' => trim((string) ($slide['image'] ?? '')),
        'alt' => trim((string) ($slide['alt'] ?? '')),
        'href' => trim((string) ($slide['href'] ?? '')),
        'external' => !empty($slide['external']),
    ];
}

function smks3_get_json_content(string $key, array $defaults): array
{
    try {
        require_once BASE_PATH . '/config/database.php';
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT content_value FROM site_content WHERE content_key = ? LIMIT 1');
        $stmt->execute([$key]);
        $raw = $stmt->fetchColumn();
        if ($raw === false || $raw === null || trim((string) $raw) === '') {
            return $defaults;
        }
        $decoded = json_decode((string) $raw, true);
        return is_array($decoded) ? $decoded : $defaults;
    } catch (Throwable $e) {
        return $defaults;
    }
}

function smks3_save_json_content(string $key, array $value): bool
{
    return smks3_save_site_content($key, json_encode(array_values($value), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

function smks3_get_quick_links(): array
{
    $raw = smks3_get_json_content('home_quick_links', smks3_default_quick_links());
    $out = [];
    foreach ($raw as $link) {
        if (!is_array($link)) {
            continue;
        }
        $n = smks3_normalize_quick_link($link);
        if ($n['title'] === '') {
            continue;
        }
        $out[] = $n;
    }
    return $out ?: smks3_default_quick_links();
}

function smks3_get_slideshow(?string $baseDir = null): array
{
    $baseDir = $baseDir ?? BASE_PATH;
    $raw = smks3_get_json_content('home_slideshow', smks3_default_slideshow());
    $out = [];
    foreach ($raw as $slide) {
        if (!is_array($slide)) {
            continue;
        }
        $n = smks3_normalize_slide($slide);
        if ($n['image'] === '') {
            continue;
        }
        if (!is_file($baseDir . '/' . $n['image'])) {
            continue;
        }
        $out[] = $n;
    }
    return $out;
}

/** Persist homepage defaults once so edit indices stay stable. */
function smks3_ensure_home_media_seed(?string $baseDir = null): void
{
    $baseDir = $baseDir ?? BASE_PATH;
    try {
        require_once BASE_PATH . '/config/database.php';
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT content_value FROM site_content WHERE content_key = ? LIMIT 1');

        $stmt->execute(['home_quick_links']);
        if ($stmt->fetchColumn() === false) {
            smks3_save_json_content('home_quick_links', smks3_default_quick_links());
        }

        $stmt->execute(['home_slideshow']);
        if ($stmt->fetchColumn() === false) {
            $slides = [];
            foreach (smks3_default_slideshow() as $slide) {
                $n = smks3_normalize_slide($slide);
                if ($n['image'] !== '' && is_file($baseDir . '/' . $n['image'])) {
                    $slides[] = $n;
                }
            }
            smks3_save_json_content('home_slideshow', $slides);
        }
    } catch (Throwable $e) {
        // ignore seed failures
    }
}

/**
 * Store an uploaded file under project-relative $relativeDir (e.g. uploads/pengetua).
 * Returns relative path like "uploads/pengetua/file.jpg" or filename-only if $filenameOnly.
 */
function smks3_store_upload(array $file, string $relativeDir, array $allowedExt, bool $filenameOnly = false, int $maxBytes = 20971520): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Muat naik gagal.');
    }
    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        throw new RuntimeException('Fail muat naik tidak sah.');
    }
    if (($file['size'] ?? 0) > $maxBytes) {
        throw new RuntimeException('Fail terlalu besar (max ' . (int) ($maxBytes / 1048576) . 'MB).');
    }
    $ext = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        throw new RuntimeException('Format fail tidak dibenarkan.');
    }
    smks3_assert_upload_mime($tmp, $ext, $allowedExt);
    $dir = BASE_PATH . '/' . trim($relativeDir, '/');
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('Tidak dapat cipta folder muat naik.');
    }
    $name = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($tmp, $dir . '/' . $name)) {
        throw new RuntimeException('Gagal simpan fail.');
    }
    return $filenameOnly ? $name : (trim($relativeDir, '/') . '/' . $name);
}

function smks3_delete_project_file(?string $relativePath): void
{
    $relativePath = trim((string) $relativePath);
    if ($relativePath === '' || str_contains($relativePath, '..')) {
        return;
    }
    $full = BASE_PATH . '/' . ltrim($relativePath, '/');
    if (is_file($full)) {
        @unlink($full);
    }
}

function smks3_get_html_content(string $key, string $default = ''): string
{
    try {
        require_once BASE_PATH . '/config/database.php';
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT content_value FROM site_content WHERE content_key = ? LIMIT 1');
        $stmt->execute([$key]);
        $raw = $stmt->fetchColumn();
        if ($raw === false || $raw === null) {
            return $default;
        }
        $raw = (string) $raw;
        return $raw !== '' ? $raw : $default;
    } catch (Throwable $e) {
        return $default;
    }
}

function smks3_default_cuti_table_html(): string
{
    return <<<'HTML'
<table class="table table-bordered text-center align-middle jadual-cuti">
                <thead class="table-dark">
                    <tr>
                        <th>Cuti Perayaan</th>
                        <th>Kumpulan A</th>
                        <th>Kumpulan B</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="3">Tahun Baru Cina<br>17 &amp; 18.02.2026</td>
                        <td>15.02.2026 (Ahad)</td>
                        <td>16.02.2026 (Isnin)</td>
                        <td rowspan="3">Tiga (3) Hari Cuti Tambahan KPM untuk Kumpulan A dan B</td>
                    </tr>
                    <tr>
                        <td>16.02.2026 (Isnin)</td>
                        <td>19.02.2026 (Khamis)</td>
                    </tr>
                    <tr>
                        <td>19.02.2026 (Khamis)</td>
                        <td>20.02.2026 (Jumaat)</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Hari Raya Aidilfitri<br>21 &amp; 22.03.2026</td>
                        <td>19.03.2026 (Khamis)</td>
                        <td>19.03.2026 (Khamis)</td>
                        <td rowspan="2">Satu (1) Hari Cuti Tambahan KPM untuk Kumpulan A dan Dua (2) Hari Cuti Tambahan KPM untuk Kumpulan B</td>
                    </tr>
                    <tr>
                        <td>21.03.2026 (Sabtu)</td>
                        <td>20.03.2026 (Jumaat)</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Hari Deepavali<br>08.11.2026 (Ahad) kecuali Negeri Sarawak</td>
                        <td>09.11.2026 (Isnin)</td>
                        <td>10.11.2026 (Selasa) Semua Negeri Kumpulan B kecuali Negeri Sarawak</td>
                        <td rowspan="2">Satu (1) Hari Cuti Tambahan KPM untuk Kumpulan A dan B / Satu (1) Hari Cuti Peruntukan KPM</td>
                    </tr>
                    <tr>
                        <td>09.11.2026 (Isnin)</td>
                        <td>09.11.2026 (Isnin) Negeri Sarawak sahaja</td>
                    </tr>
                </tbody>
            </table>
HTML;
}

function smks3_default_cuti_notes_html(): string
{
    return <<<'HTML'
<ul>
                <li>Hari Raya Aidilfitri: 21 &amp; 22 Mac 2026 (Dalam Cuti Penggal 1, Tahun 2026)</li>
                <li>Pesta Kaamatan (Sabah &amp; Wilayah Persekutuan Labuan sahaja): 30 &amp; 31 Mei 2026 (Dalam Cuti Pertengahan Tahun 2026)</li>
                <li>Hari Gawai Dayak (Sarawak sahaja): 01 &amp; 02 Jun 2026 (Dalam Cuti Pertengahan Tahun 2026)</li>
                <li>Hari Krismas: 25 Disember 2026 (Dalam Cuti Akhir Persekolahan Tahun 2026)</li>
            </ul>
HTML;
}

function smks3_default_cuti_intro_html(): string
{
    return smks3_format_cuti_kumpulan_html(smks3_default_cuti_kumpulan());
}

function smks3_default_cuti_kumpulan(): array
{
    return [
        'a' => 'Kedah, Kelantan, Terengganu',
        'b' => 'Johor, Melaka, Negeri Sembilan, Pahang, Perak, Perlis, Pulau Pinang, Sabah, Sarawak, Selangor, Wilayah Persekutuan KL, Labuan & Putrajaya',
    ];
}

function smks3_format_cuti_kumpulan_html(array $groups): string
{
    $a = trim((string) ($groups['a'] ?? ''));
    $b = trim((string) ($groups['b'] ?? ''));
    return '<strong>Kumpulan A:</strong> ' . htmlspecialchars($a, ENT_QUOTES, 'UTF-8')
        . '<br><strong>Kumpulan B:</strong> ' . htmlspecialchars($b, ENT_QUOTES, 'UTF-8');
}

function smks3_parse_cuti_kumpulan_from_html(string $html): array
{
    $defaults = smks3_default_cuti_kumpulan();
    $plain = trim(html_entity_decode(strip_tags(str_replace(
        ['<br>', '<br/>', '<br />', '</p>', '</div>'],
        "\n",
        $html
    )), ENT_QUOTES, 'UTF-8'));
    if ($plain === '') {
        return $defaults;
    }

    $a = '';
    $b = '';
    if (preg_match('/Kumpulan\s*A\s*:\s*(.*?)(?=Kumpulan\s*B\s*:|$)/is', $plain, $mA)) {
        $a = trim(preg_replace('/\s+/', ' ', $mA[1]) ?? '');
    }
    if (preg_match('/Kumpulan\s*B\s*:\s*(.*)$/is', $plain, $mB)) {
        $b = trim(preg_replace('/\s+/', ' ', $mB[1]) ?? '');
    }

    return [
        'a' => $a !== '' ? $a : $defaults['a'],
        'b' => $b !== '' ? $b : $defaults['b'],
    ];
}

function smks3_get_cuti_kumpulan(): array
{
    $defaults = smks3_default_cuti_kumpulan();
    try {
        require_once BASE_PATH . '/config/database.php';
        $pdo = getConnection();

        $stmt = $pdo->prepare('SELECT content_value FROM site_content WHERE content_key = ? LIMIT 1');
        $stmt->execute(['cuti_perayaan_kumpulan']);
        $raw = $stmt->fetchColumn();
        if ($raw !== false && $raw !== null && trim((string) $raw) !== '') {
            $decoded = json_decode((string) $raw, true);
            if (is_array($decoded)) {
                $a = trim((string) ($decoded['a'] ?? ''));
                $b = trim((string) ($decoded['b'] ?? ''));
                return [
                    'a' => $a !== '' ? $a : $defaults['a'],
                    'b' => $b !== '' ? $b : $defaults['b'],
                ];
            }
        }

        $intro = smks3_get_html_content('cuti_perayaan_intro', '');
        if ($intro !== '') {
            return smks3_parse_cuti_kumpulan_from_html($intro);
        }
    } catch (Throwable $e) {
        // keep defaults
    }
    return $defaults;
}

/** Default Falsafah Pendidikan Kebangsaan (misi-visi page). */
function smks3_default_fpk_falsafah(): array
{
    return [
        'title' => 'Falsafah Pendidikan Kebangsaan',
        'content' => "Pendidikan di Malaysia adalah suatu usaha berterusan ke arah lebih memperkembangkan potensi individu secara menyeluruh dan bersepadu untuk mewujudkan insan yang seimbang dan harmonis dari segi intelek, rohani, emosi dan jasmani, berdasarkan kepercayaan dan kepatuhan kepada Tuhan.\n\nUsaha ini adalah bagi melahirkan warganegara Malaysia yang berilmu pengetahuan, berketerampilan, berakhlak mulia, bertanggungjawab dan berkeupayaan mencapai kesejahteraan diri, serta memberi sumbangan terhadap keharmonian dan kemakmuran keluarga, masyarakat dan negara.",
    ];
}

function smks3_get_fpk_falsafah(): array
{
    $defaults = smks3_default_fpk_falsafah();
    try {
        require_once BASE_PATH . '/config/database.php';
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT content_value FROM site_content WHERE content_key = ? LIMIT 1');
        $stmt->execute(['fpk_falsafah']);
        $raw = $stmt->fetchColumn();
        if ($raw === false || $raw === null || trim((string) $raw) === '') {
            return $defaults;
        }
        $decoded = json_decode((string) $raw, true);
        if (!is_array($decoded)) {
            return $defaults;
        }
        $title = trim((string) ($decoded['title'] ?? ''));
        $content = trim((string) ($decoded['content'] ?? ''));
        if ($title !== '') {
            $defaults['title'] = $title;
        }
        if ($content !== '') {
            $defaults['content'] = $content;
        }
    } catch (Throwable $e) {
        // keep defaults
    }
    return $defaults;
}

function smks3_make_slug(string $title): string
{
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title) ?? ''));
    return trim($slug, '-') ?: ('item-' . time());
}

function smks3_upload_slideshow_image(array $file): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Muat naik gambar gagal.');
    }
    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        throw new RuntimeException('Fail muat naik tidak sah.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp) ?: '';
    $map = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    if (!isset($map[$mime])) {
        throw new RuntimeException('Format gambar tidak disokong (JPG/PNG/WEBP/GIF).');
    }
    $dir = BASE_PATH . '/images/slideshow';
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('Tidak dapat cipta folder slideshow.');
    }
    $name = 'slide_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $map[$mime];
    $dest = $dir . '/' . $name;
    if (!move_uploaded_file($tmp, $dest)) {
        throw new RuntimeException('Gagal simpan gambar.');
    }
    return 'images/slideshow/' . $name;
}

/** News items: newest `published_at` first. */
function smks3_sort_news_by_published_desc(array $items): array
{
    usort($items, static function (array $a, array $b): int {
        $ta = strtotime($a['published_at'] ?? '0');
        $tb = strtotime($b['published_at'] ?? '0');
        return $tb <=> $ta;
    });
    return $items;
}

/** Plain text → <p> blocks (double newlines = new paragraph; single newlines inside a block get <br>). */
function smks3_plain_text_to_paragraphs(string $text): string
{
    $text = trim($text);
    if ($text === '') {
        return '';
    }
    $parts = preg_split('/\r\n\r\n|\n\n|\r\r/', $text, -1, PREG_SPLIT_NO_EMPTY);
    if (count($parts) === 1 && strpbrk($parts[0], "\r\n") !== false) {
        $lines = preg_split('/\r\n|\n|\r/', $parts[0], -1, PREG_SPLIT_NO_EMPTY);
        $html = '';
        foreach ($lines as $line) {
            $t = trim($line);
            if ($t === '') {
                continue;
            }
            $html .= '<p>' . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . '</p>';
        }
        return $html;
    }
    $html = '';
    foreach ($parts as $part) {
        $t = trim($part);
        if ($t === '') {
            continue;
        }
        $inner = nl2br(htmlspecialchars($t, ENT_QUOTES, 'UTF-8'));
        $html .= '<p>' . $inner . '</p>';
    }
    return $html;
}

/**
 * News article body: trusted HTML if it contains tags; else plain text as paragraphs.
 * Falls back to excerpt when content is empty.
 */
function smks3_news_body_html(?string $content, ?string $fallbackExcerpt = ''): string
{
    $content = trim((string) $content);
    $fallback = trim((string) $fallbackExcerpt);
    if ($content !== '') {
        if (preg_match('/<[a-z][a-z0-9]*\b/i', $content)) {
            return $content;
        }
        return smks3_plain_text_to_paragraphs($content);
    }
    if ($fallback !== '') {
        return smks3_plain_text_to_paragraphs($fallback);
    }
    return '';
}

/** Ensure useful indexes on news for list/home queries. */
function smks3_ensure_news_indexes(?PDO $pdo = null): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;
    try {
        $pdo = $pdo ?? getConnection();
        $indexRows = $pdo->query('SHOW INDEX FROM news')->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $names = [];
        foreach ($indexRows as $row) {
            $names[(string) ($row['Key_name'] ?? '')] = true;
        }
        if (!isset($names['idx_news_published_at'])) {
            $pdo->exec('ALTER TABLE news ADD INDEX idx_news_published_at (published_at)');
        }
        $yearCol = $pdo->query("SHOW COLUMNS FROM news LIKE 'year'")->fetch(PDO::FETCH_ASSOC);
        if ($yearCol && !isset($names['idx_news_year'])) {
            $pdo->exec('ALTER TABLE news ADD INDEX idx_news_year (year)');
        }
    } catch (Throwable $e) {
        // best-effort
    }
}

/** Published news rows, newest first. Empty if DB unavailable or `news` missing. */
function smks3_fetch_published_news_paginated(int $page, int $perPage = 3, ?string $year = null): ?array
{
    require_once BASE_PATH . '/config/database.php';

    try {
        $pdo = getConnection();
        smks3_ensure_news_indexes($pdo);

        $where = "WHERE published_at IS NOT NULL";
        $params = [];

        if ($year) {
            $where .= " AND year = ?";
            $params[] = $year;
        }

        // COUNT
        $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM news $where");
        $stmtCount->execute($params);
        $total = (int) $stmtCount->fetchColumn();

        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        // DATA
        $stmt = $pdo->prepare("
            SELECT id, title, slug, excerpt, content, image, published_at, year
            FROM news
            $where
            ORDER BY published_at DESC
            LIMIT $perPage OFFSET $offset
        ");

        $stmt->execute($params);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'items' => $items,
            'total' => $total,
            'total_pages' => $totalPages,
            'page' => $page,
            'per_page' => $perPage,
        ];

    } catch (Throwable $e) {
        return null;
    }
}

/** Single news row by id, or null. */
function smks3_fetch_news_by_id(int $id): ?array
{
    require_once BASE_PATH . '/config/database.php';
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT id, title, slug, excerpt, content, image, published_at FROM news WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

/** Single published news row by URL slug, or null. */
function smks3_fetch_news_by_slug(string $slug): ?array
{
    $slug = trim($slug);
    if ($slug === '' || !preg_match('/^[a-zA-Z0-9_-]+$/', $slug)) {
        return null;
    }
    require_once BASE_PATH . '/config/database.php';
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            'SELECT id, title, slug, excerpt, content, image, published_at FROM news WHERE slug = :slug AND published_at IS NOT NULL LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

/** Link to one article: prefers readable slug on news-details (not numeric id). */
function smks3_news_article_url(array $n): string
{
    $slug = isset($n['slug']) ? trim((string) $n['slug']) : '';
    if ($slug !== '' && preg_match('/^[a-zA-Z0-9_-]+$/', $slug)) {
        return 'news-details?' . http_build_query(['slug' => $slug]);
    }
    if (!empty($n['id'])) {
        return 'news-details?' . http_build_query(['id' => (int) $n['id']]);
    }
    return 'news';
}

/** Default field map used to seed profil_item from legacy profil_sekolah row. */
function smks3_default_profil_field_map(): array
{
    return [
        'nama_pengetua' => ['title' => 'Nama Pengetua', 'icon' => 'bi-person-badge', 'suffix' => ''],
        'bilangan_guru' => ['title' => 'Bilangan Guru', 'icon' => 'bi-people', 'suffix' => ' orang'],
        'bilangan_murid' => ['title' => 'Bilangan Murid', 'icon' => 'bi-people-fill', 'suffix' => ' orang'],
        'keluasan_sekolah' => ['title' => 'Keluasan Sekolah', 'icon' => 'bi-arrows-fullscreen', 'suffix' => ''],
        'sesi_persekolahan' => ['title' => 'Sesi Persekolahan', 'icon' => 'bi-clock', 'suffix' => ''],
        'tingkatan_tertinggi' => ['title' => 'Tingkatan Tertinggi', 'icon' => 'bi-mortarboard', 'suffix' => ''],
        'alamat_sekolah' => ['title' => 'Alamat Sekolah', 'icon' => 'bi-geo-alt', 'suffix' => ''],
        'kod_sekolah' => ['title' => 'Kod Sekolah', 'icon' => 'bi-hash', 'suffix' => ''],
        'lokasi' => ['title' => 'Lokasi', 'icon' => 'bi-map', 'suffix' => ''],
        'daerah_pentadbiran' => ['title' => 'Daerah Pentadbiran', 'icon' => 'bi-building', 'suffix' => ''],
        'gred_sekolah' => ['title' => 'Gred Sekolah', 'icon' => 'bi-award', 'suffix' => ''],
        'pejabat_pendidikan_daerah' => ['title' => 'Pejabat Pendidikan Daerah', 'icon' => 'bi-building', 'suffix' => ''],
        'jenis_bantuan' => ['title' => 'Jenis Bantuan', 'icon' => 'bi-bank', 'suffix' => ''],
    ];
}

function smks3_ensure_profil_items_table(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS profil_item (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            value_text TEXT NOT NULL,
            icon VARCHAR(64) NOT NULL DEFAULT \'bi-info-circle\',
            sort_order INT NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY idx_profil_item_sort (sort_order, id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $count = (int) $pdo->query('SELECT COUNT(*) FROM profil_item')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $school = [];
    try {
        $school = $pdo->query('SELECT * FROM profil_sekolah LIMIT 1')->fetch(PDO::FETCH_ASSOC) ?: [];
    } catch (Throwable $e) {
        $school = [];
    }

    $order = 0;
    $ins = $pdo->prepare('INSERT INTO profil_item (title, value_text, icon, sort_order) VALUES (?,?,?,?)');
    foreach (smks3_default_profil_field_map() as $key => $meta) {
        $raw = trim((string) ($school[$key] ?? ''));
        $value = $raw;
        if ($meta['suffix'] !== '' && $raw !== '') {
            $value = $raw . $meta['suffix'];
        }
        $ins->execute([$meta['title'], $value, $meta['icon'], $order++]);
    }
}

/** @return list<array{id:int,title:string,value:string,icon:string,sort_order:int}> */
function smks3_get_profil_items(PDO $pdo): array
{
    smks3_ensure_profil_items_table($pdo);
    $rows = $pdo->query('SELECT id, title, value_text, icon, sort_order FROM profil_item ORDER BY sort_order ASC, id ASC')->fetchAll(PDO::FETCH_ASSOC) ?: [];
    $out = [];
    foreach ($rows as $row) {
        $out[] = [
            'id' => (int) ($row['id'] ?? 0),
            'title' => (string) ($row['title'] ?? ''),
            'value' => (string) ($row['value_text'] ?? ''),
            'icon' => (string) ($row['icon'] ?? 'bi-info-circle'),
            'sort_order' => (int) ($row['sort_order'] ?? 0),
        ];
    }
    return $out;
}

function getLatestYear($pdo) {
    smks3_ensure_news_indexes($pdo);
    $stmt = $pdo->query("SELECT MAX(year) as latest_year FROM news");
    $row = $stmt->fetch();
    return $row['latest_year'] ?? null;
}

/** Latest published news for homepage (DB LIMIT — not full-year fetch). */
function getLatestNewsByYear($pdo, int $limit = 3) {
    smks3_ensure_news_indexes($pdo);
    $limit = max(1, min(50, $limit));
    $stmt = $pdo->prepare(
        'SELECT id, title, slug, excerpt, content, image, pdf_file, published_at, year
         FROM news
         WHERE published_at IS NOT NULL
         ORDER BY published_at DESC
         LIMIT ' . $limit
    );
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return is_array($rows) ? $rows : [];
}
