<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/bootstrap.php';

smks3_ensure_session();

if (!smks3_is_editor()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Kaedah tidak dibenarkan.']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: '', true);
if (!is_array($data)) {
    $data = $_POST;
}

smks3_require_csrf($data);

$block = trim((string) ($data['block'] ?? ''));
if ($block === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Blok tidak sah.']);
    exit;
}

if (!smks3_can_edit_block($block, $data)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Tiada kebenaran untuk sunting bahagian ini.']);
    exit;
}

$homeKeys = array_keys(smks3_default_home_content());
$bool = static function ($v): bool {
    return $v === true || $v === 1 || $v === '1' || $v === 'true' || $v === 'on';
};

try {
    $pdo = getConnection();

    $before = smks3_activity_snapshot_block($block, $data, $pdo);
    $requestMeta = smks3_activity_sanitize_payload($data);
    if (!empty($_FILES)) {
        $fileMeta = [];
        foreach ($_FILES as $field => $info) {
            if (is_array($info['name'] ?? null)) {
                $names = array_values(array_filter((array) $info['name'], static fn($n) => $n !== '' && $n !== null));
                $fileMeta[$field] = ['count' => count($names), 'names' => $names];
            } elseif (!empty($info['name'])) {
                $fileMeta[$field] = ['name' => (string) $info['name'], 'size' => (int) ($info['size'] ?? 0)];
            }
        }
        if ($fileMeta !== []) {
            $requestMeta['_files'] = $fileMeta;
        }
    }

    $respond = static function (array $payload) use ($block, $data, $pdo, $before, $requestMeta): void {
        $after = smks3_activity_snapshot_block($block, $data, $pdo);
        $entityId = isset($data['id']) && (string) $data['id'] !== ''
            ? (string) $data['id']
            : (isset($data['index']) ? (string) $data['index'] : null);
        $summary = (string) ($payload['message'] ?? smks3_activity_action_label(smks3_activity_content_op($block)));
        if ($summary === '') {
            $summary = $block;
        }
        $pageKey = smks3_activity_resolve_page_key($block, is_array($data) ? $data : []);
        $meta = ['request' => $requestMeta];
        if ($pageKey !== null && $pageKey !== '') {
            $meta['page_key'] = $pageKey;
        }
        smks3_activity_log(
            smks3_activity_content_op($block),
            $before,
            $after ?? ($payload['fields'] ?? null),
            $block,
            $entityId,
            $summary,
            $meta
        );
        echo json_encode($payload);
    };

    if ($block === 'school_info') {
        $fields = [
            'school_name' => $data['school_name'] ?? '',
            'address' => $data['address'] ?? '',
            'phone' => $data['phone'] ?? '',
            'email' => $data['email'] ?? '',
        ];
        if (!smks3_save_settings($fields)) {
            throw new RuntimeException('Gagal simpan maklumat sekolah.');
        }
        $respond(['ok' => true, 'message' => 'Maklumat sekolah dikemaskini.', 'fields' => array_map('trim', $fields)]);
        exit;
    }

    if ($block === 'quick_link') {
        $index = (int) ($data['index'] ?? -1);
        $links = smks3_get_quick_links();
        if (!isset($links[$index])) {
            throw new InvalidArgumentException('Pautan tidak dijumpai.');
        }
        $links[$index] = smks3_normalize_quick_link([
            'title' => $data['title'] ?? '',
            'subtitle' => $data['subtitle'] ?? '',
            'href' => $data['href'] ?? '#',
            'icon' => $data['icon'] ?? 'bi-link-45deg',
            'external' => $bool($data['external'] ?? false),
        ]);
        if ($links[$index]['title'] === '') {
            throw new InvalidArgumentException('Tajuk pautan diperlukan.');
        }
        if (!smks3_save_json_content('home_quick_links', $links)) {
            throw new RuntimeException('Gagal simpan pautan.');
        }
        $respond(['ok' => true, 'message' => 'Pautan dikemaskini.', 'fields' => $links[$index] + ['index' => $index], 'reload' => true]);
        exit;
    }

    if ($block === 'quick_link_add') {
        $links = smks3_get_quick_links();
        $links[] = smks3_normalize_quick_link([
            'title' => $data['title'] ?? 'Pautan Baharu',
            'subtitle' => $data['subtitle'] ?? '',
            'href' => $data['href'] ?? '#',
            'icon' => $data['icon'] ?? 'bi-link-45deg',
            'external' => $bool($data['external'] ?? false),
        ]);
        if (!smks3_save_json_content('home_quick_links', $links)) {
            throw new RuntimeException('Gagal tambah pautan.');
        }
        $respond(['ok' => true, 'message' => 'Pautan ditambah.', 'reload' => true]);
        exit;
    }

    if ($block === 'quick_link_delete') {
        $index = (int) ($data['index'] ?? -1);
        $links = smks3_get_quick_links();
        if (!isset($links[$index])) {
            throw new InvalidArgumentException('Pautan tidak dijumpai.');
        }
        array_splice($links, $index, 1);
        if (!smks3_save_json_content('home_quick_links', $links)) {
            throw new RuntimeException('Gagal padam pautan.');
        }
        $respond(['ok' => true, 'message' => 'Pautan dipadam.', 'reload' => true]);
        exit;
    }

    if ($block === 'slideshow_gallery') {
        $uploads = smks3_normalize_uploaded_files($_FILES['images'] ?? null);
        if ($uploads === [] && !empty($_FILES['image']['name'])) {
            $uploads = [$_FILES['image']];
        }
        $slides = smks3_sync_slideshow_gallery($data, $uploads);
        if (!smks3_save_json_content('home_slideshow', $slides)) {
            throw new RuntimeException('Gagal simpan slaid.');
        }
        $respond(['ok' => true, 'message' => 'Slaid dikemaskini.', 'reload' => true]);
        exit;
    }

    // Legacy single-row profil_sekolah editor removed — use profil_item cards.

    if ($block === 'fpk_item') {
        $id = (int) ($data['id'] ?? 0);
        $content = trim((string) ($data['content'] ?? ''));
        $kategori = trim((string) ($data['kategori'] ?? ''));
        if ($id < 1 || $content === '') {
            throw new InvalidArgumentException('Kandungan diperlukan.');
        }
        if ($kategori !== '') {
            $stmt = $pdo->prepare('UPDATE fpk_misi_visi SET kategori = ?, content = ? WHERE id = ?');
            $stmt->execute([$kategori, $content, $id]);
        } else {
            $stmt = $pdo->prepare('UPDATE fpk_misi_visi SET content = ? WHERE id = ?');
            $stmt->execute([$content, $id]);
        }
        $respond(['ok' => true, 'message' => 'Kandungan dikemaskini.', 'fields' => compact('id', 'content', 'kategori')]);
        exit;
    }

    require_once __DIR__ . '/../app/Services/CmsHandlers.php';
    $extra = smks3_handle_cms_block($block, $data, $pdo, $bool);
    if (is_array($extra)) {
        $respond($extra);
        exit;
    }

    if (in_array($block, $homeKeys, true)) {
        $value = trim((string) ($data['value'] ?? ''));
        if ($value === '') {
            throw new InvalidArgumentException('Kandungan tidak boleh kosong.');
        }
        if ($block === 'cta_text') {
            $value = smks3_tokenize_content_placeholders($value);
        }
        if (!smks3_save_site_content($block, $value)) {
            throw new RuntimeException('Gagal simpan kandungan.');
        }
        $respond(['ok' => true, 'message' => 'Kandungan dikemaskini.', 'fields' => ['block' => $block, 'value' => $value]]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Jenis blok tidak dikenali.']);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
