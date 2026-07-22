<?php
/** @var list<array<string,mixed>> $units */
/** @var list<array<string,mixed>> $admins */
/** @var array<string, array{label:string,group:string}> $catalog */
/** @var int $selectedAdminId */
/** @var list<string> $selectedPermissions */
/** @var string $tab */

$units = is_array($units ?? null) ? $units : [];
$admins = is_array($admins ?? null) ? $admins : [];
$catalog = is_array($catalog ?? null) ? $catalog : [];
$selectedAdminId = (int) ($selectedAdminId ?? 0);
$selectedPermissions = is_array($selectedPermissions ?? null) ? $selectedPermissions : [];
$tab = (string) ($tab ?? 'units');

$groups = [];
foreach ($catalog as $key => $meta) {
    $g = (string) ($meta['group'] ?? 'Lain');
    $groups[$g][$key] = $meta['label'];
}

$permLabelsJson = [];
foreach ($catalog as $key => $meta) {
    $permLabelsJson[$key] = [
        'label' => (string) ($meta['label'] ?? $key),
        'group' => (string) ($meta['group'] ?? 'Lain'),
    ];
}
if (function_exists('smks3_rbac_permission_aliases')) {
    foreach (smks3_rbac_permission_aliases() as $alias => $target) {
        if (isset($permLabelsJson[$target])) {
            $permLabelsJson[$alias] = $permLabelsJson[$target];
        }
    }
}

