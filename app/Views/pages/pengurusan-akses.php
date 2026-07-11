<?php
/** @var list<array<string,mixed>> $units */
/** @var list<array<string,mixed>> $admins */
/** @var array<string, array{label:string,group:string}> $catalog */
/** @var int $selectedUnitId */
/** @var list<string> $selectedPermissions */
/** @var string $tab */

$units = is_array($units ?? null) ? $units : [];
$admins = is_array($admins ?? null) ? $admins : [];
$catalog = is_array($catalog ?? null) ? $catalog : [];
$selectedUnitId = (int) ($selectedUnitId ?? 0);
$selectedPermissions = is_array($selectedPermissions ?? null) ? $selectedPermissions : [];
$tab = (string) ($tab ?? 'units');

$groups = [];
foreach ($catalog as $key => $meta) {
    $g = (string) ($meta['group'] ?? 'Lain');
    $groups[$g][$key] = $meta['label'];
}

$unitsJson = [];
foreach ($units as $u) {
    $unitsJson[] = [
        'id' => (int) ($u['id'] ?? 0),
        'name' => (string) ($u['name'] ?? ''),
        'slug' => (string) ($u['slug'] ?? ''),
        'description' => (string) ($u['description'] ?? ''),
        'admin_count' => (int) ($u['admin_count'] ?? 0),
        'permission_count' => (int) ($u['permission_count'] ?? 0),
    ];
}
$adminsJson = [];
foreach ($admins as $a) {
    $adminsJson[] = [
        'id' => (int) ($a['id'] ?? 0),
        'username' => (string) ($a['username'] ?? ''),
        'role' => (string) ($a['role'] ?? 'admin'),
        'unit_id' => isset($a['unit_id']) ? (int) $a['unit_id'] : null,
        'unit_name' => (string) ($a['unit_name'] ?? ''),
        'is_active' => isset($a['is_active']) ? (int) $a['is_active'] : 1,
    ];
}
?>
<section class="page-section">
    <div class="container">
        <div class="rbac-tab-bar border rounded-3 bg-white shadow-sm mb-4 px-2 py-2">
            <ul class="nav nav-pills gap-2 rbac-tabs mb-0" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?= $tab === 'units' ? 'active' : '' ?>" href="pengurusan-akses?tab=units">Unit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $tab === 'admins' ? 'active' : '' ?>" href="pengurusan-akses?tab=admins">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $tab === 'permissions' ? 'active' : '' ?>" href="pengurusan-akses?tab=permissions<?= $selectedUnitId ? '&unit=' . $selectedUnitId : '' ?>">Kebenaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $tab === 'lain-lain' ? 'active' : '' ?>" href="pengurusan-akses?tab=lain-lain">Lain-lain</a>
                </li>
            </ul>
        </div>
