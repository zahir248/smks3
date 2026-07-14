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
        echo json_encode(['ok' => true, 'message' => 'Maklumat sekolah dikemaskini.', 'fields' => array_map('trim', $fields)]);
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
        echo json_encode(['ok' => true, 'message' => 'Pautan dikemaskini.', 'fields' => $links[$index] + ['index' => $index], 'reload' => true]);
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
        echo json_encode(['ok' => true, 'message' => 'Pautan ditambah.', 'reload' => true]);
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
        echo json_encode(['ok' => true, 'message' => 'Pautan dipadam.', 'reload' => true]);
        exit;
    }

    if ($block === 'slideshow_slide') {
        $index = (int) ($data['index'] ?? -1);
        $slides = smks3_get_slideshow(dirname(__DIR__));
        if (!isset($slides[$index])) {
            throw new InvalidArgumentException('Slaid tidak dijumpai.');
        }
        $slides[$index]['alt'] = trim((string) ($data['alt'] ?? $slides[$index]['alt']));
        $slides[$index]['href'] = trim((string) ($data['href'] ?? ''));
        $slides[$index]['external'] = $bool($data['external'] ?? false);
        if (!empty($_FILES['image']['name'])) {
            $slides[$index]['image'] = smks3_upload_slideshow_image($_FILES['image']);
        }
        if ($slides[$index]['image'] === '') {
            throw new InvalidArgumentException('Gambar slaid diperlukan.');
        }
        if (!smks3_save_json_content('home_slideshow', $slides)) {
            throw new RuntimeException('Gagal simpan slaid.');
        }
        echo json_encode(['ok' => true, 'message' => 'Slaid dikemaskini.', 'reload' => true]);
        exit;
    }

    if ($block === 'slideshow_add') {
        $slides = smks3_get_slideshow(dirname(__DIR__));
        if (empty($_FILES['image']['name'])) {
            throw new InvalidArgumentException('Sila muat naik gambar slaid.');
        }
        $slides[] = smks3_normalize_slide([
            'image' => smks3_upload_slideshow_image($_FILES['image']),
            'alt' => $data['alt'] ?? 'Slaid baharu',
            'href' => $data['href'] ?? '',
            'external' => $bool($data['external'] ?? false),
        ]);
        if (!smks3_save_json_content('home_slideshow', $slides)) {
            throw new RuntimeException('Gagal tambah slaid.');
        }
        echo json_encode(['ok' => true, 'message' => 'Slaid ditambah.', 'reload' => true]);
        exit;
    }

    if ($block === 'slideshow_delete') {
        $index = (int) ($data['index'] ?? -1);
        $slides = smks3_get_slideshow(dirname(__DIR__));
        if (!isset($slides[$index])) {
            throw new InvalidArgumentException('Slaid tidak dijumpai.');
        }
        array_splice($slides, $index, 1);
        if (!smks3_save_json_content('home_slideshow', $slides)) {
            throw new RuntimeException('Gagal padam slaid.');
        }
        echo json_encode(['ok' => true, 'message' => 'Slaid dipadam.', 'reload' => true]);
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
        echo json_encode(['ok' => true, 'message' => 'Kandungan dikemaskini.', 'fields' => compact('id', 'content', 'kategori')]);
        exit;
    }

    require_once __DIR__ . '/../app/Services/CmsHandlers.php';
    $extra = smks3_handle_cms_block($block, $data, $pdo, $bool);
    if (is_array($extra)) {
        echo json_encode($extra);
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
        echo json_encode(['ok' => true, 'message' => 'Kandungan dikemaskini.', 'fields' => ['block' => $block, 'value' => $value]]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Jenis blok tidak dikenali.']);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