$unitsJson = [];
foreach ($units as $u) {
    $unitsJson[] = [
        'id' => (int) ($u['id'] ?? 0),
        'name' => (string) ($u['name'] ?? ''),
        'slug' => (string) ($u['slug'] ?? ''),
        'description' => (string) ($u['description'] ?? ''),
        'admin_count' => (int) ($u['admin_count'] ?? 0),
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
        'permission_count' => (int) ($a['permission_count'] ?? 0),
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
                    <a class="nav-link <?= $tab === 'permissions' ? 'active' : '' ?>" href="pengurusan-akses?tab=permissions<?= $selectedAdminId ? '&admin=' . $selectedAdminId : '' ?>">Kebenaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $tab === 'log' ? 'active' : '' ?>" href="pengurusan-akses?tab=log">Log</a>
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
/* Above fixed edit bar / navbar (z-index ~1080–1090) */
#rbacAdminHistoryModal {
    z-index: 12030 !important;
}
#rbacLogDetailModal {
    z-index: 12050 !important;
}
#rbacLogDetailModal .modal-content {
    border: 0;
    box-shadow: 0 1rem 2.5rem rgba(11, 60, 93, 0.2);
}
#rbacLogDetailModal .modal-header {
    background: #fff;
    border-bottom: 1px solid rgba(11, 60, 93, 0.12);
}
#rbacLogDetailModal .modal-body {
    background: #fff;
}
.modal-backdrop.rbac-log-backdrop {
    z-index: 12040 !important;
    opacity: 0.55 !important;
}
.modal-backdrop.rbac-history-backdrop {
    z-index: 12020 !important;
}
.rbac-log-panel {
    border: 1px solid rgba(11, 60, 93, 0.12);
    border-radius: 0.75rem;
    background: #f8fafc;
    padding: 0.85rem 1rem;
    max-height: 26rem;
    overflow: auto;
}
.rbac-log-panel h3 {
    font-size: 0.8rem;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #64748b;
    margin: 0 0 0.75rem;
}
.rbac-log-fields {
    display: grid;
    gap: 0.65rem;
}
.rbac-log-field {
    background: #fff;
    border: 1px solid rgba(11, 60, 93, 0.08);
    border-radius: 0.5rem;
    padding: 0.55rem 0.7rem;
}
.rbac-log-field.is-changed {
    border-color: rgba(180, 83, 9, 0.35);
    background: #fffbeb;
}
.rbac-log-field__label {
    display: block;
    font-size: 0.72rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.2rem;
}
.rbac-log-field__value {
    font-size: 0.9rem;
    color: #0f172a;
    white-space: pre-wrap;
    word-break: break-word;
    line-height: 1.4;
}
.rbac-log-field__value.is-empty {
    color: #94a3b8;
    font-style: italic;
}
.rbac-log-diff {
    display: grid;
    gap: 0.75rem;
}
.rbac-log-diff__hint {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0 0 0.25rem;
}
.rbac-log-change {
    border: 1px solid rgba(11, 60, 93, 0.12);
    border-radius: 0.75rem;
    background: #fff;
    padding: 0.85rem 1rem;
}
.rbac-log-change.is-changed {
    border-color: rgba(14, 116, 144, 0.28);
    background: linear-gradient(180deg, #f8fcff 0%, #fff 100%);
}
.rbac-log-change.is-same {
    opacity: 0.72;
}
.rbac-log-change__label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #0B3C5D;
    margin-bottom: 0.65rem;
}
.rbac-log-change__flow {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 0.65rem;
    align-items: stretch;
}
.rbac-log-side {
    border: 1px solid rgba(11, 60, 93, 0.1);
    border-radius: 0.6rem;
    padding: 0.55rem 0.7rem;
    min-width: 0;
    background: #f8fafc;
}
.rbac-log-side--old {
    background: #fff7ed;
    border-color: rgba(194, 65, 12, 0.18);
}
.rbac-log-side--new {
    background: #f0fdf4;
    border-color: rgba(22, 101, 52, 0.18);
}
.rbac-log-side__caption {
    display: block;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 0.3rem;
}
.rbac-log-side--old .rbac-log-side__caption { color: #c2410c; }
.rbac-log-side--new .rbac-log-side__caption { color: #15803d; }
.rbac-log-side__value {
    font-size: 0.9rem;
    color: #0f172a;
    white-space: pre-wrap;
    word-break: break-word;
    line-height: 1.4;
}
.rbac-log-side__value.is-empty {
    color: #94a3b8;
    font-style: italic;
}
.rbac-log-media {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    align-items: flex-start;
}
.rbac-log-thumb {
    display: block;
    max-width: 100%;
    width: auto;
    max-height: 7.5rem;
    border-radius: 0.45rem;
    border: 1px solid rgba(11, 60, 93, 0.15);
    background: #fff;
    object-fit: contain;
}
.rbac-log-thumb-link {
    display: inline-block;
    line-height: 0;
}
.rbac-log-file {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.65rem;
    border-radius: 0.5rem;
    border: 1px solid rgba(11, 60, 93, 0.15);
    background: #fff;
    color: #0B3C5D;
    text-decoration: none;
    font-size: 0.82rem;
    font-weight: 600;
    max-width: 100%;
}
.rbac-log-file:hover {
    background: #f1f5f9;
    color: #0B3C5D;
}
.rbac-log-file i {
    font-size: 1.1rem;
    flex: 0 0 auto;
}
.rbac-log-file span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.rbac-log-media-path {
    font-size: 0.72rem;
    color: #64748b;
    word-break: break-all;
}
.rbac-log-media-strip {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
}
.rbac-log-media-strip .rbac-log-thumb {
    max-height: 4.5rem;
}
.rbac-log-arrow {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.15rem;
    color: #0B3C5D;
    min-width: 2.5rem;
    padding-top: 0.9rem;
}
.rbac-log-arrow i {
    font-size: 1.25rem;
    line-height: 1;
}
.rbac-log-arrow span {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #64748b;
}
@media (max-width: 575.98px) {
    .rbac-log-change__flow {
        grid-template-columns: 1fr;
        gap: 0.4rem;
    }
    .rbac-log-arrow {
        flex-direction: row;
        padding: 0.15rem 0;
        min-width: 0;
    }
    .rbac-log-arrow i {
        transform: rotate(90deg);
    }
}
.rbac-log-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
}
.rbac-log-chip {
    display: inline-block;
    padding: 0.15rem 0.5rem;
    border-radius: 999px;
    background: #e2e8f0;
    font-size: 0.75rem;
    color: #334155;
}
.rbac-log-story {
    border: 1px solid rgba(11, 60, 93, 0.12);
    border-radius: 0.75rem;
    background: #f0f9ff;
    padding: 0.9rem 1rem;
    margin-bottom: 1rem;
}
.rbac-log-story__title {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #0B3C5D;
    margin: 0 0 0.65rem;
}
.rbac-log-story ol {
    margin: 0;
    padding-left: 1.2rem;
}
.rbac-log-story li {
    margin: 0.35rem 0;
    color: #0f172a;
    line-height: 1.45;
}
.rbac-log-story li::marker {
    color: #0B3C5D;
    font-weight: 700;
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
                            <input type="text" class="form-control" id="admin_username" name="username" required minlength="3" maxlength="100" autocomplete="off" placeholder="cth. Muhd Bakar">
                            <div class="form-text">3–100 aksara. Huruf, nombor, ruang, titik, _ atau - dibenarkan. Sensitif huruf besar/kecil (contoh: Ahmad Jais ≠ Ahmad jais).</div>
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
                                        <th>Kebenaran</th>
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

        <div class="modal fade" id="rbacAdminHistoryModal" tabindex="-1" aria-labelledby="rbacAdminHistoryTitle" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="rbacAdminHistoryTitle">Sejarah admin</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted mb-3" id="rbacAdminHistoryHint">Log aktiviti oleh atau berkaitan admin ini.</p>
                        <div id="rbacAdminHistoryStatus" class="small mb-2" aria-live="polite" hidden></div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Masa</th>
                                        <th>Pelaku</th>
                                        <th>Tindakan</th>
                                        <th>Butiran</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="rbacAdminHistoryBody">
                                    <tr><td colspan="5" class="text-muted text-center py-4">Memuatkan…</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <nav class="mt-3" id="rbacAdminHistoryPager" aria-label="Halaman sejarah admin" hidden></nav>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="rbacLogDetailModal" tabindex="-1" aria-labelledby="rbacLogDetailTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="rbacLogDetailTitle">Butiran log</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div id="rbacLogDetailMeta" class="small text-muted mb-3"></div>
                        <div id="rbacLogDetailBody"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($tab === 'permissions'): ?>
        <div class="border rounded-3 p-3 bg-white shadow-sm">
            <div class="d-flex flex-wrap gap-3 align-items-end mb-4">
                <div>
                    <label class="form-label" for="perm_admin">Admin</label>
                    <select class="form-select" id="perm_admin" style="min-width:16rem;">
                        <?php foreach ($admins as $a): ?>
                            <option value="<?= (int) $a['id'] ?>" <?= $selectedAdminId === (int) $a['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars((string) $a['username']) ?>
                                <?php if (!empty($a['unit_name'])): ?>
                                    — <?= htmlspecialchars((string) $a['unit_name']) ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="text-muted small mb-2 mb-md-0">Tandakan halaman / kawasan yang admin ini boleh sunting.</p>
            </div>

            <?php if ($admins === [] || $selectedAdminId < 1): ?>
                <p class="text-muted mb-0">Tiada admin untuk ditetapkan kebenaran. <a href="pengurusan-akses?tab=admins">Daftar admin</a> dahulu.</p>
            <?php else: ?>
            <form id="rbacPermissions">
                <input type="hidden" name="id" value="<?= $selectedAdminId ?>">
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

        <?php if ($tab === 'log'):
            $logActors = is_array($logActors ?? null) ? $logActors : [];
            $logPageCatalog = function_exists('smks3_rbac_permission_catalog') ? smks3_rbac_permission_catalog() : [];
            $logPageGroups = [];
            foreach ($logPageCatalog as $pk => $meta) {
                $g = (string) ($meta['group'] ?? 'Lain');
                if (!isset($logPageGroups[$g])) {
                    $logPageGroups[$g] = [];
                }
                $logPageGroups[$g][$pk] = (string) ($meta['label'] ?? $pk);
            }
        ?>
        <div class="border rounded-3 p-3 bg-white shadow-sm">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
                <div>
                    <h2 class="h5 mb-1">Log aktiviti</h2>
                    <p class="text-muted small mb-0">Jejak log masuk/keluar dan perubahan kandungan (sebelum &amp; selepas) untuk admin dan superadmin.</p>
                </div>
            </div>
            <form id="rbacLogFilters" class="row g-2 align-items-end mb-3">
                <div class="col-sm-6 col-md-3 col-lg-2">
                    <label class="form-label small mb-1" for="log_actor">Pengguna</label>
                    <select class="form-select form-select-sm" id="log_actor" name="actor_id">
                        <option value="0">Semua</option>
                        <?php
                        $meId = (int) ($_SESSION['user_id'] ?? 0);
                        $meName = (string) ($_SESSION['username'] ?? '');
                        foreach ($logActors as $la):
                            $laId = (int) ($la['id'] ?? 0);
                            $laName = (string) ($la['username'] ?? '');
                            $label = ($meId > 0 && $laId === $meId) || ($meName !== '' && $laName === $meName)
                                ? 'Anda'
                                : $laName;
                        ?>
                            <option value="<?= $laId ?>">
                                <?= htmlspecialchars($label) ?>
                                (<?= htmlspecialchars((string) ($la['role'] ?? '')) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-2">
                    <label class="form-label small mb-1" for="log_filter">Jenis</label>
                    <select class="form-select form-select-sm" id="log_filter" name="filter">
                        <option value="">Semua</option>
                        <option value="auth">Log masuk / keluar</option>
                        <option value="content">Kandungan</option>
                        <option value="rbac">Pengurusan akses</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="form-label small mb-1" for="log_page">Halaman (kebenaran)</label>
                    <select class="form-select form-select-sm" id="log_page" name="page_key">
                        <option value="">Semua halaman</option>
                        <?php foreach ($logPageGroups as $groupLabel => $pages): ?>
                            <optgroup label="<?= htmlspecialchars($groupLabel) ?>">
                                <?php foreach ($pages as $pk => $plabel): ?>
                                    <option value="<?= htmlspecialchars($pk) ?>"><?= htmlspecialchars($plabel) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-2">
                    <label class="form-label small mb-1" for="log_from">Dari</label>
                    <input type="date" class="form-control form-control-sm" id="log_from" name="from">
                </div>
                <div class="col-sm-6 col-md-3 col-lg-2">
                    <label class="form-label small mb-1" for="log_to">Hingga</label>
                    <input type="date" class="form-control form-control-sm" id="log_to" name="to">
                </div>
                <div class="col-sm-8 col-md-6 col-lg-3">
                    <label class="form-label small mb-1" for="log_q">Cari</label>
                    <input type="search" class="form-control form-control-sm" id="log_q" name="q" placeholder="Ringkasan, blok, pengguna…" autocomplete="off">
                </div>
                <div class="col-sm-4 col-md-3 col-lg-1">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100" id="rbacLogClear" title="Padam semua rekod log">Kosongkan</button>
                </div>
            </form>
            <div id="rbacLogStatus" class="small mb-2" aria-live="polite" hidden></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Masa</th>
                            <th>Pengguna</th>
                            <th>Tindakan</th>
                            <th>Butiran</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="rbacLogBody">
                        <tr><td colspan="5" class="text-muted text-center py-4">Memuatkan…</td></tr>
                    </tbody>
                </table>
            </div>
            <nav class="mt-3" id="rbacLogPager" aria-label="Halaman log" hidden></nav>
        </div>

        <div class="modal fade" id="rbacLogDetailModal" tabindex="-1" aria-labelledby="rbacLogDetailTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="rbacLogDetailTitle">Butiran log</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div id="rbacLogDetailMeta" class="small text-muted mb-3"></div>
                        <div id="rbacLogDetailBody"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($tab === 'lain-lain'):
            $publicExternalDocs = !empty($publicExternalDocs);
        ?>
        <div class="border rounded-3 p-3 p-md-4 bg-white shadow-sm mb-4">
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

        <div class="border rounded-3 p-3 p-md-4 bg-white shadow-sm">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <h2 class="h5 mb-1">Tong sampah media</h2>
                    <p class="text-muted small mb-0">
                        Fail imej / PDF yang dipadam dari laman disimpan di sini sebagai sandaran.
                        Muat turun untuk pulihkan, atau padam kekal satu demi satu / pukal.
                    </p>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" id="rbacTrashPurgeSelected" disabled>Padam dipilih</button>
            </div>
            <form id="rbacTrashFilters" class="row g-2 align-items-end mb-3">
                <div class="col-sm-6 col-md-3">
                    <label class="form-label small mb-1" for="trash_kind">Jenis</label>
                    <select class="form-select form-select-sm" id="trash_kind">
                        <option value="">Semua</option>
                        <option value="image">Imej</option>
                        <option value="pdf">PDF</option>
                        <option value="document">Dokumen</option>
                        <option value="archive">Arkib</option>
                        <option value="other">Lain-lain</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-5">
                    <label class="form-label small mb-1" for="trash_q">Cari</label>
                    <input type="search" class="form-control form-control-sm" id="trash_q" placeholder="Nama fail, laluan, pengguna…" autocomplete="off">
                </div>
            </form>
            <div id="rbacTrashStatus" class="small mb-2" aria-live="polite" hidden></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:2.25rem;">
                                <input type="checkbox" class="form-check-input m-0" id="trash_select_all" title="Pilih semua pada halaman ini" aria-label="Pilih semua">
                            </th>
                            <th>Fail</th>
                            <th>Jenis</th>
                            <th>Saiz</th>
                            <th>Dipadam</th>
                            <th>Oleh</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="rbacTrashBody">
                        <tr><td colspan="7" class="text-muted text-center py-4">Memuatkan…</td></tr>
                    </tbody>
                </table>
            </div>
            <nav class="mt-3" id="rbacTrashPager" aria-label="Halaman tong sampah" hidden></nav>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function () {
    var API = 'api/rbac.php';
    var PER_PAGE = 4;
    var TAB = <?= json_encode($tab, JSON_UNESCAPED_UNICODE) ?>;
    var CURRENT_USER_ID = <?= (int) ($_SESSION['user_id'] ?? 0) ?>;
    var CURRENT_USERNAME = <?= json_encode((string) ($_SESSION['username'] ?? ''), JSON_UNESCAPED_UNICODE) ?>;
    var units = <?= json_encode($unitsJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    var admins = <?= json_encode($adminsJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    var PERM_LABELS = <?= json_encode($permLabelsJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    var unitPage = 1;
    var adminPage = 1;
    var unitQuery = '';
    var adminQuery = '';
    var adminUnitFilter = 0;
    var openAdminHistory = null;

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
        var cur = Number(pagination.page) || 1;
        var totalPages = Number(pagination.total_pages) || 1;
        var pages = [];
        if (totalPages <= 9) {
            for (var i = 1; i <= totalPages; i++) pages.push(i);
        } else {
            var start = Math.max(2, cur - 2);
            var end = Math.min(totalPages - 1, cur + 2);
            pages.push(1);
            if (start > 2) pages.push('…');
            for (var p = start; p <= end; p++) pages.push(p);
            if (end < totalPages - 1) pages.push('…');
            pages.push(totalPages);
        }
        var html = '<ul class="pagination pagination-sm justify-content-center mb-0 rbac-pagination">';
        html += '<li class="page-item' + (cur <= 1 ? ' disabled' : '') + '"><a class="page-link" data-page="' + (cur - 1) + '">Sebelumnya</a></li>';
        pages.forEach(function (n) {
            if (n === '…') {
                html += '<li class="page-item disabled"><span class="page-link">…</span></li>';
                return;
            }
            html += '<li class="page-item' + (n === cur ? ' active' : '') + '"><a class="page-link" data-page="' + n + '">' + n + '</a></li>';
        });
        html += '<li class="page-item' + (cur >= totalPages ? ' disabled' : '') + '"><a class="page-link" data-page="' + (cur + 1) + '">Seterusnya</a></li>';
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
                + '<td class="text-end text-nowrap">'
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
                + '<td><a class="btn btn-sm btn-outline-primary" href="pengurusan-akses?tab=permissions&admin=' + a.id + '">'
                + Number(a.permission_count || 0) + ' kebenaran</a></td>'
                + '<td>'
                + '<span class="badge ' + (active ? 'text-bg-success' : 'text-bg-secondary') + '">'
                + (active ? 'Aktif' : 'Tidak aktif')
                + '</span>'
                + '</td>'
                + '<td class="text-end text-nowrap">'
                + '<button type="button" class="btn btn-sm btn-outline-secondary" data-admin-history="' + a.id + '" data-admin-name="' + esc(a.username) + '">Sejarah</button> '
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
            var histBtn = e.target.closest('[data-admin-history]');
            if (histBtn) {
                var idH = Number(histBtn.getAttribute('data-admin-history'));
                var nameH = histBtn.getAttribute('data-admin-name') || 'admin';
                if (typeof openAdminHistory === 'function') {
                    openAdminHistory(idH, nameH);
                } else {
                    setStatus(adminListStatus, 'Modul sejarah belum sedia. Muat semula halaman.', false);
                }
                return;
            }
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

    // Permissions tab — per admin
    var permAdmin = document.getElementById('perm_admin');
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
        if (permAdmin) {
            permAdmin.addEventListener('change', function () {
                var id = Number(permAdmin.value) || 0;
                if (id < 1) return;
                var idInput = permForm.querySelector('[name="id"]');
                if (idInput) idInput.value = String(id);
                if (window.history && window.history.replaceState) {
                    window.history.replaceState(null, '', 'pengurusan-akses?tab=permissions&admin=' + encodeURIComponent(id));
                }
                post({ action: 'admin_get_permissions', id: id }, permStatus, 'Memuatkan kebenaran…').then(function (j) {
                    applyPermissions(j.permissions || []);
                    setStatus(permStatus, 'Kebenaran admin dimuatkan.', true);
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
            post({ action: 'admin_permissions', id: id, permissions: permissions }, permStatus).then(function (j) {
                admins.forEach(function (a) {
                    if (Number(a.id) === id) {
                        a.permission_count = Number(j.permission_count || permissions.length);
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

    // Lain-lain: media trash / recycle bin
    var trashBody = document.getElementById('rbacTrashBody');
    var trashPager = document.getElementById('rbacTrashPager');
    var trashStatus = document.getElementById('rbacTrashStatus');
    var trashSelectAll = document.getElementById('trash_select_all');
    var trashPurgeSelected = document.getElementById('rbacTrashPurgeSelected');
    var trashPage = 1;
    if (trashBody) {
        var KIND_LABEL = {
            image: 'Imej',
            pdf: 'PDF',
            document: 'Dokumen',
            archive: 'Arkib',
            other: 'Lain-lain'
        };

        function selectedTrashIds() {
            return Array.prototype.map.call(
                trashBody.querySelectorAll('[data-trash-check]:checked'),
                function (el) { return Number(el.value); }
            ).filter(function (id) { return id > 0; });
        }

        function syncTrashBulkUi() {
            var ids = selectedTrashIds();
            var boxes = trashBody.querySelectorAll('[data-trash-check]');
            var checked = ids.length;
            if (trashPurgeSelected) {
                trashPurgeSelected.disabled = checked < 1;
                trashPurgeSelected.textContent = checked > 0
                    ? ('Padam dipilih (' + checked + ')')
                    : 'Padam dipilih';
            }
            if (trashSelectAll) {
                trashSelectAll.checked = boxes.length > 0 && checked === boxes.length;
                trashSelectAll.indeterminate = checked > 0 && checked < boxes.length;
            }
        }

        function loadTrash(page) {
            trashPage = page || 1;
            var kindEl = document.getElementById('trash_kind');
            var qEl = document.getElementById('trash_q');
            post({
                action: 'media_trash_list',
                page: trashPage,
                per_page: 20,
                kind: String(kindEl && kindEl.value || ''),
                q: String(qEl && qEl.value || '')
            }, trashStatus, 'Memuatkan tong sampah…').then(function (j) {
                var items = j.items || [];
                if (trashSelectAll) {
                    trashSelectAll.checked = false;
                    trashSelectAll.indeterminate = false;
                }
                if (!items.length) {
                    trashBody.innerHTML = '<tr><td colspan="7" class="text-muted text-center py-4">Tiada fail dalam tong sampah.</td></tr>';
                } else {
                    trashBody.innerHTML = items.map(function (row) {
                        var kind = KIND_LABEL[row.kind] || row.kind || '—';
                        var missing = row.exists === false
                            ? ' <span class="badge text-bg-warning">fail hilang</span>'
                            : '';
                        var pathHint = row.original_path
                            ? '<div class="small text-muted text-truncate" style="max-width:16rem;" title="' + esc(row.original_path) + '">' + esc(row.original_path) + '</div>'
                            : '';
                        var dl = row.download_url
                            ? '<a class="btn btn-sm btn-outline-primary" href="' + esc(row.download_url) + '">Muat turun</a> '
                            : '';
                        return '<tr>'
                            + '<td><input type="checkbox" class="form-check-input m-0" data-trash-check value="' + row.id + '" aria-label="Pilih ' + esc(row.file_name || 'fail') + '"></td>'
                            + '<td><div class="fw-semibold">' + esc(row.file_name || 'fail') + missing + '</div>' + pathHint + '</td>'
                            + '<td>' + esc(kind) + '</td>'
                            + '<td class="text-nowrap small">' + esc(row.file_size_label || '—') + '</td>'
                            + '<td class="text-nowrap small">' + esc(row.deleted_at || '—') + '</td>'
                            + '<td class="small">' + esc(row.deleted_by_username || '—') + '</td>'
                            + '<td class="text-end text-nowrap">'
                            + dl
                            + '<button type="button" class="btn btn-sm btn-outline-danger" data-trash-purge="' + row.id + '" data-trash-name="' + esc(row.file_name || '') + '">Padam kekal</button>'
                            + '</td></tr>';
                    }).join('');
                }
                syncTrashBulkUi();
                renderPager(trashPager, {
                    page: Number(j.page || 1),
                    total: Number(j.total || 0),
                    total_pages: Number(j.pages || 1)
                }, function (p) { loadTrash(p); });
                setStatus(
                    trashStatus,
                    'Jumlah: ' + Number(j.total || 0) + ' fail.',
                    true
                );
            }).catch(function () {
                trashBody.innerHTML = '<tr><td colspan="7" class="text-danger text-center py-4">Gagal memuatkan tong sampah.</td></tr>';
                syncTrashBulkUi();
            });
        }

        var trashTimer = null;
        function scheduleTrashReload() {
            if (trashTimer) clearTimeout(trashTimer);
            trashTimer = setTimeout(function () { loadTrash(1); }, 200);
        }
        var trashKind = document.getElementById('trash_kind');
        var trashQ = document.getElementById('trash_q');
        if (trashKind) trashKind.addEventListener('change', scheduleTrashReload);
        if (trashQ) trashQ.addEventListener('input', scheduleTrashReload);

        if (trashSelectAll) {
            trashSelectAll.addEventListener('change', function () {
                var on = trashSelectAll.checked;
                trashBody.querySelectorAll('[data-trash-check]').forEach(function (el) {
                    el.checked = on;
                });
                syncTrashBulkUi();
            });
        }

        trashBody.addEventListener('change', function (e) {
            if (e.target && e.target.matches('[data-trash-check]')) {
                syncTrashBulkUi();
            }
        });

        trashBody.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-trash-purge]');
            if (!btn) return;
            var id = Number(btn.getAttribute('data-trash-purge'));
            var name = btn.getAttribute('data-trash-name') || 'fail ini';
            if (id < 1) return;
            if (!confirm('Padam kekal “' + name + '”? Fail tidak boleh dipulihkan lagi.')) return;
            post({ action: 'media_trash_purge', id: id }, trashStatus, 'Memadam…').then(function () {
                loadTrash(trashPage);
            }).catch(function () {});
        });

        if (trashPurgeSelected) {
            trashPurgeSelected.addEventListener('click', function () {
                var ids = selectedTrashIds();
                if (!ids.length) return;
                if (!confirm('Padam kekal ' + ids.length + ' fail dipilih? Fail tidak boleh dipulihkan lagi.')) return;
                post({ action: 'media_trash_purge_bulk', ids: ids }, trashStatus, 'Memadam…').then(function () {
                    loadTrash(trashPage);
                }).catch(function () {});
            });
        }

        if (TAB === 'lain-lain') {
            loadTrash(1);
        }
    }

    // Activity log tab (+ shared detail/history helpers for Admin → Sejarah)
    var logBody = document.getElementById('rbacLogBody');
    var logPager = document.getElementById('rbacLogPager');
    var logStatus = document.getElementById('rbacLogStatus');
    var logFilters = document.getElementById('rbacLogFilters');
    var logPage = 1;
    var adminHistoryModalEl = document.getElementById('rbacAdminHistoryModal');
    var adminHistoryBody = document.getElementById('rbacAdminHistoryBody');
    var adminHistoryPager = document.getElementById('rbacAdminHistoryPager');
    var adminHistoryStatus = document.getElementById('rbacAdminHistoryStatus');
    var adminHistoryPage = 1;
    var adminHistoryUserId = 0;
    var adminHistoryName = '';
    if ((logBody && logFilters) || adminHistoryModalEl) {
        var FIELD_LABELS = {
            school_name: 'Nama sekolah',
            address: 'Alamat',
            phone: 'Telefon',
            email: 'E-mel',
            title: 'Tajuk',
            subtitle: 'Sari kata',
            href: 'Pautan',
            icon: 'Ikon',
            external: 'Pautan luar',
            content: 'Kandungan',
            excerpt: 'Petikan',
            value: 'Nilai',
            value_text: 'Nilai',
            html: 'HTML',
            key: 'Kunci',
            page_key: 'Halaman',
            username: 'Nama pengguna',
            role: 'Peranan',
            unit_id: 'ID unit',
            unit_name: 'Unit',
            is_active: 'Status aktif',
            password_changed: 'Kata laluan ditukar',
            permissions: 'Kebenaran',
            nama: 'Nama',
            name: 'Nama',
            jawatan: 'Jawatan',
            gred: 'Gred',
            dg: 'DG',
            kategori: 'Kategori',
            image: 'Imej',
            photo: 'Foto',
            gambar: 'Gambar',
            pdf_file: 'Fail PDF',
            file_pdf: 'Fail PDF',
            file: 'Fail',
            year: 'Tahun',
            start_year: 'Tahun mula',
            end_year: 'Tahun tamat',
            tarikh: 'Tarikh',
            tajuk: 'Tajuk',
            slug: 'Slug',
            description: 'Penerangan',
            moto: 'Moto',
            lirik: 'Lirik',
            lirik_penggubah: 'Penggubah',
            lirik_penulis: 'Penulis',
            public_external_docs: 'Akses awam Google Docs',
            user_id: 'ID pengguna',
            index: 'Indeks',
            item: 'Item',
            all: 'Senarai penuh',
            slides: 'Slaid',
            files: 'Fail',
            rows: 'Rekod',
            footer: 'Footer',
            reason: 'Sebab',
            via: 'Melalui',
            request: 'Data dihantar',
            _files: 'Fail dimuat naik',
            sort_order: 'Susunan',
            status: 'Status',
            published_at: 'Tarikh terbit',
            id: 'ID',
            count: 'Jumlah',
            image_count: 'Jumlah imej',
            pdf_count: 'Jumlah PDF'
        };
        var SKIP_KEYS = {
            password: 1, csrf_token: 1, _csrf: 1, block: 1,
            _truncated: 1, _note: 1, _snapshot_error: 1,
            /* Derived from media lists — already covered by imej/fail narration */
            count: 1, image_count: 1, pdf_count: 1
        };

        function actorLabel(username, userId) {
            var name = String(username || '');
            var id = Number(userId || 0);
            if ((CURRENT_USER_ID > 0 && id === CURRENT_USER_ID)
                || (CURRENT_USERNAME && name && name === CURRENT_USERNAME)) {
                return 'Anda';
            }
            return name || '—';
        }

        function fieldLabel(key) {
            var k = String(key || '');
            if (FIELD_LABELS[k]) return FIELD_LABELS[k];
            return k.replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
        }

        function isPlainObject(v) {
            return v && typeof v === 'object' && !Array.isArray(v);
        }

        function isBooleanishField(fieldKey) {
            var k = String(fieldKey || '').toLowerCase().replace(/\s+/g, ' ').trim();
            if (!k) return false;
            // Raw keys
            if (/^(is_|has_|can_)/.test(k)) return true;
            if (k === 'external' || k === 'enabled' || k === 'password_changed'
                || k === 'public_external_docs' || k === 'is_active') {
                return true;
            }
            // Flattened labels (fieldLabel paths)
            if (/(^|\/ )(status aktif|pautan luar|kata laluan ditukar|akses awam google docs)$/.test(k)) {
                return true;
            }
            return false;
        }

        function formatScalar(v, fieldKey) {
            if (v == null || v === '') return '';
            if (typeof v === 'boolean') return v ? 'Ya' : 'Tidak';
            // Only map 1/0 → Ya/Tidak for real boolean fields (not counts like jumlah imej)
            if (isBooleanishField(fieldKey) && (v === 1 || v === '1' || v === 0 || v === '0')) {
                return (v === 1 || v === '1') ? 'Ya' : 'Tidak';
            }
            if (typeof v === 'number') return String(v);
            return String(v);
        }

        function looksLikeImagePath(v) {
            var s = String(v == null ? '' : v).trim();
            if (!s || s.length > 400) return false;
            if (/^data:image\//i.test(s)) return true;
            return /\.(png|jpe?g|gif|webp|bmp|svg)(\?.*)?$/i.test(s);
        }

        function looksLikeFilePath(v) {
            var s = String(v == null ? '' : v).trim();
            if (!s || s.length > 400) return false;
            if (looksLikeImagePath(s)) return false;
            return /\.(pdf|doc|docx|xls|xlsx|ppt|pptx|zip|rar|txt|csv)(\?.*)?$/i.test(s)
                || /(^|\/)uploads\//i.test(s);
        }

        function mediaPublicUrl(v) {
            var s = String(v == null ? '' : v).trim().replace(/\\/g, '/');
            if (!s) return '';
            if (/^https?:\/\//i.test(s) || s.indexOf('//') === 0 || /^data:/i.test(s)) return s;
            if (s.charAt(0) === '/') return s.replace(/^\//, '');
            if (/^(uploads|images)\//i.test(s)) return s;
            // filename-only — prefer uploads/ as first guess; img fallback tries more paths
            if (looksLikeImagePath(s) || /\.pdf(\?.*)?$/i.test(s)) {
                return 'uploads/' + s.replace(/^\/+/, '');
            }
            return s;
        }

        function mediaCandidateUrls(path) {
            var s = String(path == null ? '' : path).trim().replace(/\\/g, '/');
            if (!s) return [];
            if (/^https?:\/\//i.test(s) || s.indexOf('//') === 0 || /^data:/i.test(s)) return [s];
            if (/^(uploads|images)\//i.test(s)) return [s];
            var name = s.replace(/^\/+/, '');
            return [
                'images/pelan-sekolah/' + name,
                'uploads/' + name,
                'uploads/pdf/' + name,
                'uploads/slideshow/' + name,
                'uploads/pra_sekolah/' + name,
                'uploads/peraturan/' + name,
                'uploads/pemimpin_murid/' + name,
                'uploads/enrolmen/' + name,
                'uploads/bil_kelas/' + name,
                'uploads/pengurusan/' + name,
                'uploads/pengetua/' + name,
                'uploads/guru/' + name,
                'uploads/akp/' + name,
                'uploads/ubk/' + name,
                'uploads/pibg/' + name,
                'uploads/kalendar/' + name,
                'uploads/cuti_perayaan/' + name,
                'uploads/pilihan_mata_pelajaran/' + name,
                'images/slideshow/' + name,
                'images/' + name,
                name
            ];
        }

        if (!window.smks3LogImgFallback) {
            window.smks3LogImgFallback = function (img) {
                if (!img) return;
                var raw = String(img.getAttribute('data-candidates') || '');
                var list = raw ? raw.split('|') : [];
                var i = Number(img.getAttribute('data-i') || 0) + 1;
                if (i < list.length && list[i]) {
                    img.setAttribute('data-i', String(i));
                    img.src = list[i];
                    return;
                }
                img.style.display = 'none';
            };
        }

        function fileBasename(v) {
            var s = String(v == null ? '' : v).trim().replace(/\\/g, '/');
            var parts = s.split('/');
            return parts[parts.length - 1] || s;
        }

        function parseMediaList(v) {
            return extractMediaPaths(v);
        }

        function mediaKey(path) {
            return fileBasename(path).toLowerCase();
        }

        function narrateMediaListChange(label, beforePaths, afterPaths) {
            var beforeList = (beforePaths || []).map(String);
            var afterList = (afterPaths || []).map(String);
            var beforeMap = {};
            beforeList.forEach(function (p) { beforeMap[mediaKey(p)] = p; });
            var afterMap = {};
            afterList.forEach(function (p) { afterMap[mediaKey(p)] = p; });

            var added = afterList.filter(function (p) { return !beforeMap[mediaKey(p)]; });
            var removed = beforeList.filter(function (p) { return !afterMap[mediaKey(p)]; });
            var sentences = [];
            var field = label || 'Imej';

            if (added.length === 1) {
                sentences.push(field + ' baharu ' + quoteText(fileBasename(added[0])) + ' telah ditambah.');
            } else if (added.length > 1) {
                sentences.push(added.length + ' ' + field.toLowerCase() + ' baharu telah ditambah: '
                    + added.map(function (p) { return quoteText(fileBasename(p)); }).join(', ') + '.');
            }

            if (removed.length === 1) {
                sentences.push(field + ' ' + quoteText(fileBasename(removed[0])) + ' telah dibuang.');
            } else if (removed.length > 1) {
                sentences.push(removed.length + ' ' + field.toLowerCase() + ' telah dibuang: '
                    + removed.map(function (p) { return quoteText(fileBasename(p)); }).join(', ') + '.');
            }

            if (!added.length && !removed.length && beforeList.length && afterList.length
                && beforeList.map(mediaKey).join('|') !== afterList.map(mediaKey).join('|')) {
                sentences.push('Susunan ' + field.toLowerCase() + ' telah diubah.');
            }

            if (!sentences.length && beforeList.length !== afterList.length) {
                sentences.push('Jumlah ' + field.toLowerCase() + ' berubah daripada '
                    + beforeList.length + ' kepada ' + afterList.length + '.');
            }

            if (!sentences.length && (added.length || removed.length || beforeList.length || afterList.length)) {
                sentences.push(field + ' dikemas kini.');
            }

            if (beforeList.length !== afterList.length && (added.length || removed.length)) {
                sentences.push('Jumlah kini ' + afterList.length + ' (sebelumnya ' + beforeList.length + ').');
            }

            return sentences;
        }

        function extractMediaPaths(v) {
            var out = [];
            var seen = {};
            function pushPath(p) {
                p = String(p == null ? '' : p).trim();
                if (!p) return;
                var k = mediaKey(p);
                if (seen[k]) return;
                seen[k] = true;
                out.push(p);
            }
            function walk(val) {
                if (val == null || val === '') return;
                if (typeof val === 'string') {
                    var t = val.trim();
                    if (!t) return;
                    if (t.charAt(0) === '[' || t.charAt(0) === '{') {
                        try {
                            walk(JSON.parse(t));
                            return;
                        } catch (e) { /* fall through */ }
                    }
                    // comma-separated filenames (rare)
                    if (t.indexOf(',') !== -1 && !/\s{2,}/.test(t) && /\.(png|jpe?g|gif|webp|pdf)/i.test(t) && t.indexOf(' ') === -1) {
                        t.split(',').forEach(function (part) { walk(part.trim()); });
                        return;
                    }
                    if (looksLikeImagePath(t) || looksLikeFilePath(t) || /^(uploads|images)\//i.test(t)) {
                        pushPath(t);
                    }
                    return;
                }
                if (Array.isArray(val)) {
                    val.forEach(walk);
                    return;
                }
                if (isPlainObject(val)) {
                    ['image', 'photo', 'gambar', 'src', 'file', 'file_pdf', 'pdf_file', 'pdf', 'path', 'url'].forEach(function (k) {
                        if (val[k] != null && val[k] !== '') walk(val[k]);
                    });
                    ['gambar_galeri', 'gambar_carta', 'images', 'files', 'slides'].forEach(function (k) {
                        if (val[k] != null) walk(val[k]);
                    });
                }
            }
            walk(v);
            return out;
        }

        function mediaPreviewHtml(v, opts) {
            opts = opts || {};
            var paths = extractMediaPaths(v);
            if (!paths.length) return '';

            function onePreview(path) {
                var cands = mediaCandidateUrls(path);
                var first = cands[0] || mediaPublicUrl(path);
                if (looksLikeImagePath(path) || /\.(png|jpe?g|gif|webp|bmp|svg)(\?.*)?$/i.test(path)) {
                    return '<div class="rbac-log-media">'
                        + '<a class="rbac-log-thumb-link" href="' + esc(first) + '" target="_blank" rel="noopener">'
                        + '<img class="rbac-log-thumb" src="' + esc(first) + '" alt="Pratonton" loading="lazy"'
                        + ' data-candidates="' + esc(cands.join('|')) + '" data-i="0"'
                        + ' onerror="window.smks3LogImgFallback && window.smks3LogImgFallback(this)">'
                        + '</a>'
                        + '<div class="rbac-log-media-path">' + esc(fileBasename(path)) + '</div>'
                        + '</div>';
                }
                var icon = /\.pdf(\?.*)?$/i.test(path) ? 'bi-file-earmark-pdf' : 'bi-file-earmark';
                return '<div class="rbac-log-media">'
                    + '<a class="rbac-log-file" href="' + esc(first) + '" target="_blank" rel="noopener">'
                    + '<i class="bi ' + icon + '" aria-hidden="true"></i>'
                    + '<span>' + esc(fileBasename(path)) + '</span>'
                    + '</a>'
                    + '</div>';
            }

            if (paths.length === 1) {
                return onePreview(paths[0]);
            }
            return '<div class="rbac-log-media-strip">' + paths.slice(0, 12).map(onePreview).join('') + '</div>';
        }

        function sideValueHtml(v) {
            if (v == null || v === '') {
                return '<span class="rbac-log-side__value is-empty">Tiada</span>';
            }
            var media = mediaPreviewHtml(v);
            if (media) {
                return media;
            }
            if (Array.isArray(v)) {
                if (!v.length) {
                    return '<span class="rbac-log-side__value is-empty">Tiada</span>';
                }
                // array of objects with images
                var mediaItems = v.map(function (item) { return mediaPreviewHtml(item); }).filter(Boolean);
                if (mediaItems.length) {
                    return '<div class="rbac-log-media-strip">' + mediaItems.join('') + '</div>';
                }
                if (v.every(function (x) { return typeof x === 'string' || typeof x === 'number'; })) {
                    return '<div class="rbac-log-chips">' + v.map(function (x) {
                        return '<span class="rbac-log-chip">' + esc(String(x)) + '</span>';
                    }).join('') + '</div>';
                }
            }
            return '<div class="rbac-log-side__value">' + esc(formatScalar(v)) + '</div>';
        }

        function flowCardHtml(label, oldV, newV, changed) {
            return '<div class="rbac-log-change ' + (changed ? 'is-changed' : 'is-same') + '">'
                + '<div class="rbac-log-change__label">' + esc(label) + '</div>'
                + '<div class="rbac-log-change__flow">'
                + '<div class="rbac-log-side rbac-log-side--old">'
                + '<span class="rbac-log-side__caption">Sebelum</span>'
                + sideValueHtml(oldV)
                + '</div>'
                + '<div class="rbac-log-arrow" aria-hidden="true">'
                + '<i class="bi bi-arrow-right"></i>'
                + '<span>jadi</span>'
                + '</div>'
                + '<div class="rbac-log-side rbac-log-side--new">'
                + '<span class="rbac-log-side__caption">Selepas</span>'
                + sideValueHtml(newV)
                + '</div>'
                + '</div>'
                + '</div>';
        }

        function renderMediaAliran(before, after, entityType) {
            var cards = [];
            var beforeList = asList(before);
            var afterList = asList(after);
            var noun = listNoun(entityType, '');

            if (beforeList || afterList) {
                beforeList = beforeList || [];
                afterList = afterList || [];
                var beforeIndex = {};
                beforeList.forEach(function (item, i) {
                    beforeIndex[itemKey(item, i)] = { item: item, index: i };
                });
                var afterIndex = {};
                afterList.forEach(function (item, i) {
                    afterIndex[itemKey(item, i)] = { item: item, index: i };
                });

                // Removals with preview
                beforeList.forEach(function (item, i) {
                    var key = itemKey(item, i);
                    if (afterIndex[key]) return;
                    if (!extractMediaPaths(item).length) return;
                    cards.push(flowCardHtml(
                        noun + ' dibuang · posisi ' + ordinalMs(i + 1) + ' · ' + itemLabel(item, noun.toLowerCase()),
                        item,
                        null,
                        true
                    ));
                });

                // Additions
                afterList.forEach(function (item, i) {
                    var key = itemKey(item, i);
                    if (beforeIndex[key]) return;
                    if (!extractMediaPaths(item).length) return;
                    cards.push(flowCardHtml(
                        noun + ' ditambah · posisi ' + ordinalMs(i + 1) + ' · ' + itemLabel(item, noun.toLowerCase()),
                        null,
                        item,
                        true
                    ));
                });

                // Moves / replacements for existing items that have media
                afterList.forEach(function (item, i) {
                    var key = itemKey(item, i);
                    var prev = beforeIndex[key];
                    if (!prev) return;
                    var oldItem = prev.item;
                    var moved = prev.index !== i;
                    var oldMedia = extractMediaPaths(oldItem).join('|');
                    var newMedia = extractMediaPaths(item).join('|');
                    var mediaChanged = oldMedia !== newMedia;
                    if (!moved && !mediaChanged) return;
                    if (!oldMedia && !newMedia) return;
                    var label = noun + ' ' + itemLabel(item, itemLabel(oldItem, noun.toLowerCase()));
                    if (moved) {
                        label += ' · posisi ' + ordinalMs(prev.index + 1) + ' → ' + ordinalMs(i + 1);
                    } else if (mediaChanged) {
                        label += ' · fail/imej dikemas kini';
                    }
                    cards.push(flowCardHtml(label, oldItem, item, true));
                });
            } else {
                // Single object: compare media-bearing fields
                var left = isPlainObject(before) ? before : {};
                var right = isPlainObject(after) ? after : {};
                var mediaKeys = {};
                Object.keys(left).concat(Object.keys(right)).forEach(function (k) {
                    var lk = String(k).toLowerCase();
                    if (/(image|photo|gambar|pdf|file|src|slides|files)/.test(lk)
                        || extractMediaPaths(left[k]).length
                        || extractMediaPaths(right[k]).length) {
                        mediaKeys[k] = true;
                    }
                });
                Object.keys(mediaKeys).forEach(function (k) {
                    var ov = left[k];
                    var nv = right[k];
                    if (sameValue(ov, nv) && !extractMediaPaths(ov).length && !extractMediaPaths(nv).length) return;
                    if (!extractMediaPaths(ov).length && !extractMediaPaths(nv).length) return;
                    cards.push(flowCardHtml(fieldLabel(k), ov, nv, !sameValue(ov, nv)));
                });
            }

            if (!cards.length) return '';
            return '<div class="mt-2 mb-3">'
                + '<p class="rbac-log-diff__hint mb-2">Aliran perubahan (imej / fail): <strong>Sebelum</strong> → <strong>Selepas</strong></p>'
                + '<div class="rbac-log-diff">' + cards.join('') + '</div>'
                + '</div>';
        }

        function valueHtml(v) {
            if (v == null || v === '') {
                return '<span class="rbac-log-field__value is-empty">Tiada</span>';
            }
            var media = mediaPreviewHtml(v);
            if (media) return media;
            if (Array.isArray(v)) {
                if (!v.length) {
                    return '<span class="rbac-log-field__value is-empty">Tiada</span>';
                }
                if (v.every(function (x) { return typeof x === 'string' || typeof x === 'number'; })) {
                    return '<div class="rbac-log-chips">' + v.map(function (x) {
                        return '<span class="rbac-log-chip">' + esc(String(x)) + '</span>';
                    }).join('') + '</div>';
                }
                return '<div class="rbac-log-field__value">' + esc(v.map(function (x) {
                    if (isPlainObject(x)) {
                        return Object.keys(x).slice(0, 4).map(function (k) {
                            return fieldLabel(k) + ': ' + formatScalar(x[k], k);
                        }).filter(Boolean).join(' · ');
                    }
                    return formatScalar(x);
                }).join('\n')) + '</div>';
            }
            if (isPlainObject(v)) {
                var keys = Object.keys(v).filter(function (k) { return !SKIP_KEYS[k]; });
                if (!keys.length) {
                    return '<span class="rbac-log-field__value is-empty">Tiada</span>';
                }
                return '<div class="rbac-log-fields">' + keys.map(function (k) {
                    return '<div class="rbac-log-field">'
                        + '<span class="rbac-log-field__label">' + esc(fieldLabel(k)) + '</span>'
                        + valueHtml(v[k])
                        + '</div>';
                }).join('') + '</div>';
            }
            var text = formatScalar(v);
            if (!text) {
                return '<span class="rbac-log-field__value is-empty">Tiada</span>';
            }
            return '<div class="rbac-log-field__value">' + esc(text) + '</div>';
        }

        function flattenData(data, prefix, out) {
            out = out || {};
            prefix = prefix || '';
            if (data == null) return out;
            if (!isPlainObject(data) && !Array.isArray(data)) {
                out[prefix || 'nilai'] = data;
                return out;
            }
            if (Array.isArray(data)) {
                // Keep short string arrays intact; summarize object arrays.
                if (data.every(function (x) { return typeof x === 'string' || typeof x === 'number'; })) {
                    out[prefix || 'senarai'] = data;
                    return out;
                }
                out[prefix || 'senarai'] = data.length + ' item';
                data.slice(0, 8).forEach(function (item, i) {
                    if (isPlainObject(item)) {
                        var label = item.title || item.nama || item.name || item.username || ('#' + (item.id != null ? item.id : i + 1));
                        out[(prefix ? prefix + ' / ' : '') + 'Item ' + (i + 1)] = label;
                    } else {
                        out[(prefix ? prefix + ' / ' : '') + 'Item ' + (i + 1)] = item;
                    }
                });
                if (data.length > 8) {
                    out[(prefix ? prefix + ' / ' : '') + '…'] = '+' + (data.length - 8) + ' lagi';
                }
                return out;
            }
            Object.keys(data).forEach(function (k) {
                if (SKIP_KEYS[k]) return;
                // Skip huge nested dumps that aren't useful
                if (k === 'all' || k === 'request') return;
                var path = prefix ? (prefix + ' / ' + fieldLabel(k)) : fieldLabel(k);
                var v = data[k];
                if (isPlainObject(v) || Array.isArray(v)) {
                    // One-level friendly: permissions / simple maps
                    if (k === 'permissions' && Array.isArray(v)) {
                        out[path] = v;
                        return;
                    }
                    if (k === 'item' && isPlainObject(v)) {
                        flattenData(v, path, out);
                        return;
                    }
                    if (Array.isArray(v) || (isPlainObject(v) && Object.keys(v).length > 6)) {
                        flattenData(v, path, out);
                        return;
                    }
                    flattenData(v, path, out);
                    return;
                }
                out[path] = v;
            });
            return out;
        }

        function sameValue(a, b) {
            if (a === b) return true;
            if (a == null && b == null) return true;
            if (Array.isArray(a) && Array.isArray(b)) {
                return JSON.stringify(a) === JSON.stringify(b);
            }
            return String(a ?? '') === String(b ?? '');
        }

        function ordinalMs(n) {
            var map = {
                1: 'pertama', 2: 'kedua', 3: 'ketiga', 4: 'keempat', 5: 'kelima',
                6: 'keenam', 7: 'ketujuh', 8: 'kelapan', 9: 'kesembilan', 10: 'kesepuluh'
            };
            n = Number(n) || 0;
            return map[n] || ('ke-' + n);
        }

        function quoteText(s) {
            s = String(s == null ? '' : s).trim().replace(/\s+/g, ' ');
            if (!s) return 'tanpa tajuk';
            if (s.length > 90) s = s.slice(0, 87) + '…';
            return '«' + s + '»';
        }

        function itemLabel(item, fallback) {
            if (!item || typeof item !== 'object') return fallback || 'item';
            return item.title || item.tajuk || item.alt || item.nama || item.name
                || item.username || item.label || item.school_name || fallback || 'item';
        }

        function itemKey(item, index) {
            if (!item || typeof item !== 'object') return 'i:' + index;
            return String(
                item.id || item.image || item.src || item.file || item.href
                || item.slug || item.username || ('i:' + index)
            );
        }

        function asList(data) {
            if (!data) return null;
            if (Array.isArray(data)) return data;
            if (Array.isArray(data.slides)) return data.slides;
            if (Array.isArray(data.files)) return data.files;
            if (Array.isArray(data.rows)) return data.rows;
            if (Array.isArray(data.all)) return data.all;
            if (Array.isArray(data.items)) return data.items;
            if (Array.isArray(data.permissions)) return data.permissions;
            return null;
        }

        function listNoun(entityType, action) {
            var map = {
                slideshow_gallery: 'Slaid',
                quick_link: 'Pautan pantas',
                quick_link_add: 'Pautan pantas',
                quick_link_delete: 'Pautan pantas',
                peraturan_gallery: 'Imej peraturan',
                pemimpin_gallery: 'Imej pemimpin',
                kalendar_pdf_gallery: 'Fail PDF kalendar',
                cuti_pdf_gallery: 'Fail PDF cuti',
                pilihan_pdf_gallery: 'Fail PDF pilihan',
                enrolmen_gallery: 'Imej enrolmen',
                bil_kelas_gallery: 'Imej bilangan kelas',
                news_item: 'Berita',
                news_add: 'Berita',
                news_delete: 'Berita',
                pengetua_item: 'Rekod pengetua',
                guru_item: 'Rekod guru',
                akp_item: 'Rekod AKP',
                pengurusan_item: 'Rekod pengurusan',
                profil_item: 'Item profil',
                lencana_item: 'Item lencana',
                kurikulum_card: 'Kad kurikulum'
            };
            if (map[entityType]) return map[entityType];
            if (String(action || '').indexOf('auth.') === 0) return 'Sesi';
            if (String(action || '').indexOf('rbac.') === 0) return 'Akses';
            return 'Item';
        }

        function narrateListChanges(beforeList, afterList, noun) {
            var sentences = [];
            beforeList = Array.isArray(beforeList) ? beforeList : [];
            afterList = Array.isArray(afterList) ? afterList : [];
            noun = noun || 'Item';

            var beforeIndex = {};
            beforeList.forEach(function (item, i) {
                beforeIndex[itemKey(item, i)] = { item: item, index: i };
            });
            var afterIndex = {};
            afterList.forEach(function (item, i) {
                afterIndex[itemKey(item, i)] = { item: item, index: i };
            });

            // Removals
            beforeList.forEach(function (item, i) {
                var key = itemKey(item, i);
                if (!afterIndex[key]) {
                    sentences.push(noun + ' ' + quoteText(itemLabel(item, noun.toLowerCase()))
                        + ' pada posisi ' + ordinalMs(i + 1) + ' telah dibuang.');
                }
            });

            // Additions
            afterList.forEach(function (item, i) {
                var key = itemKey(item, i);
                if (!beforeIndex[key]) {
                    sentences.push(noun + ' baharu ' + quoteText(itemLabel(item, noun.toLowerCase()))
                        + ' telah ditambah pada posisi ' + ordinalMs(i + 1) + '.');
                }
            });

            // Moves + field edits for items that still exist
            afterList.forEach(function (item, i) {
                var key = itemKey(item, i);
                var prev = beforeIndex[key];
                if (!prev) return;
                var oldItem = prev.item;
                var oldPos = prev.index + 1;
                var newPos = i + 1;
                var label = quoteText(itemLabel(item, itemLabel(oldItem, noun.toLowerCase())));

                if (oldPos !== newPos) {
                    sentences.push(noun + ' ' + label + ' telah dipindahkan dari posisi '
                        + ordinalMs(oldPos) + ' ke posisi ' + ordinalMs(newPos) + '.');
                }

                var fields = ['title', 'tajuk', 'alt', 'nama', 'name', 'subtitle', 'href', 'content', 'excerpt', 'jawatan', 'gred', 'dg'];
                fields.forEach(function (f) {
                    var ov = oldItem[f];
                    var nv = item[f];
                    if (ov == null && nv == null) return;
                    if (String(ov == null ? '' : ov) === String(nv == null ? '' : nv)) return;
                    var fl = fieldLabel(f).toLowerCase();
                    if ((ov == null || ov === '') && nv != null && nv !== '') {
                        sentences.push(fieldLabel(f) + ' untuk ' + noun.toLowerCase() + ' ' + label
                            + ' ditetapkan kepada ' + quoteText(nv) + '.');
                    } else if ((nv == null || nv === '') && ov != null && ov !== '') {
                        sentences.push(fieldLabel(f) + ' untuk ' + noun.toLowerCase() + ' ' + label
                            + ' dikosongkan (sebelumnya ' + quoteText(ov) + ').');
                    } else {
                        sentences.push(fieldLabel(f) + ' untuk ' + noun.toLowerCase() + ' ' + label
                            + ' ditukar daripada ' + quoteText(ov) + ' kepada ' + quoteText(nv) + '.');
                    }
                });
            });

            if (!sentences.length && beforeList.length === afterList.length && beforeList.length > 0) {
                sentences.push('Senarai ' + noun.toLowerCase() + ' dikemas kini, tetapi tiada perubahan jelas dikesan.');
            }
            return sentences;
        }

        function managedAdminName(log) {
            if (!log) return '';
            if (log.target_username) return String(log.target_username).trim();
            if (log.meta && log.meta.username) return String(log.meta.username).trim();
            if (log.summary) {
                var m = String(log.summary).match(/:\s*(.+)$/);
                if (m) return m[1].trim();
            }
            if (log.entity_type === 'user' && log.entity_id) {
                var uid = Number(log.entity_id);
                var found = admins.find(function (a) { return Number(a.id) === uid; });
                if (found && found.username) return String(found.username).trim();
            }
            return '';
        }

        function formatLogSummaryCell(row) {
            var action = String(row.action || '');
            var target = managedAdminName(row);
            if (action.indexOf('rbac.admin_') === 0 && target) {
                if (action === 'rbac.admin_permissions') {
                    return 'Untuk admin <strong>' + esc(target) + '</strong> · kebenaran sunting dikemaskini';
                }
                if (action === 'rbac.admin_create') {
                    return 'Admin baharu <strong>' + esc(target) + '</strong> didaftarkan';
                }
                if (action === 'rbac.admin_delete') {
                    return 'Admin <strong>' + esc(target) + '</strong> dipadam';
                }
                if (action === 'rbac.admin_set_active') {
                    return 'Status admin <strong>' + esc(target) + '</strong> dikemaskini';
                }
                if (action === 'rbac.admin_update') {
                    return 'Maklumat admin <strong>' + esc(target) + '</strong> dikemaskini';
                }
                return 'Untuk admin <strong>' + esc(target) + '</strong>';
            }
            if (action.indexOf('content.') === 0) {
                var pageLabel = logPageLabel(row);
                var summary = String(row.summary || '').trim();
                if (pageLabel && summary) {
                    return 'Halaman <strong>' + esc(pageLabel) + '</strong> · ' + esc(summary);
                }
                if (pageLabel) {
                    return 'Halaman <strong>' + esc(pageLabel) + '</strong>';
                }
            }
            return esc(row.summary || '');
        }

        function permissionLabel(key) {
            key = String(key == null ? '' : key).trim();
            if (!key) return 'Tidak diketahui';
            if (key === 'home') return 'Laman Utama';
            if (key === 'news') return 'Berita';
            if (key === 'footer') return 'Footer laman';
            if (key === 'contact') return 'Hubungi';
            var meta = PERM_LABELS[key];
            if (meta && meta.label) return meta.label;
            return key.replace(/-/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
        }

        function logPageKey(log) {
            if (log.page_key) return String(log.page_key).trim();
            var meta = log.meta || {};
            if (meta.page_key) return String(meta.page_key).trim();
            if (meta.request && meta.request.page_key) return String(meta.request.page_key).trim();
            if (meta.request && meta.request.key) return String(meta.request.key).trim();
            var route = String(log.route || '').trim();
            if (route && route !== 'save-content' && route !== 'save-content.php') {
                return route === 'index' ? 'home' : route;
            }
            return '';
        }

        function logPageLabel(log) {
            if (log.page_label) return String(log.page_label).trim();
            var key = logPageKey(log);
            return key ? permissionLabel(key) : '';
        }

        function permissionGroup(key) {
            var meta = PERM_LABELS[String(key == null ? '' : key)];
            return (meta && meta.group) ? meta.group : 'Lain';
        }

        /** Human-readable list of permission keys, grouped when many. */
        function formatPermissionList(keys) {
            keys = (Array.isArray(keys) ? keys : []).map(String).filter(Boolean);
            if (!keys.length) return '';
            if (keys.length >= 3) {
                var groups = {};
                keys.forEach(function (k) {
                    var g = permissionGroup(k);
                    if (!groups[g]) groups[g] = [];
                    groups[g].push(permissionLabel(k));
                });
                return Object.keys(groups).sort().map(function (g) {
                    return g + ' — ' + groups[g].join(', ');
                }).join('; ');
            }
            return keys.map(permissionLabel).join(', ');
        }

        function narratePermissions(before, after, log) {
            var b = Array.isArray(before) ? before.map(String) : [];
            var a = Array.isArray(after) ? after.map(String) : [];
            var bSet = {};
            var aSet = {};
            b.forEach(function (k) { bSet[k] = true; });
            a.forEach(function (k) { aSet[k] = true; });
            var added = a.filter(function (k) { return !bSet[k]; });
            var removed = b.filter(function (k) { return !aSet[k]; });
            var out = [];

            var adminName = managedAdminName(log);
            var adminRef = adminName ? quoteText(adminName) : 'admin';

            if (added.length || removed.length) {
                out.push('Superadmin mengemaskini kebenaran sunting halaman'
                    + (adminName ? '' : (' untuk admin ' + adminRef)) + '.');
            }
            if (added.length) {
                out.push('Halaman baharu boleh disunting (' + added.length + '): ' + formatPermissionList(added) + '.');
            }
            if (removed.length) {
                out.push('Halaman tidak lagi boleh disunting (' + removed.length + '): ' + formatPermissionList(removed) + '.');
            }
            if (!added.length && !removed.length) {
                out.push('Tiada perubahan kebenaran'
                    + (adminName ? '' : (' untuk admin ' + adminRef))
                    + ' — senarai yang sama disimpan semula.');
            }
            return out;
        }

        function narrateObjectChanges(before, after, contextLabel) {
            var left = flattenData(before || {});
            var right = flattenData(after || {});
            // Prefer raw media fields from objects (not flattened summaries)
            if (isPlainObject(before) || isPlainObject(after)) {
                var rawKeys = {};
                Object.keys(before || {}).forEach(function (k) { rawKeys[k] = true; });
                Object.keys(after || {}).forEach(function (k) { rawKeys[k] = true; });
                Object.keys(rawKeys).forEach(function (k) {
                    var lk = String(k).toLowerCase();
                    if (!/(image|photo|gambar|pdf|file|slides|files)/.test(lk)) return;
                    var oMedia = parseMediaList((before || {})[k]);
                    var nMedia = parseMediaList((after || {})[k]);
                    if (!oMedia.length && !nMedia.length) return;
                    // Remove flattened scalar noise for this field from left/right
                    Object.keys(left).forEach(function (fk) {
                        if (fk === fieldLabel(k) || fk.indexOf(fieldLabel(k)) === 0) delete left[fk];
                    });
                    Object.keys(right).forEach(function (fk) {
                        if (fk === fieldLabel(k) || fk.indexOf(fieldLabel(k)) === 0) delete right[fk];
                    });
                });
            }

            var keys = {};
            Object.keys(left).forEach(function (k) { keys[k] = true; });
            Object.keys(right).forEach(function (k) { keys[k] = true; });
            var sentences = [];
            var prefix = contextLabel ? (contextLabel + ': ') : '';

            // Dedicated media narration from raw before/after
            if (isPlainObject(before) || isPlainObject(after)) {
                var mediaFieldKeys = {};
                Object.keys(before || {}).concat(Object.keys(after || {})).forEach(function (k) {
                    var lk = String(k).toLowerCase();
                    if (/(image|photo|gambar|pdf|file|slides|files)/.test(lk)
                        || parseMediaList((before || {})[k]).length
                        || parseMediaList((after || {})[k]).length) {
                        mediaFieldKeys[k] = true;
                    }
                });
                Object.keys(mediaFieldKeys).forEach(function (k) {
                    var oMedia = parseMediaList((before || {})[k]);
                    var nMedia = parseMediaList((after || {})[k]);
                    if (!oMedia.length && !nMedia.length) return;
                    if (oMedia.map(mediaKey).join('|') === nMedia.map(mediaKey).join('|')) return;
                    sentences = sentences.concat(
                        narrateMediaListChange(prefix + fieldLabel(k), oMedia, nMedia)
                    );
                });
            }

            Object.keys(keys).forEach(function (k) {
                var ov = left[k];
                var nv = right[k];
                if (sameValue(ov, nv)) return;

                // Skip noisy aggregate / derived count keys already covered by list narrator
                if (/^Senarai|^Item |\/ Item /.test(k) || k === 'senarai' || k.indexOf('…') !== -1) return;
                if (/^(Count|Jumlah|Image Count|Pdf Count|Jumlah imej|Jumlah PDF)$/i.test(k)
                    || /(^|\/ )(Count|Jumlah|Image Count|Pdf Count|Jumlah imej|Jumlah PDF)$/i.test(k)) {
                    return;
                }

                var oMedia = parseMediaList(ov);
                var nMedia = parseMediaList(nv);
                // If either side is a media list / JSON gallery string, narrate as media add/remove
                if (oMedia.length || nMedia.length) {
                    if (oMedia.map(mediaKey).join('|') !== nMedia.map(mediaKey).join('|')) {
                        sentences = sentences.concat(narrateMediaListChange(prefix + k, oMedia, nMedia));
                    }
                    return;
                }

                var label = k;
                if (Array.isArray(ov) || Array.isArray(nv)) {
                    var oCount = Array.isArray(ov) ? ov.length : 0;
                    var nCount = Array.isArray(nv) ? nv.length : 0;
                    if (oCount !== nCount) {
                        sentences.push(prefix + label + ' berubah daripada ' + oCount + ' item kepada ' + nCount + ' item.');
                    }
                    return;
                }

                var oEmpty = ov == null || ov === '';
                var nEmpty = nv == null || nv === '';
                if (oEmpty && !nEmpty) {
                    sentences.push(prefix + label + ' ditetapkan kepada ' + quoteText(formatScalar(nv, k)) + '.');
                } else if (!oEmpty && nEmpty) {
                    sentences.push(prefix + label + ' dikosongkan (sebelumnya ' + quoteText(formatScalar(ov, k)) + ').');
                } else {
                    sentences.push(prefix + label + ' ditukar daripada ' + quoteText(formatScalar(ov, k))
                        + ' kepada ' + quoteText(formatScalar(nv, k)) + '.');
                }
            });

            // Deduplicate sentences
            var seen = {};
            return sentences.filter(function (s) {
                s = String(s || '').trim();
                if (!s || seen[s]) return false;
                seen[s] = true;
                return true;
            });
        }

        function buildChangeSentences(log) {
            var before = log.before;
            var after = log.after;
            var action = String(log.action || '');
            var entity = String(log.entity_type || '');
            var sentences = [];
            var noun = listNoun(entity, action);

            if (action.indexOf('auth.login') === 0) {
                sentences.push((actorLabel(log.actor_username, log.actor_user_id) === 'Anda' ? 'Anda' : quoteText(log.actor_username))
                    + ' telah log masuk ke sistem.');
                return sentences;
            }
            if (action.indexOf('auth.logout') === 0) {
                var why = (log.meta && log.meta.reason === 'idle')
                    ? ' kerana sesi tamat masa tidak aktif'
                    : ((log.meta && log.meta.reason === 'deactivated') ? ' kerana akaun dinyahaktif' : '');
                sentences.push((actorLabel(log.actor_username, log.actor_user_id) === 'Anda' ? 'Anda' : quoteText(log.actor_username))
                    + ' telah log keluar' + why + '.');
                return sentences;
            }

            // Permissions (admin page access)
            var isPermLog = action === 'rbac.admin_permissions';
            if (isPermLog) {
                var bp = (before && before.permissions) || [];
                var ap = (after && after.permissions) || [];
                sentences = sentences.concat(narratePermissions(bp, ap, log));
            }

            // List-like content (slideshow, galleries, quick links)
            var beforeList = isPermLog ? null : asList(before);
            var afterList = isPermLog ? null : asList(after);
            if (beforeList || afterList) {
                // Quick link single-item edits still include `all`
                if (entity.indexOf('quick_link') === 0 && before && before.item != null && after && after.item != null
                    && !Array.isArray(before.item) && !Array.isArray(after.item)) {
                    sentences = sentences.concat(narrateObjectChanges(before.item, after.item, 'Pautan pantas'));
                    if (before.index != null && after.index != null && Number(before.index) !== Number(after.index)) {
                        sentences.push('Pautan pantas ' + quoteText(itemLabel(after.item || before.item))
                            + ' dipindahkan dari posisi ' + ordinalMs(Number(before.index) + 1)
                            + ' ke posisi ' + ordinalMs(Number(after.index) + 1) + '.');
                    }
                } else {
                    sentences = sentences.concat(narrateListChanges(beforeList || [], afterList || [], noun));
                }
            } else if (before != null || after != null) {
                // Single object / settings / row entity
                if (action.indexOf('content.create') === 0 || (action.indexOf('content.') === 0 && before == null && after != null)) {
                    sentences.push(noun + ' baharu ' + quoteText(itemLabel(after, noun.toLowerCase())) + ' telah ditambah.');
                    sentences = sentences.concat(narrateObjectChanges({}, after, null).slice(0, 6));
                } else if (action.indexOf('content.delete') === 0 || (after == null && before != null && action.indexOf('content.') === 0)) {
                    sentences.push(noun + ' ' + quoteText(itemLabel(before, noun.toLowerCase())) + ' telah dipadam.');
                } else if (action === 'rbac.admin_set_active') {
                    var was = before && Number(before.is_active) === 1;
                    var now = after && Number(after.is_active) === 1;
                    var who = quoteText((after && after.username) || (before && before.username) || 'admin');
                    if (was !== now) {
                        sentences.push('Admin ' + who + (now ? ' telah diaktifkan.' : ' telah dinyahaktifkan.'));
                    }
                } else if (action === 'rbac.admin_update') {
                    var uname = quoteText((after && after.username) || (before && before.username) || 'admin');
                    if (before && after && before.username !== after.username) {
                        sentences.push('Nama pengguna admin ditukar daripada ' + quoteText(before.username)
                            + ' kepada ' + quoteText(after.username) + '.');
                    }
                    if (before && after && Number(before.unit_id) !== Number(after.unit_id)) {
                        sentences.push('Unit untuk admin ' + uname + ' ditukar'
                            + (after.unit_name ? (' kepada ' + quoteText(after.unit_name)) : '') + '.');
                    }
                    if (after && after.password_changed) {
                        sentences.push('Kata laluan untuk admin ' + uname + ' telah ditukar.');
                    }
                    if (!sentences.length) {
                        sentences.push('Maklumat admin ' + uname + ' dikemas kini.');
                    }
                } else if (action === 'rbac.admin_create') {
                    sentences.push('Admin baharu ' + quoteText(itemLabel(after, 'admin')) + ' telah didaftarkan'
                        + (after && after.unit_name ? (' dalam unit ' + quoteText(after.unit_name)) : '') + '.');
                } else if (action === 'rbac.admin_delete') {
                    sentences.push('Admin ' + quoteText(itemLabel(before, 'admin')) + ' telah dipadam.');
                } else if (action === 'rbac.unit_create') {
                    sentences.push('Unit baharu ' + quoteText(itemLabel(after, 'unit')) + ' telah dicipta.');
                } else if (action === 'rbac.unit_delete') {
                    sentences.push('Unit ' + quoteText(itemLabel(before, 'unit')) + ' telah dipadam.');
                } else if (action === 'rbac.site_setting') {
                    sentences = sentences.concat(narrateObjectChanges(before, after, 'Tetapan'));
                } else {
                    sentences = sentences.concat(narrateObjectChanges(before, after, null));
                }
            }

            // Deduplicate
            var seen = {};
            sentences = sentences.filter(function (s) {
                s = String(s || '').trim();
                if (!s || seen[s]) return false;
                seen[s] = true;
                return true;
            });

            if (!sentences.length && log.summary) {
                sentences.push(String(log.summary));
            }
            if (!sentences.length) {
                sentences.push('Terdapat aktiviti direkodkan, tetapi ringkasan terperinci tidak tersedia.');
            }
            return sentences;
        }

        function renderStory(sentences) {
            if (!sentences || !sentences.length) return '';
            return '<div class="rbac-log-story">'
                + '<p class="rbac-log-story__title">Ringkasan perubahan</p>'
                + '<ol>' + sentences.map(function (s) {
                    return '<li>' + esc(s) + '</li>';
                }).join('') + '</ol>'
                + '</div>';
        }

        function renderExtraNotes(log) {
            var bits = [];
            var meta = log.meta || {};
            var action = String(log.action || '');
            // Logout reason is already woven into the ringkasan sentence
            if (meta.reason && action.indexOf('auth.logout') !== 0) {
                var reasonMap = { idle: 'Tamat masa tidak aktif', manual: 'Manual', deactivated: 'Akaun dinyahaktif', session_clear: 'Sesi dikosongkan' };
                bits.push('<div class="small text-muted">Sebab: <strong>' + esc(reasonMap[meta.reason] || meta.reason) + '</strong></div>');
            }
            // Skip upload note when before/after media narration already covers the same files
            if (meta._files && isPlainObject(meta._files) && log.before == null && log.after == null) {
                Object.keys(meta._files).forEach(function (field) {
                    var info = meta._files[field];
                    if (info && info.names && info.names.length) {
                        bits.push('<div class="small mt-1">Fail dimuat naik: <strong>' + esc(info.names.join(', ')) + '</strong></div>');
                    } else if (info && info.name) {
                        bits.push('<div class="small mt-1">Fail dimuat naik: <strong>' + esc(info.name) + '</strong></div>');
                    }
                });
            }
            return bits.length ? '<div class="mt-3">' + bits.join('') + '</div>' : '';
        }

        function fillLogDetail(log) {
            var metaEl = document.getElementById('rbacLogDetailMeta');
            var bodyEl = document.getElementById('rbacLogDetailBody');
            var titleEl = document.getElementById('rbacLogDetailTitle');
            var targetAdmin = managedAdminName(log);
            var action = String(log.action || '');
            var adminInTitle = false;
            if (titleEl) {
                if (action === 'rbac.admin_permissions' && targetAdmin) {
                    titleEl.textContent = 'Kebenaran admin — ' + targetAdmin;
                    adminInTitle = true;
                } else if (action.indexOf('rbac.admin_') === 0 && targetAdmin) {
                    titleEl.textContent = (log.action_label || 'Admin') + ' — ' + targetAdmin;
                    adminInTitle = true;
                } else {
                    titleEl.textContent = log.action_label || log.action || 'Butiran log';
                }
            }
            if (metaEl) {
                var pageLabel = logPageLabel(log);
                metaEl.innerHTML = '<div><strong>' + esc(log.occurred_at || '') + '</strong></div>'
                    + '<div>Dilakukan oleh: ' + esc(actorLabel(log.actor_username, log.actor_user_id))
                    + (log.actor_role ? ' · ' + esc(log.actor_role) : '')
                    + (log.ip ? ' · IP ' + esc(log.ip) : '')
                    + '</div>'
                    + (pageLabel
                        ? ('<div>Halaman: <strong>' + esc(pageLabel) + '</strong></div>')
                        : '')
                    + (targetAdmin && !adminInTitle
                        ? ('<div>Untuk admin: <strong>' + esc(targetAdmin) + '</strong></div>')
                        : '');
            }
            if (!bodyEl) return;

            var sentences = buildChangeSentences(log);
            var html = renderStory(sentences);

            if (log.before != null || log.after != null) {
                html += renderMediaAliran(log.before, log.after, log.entity_type || '');
            }
            html += renderExtraNotes(log);
            bodyEl.innerHTML = html;
        }

        function showLogModal(options) {
            options = options || {};
            var parentEl = options.parentEl || null;
            var modalEl = document.getElementById('rbacLogDetailModal');
            if (!modalEl || !window.bootstrap) return;
            if (modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }

            function markDetailBackdrop() {
                document.querySelectorAll('.modal-backdrop').forEach(function (b) {
                    if (!b.classList.contains('rbac-history-backdrop')) {
                        b.classList.add('rbac-log-backdrop');
                    }
                });
            }

            function openDetail() {
                var instance = bootstrap.Modal.getOrCreateInstance(modalEl, { backdrop: true, focus: true });
                modalEl.addEventListener('shown.bs.modal', markDetailBackdrop, { once: true });
                modalEl.addEventListener('hidden.bs.modal', function () {
                    // Remove leftover detail backdrops
                    document.querySelectorAll('.modal-backdrop.rbac-log-backdrop').forEach(function (b) {
                        b.remove();
                    });
                    if (parentEl && window.bootstrap) {
                        if (parentEl.parentElement !== document.body) {
                            document.body.appendChild(parentEl);
                        }
                        var parentInst = bootstrap.Modal.getOrCreateInstance(parentEl, { backdrop: true, focus: true });
                        parentEl.addEventListener('shown.bs.modal', function () {
                            document.querySelectorAll('.modal-backdrop').forEach(function (b) {
                                if (!b.classList.contains('rbac-log-backdrop')) {
                                    b.classList.add('rbac-history-backdrop');
                                }
                            });
                            parentEl.removeAttribute('aria-hidden');
                            parentEl.removeAttribute('inert');
                        }, { once: true });
                        parentInst.show();
                    }
                }, { once: true });
                instance.show();
            }

            // Avoid nested modals: hide Sejarah first, then open Lihat; restore Sejarah on close
            if (parentEl && parentEl.classList.contains('show')) {
                var parentInst = bootstrap.Modal.getInstance(parentEl)
                    || bootstrap.Modal.getOrCreateInstance(parentEl, { backdrop: true, focus: true });
                parentEl.addEventListener('hidden.bs.modal', function () {
                    openDetail();
                }, { once: true });
                parentInst.hide();
                return;
            }

            openDetail();
        }

        function loadLogs(page) {
            if (!logBody) return;
            logPage = page || 1;
            var actorEl = document.getElementById('log_actor');
            var filterEl = document.getElementById('log_filter');
            var pageEl = document.getElementById('log_page');
            var fromEl = document.getElementById('log_from');
            var toEl = document.getElementById('log_to');
            var qEl = document.getElementById('log_q');
            post({
                action: 'activity_log_list',
                page: logPage,
                per_page: 50,
                actor_id: Number(actorEl && actorEl.value || 0),
                filter: String(filterEl && filterEl.value || ''),
                page_key: String(pageEl && pageEl.value || ''),
                from: String(fromEl && fromEl.value || ''),
                to: String(toEl && toEl.value || ''),
                q: String(qEl && qEl.value || '')
            }, logStatus, 'Memuatkan log…').then(function (j) {
                var items = j.items || [];
                if (!items.length) {
                    logBody.innerHTML = '<tr><td colspan="5" class="text-muted text-center py-4">Tiada rekod log.</td></tr>';
                } else {
                    logBody.innerHTML = items.map(function (row) {
                        var actor = esc(actorLabel(row.actor_username, row.actor_user_id));
                        if (row.actor_role) actor += ' <span class="text-muted">(' + esc(row.actor_role) + ')</span>';
                        var detail = formatLogSummaryCell(row);
                        if (row.entity_type && String(row.action || '').indexOf('rbac.admin_') !== 0) {
                            detail += (detail ? ' · ' : '') + '<span class="text-muted">' + esc(row.entity_type)
                                + (row.entity_id ? ' #' + esc(row.entity_id) : '') + '</span>';
                        }
                        var badge = '';
                        if (row.has_before || row.has_after) {
                            badge = ' <span class="badge text-bg-light border">perubahan</span>';
                        }
                        return '<tr>'
                            + '<td class="text-nowrap small">' + esc(row.occurred_at) + '</td>'
                            + '<td>' + actor + '</td>'
                            + '<td>' + esc(row.action_label || row.action) + badge + '</td>'
                            + '<td class="small">' + detail + '</td>'
                            + '<td class="text-end"><button type="button" class="btn btn-sm btn-outline-primary" data-log-id="' + row.id + '">Lihat</button></td>'
                            + '</tr>';
                    }).join('');
                }
                renderPager(logPager, {
                    page: Number(j.page || 1),
                    total: Number(j.total || 0),
                    total_pages: Number(j.pages || 1)
                }, function (p) { loadLogs(p); });
                var fromN = items.length ? ((Number(j.page || 1) - 1) * Number(j.per_page || 50) + 1) : 0;
                var toN = items.length ? (fromN + items.length - 1) : 0;
                setStatus(
                    logStatus,
                    items.length
                        ? ('Menunjukkan ' + fromN + '–' + toN + ' daripada ' + Number(j.total || 0) + ' rekod.')
                        : ('Jumlah: ' + Number(j.total || 0) + ' rekod.'),
                    true
                );
            }).catch(function () {
                logBody.innerHTML = '<tr><td colspan="5" class="text-danger text-center py-4">Gagal memuatkan log.</td></tr>';
            });
        }

        function loadAdminHistory(page) {
            if (!adminHistoryBody || adminHistoryUserId < 1) return;
            adminHistoryPage = page || 1;
            post({
                action: 'activity_log_list',
                page: adminHistoryPage,
                per_page: 50,
                related_user_id: adminHistoryUserId
            }, adminHistoryStatus, 'Memuatkan sejarah…').then(function (j) {
                var items = j.items || [];
                if (!items.length) {
                    adminHistoryBody.innerHTML = '<tr><td colspan="5" class="text-muted text-center py-4">Tiada rekod sejarah untuk admin ini.</td></tr>';
                } else {
                    adminHistoryBody.innerHTML = items.map(function (row) {
                        var actor = esc(actorLabel(row.actor_username, row.actor_user_id));
                        if (row.actor_role) actor += ' <span class="text-muted">(' + esc(row.actor_role) + ')</span>';
                        var detail = formatLogSummaryCell(row);
                        if (row.entity_type && String(row.action || '').indexOf('rbac.admin_') !== 0) {
                            detail += (detail ? ' · ' : '') + '<span class="text-muted">' + esc(row.entity_type)
                                + (row.entity_id ? ' #' + esc(row.entity_id) : '') + '</span>';
                        }
                        var badge = '';
                        if (row.has_before || row.has_after) {
                            badge = ' <span class="badge text-bg-light border">perubahan</span>';
                        }
                        return '<tr>'
                            + '<td class="text-nowrap small">' + esc(row.occurred_at) + '</td>'
                            + '<td>' + actor + '</td>'
                            + '<td>' + esc(row.action_label || row.action) + badge + '</td>'
                            + '<td class="small">' + detail + '</td>'
                            + '<td class="text-end"><button type="button" class="btn btn-sm btn-outline-primary" data-admin-log-id="' + row.id + '">Lihat</button></td>'
                            + '</tr>';
                    }).join('');
                }
                renderPager(adminHistoryPager, {
                    page: Number(j.page || 1),
                    total: Number(j.total || 0),
                    total_pages: Number(j.pages || 1)
                }, function (p) { loadAdminHistory(p); });
                var fromN = items.length ? ((Number(j.page || 1) - 1) * Number(j.per_page || 50) + 1) : 0;
                var toN = items.length ? (fromN + items.length - 1) : 0;
                setStatus(
                    adminHistoryStatus,
                    items.length
                        ? ('Menunjukkan ' + fromN + '–' + toN + ' daripada ' + Number(j.total || 0) + ' rekod.')
                        : ('Jumlah: ' + Number(j.total || 0) + ' rekod.'),
                    true
                );
            }).catch(function () {
                adminHistoryBody.innerHTML = '<tr><td colspan="5" class="text-danger text-center py-4">Gagal memuatkan sejarah.</td></tr>';
            });
        }

        openAdminHistory = function (userId, username) {
            adminHistoryUserId = Number(userId) || 0;
            adminHistoryName = String(username || 'admin');
            if (adminHistoryUserId < 1 || !adminHistoryModalEl || !window.bootstrap) return;
            var titleEl = document.getElementById('rbacAdminHistoryTitle');
            if (titleEl) titleEl.textContent = 'Sejarah · ' + adminHistoryName;
            var hintEl = document.getElementById('rbacAdminHistoryHint');
            if (hintEl) {
                hintEl.textContent = 'Log aktiviti yang dilakukan oleh ' + adminHistoryName
                    + ', serta perubahan akaun berkaitan admin ini.';
            }
            if (adminHistoryBody) {
                adminHistoryBody.innerHTML = '<tr><td colspan="5" class="text-muted text-center py-4">Memuatkan…</td></tr>';
            }
            if (adminHistoryModalEl.parentElement !== document.body) {
                document.body.appendChild(adminHistoryModalEl);
            }
            loadAdminHistory(1);
            adminHistoryModalEl.addEventListener('shown.bs.modal', function () {
                document.querySelectorAll('.modal-backdrop').forEach(function (b) {
                    if (!b.classList.contains('rbac-log-backdrop')) {
                        b.classList.add('rbac-history-backdrop');
                    }
                });
            }, { once: true });
            bootstrap.Modal.getOrCreateInstance(adminHistoryModalEl, { backdrop: true, focus: true }).show();
        };

        if (adminHistoryBody) {
            adminHistoryBody.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-admin-log-id]');
                if (!btn) return;
                var id = Number(btn.getAttribute('data-admin-log-id'));
                if (id < 1) return;
                post({ action: 'activity_log_get', id: id }, adminHistoryStatus, 'Memuatkan butiran…').then(function (j) {
                    fillLogDetail(j.log || {});
                    showLogModal({ parentEl: adminHistoryModalEl });
                }).catch(function () {});
            });
        }

        if (logFilters) {
        var logFilterTimer = null;
        function scheduleLogReload() {
            if (logFilterTimer) clearTimeout(logFilterTimer);
            logFilterTimer = setTimeout(function () { loadLogs(1); }, 200);
        }

        logFilters.addEventListener('submit', function (e) {
            e.preventDefault();
        });
        ['log_actor', 'log_filter', 'log_page', 'log_from', 'log_to'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('change', scheduleLogReload);
        });
        var logQ = document.getElementById('log_q');
        if (logQ) {
            logQ.addEventListener('input', scheduleLogReload);
        }

        var logClearBtn = document.getElementById('rbacLogClear');
        if (logClearBtn) {
            logClearBtn.addEventListener('click', function () {
                if (!confirm('Kosongkan semua rekod log aktiviti? Tindakan ini tidak boleh diundur.')) {
                    return;
                }
                post({ action: 'activity_log_clear' }, logStatus, 'Mengosongkan log…').then(function () {
                    loadLogs(1);
                }).catch(function () {});
            });
        }
        }

        if (logBody) {
        logBody.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-log-id]');
            if (!btn) return;
            var id = Number(btn.getAttribute('data-log-id'));
            if (id < 1) return;
            post({ action: 'activity_log_get', id: id }, logStatus, 'Memuatkan butiran…').then(function (j) {
                fillLogDetail(j.log || {});
                showLogModal();
            }).catch(function () {});
        });
        }
        if (TAB === 'log' && logBody) {
            loadLogs(1);
        }
    }
})();
</script>