<style>
.rbac-tab-bar {
    border-color: rgba(11, 60, 93, 0.15) !important;
}
.rbac-tabs {
    display: flex;
    flex-wrap: nowrap;
    width: 100%;
}
.rbac-tabs > .nav-item {
    flex: 1 1 0;
    min-width: 0;
}
.rbac-tabs .nav-link {
    width: 100%;
    text-align: center;
}
.rbac-tabs.nav-pills .nav-link.active,
.rbac-tabs.nav-pills .nav-link.active:hover,
.rbac-tabs.nav-pills .nav-link.active:focus,
.rbac-tabs.nav-pills .nav-link.active:focus-visible,
.rbac-tabs.nav-pills .nav-item.show .nav-link.active {
    color: #ffffff !important;
    background-color: var(--school-primary, #0B3C5D) !important;
    background-image: none !important;
}
.rbac-pagination .page-item.active .page-link {
    color: #fff !important;
}
.rbac-admin-filters {
    flex: 1 1 auto;
    min-width: 0;
    max-width: 100%;
}
.rbac-admin-filters input[name="q"] {
    flex: 1 1 9rem;
    min-width: 7rem;
    width: auto;
}
.rbac-admin-filters select[name="unit_filter"] {
    flex: 0 0 10rem;
    width: 10rem;
}
.rbac-pagination .page-link { cursor: pointer; }
.rbac-panel {
    min-height: 18rem;
}
.rbac-list-body {
    flex: 1 1 auto;
    min-height: 12rem;
    display: flex;
    flex-direction: column;
}
.rbac-list-empty:not([hidden]) {
    flex: 1 1 auto;
    display: grid !important;
    place-items: center;
    text-align: center;
    width: 100%;
    min-height: 12rem;
    margin: 0;
}
</style>

        <?php if ($tab === 'units'): ?>
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="border rounded-3 p-3 bg-white shadow-sm h-100 d-flex flex-column justify-content-center rbac-panel">
                    <h2 class="h5 mb-3">Tambah unit</h2>
                    <form id="rbacUnitCreate" class="vstack gap-3">
                        <div>
                            <label class="form-label" for="unit_name">Nama unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="unit_name" name="name" required placeholder="Contoh: Kurikulum">
                        </div>
                        <div>
                            <label class="form-label" for="unit_desc">Penerangan (pilihan)</label>
                            <input type="text" class="form-control" id="unit_desc" name="description" placeholder="Ringkas">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Simpan unit</button>
                            <div id="rbacUnitFormStatus" class="small mt-2" aria-live="polite" hidden></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="border rounded-3 p-3 bg-white shadow-sm h-100 d-flex flex-column rbac-panel">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h2 class="h5 mb-0">Senarai unit</h2>
                        <input type="search" class="form-control form-control-sm" id="rbacUnitSearch" placeholder="Cari unit…" style="min-width:10rem; max-width:14rem;" autocomplete="off">
                    </div>
                    <div class="rbac-list-body">
                        <div id="rbacUnitsEmpty" class="text-muted rbac-list-empty" hidden></div>
                        <div class="table-responsive" id="rbacUnitsTableWrap">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Admin</th>
                                        <th>Kebenaran</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="rbacUnitsBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <nav class="mt-3" id="rbacUnitsPager" aria-label="Halaman senarai" hidden></nav>
                    <div id="rbacUnitListStatus" class="small mt-2" aria-live="polite" hidden></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($tab === 'admins'): ?>
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="border rounded-3 p-3 bg-white shadow-sm h-100 d-flex flex-column justify-content-center rbac-panel">
                    <h2 class="h5 mb-3">Daftar admin</h2>
                    <div id="rbacAdminCreateEmpty" class="text-muted" <?= $units === [] ? '' : 'hidden' ?>>
                        Sila <a href="pengurusan-akses?tab=units">cipta unit</a> dahulu.
                    </div>
                    <form id="rbacAdminCreate" class="vstack gap-3" <?= $units === [] ? 'hidden' : '' ?>>
                        <div>
                            <label class="form-label" for="admin_username">Nama pengguna <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="admin_username" name="username" required autocomplete="off">
                        </div>
                        <div>
                            <label class="form-label" for="admin_password">Kata laluan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="admin_password" name="password" required minlength="6" autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary" id="rbacAdminTogglePw" title="Tunjuk/sembunyi kata laluan" aria-label="Tunjuk kata laluan">
                                    <i class="bi bi-eye" id="rbacAdminEye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="form-label" for="admin_unit">Unit <span class="text-danger">*</span></label>
                            <select class="form-select" id="admin_unit" name="unit_id" required>
                                <option value="">— Pilih unit —</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Daftar admin</button>
                            <div id="rbacAdminFormStatus" class="small mt-2" aria-live="polite" hidden></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="border rounded-3 p-3 bg-white shadow-sm h-100 d-flex flex-column rbac-panel">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h2 class="h5 mb-0">Senarai admin</h2>
                        <div class="d-flex flex-nowrap gap-2 align-items-center rbac-admin-filters">
                            <input type="search" class="form-control form-control-sm" id="rbacAdminSearch" placeholder="Cari admin…" autocomplete="off">
                            <select class="form-select form-select-sm" id="rbacAdminUnitFilter">
                                <option value="0">Semua unit</option>
                            </select>
                        </div>
                    </div>
                    <div class="rbac-list-body">
                        <div id="rbacAdminsEmpty" class="text-muted rbac-list-empty" hidden></div>
                        <div class="table-responsive" id="rbacAdminsTableWrap">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Pengguna</th>
                                        <th>Unit</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="rbacAdminsBody"></tbody>
                            </table>
                        </div>
                    </div>
                    <nav class="mt-3" id="rbacAdminsPager" aria-label="Halaman senarai" hidden></nav>
                    <div id="rbacAdminListStatus" class="small mt-2" aria-live="polite" hidden></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($tab === 'permissions'): ?>
        <div class="border rounded-3 p-3 bg-white shadow-sm">
            <div class="d-flex flex-wrap gap-3 align-items-end mb-4">
                <div>
                    <label class="form-label" for="perm_unit">Unit</label>
                    <select class="form-select" id="perm_unit" style="min-width:16rem;">
                        <?php foreach ($units as $u): ?>
                            <option value="<?= (int) $u['id'] ?>" <?= $selectedUnitId === (int) $u['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars((string) $u['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="text-muted small mb-2 mb-md-0">Tandakan halaman / kawasan yang admin dalam unit ini boleh sunting.</p>
            </div>

            <?php if ($units === [] || $selectedUnitId < 1): ?>
                <p class="text-muted mb-0">Tiada unit untuk ditetapkan kebenaran.</p>
            <?php else: ?>
            <form id="rbacPermissions">
                <input type="hidden" name="id" value="<?= $selectedUnitId ?>">
                <?php foreach ($groups as $groupName => $items):
                    $groupKeys = array_keys($items);
                    $groupCheckedCount = count(array_intersect($groupKeys, $selectedPermissions));
                    $groupAllChecked = $groupCheckedCount > 0 && $groupCheckedCount === count($groupKeys);
                    $groupPartial = $groupCheckedCount > 0 && !$groupAllChecked;
                ?>
                    <div class="mb-4" data-perm-group>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <h3 class="h6 text-uppercase text-muted mb-0"><?= htmlspecialchars($groupName) ?></h3>
                            <label class="small mb-0 d-inline-flex align-items-center gap-2 user-select-none">
                                <input type="checkbox"
                                       class="form-check-input m-0"
                                       data-perm-group-toggle
                                       <?= $groupAllChecked ? 'checked' : '' ?>
                                       <?= $groupPartial ? 'data-indeterminate="1"' : '' ?>>
                                <span>Pilih semua</span>
                            </label>
                        </div>
                        <div class="row g-2">
                            <?php foreach ($items as $key => $label): ?>
                            <div class="col-md-6 col-lg-4">
                                <label class="border rounded-2 px-3 py-2 d-flex align-items-center gap-2 h-100 mb-0">
                                    <input type="checkbox" class="form-check-input m-0" name="permissions[]" value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                                        data-perm-item
                                        <?= in_array($key, $selectedPermissions, true) ? 'checked' : '' ?>>
                                    <span><?= htmlspecialchars($label) ?></span>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div>
                    <button type="submit" class="btn btn-primary">Simpan kebenaran</button>
                    <div id="rbacPermStatus" class="small mt-2" aria-live="polite" hidden></div>
                </div>
            </form>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($tab === 'lain-lain'):
            $publicExternalDocs = !empty($publicExternalDocs);
        ?>
        <div class="border rounded-3 p-3 p-md-4 bg-white shadow-sm">
            <h2 class="h5 mb-1">Pautan Google Sheets / Drive / Docs</h2>
            <p class="text-muted small mb-3">
                Kawal sama ada pelawat awam boleh membuka pautan Google Sheets, Drive dan Docs.
                Looker Studio tidak dikunci.
                Kebenaran fail di Google masih perlu dikawal berasingan (contoh: view sahaja).
            </p>
            <div class="border rounded-3 bg-light px-3 py-2 mb-4">
                <p class="small fw-semibold mb-2">Modul terlibat</p>
                <ul class="small mb-0 ps-3">
                    <li class="mb-1">
                        <span class="text-muted">Kurikulum <span class="opacity-50">/</span> Pentaksiran Dan Peperiksaan <span class="opacity-50">/</span></span>
                        <a href="analisis-ppt">Analisis PPT</a>
                    </li>
                    <li class="mb-1">
                        <span class="text-muted">Kurikulum <span class="opacity-50">/</span> Pentaksiran Dan Peperiksaan <span class="opacity-50">/</span></span>
                        <a href="analisis-pat-t4-uasa-t1,2,3">Analisis PAT T4 &amp; UASA T1,2,3</a>
                    </li>
                    <li class="mb-1">
                        <span class="text-muted">Kurikulum <span class="opacity-50">/</span> Pentaksiran Dan Peperiksaan <span class="opacity-50">/</span></span>
                        <a href="bank-soalan-uasa-ppt-pat-selaras">Bank Soalan UASA PPT, PAT</a>
                    </li>
                    <li class="mb-1">
                        <span class="text-muted">Kurikulum <span class="opacity-50">/</span> Pentaksiran Dan Peperiksaan <span class="opacity-50">/</span></span>
                        <a href="keputusan">Keputusan 2018-2024</a>
                    </li>
                    <li>
                        <span class="text-muted">Kurikulum <span class="opacity-50">/</span> Pentaksiran Dan Peperiksaan <span class="opacity-50">/</span></span>
                        <a href="penggubal-soalan-upsa-uasa">Penggubal Soalan UPSA &amp; UASA</a>
                    </li>
                </ul>
            </div>
            <form id="rbacLainLainForm">
                <div class="border rounded-3 p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="me-auto" style="max-width: 36rem;">
                        <label class="form-label fw-semibold mb-1" for="publicExternalDocsToggle">Benarkan akses awam</label>
                        <p class="text-muted small mb-0">
                            Apabila dimatikan, hanya admin dan superadmin yang log masuk boleh membuka pautan pada modul di atas.
                            Pelawat akan diminta log masuk.
                        </p>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" role="switch" id="publicExternalDocsToggle"
                               <?= $publicExternalDocs ? 'checked' : '' ?>
                               style="width: 2.75rem; height: 1.4rem; cursor: pointer;">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan tetapan</button>
                    <div id="rbacLainLainStatus" class="small mt-2" aria-live="polite" hidden></div>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function () {
    var API = 'api/rbac.php';
    var PER_PAGE = 4;
    var TAB = <?= json_encode($tab, JSON_UNESCAPED_UNICODE) ?>;
    var units = <?= json_encode($unitsJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    var admins = <?= json_encode($adminsJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    var unitPage = 1;
    var adminPage = 1;
    var unitQuery = '';
    var adminQuery = '';
    var adminUnitFilter = 0;

    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function setStatus(el, msg, ok) {
        if (!el) return;
        el.textContent = msg || '';
        el.hidden = !msg;
        el.className = 'small mt-2 ' + (ok === false ? 'text-danger' : (ok ? 'text-success' : 'text-muted'));
    }

    function post(payload, statusEl, busyMsg) {
        setStatus(statusEl, busyMsg || 'Menyimpan…');
        payload = payload || {};
        payload.csrf_token = window.smks3Csrf || '';
        return fetch(API, {
            method: 'POST',
            credentials: 'same-origin',
            headers: window.smks3WithCsrf({ 'Content-Type': 'application/json', 'Accept': 'application/json' }),
            body: JSON.stringify(payload)
        }).then(function (r) {
            return r.json().then(function (j) { return { ok: r.ok, j: j }; });
        }).then(function (res) {
            if (!res.ok || !res.j || !res.j.ok) {
                throw new Error((res.j && res.j.error) || 'Gagal menyimpan.');
            }
            setStatus(statusEl, res.j.message || 'Berjaya.', true);
            return res.j;
        }).catch(function (err) {
            setStatus(statusEl, err.message || 'Ralat.', false);
            throw err;
        });
    }

    function paginate(items, page) {
        var total = items.length;
        var totalPages = Math.max(1, Math.ceil(total / PER_PAGE) || 1);
        page = Math.max(1, Math.min(page, totalPages));
        var start = (page - 1) * PER_PAGE;
        return {
            items: items.slice(start, start + PER_PAGE),
            page: page,
            total: total,
            total_pages: totalPages
        };
    }

    function renderPager(el, pagination, onPage) {
        if (!el) return;
        if (pagination.total_pages <= 1) {
            el.hidden = true;
            el.innerHTML = '';
            return;
        }
        el.hidden = false;
        var html = '<ul class="pagination pagination-sm justify-content-center mb-0 rbac-pagination">';
        html += '<li class="page-item' + (pagination.page <= 1 ? ' disabled' : '') + '"><a class="page-link" data-page="' + (pagination.page - 1) + '">Sebelumnya</a></li>';
        for (var i = 1; i <= pagination.total_pages; i++) {
            html += '<li class="page-item' + (i === pagination.page ? ' active' : '') + '"><a class="page-link" data-page="' + i + '">' + i + '</a></li>';
        }
        html += '<li class="page-item' + (pagination.page >= pagination.total_pages ? ' disabled' : '') + '"><a class="page-link" data-page="' + (pagination.page + 1) + '">Seterusnya</a></li>';
        html += '</ul>';
        el.innerHTML = html;
        el.querySelectorAll('a.page-link').forEach(function (a) {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                var li = a.closest('.page-item');
                if (li && li.classList.contains('disabled')) return;
                onPage(Number(a.getAttribute('data-page')) || 1);
            });
        });
    }

    function filteredUnits() {
        var q = unitQuery.trim().toLowerCase();
        if (!q) return units.slice();
        return units.filter(function (u) {
            return ((u.name || '') + ' ' + (u.description || '') + ' ' + (u.slug || '')).toLowerCase().indexOf(q) !== -1;
        });
    }

    function filteredAdmins() {
        var list = admins.slice();
        if (adminUnitFilter > 0) {
            list = list.filter(function (a) { return Number(a.unit_id) === adminUnitFilter; });
        }
        var q = adminQuery.trim().toLowerCase();
        if (q) {
            list = list.filter(function (a) {
                return ((a.username || '') + ' ' + (a.unit_name || '')).toLowerCase().indexOf(q) !== -1;
            });
        }
        return list;
    }

    function recountUnitAdmins() {
        units.forEach(function (u) {
            u.admin_count = admins.filter(function (a) { return Number(a.unit_id) === Number(u.id); }).length;
        });
    }

    function syncUnitSelects() {
        var createSel = document.getElementById('admin_unit');
        var filterSel = document.getElementById('rbacAdminUnitFilter');
        var createForm = document.getElementById('rbacAdminCreate');
        var createEmpty = document.getElementById('rbacAdminCreateEmpty');
        var opts = units.slice().sort(function (a, b) {
            return String(a.name).localeCompare(String(b.name));
        });
        if (createSel) {
            var prev = createSel.value;
            createSel.innerHTML = '<option value="">— Pilih unit —</option>' + opts.map(function (u) {
                return '<option value="' + u.id + '">' + esc(u.name) + '</option>';
            }).join('');
            if (prev) createSel.value = prev;
        }
        if (filterSel) {
            var prevF = filterSel.value;
            filterSel.innerHTML = '<option value="0">Semua unit</option>' + opts.map(function (u) {
                return '<option value="' + u.id + '">' + esc(u.name) + '</option>';
            }).join('');
            filterSel.value = prevF || '0';
        }
        if (createForm && createEmpty) {
            var has = units.length > 0;
            createForm.hidden = !has;
            createEmpty.hidden = has;
        }
    }

    function renderUnits() {
        var body = document.getElementById('rbacUnitsBody');
        var empty = document.getElementById('rbacUnitsEmpty');
        var wrap = document.getElementById('rbacUnitsTableWrap');
        var pager = document.getElementById('rbacUnitsPager');
        if (!body) return;
        var list = filteredUnits();
        var pageData = paginate(list, unitPage);
        unitPage = pageData.page;
        if (pageData.total === 0) {
            body.innerHTML = '';
            if (wrap) wrap.hidden = true;
            if (empty) {
                empty.hidden = false;
                empty.textContent = units.length === 0
                    ? 'Belum ada unit. Cipta unit dahulu sebelum daftar admin.'
                    : 'Tiada unit sepadan dengan carian.';
            }
            renderPager(pager, pageData, function () {});
            return;
        }
        if (wrap) wrap.hidden = false;
        if (empty) empty.hidden = true;
        body.innerHTML = pageData.items.map(function (u) {
            return '<tr>'
                + '<td><strong>' + esc(u.name) + '</strong>'
                + (u.description ? '<div class="small text-muted">' + esc(u.description) + '</div>' : '')
                + '</td>'
                + '<td>' + Number(u.admin_count || 0) + '</td>'
                + '<td>' + Number(u.permission_count || 0) + '</td>'
                + '<td class="text-end text-nowrap">'
                + '<a class="btn btn-sm btn-outline-primary" href="pengurusan-akses?tab=permissions&unit=' + u.id + '">Kebenaran</a> '
                + '<button type="button" class="btn btn-sm btn-outline-danger" data-unit-delete="' + u.id + '" data-unit-name="' + esc(u.name) + '">Padam</button>'
                + '</td></tr>';
        }).join('');
        renderPager(pager, pageData, function (p) { unitPage = p; renderUnits(); });
    }

    function unitOptionsHtml(selectedId) {
        return units.slice().sort(function (a, b) {
            return String(a.name).localeCompare(String(b.name));
        }).map(function (u) {
            return '<option value="' + u.id + '"' + (Number(u.id) === Number(selectedId) ? ' selected' : '') + '>' + esc(u.name) + '</option>';
        }).join('');
    }

    function renderAdmins() {
        var body = document.getElementById('rbacAdminsBody');
        var empty = document.getElementById('rbacAdminsEmpty');
        var wrap = document.getElementById('rbacAdminsTableWrap');
        var pager = document.getElementById('rbacAdminsPager');
        if (!body) return;
        var list = filteredAdmins();
        var pageData = paginate(list, adminPage);
        adminPage = pageData.page;
        if (pageData.total === 0) {
            body.innerHTML = '';
            if (wrap) wrap.hidden = true;
            if (empty) {
                empty.hidden = false;
                empty.textContent = admins.length === 0
                    ? 'Tiada admin lagi.'
                    : 'Tiada admin sepadan dengan carian / penapis.';
            }
            renderPager(pager, pageData, function () {});
            return;
        }
        if (wrap) wrap.hidden = false;
        if (empty) empty.hidden = true;
        body.innerHTML = pageData.items.map(function (a) {
            var active = Number(a.is_active) !== 0;
            return '<tr' + (active ? '' : ' class="table-secondary"') + '>'
                + '<td><input type="text" class="form-control form-control-sm" data-admin-username="' + a.id + '" value="' + esc(a.username) + '" autocomplete="off" style="min-width:8rem;"></td>'
                + '<td><select class="form-select form-select-sm" data-admin-unit="' + a.id + '" style="min-width:10rem;">'
                + unitOptionsHtml(a.unit_id)
                + '</select></td>'
                + '<td>'
                + '<span class="badge ' + (active ? 'text-bg-success' : 'text-bg-secondary') + '">'
                + (active ? 'Aktif' : 'Tidak aktif')
                + '</span>'
                + '</td>'
                + '<td class="text-end text-nowrap">'
                + '<button type="button" class="btn btn-sm ' + (active ? 'btn-outline-warning' : 'btn-outline-success') + '" data-admin-active="' + a.id + '" data-active="' + (active ? '0' : '1') + '">'
                + (active ? 'Nyahaktif' : 'Aktifkan')
                + '</button> '
                + '<button type="button" class="btn btn-sm btn-outline-primary" data-admin-save="' + a.id + '">Simpan</button> '
                + '<button type="button" class="btn btn-sm btn-outline-secondary" data-admin-pass="' + a.id + '">Reset kata laluan</button> '
                + '<button type="button" class="btn btn-sm btn-outline-danger" data-admin-delete="' + a.id + '" data-admin-name="' + esc(a.username) + '">Padam</button>'
                + '</td></tr>';
        }).join('');
        renderPager(pager, pageData, function (p) { adminPage = p; renderAdmins(); });
    }

    // Units tab
    if (TAB === 'units') {
        var unitFormStatus = document.getElementById('rbacUnitFormStatus');
        var unitListStatus = document.getElementById('rbacUnitListStatus');
        var unitSearch = document.getElementById('rbacUnitSearch');
        var unitTimer = null;
        if (unitSearch) {
            unitSearch.addEventListener('input', function () {
                if (unitTimer) clearTimeout(unitTimer);
                unitTimer = setTimeout(function () {
                    unitQuery = unitSearch.value || '';
                    unitPage = 1;
                    renderUnits();
                }, 200);
            });
        }
        var unitCreate = document.getElementById('rbacUnitCreate');
        if (unitCreate) {
            unitCreate.addEventListener('submit', function (e) {
                e.preventDefault();
                var fd = new FormData(unitCreate);
                post({ action: 'unit_create', name: fd.get('name'), description: fd.get('description') }, unitFormStatus)
                    .then(function (j) {
                        if (j.unit) {
                            units.push(j.unit);
                            units.sort(function (a, b) { return String(a.name).localeCompare(String(b.name)); });
                        }
                        unitCreate.reset();
                        renderUnits();
                    }).catch(function () {});
            });
        }
        document.getElementById('rbacUnitsBody').addEventListener('click', function (e) {
            var btn = e.target.closest('[data-unit-delete]');
            if (!btn) return;
            var id = Number(btn.getAttribute('data-unit-delete'));
            var name = btn.getAttribute('data-unit-name') || 'unit ini';
            if (!confirm('Padam unit “' + name + '”? Admin di bawahnya akan hilang pautan unit.')) return;
            post({ action: 'unit_delete', id: id }, unitListStatus).then(function () {
                units = units.filter(function (u) { return Number(u.id) !== id; });
                admins.forEach(function (a) {
                    if (Number(a.unit_id) === id) {
                        a.unit_id = null;
                        a.unit_name = '';
                    }
                });
                renderUnits();
            }).catch(function () {});
        });
        renderUnits();
    }

    // Admins tab
    if (TAB === 'admins') {
        var adminFormStatus = document.getElementById('rbacAdminFormStatus');
        var adminListStatus = document.getElementById('rbacAdminListStatus');
        var adminPw = document.getElementById('admin_password');
        var adminTogglePw = document.getElementById('rbacAdminTogglePw');
        var adminEye = document.getElementById('rbacAdminEye');
        if (adminTogglePw && adminPw && adminEye) {
            adminTogglePw.addEventListener('click', function () {
                var show = adminPw.type === 'password';
                adminPw.type = show ? 'text' : 'password';
                adminEye.classList.toggle('bi-eye', !show);
                adminEye.classList.toggle('bi-eye-slash', show);
                adminTogglePw.setAttribute('aria-label', show ? 'Sembunyi kata laluan' : 'Tunjuk kata laluan');
            });
        }
        syncUnitSelects();
        var adminSearch = document.getElementById('rbacAdminSearch');
        var adminFilter = document.getElementById('rbacAdminUnitFilter');
        var adminTimer = null;
        if (adminSearch) {
            adminSearch.addEventListener('input', function () {
                if (adminTimer) clearTimeout(adminTimer);
                adminTimer = setTimeout(function () {
                    adminQuery = adminSearch.value || '';
                    adminPage = 1;
                    renderAdmins();
                }, 200);
            });
        }
        if (adminFilter) {
            adminFilter.addEventListener('change', function () {
                adminUnitFilter = Number(adminFilter.value) || 0;
                adminPage = 1;
                renderAdmins();
            });
        }
        var adminCreate = document.getElementById('rbacAdminCreate');
        if (adminCreate) {
            adminCreate.addEventListener('submit', function (e) {
                e.preventDefault();
                var fd = new FormData(adminCreate);
                post({
                    action: 'admin_create',
                    username: fd.get('username'),
                    password: fd.get('password'),
                    unit_id: Number(fd.get('unit_id'))
                }, adminFormStatus).then(function (j) {
                    if (j.admin) {
                        admins.push(j.admin);
                        admins.sort(function (a, b) { return String(a.username).localeCompare(String(b.username)); });
                        recountUnitAdmins();
                    }
                    adminCreate.reset();
                    renderAdmins();
                }).catch(function () {});
            });
        }
        var adminsBody = document.getElementById('rbacAdminsBody');
        adminsBody.addEventListener('click', function (e) {
            var saveBtn = e.target.closest('[data-admin-save]');
            var passBtn = e.target.closest('[data-admin-pass]');
            var delBtn = e.target.closest('[data-admin-delete]');
            var activeBtn = e.target.closest('[data-admin-active]');
            if (activeBtn) {
                var idA = Number(activeBtn.getAttribute('data-admin-active'));
                var nextActive = Number(activeBtn.getAttribute('data-active')) === 1 ? 1 : 0;
                var label = nextActive ? 'aktifkan' : 'nyahaktifkan';
                if (!confirm('Sahkan ' + label + ' admin ini?')) return;
                post({ action: 'admin_set_active', id: idA, is_active: nextActive }, adminListStatus).then(function (j) {
                    admins.forEach(function (a) {
                        if (Number(a.id) === idA) {
                            a.is_active = j.admin && typeof j.admin.is_active !== 'undefined'
                                ? Number(j.admin.is_active)
                                : nextActive;
                        }
                    });
                    renderAdmins();
                }).catch(function () {});
                return;
            }
            if (saveBtn) {
                var id = Number(saveBtn.getAttribute('data-admin-save'));
                var sel = adminsBody.querySelector('[data-admin-unit="' + id + '"]');
                var nameInput = adminsBody.querySelector('[data-admin-username="' + id + '"]');
                if (!sel || !nameInput) return;
                var newUsername = String(nameInput.value || '').trim();
                if (newUsername.length < 3) {
                    setStatus(adminListStatus, 'Nama pengguna terlalu pendek.', false);
                    return;
                }
                post({
                    action: 'admin_update',
                    id: id,
                    username: newUsername,
                    unit_id: Number(sel.value)
                }, adminListStatus).then(function (j) {
                    admins.forEach(function (a) {
                        if (Number(a.id) === id && j.admin) {
                            a.username = j.admin.username || newUsername;
                            a.unit_id = j.admin.unit_id;
                            a.unit_name = j.admin.unit_name;
                        }
                    });
                    admins.sort(function (a, b) { return String(a.username).localeCompare(String(b.username)); });
                    recountUnitAdmins();
                    renderAdmins();
                }).catch(function () {});
            }
            if (passBtn) {
                var idP = Number(passBtn.getAttribute('data-admin-pass'));
                var selP = adminsBody.querySelector('[data-admin-unit="' + idP + '"]');
                var nameP = adminsBody.querySelector('[data-admin-username="' + idP + '"]');
                var pw = prompt('Kata laluan baharu (min 6 aksara):');
                if (pw == null) return;
                if (String(pw).length < 6) {
                    setStatus(adminListStatus, 'Kata laluan terlalu pendek.', false);
                    return;
                }
                post({
                    action: 'admin_update',
                    id: idP,
                    username: String(nameP && nameP.value || '').trim(),
                    unit_id: Number(selP && selP.value),
                    password: pw
                }, adminListStatus).catch(function () {});
            }
            if (delBtn) {
                var idD = Number(delBtn.getAttribute('data-admin-delete'));
                var nameD = delBtn.getAttribute('data-admin-name') || 'admin ini';
                if (!confirm('Padam admin “' + nameD + '”?')) return;
                post({ action: 'admin_delete', id: idD }, adminListStatus).then(function () {
                    admins = admins.filter(function (a) { return Number(a.id) !== idD; });
                    recountUnitAdmins();
                    renderAdmins();
                }).catch(function () {});
            }
        });
        renderAdmins();
    }

    // Permissions tab — load/save without full page reload
    var permUnit = document.getElementById('perm_unit');
    var permForm = document.getElementById('rbacPermissions');
    var permStatus = document.getElementById('rbacPermStatus');
    if (permForm) {
        function syncGroupToggle(group) {
            var toggle = group.querySelector('[data-perm-group-toggle]');
            var items = group.querySelectorAll('[data-perm-item]');
            if (!toggle || !items.length) return;
            var checked = 0;
            items.forEach(function (el) { if (el.checked) checked++; });
            toggle.checked = checked === items.length;
            toggle.indeterminate = checked > 0 && checked < items.length;
        }
        function applyPermissions(keys) {
            var set = {};
            (keys || []).forEach(function (k) { set[String(k)] = true; });
            permForm.querySelectorAll('[data-perm-item]').forEach(function (el) {
                el.checked = !!set[el.value];
            });
            permForm.querySelectorAll('[data-perm-group]').forEach(syncGroupToggle);
        }
        permForm.querySelectorAll('[data-perm-group]').forEach(function (group) {
            var toggle = group.querySelector('[data-perm-group-toggle]');
            if (toggle && toggle.getAttribute('data-indeterminate') === '1') {
                toggle.indeterminate = true;
            }
            if (toggle) {
                toggle.addEventListener('change', function () {
                    group.querySelectorAll('[data-perm-item]').forEach(function (el) {
                        el.checked = toggle.checked;
                    });
                    toggle.indeterminate = false;
                });
            }
            group.querySelectorAll('[data-perm-item]').forEach(function (el) {
                el.addEventListener('change', function () { syncGroupToggle(group); });
            });
        });
        if (permUnit) {
            permUnit.addEventListener('change', function () {
                var id = Number(permUnit.value) || 0;
                if (id < 1) return;
                var idInput = permForm.querySelector('[name="id"]');
                if (idInput) idInput.value = String(id);
                if (window.history && window.history.replaceState) {
                    window.history.replaceState(null, '', 'pengurusan-akses?tab=permissions&unit=' + encodeURIComponent(id));
                }
                post({ action: 'unit_get_permissions', id: id }, permStatus, 'Memuatkan kebenaran…').then(function (j) {
                    applyPermissions(j.permissions || []);
                    setStatus(permStatus, 'Kebenaran unit dimuatkan.', true);
                }).catch(function () {});
            });
        }
        permForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var id = Number((permForm.querySelector('[name="id"]') || {}).value || 0);
            var permissions = Array.prototype.map.call(
                permForm.querySelectorAll('[name="permissions[]"]:checked'),
                function (el) { return el.value; }
            );
            post({ action: 'unit_permissions', id: id, permissions: permissions }, permStatus).then(function (j) {
                units.forEach(function (u) {
                    if (Number(u.id) === id) {
                        u.permission_count = Number(j.permission_count || permissions.length);
                    }
                });
            }).catch(function () {});
        });
    }

    var lainForm = document.getElementById('rbacLainLainForm');
    var lainStatus = document.getElementById('rbacLainLainStatus');
    var lainToggle = document.getElementById('publicExternalDocsToggle');
    if (lainForm && lainToggle) {
        lainForm.addEventListener('submit', function (e) {
            e.preventDefault();
            post({
                action: 'site_setting_public_external_docs',
                enabled: lainToggle.checked ? 1 : 0
            }, lainStatus).catch(function () {});
        });
    }
})();
</script>
