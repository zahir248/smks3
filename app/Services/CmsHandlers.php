<?php
/**
 * Extra portal CMS handlers (create / edit / delete / upload).
 * Returns response array, or null if block is not handled here.
 */
function smks3_handle_cms_block(string $block, array $data, PDO $pdo, callable $bool): ?array
{
    $imgExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $pdfExt = ['pdf'];
    $id = (int) ($data['id'] ?? 0);

    if ($block === 'footer_about' || $block === 'footer_contact' || $block === 'footer_social' || $block === 'footer_copyright') {
        $layout = smks3_get_layout_content();

        if ($block === 'footer_about') {
            $layout['footer_brand'] = trim((string) ($data['brand'] ?? ''));
            $layout['footer_blurb'] = trim((string) ($data['blurb'] ?? ''));
            if ($layout['footer_brand'] === '') {
                throw new InvalidArgumentException('Nama sekolah di footer diperlukan.');
            }
        } elseif ($block === 'footer_contact') {
            $layout['footer_contact_title'] = trim((string) ($data['title'] ?? 'Hubungi')) ?: 'Hubungi';
            $fields = [
                'address' => trim((string) ($data['address'] ?? '')),
                'phone' => trim((string) ($data['phone'] ?? '')),
                'email' => trim((string) ($data['email'] ?? '')),
            ];
            if ($fields['address'] === '' || $fields['phone'] === '' || $fields['email'] === '') {
                throw new InvalidArgumentException('Alamat, telefon dan emel diperlukan.');
            }
            if (!smks3_save_settings($fields)) {
                throw new RuntimeException('Gagal simpan maklumat hubungi.');
            }
        } elseif ($block === 'footer_social') {
            $layout['footer_social_title'] = trim((string) ($data['title'] ?? 'Ikuti Kami')) ?: 'Ikuti Kami';
            $labels = $data['social_label'] ?? [];
            $icons = $data['social_icon'] ?? [];
            $hrefs = $data['social_href'] ?? [];
            if (!is_array($labels)) {
                $labels = [];
            }
            if (!is_array($icons)) {
                $icons = [];
            }
            if (!is_array($hrefs)) {
                $hrefs = [];
            }
            $social = [];
            $count = max(count($labels), count($icons), count($hrefs));
            for ($i = 0; $i < $count; $i++) {
                $label = trim((string) ($labels[$i] ?? ''));
                $href = trim((string) ($hrefs[$i] ?? ''));
                if ($label === '' && $href === '') {
                    continue;
                }
                $social[] = smks3_normalize_social_link([
                    'label' => $label,
                    'icon' => (string) ($icons[$i] ?? 'bi-link-45deg'),
                    'href' => $href,
                ]);
            }
            if ($social === []) {
                throw new InvalidArgumentException('Tambah sekurang-kurangnya satu pautan media sosial.');
            }
            $layout['social'] = $social;
        } else { // footer_copyright
            $layout['footer_copyright'] = trim((string) ($data['value'] ?? ''));
            if ($layout['footer_copyright'] === '') {
                throw new InvalidArgumentException('Teks hak cipta diperlukan.');
            }
        }

        if (!smks3_save_layout_content($layout)) {
            throw new RuntimeException('Gagal simpan kandungan footer.');
        }
        return ['ok' => true, 'message' => 'Footer dikemaskini.', 'reload' => true];
    }

    // ── News ──────────────────────────────────────────────
    if ($block === 'news_item') {
        smks3_ensure_news_indexes($pdo);
        $title = trim((string) ($data['title'] ?? ''));
        $content = smks3_sanitize_news_html((string) ($data['content'] ?? ''));
        $year = trim((string) ($data['year'] ?? date('Y')));
        if ($id < 1 || $title === '') {
            throw new InvalidArgumentException('Tajuk berita diperlukan.');
        }
        $row = $pdo->prepare('SELECT pdf_file, image FROM news WHERE id = ?');
        $row->execute([$id]);
        $cur = $row->fetch(PDO::FETCH_ASSOC) ?: [];
        $pdf = $cur['pdf_file'] ?? null;
        $images = smks3_news_parse_images($cur['image'] ?? null);

        $remove = $data['remove_images'] ?? [];
        if (!is_array($remove)) {
            $remove = $remove !== '' && $remove !== null ? [(string) $remove] : [];
        }
        $removeSet = [];
        foreach ($remove as $rm) {
            $base = basename(str_replace('\\', '/', trim((string) $rm)));
            if ($base !== '') {
                $removeSet[$base] = true;
            }
        }
        if ($removeSet !== []) {
            $kept = [];
            foreach ($images as $img) {
                if (isset($removeSet[$img])) {
                    smks3_delete_project_file('uploads/' . ltrim($img, '/'));
                    continue;
                }
                $kept[] = $img;
            }
            $images = $kept;
        }

        if (!empty($_FILES['pdf_file']['name'])) {
            if (!empty($pdf)) {
                smks3_delete_project_file('uploads/pdf/' . $pdf);
            }
            $pdf = smks3_store_upload($_FILES['pdf_file'], 'uploads/pdf', $pdfExt, true);
        }

        // Support legacy single `image` plus new multi `images[]`.
        $uploads = smks3_normalize_uploaded_files($_FILES['images'] ?? null);
        if ($uploads === [] && !empty($_FILES['image']['name'])) {
            $uploads = [$_FILES['image']];
        }
        foreach ($uploads as $file) {
            $images[] = smks3_store_upload($file, 'uploads', $imgExt, true);
        }
        $images = smks3_news_parse_images($images);

        $slug = smks3_make_slug($title);
        $excerpt = mb_substr(trim(html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8')), 0, 150);
        $imageStore = smks3_news_encode_images($images);
        $pdo->prepare('UPDATE news SET title=?, slug=?, excerpt=?, content=?, year=?, pdf_file=?, image=? WHERE id=?')
            ->execute([$title, $slug, $excerpt, $content, $year, $pdf, $imageStore, $id]);
        return ['ok' => true, 'message' => 'Berita dikemaskini.', 'reload' => true];
    }

    if ($block === 'news_add') {
        smks3_ensure_news_indexes($pdo);
        $title = trim((string) ($data['title'] ?? ''));
        $content = smks3_sanitize_news_html((string) ($data['content'] ?? ''));
        $year = trim((string) ($data['year'] ?? date('Y')));
        if ($title === '') {
            throw new InvalidArgumentException('Tajuk berita diperlukan.');
        }
        $pdf = null;
        $images = [];
        if (!empty($_FILES['pdf_file']['name'])) {
            $pdf = smks3_store_upload($_FILES['pdf_file'], 'uploads/pdf', $pdfExt, true);
        }
        $uploads = smks3_normalize_uploaded_files($_FILES['images'] ?? null);
        if ($uploads === [] && !empty($_FILES['image']['name'])) {
            $uploads = [$_FILES['image']];
        }
        foreach ($uploads as $file) {
            $images[] = smks3_store_upload($file, 'uploads', $imgExt, true);
        }
        $images = smks3_news_parse_images($images);
        $slug = smks3_make_slug($title);
        $excerpt = mb_substr(trim(html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8')), 0, 150);
        $imageStore = smks3_news_encode_images($images);
        $pdo->prepare("INSERT INTO news (title, slug, excerpt, content, status, published_at, year, pdf_file, image) VALUES (?,?,?,?, 'published', NOW(), ?, ?, ?)")
            ->execute([$title, $slug, $excerpt, $content, $year, $pdf, $imageStore]);
        $newId = (int) $pdo->lastInsertId();
        return [
            'ok' => true,
            'message' => 'Berita ditambah.',
            'redirect' => $slug !== ''
                ? ('news-details?' . http_build_query(['slug' => $slug]))
                : ($newId > 0 ? ('news-details?' . http_build_query(['id' => $newId])) : 'news'),
        ];
    }

    if ($block === 'news_delete') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID berita tidak sah.');
        }
        $row = $pdo->prepare('SELECT pdf_file, image FROM news WHERE id = ?');
        $row->execute([$id]);
        $cur = $row->fetch(PDO::FETCH_ASSOC);
        if ($cur) {
            if (!empty($cur['pdf_file'])) {
                smks3_delete_project_file('uploads/pdf/' . $cur['pdf_file']);
            }
            foreach (smks3_news_parse_images($cur['image'] ?? null) as $img) {
                smks3_delete_project_file('uploads/' . ltrim($img, '/'));
            }
        }
        $pdo->prepare('DELETE FROM news WHERE id = ?')->execute([$id]);
        return ['ok' => true, 'message' => 'Berita dipadam.', 'redirect' => 'news'];
    }

    // ── Pengetua ──────────────────────────────────────────
    if ($block === 'pengetua_item') {
        $name = trim((string) ($data['name'] ?? ''));
        $start = trim((string) ($data['start_year'] ?? ''));
        $end = trim((string) ($data['end_year'] ?? ''));
        if ($id < 1 || $name === '') {
            throw new InvalidArgumentException('Nama pengetua diperlukan.');
        }
        $photo = $pdo->prepare('SELECT photo FROM pengetua WHERE id = ?');
        $photo->execute([$id]);
        $current = (string) ($photo->fetchColumn() ?: '');
        $removePhoto = !empty($data['remove_photo']);
        if ($removePhoto && empty($_FILES['photo']['name'])) {
            smks3_delete_project_file($current);
            $current = '';
        }
        if (!empty($_FILES['photo']['name'])) {
            smks3_delete_project_file($current);
            $current = smks3_store_upload($_FILES['photo'], 'uploads/pengetua', $imgExt);
        }
        $pdo->prepare('UPDATE pengetua SET name=?, start_year=?, end_year=?, photo=? WHERE id=?')
            ->execute([$name, $start, $end, $current, $id]);
        return ['ok' => true, 'message' => 'Pengetua dikemaskini.', 'reload' => true];
    }

    if ($block === 'pengetua_add') {
        $name = trim((string) ($data['name'] ?? ''));
        if ($name === '') {
            throw new InvalidArgumentException('Nama pengetua diperlukan.');
        }
        $photo = '';
        if (!empty($_FILES['photo']['name'])) {
            $photo = smks3_store_upload($_FILES['photo'], 'uploads/pengetua', $imgExt);
        }
        smks3_insert_with_auto_id(
            $pdo,
            'pengetua',
            'name, start_year, end_year, photo',
            '?,?,?,?',
            [$name, trim((string) ($data['start_year'] ?? '')), trim((string) ($data['end_year'] ?? '')), $photo]
        );
        return ['ok' => true, 'message' => 'Pengetua ditambah.', 'reload' => true];
    }

    if ($block === 'pengetua_delete') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $photo = $pdo->prepare('SELECT photo FROM pengetua WHERE id = ?');
        $photo->execute([$id]);
        smks3_delete_project_file((string) ($photo->fetchColumn() ?: ''));
        $pdo->prepare('DELETE FROM pengetua WHERE id = ?')->execute([$id]);
        return ['ok' => true, 'message' => 'Pengetua dipadam.', 'reload' => true];
    }

    // ── Pengurusan ────────────────────────────────────────
    $pengurusanKategori = ['pengetua', 'pk', 'gkmp', 'kaunselor'];
    if ($block === 'pengurusan_item') {
        $nama = trim((string) ($data['nama'] ?? ''));
        $gred = trim((string) ($data['gred'] ?? ''));
        $jawatan = trim((string) ($data['jawatan'] ?? ''));
        $kategori = trim((string) ($data['kategori'] ?? ''));
        if ($id < 1 || $nama === '') {
            throw new InvalidArgumentException('Nama diperlukan.');
        }
        $cur = $pdo->prepare('SELECT gambar, kategori FROM pengurusan WHERE id = ?');
        $cur->execute([$id]);
        $row = $cur->fetch(PDO::FETCH_ASSOC) ?: [];
        $gambar = (string) ($row['gambar'] ?? '');
        if ($kategori === '' || !in_array($kategori, $pengurusanKategori, true)) {
            $kategori = (string) ($row['kategori'] ?? 'pengetua');
        }
        if (!in_array($kategori, $pengurusanKategori, true)) {
            $kategori = 'pk';
        }
        $removeGambar = !empty($data['remove_gambar']);
        if ($removeGambar && empty($_FILES['gambar']['name'])) {
            smks3_delete_project_file($gambar);
            $gambar = '';
        }
        if (!empty($_FILES['gambar']['name'])) {
            smks3_delete_project_file($gambar);
            $gambar = smks3_store_upload($_FILES['gambar'], 'uploads/pengurusan', $imgExt);
        }
        $pdo->prepare('UPDATE pengurusan SET nama=?, gred=?, jawatan=?, kategori=?, gambar=? WHERE id=?')
            ->execute([$nama, $gred, $jawatan, $kategori, $gambar, $id]);
        return ['ok' => true, 'message' => 'Pengurusan dikemaskini.', 'reload' => true];
    }

    if ($block === 'pengurusan_add') {
        $nama = trim((string) ($data['nama'] ?? ''));
        if ($nama === '') {
            throw new InvalidArgumentException('Nama diperlukan.');
        }
        $kategori = trim((string) ($data['kategori'] ?? 'pk'));
        if (!in_array($kategori, $pengurusanKategori, true)) {
            $kategori = 'pk';
        }
        $gambar = '';
        if (!empty($_FILES['gambar']['name'])) {
            $gambar = smks3_store_upload($_FILES['gambar'], 'uploads/pengurusan', $imgExt);
        }
        $max = (int) $pdo->query('SELECT COALESCE(MAX(susunan),0) FROM pengurusan')->fetchColumn();
        smks3_insert_with_auto_id(
            $pdo,
            'pengurusan',
            'nama, gred, jawatan, kategori, gambar, susunan',
            '?,?,?,?,?,?',
            [
                $nama,
                trim((string) ($data['gred'] ?? '')),
                trim((string) ($data['jawatan'] ?? '')),
                $kategori,
                $gambar,
                $max + 1,
            ]
        );
        return ['ok' => true, 'message' => 'Pengurusan ditambah.', 'reload' => true];
    }

    if ($block === 'pengurusan_delete') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $g = $pdo->prepare('SELECT gambar FROM pengurusan WHERE id = ?');
        $g->execute([$id]);
        smks3_delete_project_file((string) ($g->fetchColumn() ?: ''));
        $pdo->prepare('DELETE FROM pengurusan WHERE id = ?')->execute([$id]);
        return ['ok' => true, 'message' => 'Pengurusan dipadam.', 'reload' => true];
    }

    // ── Sejarah / FPK create-delete ───────────────────────
    if ($block === 'sejarah_item') {
        $tajuk = trim((string) ($data['tajuk'] ?? ''));
        $content = trim((string) ($data['content'] ?? ''));
        $tarikh = trim((string) ($data['tarikh'] ?? ''));
        if ($id < 1 || $tajuk === '') {
            throw new InvalidArgumentException('Tajuk diperlukan.');
        }
        try {
            $pdo->prepare('UPDATE sejarah_sekolah SET tajuk=?, content=?, tarikh=? WHERE id=?')
                ->execute([$tajuk, $content, $tarikh !== '' ? $tarikh : null, $id]);
        } catch (Throwable $e) {
            $pdo->prepare('UPDATE sejarah_sekolah SET tajuk=?, content=? WHERE id=?')
                ->execute([$tajuk, $content, $id]);
        }
        return ['ok' => true, 'message' => 'Sejarah dikemaskini.', 'fields' => compact('id', 'tajuk', 'content')];
    }

    if ($block === 'sejarah_add') {
        $tajuk = trim((string) ($data['tajuk'] ?? ''));
        $content = trim((string) ($data['content'] ?? ''));
        $tarikh = trim((string) ($data['tarikh'] ?? ''));
        if ($tajuk === '') {
            throw new InvalidArgumentException('Tajuk diperlukan.');
        }
        try {
            smks3_insert_with_auto_id(
                $pdo,
                'sejarah_sekolah',
                'tajuk, content, tarikh',
                '?,?,?',
                [$tajuk, $content, $tarikh !== '' ? $tarikh : null]
            );
        } catch (Throwable $e) {
            smks3_insert_with_auto_id(
                $pdo,
                'sejarah_sekolah',
                'tajuk, content',
                '?,?',
                [$tajuk, $content]
            );
        }
        return ['ok' => true, 'message' => 'Sejarah ditambah.', 'reload' => true];
    }

    if ($block === 'sejarah_delete') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $pdo->prepare('DELETE FROM sejarah_sekolah WHERE id = ?')->execute([$id]);
        return ['ok' => true, 'message' => 'Sejarah dipadam.', 'reload' => true];
    }

    if ($block === 'fpk_falsafah') {
        $title = trim((string) ($data['title'] ?? ''));
        $content = trim((string) ($data['content'] ?? ''));
        if ($title === '' || $content === '') {
            throw new InvalidArgumentException('Tajuk dan kandungan diperlukan.');
        }
        $payload = json_encode(
            ['title' => $title, 'content' => $content],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        if ($payload === false || !smks3_save_site_content('fpk_falsafah', $payload)) {
            throw new RuntimeException('Gagal simpan Falsafah Pendidikan Kebangsaan.');
        }
        return [
            'ok' => true,
            'message' => 'Falsafah Pendidikan Kebangsaan dikemaskini.',
            'fields' => compact('title', 'content'),
        ];
    }

    if ($block === 'fpk_add') {
        $kategori = trim((string) ($data['kategori'] ?? 'Baharu'));
        $content = trim((string) ($data['content'] ?? ''));
        if ($content === '') {
            throw new InvalidArgumentException('Kandungan diperlukan.');
        }
        smks3_insert_with_auto_id(
            $pdo,
            'fpk_misi_visi',
            'kategori, content',
            '?,?',
            [$kategori, $content]
        );
        return ['ok' => true, 'message' => 'Item ditambah.', 'reload' => true];
    }

    if ($block === 'fpk_delete') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $pdo->prepare('DELETE FROM fpk_misi_visi WHERE id = ?')->execute([$id]);
        return ['ok' => true, 'message' => 'Item dipadam.', 'reload' => true];
    }

    // ── Guru / AKP ────────────────────────────────────────
    foreach (['guru' => 'guru', 'akp' => 'akp'] as $prefix => $table) {
        if ($block === $prefix . '_item') {
            $nama = trim((string) ($data['nama'] ?? ''));
            if ($id < 1 || $nama === '') {
                throw new InvalidArgumentException('Nama diperlukan.');
            }
            $cur = $pdo->prepare("SELECT image FROM {$table} WHERE id = ?");
            $cur->execute([$id]);
            $image = (string) ($cur->fetchColumn() ?: '');
            $removeImage = !empty($data['remove_image']);
            if ($removeImage && empty($_FILES['image']['name'])) {
                if ($image !== '') {
                    smks3_delete_project_file('uploads/' . ltrim($image, '/'));
                }
                $image = '';
            }
            if (!empty($_FILES['image']['name'])) {
                if ($image !== '') {
                    smks3_delete_project_file('uploads/' . ltrim($image, '/'));
                }
                $image = smks3_store_upload($_FILES['image'], 'uploads', $imgExt, true);
            }
            $pdo->prepare("UPDATE {$table} SET nama=?, jawatan=?, dg=?, image=? WHERE id=?")
                ->execute([$nama, trim((string) ($data['jawatan'] ?? '')), trim((string) ($data['dg'] ?? '')), $image, $id]);
            return ['ok' => true, 'message' => 'Rekod dikemaskini.', 'reload' => true];
        }
        if ($block === $prefix . '_add') {
            $nama = trim((string) ($data['nama'] ?? ''));
            if ($nama === '') {
                throw new InvalidArgumentException('Nama diperlukan.');
            }
            $image = '';
            if (!empty($_FILES['image']['name'])) {
                $image = smks3_store_upload($_FILES['image'], 'uploads', $imgExt, true);
            }
            $pdo->prepare("INSERT INTO {$table} (nama, jawatan, dg, image) VALUES (?,?,?,?)")
                ->execute([$nama, trim((string) ($data['jawatan'] ?? '')), trim((string) ($data['dg'] ?? '')), $image]);
            return ['ok' => true, 'message' => 'Rekod ditambah.', 'reload' => true];
        }
        if ($block === $prefix . '_delete') {
            if ($id < 1) {
                throw new InvalidArgumentException('ID tidak sah.');
            }
            $cur = $pdo->prepare("SELECT image FROM {$table} WHERE id = ?");
            $cur->execute([$id]);
            smks3_delete_project_file('uploads/' . ltrim((string) ($cur->fetchColumn() ?: ''), '/'));
            $pdo->prepare("DELETE FROM {$table} WHERE id = ?")->execute([$id]);
            return ['ok' => true, 'message' => 'Rekod dipadam.', 'reload' => true];
        }
    }

    // ── PDF galleries ─────────────────────────────────────
    $pdfMaps = [
        'kalendar_pdf' => ['table' => 'academic_calendar', 'col' => 'file_pdf', 'dir' => 'uploads/kalendar', 'filenameOnly' => true],
        'cuti_pdf' => ['table' => 'cuti_perayaan', 'col' => 'file_pdf', 'dir' => 'uploads/cuti_perayaan', 'filenameOnly' => true],
        'pilihan_pdf' => ['table' => 'pilihan_mata_pelajaran', 'col' => 'file_pdf', 'dir' => 'uploads/pilihan_mata_pelajaran', 'filenameOnly' => true],
    ];
    foreach ($pdfMaps as $prefix => $cfg) {
        if ($block === $prefix . '_add') {
            if (empty($_FILES['pdf']['name'])) {
                throw new InvalidArgumentException('Sila muat naik PDF.');
            }
            $file = smks3_store_upload($_FILES['pdf'], $cfg['dir'], $pdfExt, $cfg['filenameOnly']);
            if ($prefix === 'pilihan_pdf') {
                $old = $pdo->query('SELECT file_pdf FROM pilihan_mata_pelajaran')->fetchAll(PDO::FETCH_COLUMN);
                foreach ($old as $o) {
                    smks3_delete_project_file($cfg['dir'] . '/' . $o);
                }
                $pdo->exec('DELETE FROM pilihan_mata_pelajaran');
            }
            if ($prefix === 'kalendar_pdf') {
                // Table also has legacy event columns (title, start_date) that are NOT NULL.
                smks3_ensure_academic_calendar_pdf_columns($pdo);
                $origName = (string) ($_FILES['pdf']['name'] ?? '');
                $title = trim((string) pathinfo($origName, PATHINFO_FILENAME));
                if ($title === '') {
                    $title = 'Kalendar Akademik';
                }
                if (function_exists('mb_substr')) {
                    $title = mb_substr($title, 0, 255);
                } else {
                    $title = substr($title, 0, 255);
                }
                $sort = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) FROM academic_calendar')->fetchColumn();
                $pdo->prepare(
                    'INSERT INTO academic_calendar (title, start_date, file_pdf, sort_order) VALUES (?,?,?,?)'
                )->execute([$title, date('Y-m-d'), $file, $sort + 1]);
            } else {
                $pdo->prepare("INSERT INTO {$cfg['table']} ({$cfg['col']}) VALUES (?)")->execute([$file]);
            }
            $msg = $prefix === 'pilihan_pdf' ? 'PDF diganti.' : 'PDF ditambah.';
            return ['ok' => true, 'message' => $msg, 'reload' => true];
        }
        if ($block === $prefix . '_delete') {
            if ($id < 1) {
                throw new InvalidArgumentException('ID tidak sah.');
            }
            $st = $pdo->prepare("SELECT {$cfg['col']} FROM {$cfg['table']} WHERE id = ?");
            $st->execute([$id]);
            $file = (string) ($st->fetchColumn() ?: '');
            smks3_delete_project_file($cfg['dir'] . '/' . ltrim($file, '/'));
            $pdo->prepare("DELETE FROM {$cfg['table']} WHERE id = ?")->execute([$id]);
            return ['ok' => true, 'message' => 'PDF dipadam.', 'reload' => true];
        }
    }

    if ($block === 'pibg_meta' || $block === 'pibg_pdf') {
        $pibg = smks3_get_pibg_content();
        if ($block === 'pibg_meta') {
            $pibg['title'] = trim((string) ($data['title'] ?? ''));
            $pibg['subtitle'] = trim((string) ($data['subtitle'] ?? ''));
            $pibg['button_label'] = trim((string) ($data['button_label'] ?? ''));
            if ($pibg['title'] === '') {
                throw new InvalidArgumentException('Tajuk diperlukan.');
            }
            if ($pibg['button_label'] === '') {
                $pibg['button_label'] = 'Buka PDF di Tab Baru';
            }
        } else {
            if (empty($_FILES['pdf']['name'])) {
                throw new InvalidArgumentException('Sila muat naik PDF.');
            }
            $old = (string) ($pibg['pdf'] ?? '');
            if ($old !== '' && str_starts_with($old, 'uploads/pibg/')) {
                smks3_delete_project_file($old);
            }
            $pibg['pdf'] = smks3_store_upload($_FILES['pdf'], 'uploads/pibg', $pdfExt, false);
        }
        if (!smks3_save_pibg_content($pibg)) {
            throw new RuntimeException('Gagal simpan kandungan PIBG.');
        }
        return ['ok' => true, 'message' => 'Kandungan PIBG dikemaskini.', 'reload' => true];
    }

    // ── Image galleries ───────────────────────────────────
    if ($block === 'enrolmen_add') {
        $title = trim((string) ($data['title'] ?? 'Enrolmen'));
        if ($title === '') {
            $title = 'Enrolmen';
        }
        if (empty($_FILES['image']['name'])) {
            throw new InvalidArgumentException('Sila muat naik gambar.');
        }
        smks3_ensure_enrolmen_sort($pdo);
        $image = smks3_store_upload($_FILES['image'], 'uploads/enrolmen', $imgExt, true);
        $existing = smks3_enrolmen_slide_list($pdo);
        $existingIds = array_column($existing, 'id');
        $maxSort = 0;
        foreach ($existing as $row) {
            $maxSort = max($maxSort, (int) ($row['sort_order'] ?? 0));
        }
        $pdo->prepare('INSERT INTO enrolmen_murid (title, image, sort_order) VALUES (?,?,?)')
            ->execute([$title, $image, $maxSort + 10]);
        $newId = (int) $pdo->lastInsertId();
        if ($newId < 1) {
            $newId = (int) $pdo->query('SELECT MAX(id) FROM enrolmen_murid')->fetchColumn();
        }
        $position = trim((string) ($data['position'] ?? 'end'));
        if ($position === '') {
            $position = 'end';
        }
        $ordered = smks3_place_enrolmen_slide($existingIds, $newId, $position);
        smks3_set_enrolmen_slide_order($pdo, $ordered);
        return ['ok' => true, 'message' => 'Gambar ditambah.', 'reload' => true];
    }
    if ($block === 'enrolmen_item') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $title = trim((string) ($data['title'] ?? ''));
        if ($title === '') {
            throw new InvalidArgumentException('Tajuk diperlukan.');
        }
        smks3_ensure_enrolmen_sort($pdo);
        if (!empty($_FILES['image']['name'])) {
            $st = $pdo->prepare('SELECT image FROM enrolmen_murid WHERE id = ?');
            $st->execute([$id]);
            $old = (string) ($st->fetchColumn() ?: '');
            $image = smks3_store_upload($_FILES['image'], 'uploads/enrolmen', $imgExt, true);
            smks3_delete_project_file('uploads/enrolmen/' . ltrim($old, '/'));
            $pdo->prepare('UPDATE enrolmen_murid SET title = ?, image = ? WHERE id = ?')
                ->execute([$title, $image, $id]);
        } else {
            $pdo->prepare('UPDATE enrolmen_murid SET title = ? WHERE id = ?')
                ->execute([$title, $id]);
        }
        if (array_key_exists('position', $data)) {
            $position = trim((string) $data['position']);
            if ($position !== '' && $position !== 'keep') {
                $existingIds = array_column(smks3_enrolmen_slide_list($pdo), 'id');
                $ordered = smks3_place_enrolmen_slide($existingIds, $id, $position);
                smks3_set_enrolmen_slide_order($pdo, $ordered);
            }
        }
        return ['ok' => true, 'message' => 'Enrolmen dikemaskini.', 'reload' => true];
    }
    if ($block === 'enrolmen_delete') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $st = $pdo->prepare('SELECT image FROM enrolmen_murid WHERE id = ?');
        $st->execute([$id]);
        smks3_delete_project_file('uploads/enrolmen/' . ltrim((string) ($st->fetchColumn() ?: ''), '/'));
        $pdo->prepare('DELETE FROM enrolmen_murid WHERE id = ?')->execute([$id]);
        return ['ok' => true, 'message' => 'Gambar dipadam.', 'reload' => true];
    }

    if ($block === 'bil_kelas_add') {
        if (empty($_FILES['image']['name'])) {
            throw new InvalidArgumentException('Sila muat naik gambar.');
        }
        $tingkatan = trim((string) ($data['tingkatan'] ?? ''));
        $title = trim((string) ($data['title'] ?? ''));
        if ($tingkatan === '') {
            throw new InvalidArgumentException('Tingkatan diperlukan.');
        }
        smks3_ensure_bilangan_kelas_sort($pdo);
        $existingOrder = smks3_bilangan_kelas_tingkatan_order($pdo);
        $isNewTingkatan = true;
        foreach ($existingOrder as $t) {
            if (strcasecmp($t, $tingkatan) === 0) {
                $isNewTingkatan = false;
                $tingkatan = $t; // keep canonical casing
                break;
            }
        }
        if ($isNewTingkatan) {
            $position = trim((string) ($data['position'] ?? 'end'));
            if ($position === '') {
                $position = 'end';
            }
            $ordered = smks3_place_bilangan_kelas_tingkatan($existingOrder, $tingkatan, $position);
            smks3_set_bilangan_kelas_tingkatan_order($pdo, $ordered);
            $idx = array_search($tingkatan, $ordered, true);
            $sortOrder = ($idx === false ? count($ordered) : ((int) $idx + 1)) * 10;
        } else {
            $sortStmt = $pdo->prepare('SELECT COALESCE(MIN(sort_order), 0) FROM bilangan_kelas WHERE tingkatan = ?');
            $sortStmt->execute([$tingkatan]);
            $sortOrder = (int) $sortStmt->fetchColumn();
            if ($sortOrder < 1) {
                $idx = array_search($tingkatan, $existingOrder, true);
                $sortOrder = ($idx === false ? count($existingOrder) + 1 : ((int) $idx + 1)) * 10;
            }
        }
        $image = smks3_store_upload($_FILES['image'], 'uploads/bil_kelas', $imgExt, true);
        $pdo->prepare('INSERT INTO bilangan_kelas (tingkatan, title, image, sort_order) VALUES (?,?,?,?)')
            ->execute([$tingkatan, $title, $image, $sortOrder]);
        return ['ok' => true, 'message' => 'Gambar ditambah.', 'reload' => true];
    }
    if ($block === 'bil_kelas_item') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $tingkatan = trim((string) ($data['tingkatan'] ?? ''));
        $title = trim((string) ($data['title'] ?? ''));
        if ($tingkatan === '' || $title === '') {
            throw new InvalidArgumentException('Tingkatan dan tajuk diperlukan.');
        }
        if (!empty($_FILES['image']['name'])) {
            $st = $pdo->prepare('SELECT image FROM bilangan_kelas WHERE id = ?');
            $st->execute([$id]);
            $old = (string) ($st->fetchColumn() ?: '');
            $image = smks3_store_upload($_FILES['image'], 'uploads/bil_kelas', $imgExt, true);
            smks3_delete_project_file('uploads/bil_kelas/' . ltrim($old, '/'));
            $pdo->prepare('UPDATE bilangan_kelas SET tingkatan = ?, title = ?, image = ? WHERE id = ?')
                ->execute([$tingkatan, $title, $image, $id]);
        } else {
            $pdo->prepare('UPDATE bilangan_kelas SET tingkatan = ?, title = ? WHERE id = ?')
                ->execute([$tingkatan, $title, $id]);
        }
        return ['ok' => true, 'message' => 'Bilangan kelas dikemaskini.', 'reload' => true];
    }
    if ($block === 'bil_kelas_delete') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $st = $pdo->prepare('SELECT image FROM bilangan_kelas WHERE id = ?');
        $st->execute([$id]);
        smks3_delete_project_file('uploads/bil_kelas/' . ltrim((string) ($st->fetchColumn() ?: ''), '/'));
        $pdo->prepare('DELETE FROM bilangan_kelas WHERE id = ?')->execute([$id]);
        return ['ok' => true, 'message' => 'Gambar dipadam.', 'reload' => true];
    }

    if (in_array($block, ['enrolmen_feb', 'enrolmen_summary', 'enrolmen_blok', 'enrolmen_floor', 'enrolmen_room'], true)) {
        $content = smks3_get_enrolmen_content();
        if ($block === 'enrolmen_feb') {
            $content['feb']['title'] = trim((string) ($data['title'] ?? $content['feb']['title']));
            if ($content['feb']['title'] === '') {
                throw new InvalidArgumentException('Tajuk diperlukan.');
            }
            if (!empty($_FILES['image']['name'])) {
                $old = (string) ($content['feb']['image'] ?? '');
                if ($old !== '' && str_starts_with($old, 'uploads/enrolmen/')) {
                    smks3_delete_project_file($old);
                }
                $stored = smks3_store_upload($_FILES['image'], 'uploads/enrolmen', $imgExt, false);
                $content['feb']['image'] = $stored;
            }
        } elseif ($block === 'enrolmen_summary') {
            $content['summary_title'] = trim((string) ($data['title'] ?? $content['summary_title']));
            $content['summary'] = smks3_parse_lines_list((string) ($data['items'] ?? ''));
            if ($content['summary_title'] === '' || $content['summary'] === []) {
                throw new InvalidArgumentException('Tajuk dan sekurang-kurangnya satu item diperlukan.');
            }
        } elseif ($block === 'enrolmen_blok') {
            $blok = trim((string) ($data['blok'] ?? ''));
            if (!in_array($blok, ['blok_a', 'blok_b'], true)) {
                throw new InvalidArgumentException('Blok tidak sah.');
            }
            $content[$blok]['title'] = trim((string) ($data['title'] ?? ''));
            if ($content[$blok]['title'] === '') {
                throw new InvalidArgumentException('Tajuk blok diperlukan.');
            }
        } elseif ($block === 'enrolmen_floor') {
            $blok = trim((string) ($data['blok'] ?? ''));
            $floorIndex = (int) ($data['floor_index'] ?? -1);
            if (!in_array($blok, ['blok_a', 'blok_b'], true) || !isset($content[$blok]['floors'][$floorIndex])) {
                throw new InvalidArgumentException('Aras tidak sah.');
            }
            $name = trim((string) ($data['name'] ?? ''));
            if ($name === '') {
                throw new InvalidArgumentException('Nama aras diperlukan.');
            }
            $content[$blok]['floors'][$floorIndex]['name'] = $name;
        } else { // enrolmen_room
            $blok = trim((string) ($data['blok'] ?? ''));
            $floorIndex = (int) ($data['floor_index'] ?? -1);
            $roomIndex = (int) ($data['room_index'] ?? -1);
            if (
                !in_array($blok, ['blok_a', 'blok_b'], true)
                || !isset($content[$blok]['floors'][$floorIndex]['rooms'][$roomIndex])
            ) {
                throw new InvalidArgumentException('Bilik tidak sah.');
            }
            $label = trim((string) ($data['label'] ?? ''));
            $roomClass = trim((string) ($data['room_class'] ?? 'special')) ?: 'special';
            $existingClass = (string) ($content[$blok]['floors'][$floorIndex]['rooms'][$roomIndex]['class'] ?? '');
            if ($roomClass === 'special' && str_contains($existingClass, 'surau')) {
                $roomClass = 'special surau';
            }
            $content[$blok]['floors'][$floorIndex]['rooms'][$roomIndex] = smks3_normalize_enrolmen_room([
                'label' => $label,
                'class' => $roomClass,
            ]);
        }
        if (!smks3_save_enrolmen_content($content)) {
            throw new RuntimeException('Gagal simpan kandungan enrolmen.');
        }
        return ['ok' => true, 'message' => 'Kandungan enrolmen dikemaskini.', 'reload' => true];
    }

    if ($block === 'peraturan_add') {
        if (empty($_FILES['image']['name'])) {
            throw new InvalidArgumentException('Sila muat naik gambar.');
        }
        $image = smks3_store_upload($_FILES['image'], 'uploads/peraturan', $imgExt, true);
        $pdo->prepare('INSERT INTO peraturan_sekolah (image) VALUES (?)')->execute([$image]);
        return ['ok' => true, 'message' => 'Gambar ditambah.', 'reload' => true];
    }
    if ($block === 'peraturan_delete') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $st = $pdo->prepare('SELECT image FROM peraturan_sekolah WHERE id = ?');
        $st->execute([$id]);
        smks3_delete_project_file('uploads/peraturan/' . ltrim((string) ($st->fetchColumn() ?: ''), '/'));
        $pdo->prepare('DELETE FROM peraturan_sekolah WHERE id = ?')->execute([$id]);
        return ['ok' => true, 'message' => 'Gambar dipadam.', 'reload' => true];
    }

    if ($block === 'pemimpin_add') {
        if (empty($_FILES['image']['name'])) {
            throw new InvalidArgumentException('Sila muat naik gambar.');
        }
        $image = smks3_store_upload($_FILES['image'], 'uploads/pemimpin_murid', $imgExt, true);
        $pdo->prepare('INSERT INTO pemimpin_murid (image) VALUES (?)')->execute([$image]);
        return ['ok' => true, 'message' => 'Gambar ditambah.', 'reload' => true];
    }
    if ($block === 'pemimpin_delete') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $st = $pdo->prepare('SELECT image FROM pemimpin_murid WHERE id = ?');
        $st->execute([$id]);
        smks3_delete_project_file('uploads/pemimpin_murid/' . ltrim((string) ($st->fetchColumn() ?: ''), '/'));
        $pdo->prepare('DELETE FROM pemimpin_murid WHERE id = ?')->execute([$id]);
        return ['ok' => true, 'message' => 'Gambar dipadam.', 'reload' => true];
    }

    if ($block === 'pelan_image') {
        if (empty($_FILES['image']['name'])) {
            throw new InvalidArgumentException('Sila muat naik gambar.');
        }
        $image = smks3_store_upload($_FILES['image'], 'images/pelan-sekolah', $imgExt, true);
        $exists = (int) $pdo->query('SELECT COUNT(*) FROM pelan_sekolah')->fetchColumn();
        if ($exists > 0) {
            $old = $pdo->query('SELECT image FROM pelan_sekolah LIMIT 1')->fetchColumn();
            if ($old) {
                smks3_delete_project_file('images/pelan-sekolah/' . ltrim((string) $old, '/'));
            }
            $pdo->prepare('UPDATE pelan_sekolah SET image = ? LIMIT 1')->execute([$image]);
        } else {
            smks3_insert_with_auto_id($pdo, 'pelan_sekolah', 'image', '?', [$image]);
        }
        return ['ok' => true, 'message' => 'Pelan dikemaskini.', 'reload' => true];
    }

    if ($block === 'pra_sekolah' || $block === 'pra_sekolah_carta' || $block === 'pra_sekolah_galeri') {
        $row = $pdo->query('SELECT * FROM pra_sekolah LIMIT 1')->fetch(PDO::FETCH_ASSOC) ?: [];
        $carta = (string) ($row['gambar_carta'] ?? '');
        $galeri = (string) ($row['gambar_galeri'] ?? '');

        $uploadCarta = $block === 'pra_sekolah' || $block === 'pra_sekolah_carta';
        $uploadGaleri = $block === 'pra_sekolah' || $block === 'pra_sekolah_galeri';

        if ($uploadCarta && !empty($_FILES['gambar_carta']['name'])) {
            smks3_delete_project_file('uploads/pra_sekolah/' . ltrim($carta, '/'));
            $carta = smks3_store_upload($_FILES['gambar_carta'], 'uploads/pra_sekolah', $imgExt, true);
        }
        if ($uploadGaleri && !empty($_FILES['gambar_galeri']['name'])) {
            smks3_delete_project_file('uploads/pra_sekolah/' . ltrim($galeri, '/'));
            $galeri = smks3_store_upload($_FILES['gambar_galeri'], 'uploads/pra_sekolah', $imgExt, true);
        }

        if ($block === 'pra_sekolah_carta' && empty($_FILES['gambar_carta']['name'])) {
            throw new InvalidArgumentException('Sila pilih gambar carta.');
        }
        if ($block === 'pra_sekolah_galeri' && empty($_FILES['gambar_galeri']['name'])) {
            throw new InvalidArgumentException('Sila pilih gambar galeri.');
        }

        if ($row) {
            $pdo->prepare('UPDATE pra_sekolah SET gambar_carta=?, gambar_galeri=? LIMIT 1')->execute([$carta, $galeri]);
        } else {
            $pdo->prepare('INSERT INTO pra_sekolah (gambar_carta, gambar_galeri) VALUES (?,?)')->execute([$carta, $galeri]);
        }
        return ['ok' => true, 'message' => 'Gambar pra sekolah dikemaskini.', 'reload' => true];
    }

    if ($block === 'lencana_main' || $block === 'lencana_moto' || $block === 'lencana_lagu') {
        $row = $pdo->query('SELECT * FROM lencana_lagu_sekolah WHERE id = 1')->fetch(PDO::FETCH_ASSOC) ?: [];
        $moto = array_key_exists('moto', $data) ? trim((string) $data['moto']) : trim((string) ($row['moto'] ?? ''));
        $lirik = array_key_exists('lirik', $data) ? trim((string) $data['lirik']) : trim((string) ($row['lirik'] ?? ''));
        $penggubah = array_key_exists('lirik_penggubah', $data)
            ? trim((string) $data['lirik_penggubah'])
            : trim((string) ($row['lirik_penggubah'] ?? ''));
        $penulis = array_key_exists('lirik_penulis', $data)
            ? trim((string) $data['lirik_penulis'])
            : trim((string) ($row['lirik_penulis'] ?? ''));
        $image = (string) ($row['image'] ?? '');

        if ($block === 'lencana_moto' && $moto === '') {
            throw new InvalidArgumentException('Moto diperlukan.');
        }
        if ($block === 'lencana_lagu' && $lirik === '') {
            throw new InvalidArgumentException('Lirik lagu diperlukan.');
        }

        if ($block === 'lencana_main' && !empty($_FILES['image']['name'])) {
            if ($image !== '') {
                smks3_delete_project_file('images/' . ltrim($image, '/'));
            }
            $image = smks3_store_upload($_FILES['image'], 'images', $imgExt, true);
        }

        if ($row) {
            $pdo->prepare('UPDATE lencana_lagu_sekolah SET moto=?, lirik=?, lirik_penggubah=?, lirik_penulis=?, image=? WHERE id=1')
                ->execute([$moto, $lirik, $penggubah, $penulis, $image]);
        } else {
            $pdo->prepare('INSERT INTO lencana_lagu_sekolah (id, moto, lirik, lirik_penggubah, lirik_penulis, image) VALUES (1,?,?,?,?,?)')
                ->execute([$moto, $lirik, $penggubah, $penulis, $image]);
        }

        $message = $block === 'lencana_moto'
            ? 'Moto dikemaskini.'
            : ($block === 'lencana_lagu' ? 'Lagu sekolah dikemaskini.' : 'Lencana & lagu dikemaskini.');

        return [
            'ok' => true,
            'message' => $message,
            'fields' => compact('moto', 'lirik') + [
                'lirik_penggubah' => $penggubah,
                'lirik_penulis' => $penulis,
            ],
            'reload' => true,
        ];
    }

    if ($block === 'lencana_item') {
        $title = trim((string) ($data['title'] ?? ''));
        $desc = trim((string) ($data['description'] ?? ''));
        if ($id < 1 || $title === '') {
            throw new InvalidArgumentException('Tajuk diperlukan.');
        }
        $pdo->prepare('UPDATE lencana_item SET title=?, description=? WHERE id=?')->execute([$title, $desc, $id]);
        return ['ok' => true, 'message' => 'Item dikemaskini.', 'reload' => true];
    }
    if ($block === 'lencana_item_add') {
        $title = trim((string) ($data['title'] ?? ''));
        if ($title === '') {
            throw new InvalidArgumentException('Tajuk diperlukan.');
        }
        $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order),0) FROM lencana_item')->fetchColumn();
        $pdo->prepare('INSERT INTO lencana_item (title, description, sort_order) VALUES (?,?,?)')
            ->execute([$title, trim((string) ($data['description'] ?? '')), $max + 1]);
        return ['ok' => true, 'message' => 'Item ditambah.', 'reload' => true];
    }
    if ($block === 'lencana_item_delete') {
        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $pdo->prepare('DELETE FROM lencana_item WHERE id = ?')->execute([$id]);
        return ['ok' => true, 'message' => 'Item dipadam.', 'reload' => true];
    }

    if ($block === 'profil_item' || $block === 'profil_item_add' || $block === 'profil_item_delete') {
        smks3_ensure_profil_items_table($pdo);
        if ($block === 'profil_item_delete') {
            if ($id < 1) {
                throw new InvalidArgumentException('ID tidak sah.');
            }
            $pdo->prepare('DELETE FROM profil_item WHERE id = ?')->execute([$id]);
            return ['ok' => true, 'message' => 'Maklumat profil dipadam.', 'reload' => true];
        }

        $title = trim((string) ($data['title'] ?? ''));
        $value = trim((string) ($data['value'] ?? ''));
        $icon = trim((string) ($data['icon'] ?? 'bi-info-circle'));
        if ($title === '') {
            throw new InvalidArgumentException('Tajuk diperlukan.');
        }
        if ($icon === '' || !preg_match('/^bi-[a-z0-9-]+$/i', $icon)) {
            $icon = 'bi-info-circle';
        }

        if ($block === 'profil_item_add') {
            $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order),0) FROM profil_item')->fetchColumn();
            $pdo->prepare('INSERT INTO profil_item (title, value_text, icon, sort_order) VALUES (?,?,?,?)')
                ->execute([$title, $value, $icon, $max + 1]);
            return ['ok' => true, 'message' => 'Maklumat profil ditambah.', 'reload' => true];
        }

        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $pdo->prepare('UPDATE profil_item SET title=?, value_text=?, icon=? WHERE id=?')
            ->execute([$title, $value, $icon, $id]);
        return ['ok' => true, 'message' => 'Maklumat profil dikemaskini.', 'reload' => true];
    }

    if ($block === 'editable_table') {
        $key = trim((string) ($data['table_key'] ?? ''));
        $store = trim((string) ($data['table_store'] ?? 'site_content'));
        $html = (string) ($data['content'] ?? '');
        if ($key === '' || !smks3_is_safe_content_key($key) || $html === '' || stripos($html, '<table') === false) {
            throw new InvalidArgumentException('Jadual tidak sah.');
        }
        if (!in_array($store, ['site_content', 'pages'], true)) {
            throw new InvalidArgumentException('Stor jadual tidak sah.');
        }
        if (preg_match('/<table\b[^>]*>.*<\/table>/is', $html, $m)) {
            $html = $m[0];
        }
        if ($store === 'pages') {
            $exists = $pdo->prepare('SELECT id FROM pages WHERE page_key = ?');
            $exists->execute([$key]);
            if ($exists->fetchColumn()) {
                $pdo->prepare('UPDATE pages SET content=? WHERE page_key=?')->execute([$html, $key]);
            } else {
                $pdo->prepare('INSERT INTO pages (page_key, title, content) VALUES (?,?,?)')
                    ->execute([$key, $key, $html]);
            }
        } else {
            if (!smks3_save_site_content($key, $html)) {
                throw new RuntimeException('Gagal simpan jadual.');
            }
        }
        return ['ok' => true, 'message' => 'Jadual dikemaskini.'];
    }

    if ($block === 'cuti_kumpulan') {
        $a = trim((string) ($data['kumpulan_a'] ?? $data['a'] ?? ''));
        $b = trim((string) ($data['kumpulan_b'] ?? $data['b'] ?? ''));
        if ($a === '' || $b === '') {
            throw new InvalidArgumentException('Kumpulan A dan B diperlukan.');
        }
        $groups = ['a' => $a, 'b' => $b];
        $payload = json_encode($groups, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $html = smks3_format_cuti_kumpulan_html($groups);
        if ($payload === false
            || !smks3_save_site_content('cuti_perayaan_kumpulan', $payload)
            || !smks3_save_site_content('cuti_perayaan_intro', $html)
        ) {
            throw new RuntimeException('Gagal simpan kumpulan cuti.');
        }
        return [
            'ok' => true,
            'message' => 'Kumpulan A & B dikemaskini.',
            'fields' => [
                'kumpulan_a' => $a,
                'kumpulan_b' => $b,
                'html' => $html,
            ],
        ];
    }

    if ($block === 'kurikulum_meta') {
        $pageKey = trim((string) ($data['page_key'] ?? ''));
        if ($pageKey === '') {
            throw new InvalidArgumentException('Halaman tidak sah.');
        }
        $existing = smks3_get_kurikulum_meta($pageKey);
        $intro = array_key_exists('intro', $data)
            ? trim((string) ($data['intro'] ?? ''))
            : (string) ($existing['intro'] ?? '');
        $sections = is_array($existing['sections'] ?? null) ? $existing['sections'] : [];
        $rawSections = $data['sections_json'] ?? $data['sections'] ?? '';
        $sectionFieldsTouched = false;
        if (is_string($rawSections) && $rawSections !== '') {
            $decoded = json_decode($rawSections, true);
            if (is_array($decoded)) {
                $sectionFieldsTouched = true;
                foreach ($decoded as $key => $sec) {
                    if (!is_array($sec)) {
                        continue;
                    }
                    $sections[(string) $key] = [
                        'title' => trim((string) ($sec['title'] ?? '')),
                        'subtitle' => trim((string) ($sec['subtitle'] ?? '')),
                    ];
                }
            }
        }
        foreach ($data as $k => $v) {
            if (!is_string($k) || !preg_match('/^section_([a-z0-9_-]+)_(title|subtitle)$/', $k, $m)) {
                continue;
            }
            $sectionFieldsTouched = true;
            $secKey = $m[1];
            $field = $m[2];
            if (!isset($sections[$secKey]) || !is_array($sections[$secKey])) {
                $sections[$secKey] = ['title' => '', 'subtitle' => ''];
            }
            $sections[$secKey][$field] = trim((string) $v);
        }
        // Intro-only panels must not wipe section titles.
        if (!$sectionFieldsTouched) {
            $sections = is_array($existing['sections'] ?? null) ? $existing['sections'] : [];
        }
        if (!smks3_save_kurikulum_meta($pageKey, ['intro' => $intro, 'sections' => $sections])) {
            throw new RuntimeException('Gagal simpan tajuk halaman.');
        }
        return ['ok' => true, 'message' => 'Tajuk halaman dikemaskini.', 'reload' => true];
    }

    if ($block === 'kurikulum_section') {
        $pageKey = trim((string) ($data['page_key'] ?? ''));
        $sectionKey = trim((string) ($data['section_key'] ?? ''));
        if ($pageKey === '' || $sectionKey === '' || !preg_match('/^[a-z0-9_-]+$/i', $sectionKey)) {
            throw new InvalidArgumentException('Bahagian tidak sah.');
        }
        $existing = smks3_get_kurikulum_meta($pageKey);
        $sections = is_array($existing['sections'] ?? null) ? $existing['sections'] : [];
        $prev = is_array($sections[$sectionKey] ?? null) ? $sections[$sectionKey] : ['title' => '', 'subtitle' => ''];
        $sections[$sectionKey] = [
            'title' => trim((string) ($data['title'] ?? '')),
            'subtitle' => array_key_exists('subtitle', $data)
                ? trim((string) ($data['subtitle'] ?? ''))
                : trim((string) ($prev['subtitle'] ?? '')),
        ];
        if (!smks3_save_kurikulum_meta($pageKey, [
            'intro' => (string) ($existing['intro'] ?? ''),
            'sections' => $sections,
        ])) {
            throw new RuntimeException('Gagal simpan tajuk bahagian.');
        }
        return ['ok' => true, 'message' => 'Tajuk bahagian dikemaskini.', 'reload' => true];
    }

    if (str_starts_with($block, 'ubk_')) {
        $ubk = smks3_get_ubk_content();
        $imageMap = [
            'ubk_carta_image' => 'carta_image',
            'ubk_pamplet1' => 'pamplet1_image',
            'ubk_pamplet2' => 'pamplet2_image',
        ];

        if (isset($imageMap[$block])) {
            $fieldKey = $imageMap[$block];
            if (empty($_FILES['image']['name'])) {
                throw new InvalidArgumentException('Sila pilih gambar.');
            }
            $old = (string) ($ubk[$fieldKey] ?? '');
            if ($old !== '' && str_starts_with($old, 'uploads/ubk/')) {
                smks3_delete_project_file($old);
            }
            $stored = smks3_store_upload($_FILES['image'], 'uploads/ubk', $imgExt, false);
            $ubk[$fieldKey] = $stored;
            if (!smks3_save_ubk_content($ubk)) {
                throw new RuntimeException('Gagal simpan gambar UBK.');
            }
            return ['ok' => true, 'message' => 'Gambar dikemaskini.', 'reload' => true];
        }

        if ($block === 'ubk_pengenalan') {
            $ubk['lead'] = trim((string) ($data['lead'] ?? ''));
            $ubk['pengenalan_title'] = trim((string) ($data['title'] ?? ''));
            $ubk['pengenalan_body'] = trim((string) ($data['body'] ?? ''));
            if ($ubk['pengenalan_title'] === '' || $ubk['pengenalan_body'] === '') {
                throw new InvalidArgumentException('Tajuk dan kandungan diperlukan.');
            }
        } elseif ($block === 'ubk_visi') {
            $ubk['visi'] = trim((string) ($data['value'] ?? ''));
            if ($ubk['visi'] === '') {
                throw new InvalidArgumentException('Visi diperlukan.');
            }
        } elseif ($block === 'ubk_misi') {
            $ubk['misi'] = trim((string) ($data['value'] ?? ''));
            if ($ubk['misi'] === '') {
                throw new InvalidArgumentException('Misi diperlukan.');
            }
        } elseif ($block === 'ubk_falsafah') {
            $ubk['falsafah'] = trim((string) ($data['value'] ?? ''));
            if ($ubk['falsafah'] === '') {
                throw new InvalidArgumentException('Falsafah diperlukan.');
            }
        } elseif ($block === 'ubk_objektif') {
            $ubk['objektif'] = smks3_parse_lines_list((string) ($data['value'] ?? ''));
            if ($ubk['objektif'] === []) {
                throw new InvalidArgumentException('Sekurang-kurangnya satu objektif diperlukan.');
            }
        } elseif ($block === 'ubk_fungsi') {
            $ubk['fungsi'] = smks3_parse_lines_list((string) ($data['value'] ?? ''));
            if ($ubk['fungsi'] === []) {
                throw new InvalidArgumentException('Sekurang-kurangnya satu fungsi diperlukan.');
            }
        } elseif ($block === 'ubk_aktiviti') {
            $ubk['aktiviti_note'] = trim((string) ($data['value'] ?? ''));
        } else {
            return null;
        }

        if (!smks3_save_ubk_content($ubk)) {
            throw new RuntimeException('Gagal simpan kandungan UBK.');
        }
        return ['ok' => true, 'message' => 'Kandungan UBK dikemaskini.', 'reload' => true];
    }

    if ($block === 'kurikulum_card' || $block === 'kurikulum_card_add' || $block === 'kurikulum_card_delete') {
        smks3_ensure_kurikulum_cards_table($pdo);
        $pageKey = trim((string) ($data['page_key'] ?? ''));
        $sectionKey = trim((string) ($data['section_key'] ?? 'main')) ?: 'main';

        if ($block === 'kurikulum_card_delete') {
            if ($id < 1) {
                throw new InvalidArgumentException('ID tidak sah.');
            }
            $pdo->prepare('DELETE FROM kurikulum_card WHERE id = ?')->execute([$id]);
            return ['ok' => true, 'message' => 'Kad dipadam.', 'reload' => true];
        }

        $title = trim((string) ($data['title'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        $icon = trim((string) ($data['icon'] ?? 'bi-folder2-open')) ?: 'bi-folder2-open';
        if (!str_starts_with($icon, 'bi-')) {
            $icon = 'bi-' . ltrim($icon, '-');
        }
        $href = trim((string) ($data['href'] ?? ''));
        $btnLabel = trim((string) ($data['btn_label'] ?? ''));
        $external = !empty($data['external']) || !empty($data['is_external']);

        $links = [];
        $titles = $data['link_title'] ?? null;
        $hrefs = $data['link_href'] ?? null;
        if (is_array($titles) && is_array($hrefs)) {
            $count = max(count($titles), count($hrefs));
            for ($i = 0; $i < $count; $i++) {
                $lt = trim((string) ($titles[$i] ?? ''));
                $lh = trim((string) ($hrefs[$i] ?? ''));
                if ($lt === '' && $lh === '') {
                    continue;
                }
                if ($lt === '') {
                    $lt = $lh !== '' ? $lh : 'Pautan';
                }
                // Skip incomplete "https://" placeholders from "Tambah pautan luar"
                if ($lh === 'https://' || $lh === 'http://') {
                    continue;
                }
                $links[] = ['title' => $lt, 'href' => $lh !== '' ? $lh : '#'];
            }
        } elseif (isset($data['links'])) {
            $links = smks3_parse_kurikulum_links_text((string) $data['links']);
        }
        $links = smks3_normalize_kurikulum_links($links);

        // Protect internal portal page links / button hrefs.
        $existing = null;
        if ($id > 0) {
            $exStmt = $pdo->prepare('SELECT href, is_external, links_json FROM kurikulum_card WHERE id = ? LIMIT 1');
            $exStmt->execute([$id]);
            $existing = $exStmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        if (is_array($existing)) {
            $existingHref = trim((string) ($existing['href'] ?? ''));
            if ($existingHref !== '' && !smks3_is_external_kurikulum_url($existingHref)) {
                $href = $existingHref;
                $external = !empty($existing['is_external']);
            }
            $existingLinks = smks3_normalize_kurikulum_links($existing['links_json'] ?? []);
            $allowedInternal = [];
            foreach ($existingLinks as $exLink) {
                if (!smks3_is_external_kurikulum_url((string) ($exLink['href'] ?? ''))) {
                    $allowedInternal[$exLink['title'] . "\0" . $exLink['href']] = $exLink;
                }
            }
            $safeLinks = [];
            $keptInternal = [];
            foreach ($links as $link) {
                $lh = (string) ($link['href'] ?? '');
                if (smks3_is_external_kurikulum_url($lh)) {
                    $safeLinks[] = $link;
                    continue;
                }
                $key = ($link['title'] ?? '') . "\0" . $lh;
                if (isset($allowedInternal[$key])) {
                    $safeLinks[] = $allowedInternal[$key];
                    $keptInternal[$key] = true;
                }
            }
            foreach ($allowedInternal as $key => $exLink) {
                if (empty($keptInternal[$key])) {
                    $safeLinks[] = $exLink;
                }
            }
            $links = smks3_normalize_kurikulum_links($safeLinks);
        } else {
            // New cards: only allow external folder links.
            $links = array_values(array_filter(
                $links,
                static fn(array $link): bool => smks3_is_external_kurikulum_url((string) ($link['href'] ?? ''))
            ));
            if ($href !== '' && !smks3_is_external_kurikulum_url($href)) {
                $href = '';
                $external = false;
            }
        }

        $linksJson = $links === [] ? null : json_encode($links, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($title === '') {
            throw new InvalidArgumentException('Tajuk kad diperlukan.');
        }
        if ($pageKey === '') {
            throw new InvalidArgumentException('Halaman tidak sah.');
        }

        if ($block === 'kurikulum_card_add') {
            $maxStmt = $pdo->prepare('SELECT COALESCE(MAX(sort_order),0) FROM kurikulum_card WHERE page_key = ?');
            $maxStmt->execute([$pageKey]);
            $max = (int) $maxStmt->fetchColumn();
            $pdo->prepare(
                'INSERT INTO kurikulum_card
                (page_key, section_key, title, description, icon, href, is_external, btn_label, links_json, sort_order)
                VALUES (?,?,?,?,?,?,?,?,?,?)'
            )->execute([
                $pageKey,
                $sectionKey,
                $title,
                $description,
                $icon,
                $href,
                $external ? 1 : 0,
                $btnLabel,
                $linksJson,
                $max + 1,
            ]);
            return ['ok' => true, 'message' => 'Kad ditambah.', 'reload' => true];
        }

        if ($id < 1) {
            throw new InvalidArgumentException('ID tidak sah.');
        }
        $pdo->prepare(
            'UPDATE kurikulum_card
             SET section_key=?, title=?, description=?, icon=?, href=?, is_external=?, btn_label=?, links_json=?
             WHERE id=?'
        )->execute([
            $sectionKey,
            $title,
            $description,
            $icon,
            $href,
            $external ? 1 : 0,
            $btnLabel,
            $linksJson,
            $id,
        ]);
        return ['ok' => true, 'message' => 'Kad dikemaskini.', 'reload' => true];
    }

    if ($block === 'editable_html') {
        $key = trim((string) ($data['content_key'] ?? ''));
        $html = (string) ($data['content'] ?? '');
        if ($key === '' || !smks3_is_safe_content_key($key)) {
            throw new InvalidArgumentException('Kunci kandungan tidak sah.');
        }
        if (!smks3_save_site_content($key, $html)) {
            throw new RuntimeException('Gagal simpan kandungan.');
        }
        return ['ok' => true, 'message' => 'Kandungan dikemaskini.'];
    }

    if ($block === 'kalendar_title') {
        $title = trim((string) ($data['value'] ?? $data['title'] ?? ''));
        if ($title === '') {
            throw new InvalidArgumentException('Tajuk diperlukan.');
        }
        $exists = $pdo->prepare('SELECT id FROM pages WHERE page_key = ?');
        $exists->execute(['kalendar_akademik']);
        if ($exists->fetchColumn()) {
            $pdo->prepare('UPDATE pages SET title=? WHERE page_key=?')->execute([$title, 'kalendar_akademik']);
        } else {
            $pdo->prepare('INSERT INTO pages (page_key, title, content) VALUES (?,?,?)')->execute(['kalendar_akademik', $title, '']);
        }
        return ['ok' => true, 'message' => 'Tajuk dikemaskini.', 'fields' => ['value' => $title]];
    }

    if ($block === 'kalendar_table') {
        $html = (string) ($data['content'] ?? '');
        if ($html === '' || stripos($html, '<table') === false) {
            throw new InvalidArgumentException('Jadual tidak sah.');
        }
        // Keep only a safe table fragment for teachers' cell edits.
        if (preg_match('/<table\b[^>]*>.*<\/table>/is', $html, $m)) {
            $html = $m[0];
        }
        $exists = $pdo->prepare('SELECT id FROM pages WHERE page_key = ?');
        $exists->execute(['kalendar_akademik']);
        if ($exists->fetchColumn()) {
            $pdo->prepare('UPDATE pages SET content=? WHERE page_key=?')->execute([$html, 'kalendar_akademik']);
        } else {
            $pdo->prepare('INSERT INTO pages (page_key, title, content) VALUES (?,?,?)')
                ->execute(['kalendar_akademik', 'Kalendar Akademik 2026', $html]);
        }
        return ['ok' => true, 'message' => 'Jadual dikemaskini.'];
    }

    if ($block === 'kalendar_page') {
        $title = trim((string) ($data['title'] ?? ''));
        $plain = trim((string) ($data['content'] ?? ''));
        $content = $plain === '' ? '' : smks3_plain_text_to_paragraphs($plain);
        $exists = $pdo->prepare('SELECT id FROM pages WHERE page_key = ?');
        $exists->execute(['kalendar_akademik']);
        if ($exists->fetchColumn()) {
            $pdo->prepare('UPDATE pages SET title=?, content=? WHERE page_key=?')->execute([$title, $content, 'kalendar_akademik']);
        } else {
            $pdo->prepare('INSERT INTO pages (page_key, title, content) VALUES (?,?,?)')->execute(['kalendar_akademik', $title, $content]);
        }
        return ['ok' => true, 'message' => 'Kalendar dikemaskini.', 'reload' => true];
    }

    return null;
}
