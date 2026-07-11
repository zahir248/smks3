<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class RbacController extends Controller
{
    public function index(): void
    {
        if (!smks3_is_superadmin()) {
            http_response_code(403);
            $page_title = 'Akses ditolak';
            $this->render('pages/rbac-forbidden', get_defined_vars());
            return;
        }

        $pdo = getConnection();
        smks3_ensure_rbac_schema($pdo);

        $page_title = 'Pengurusan Akses (RBAC)';
        $page_lead = 'Cipta unit, daftar admin, tetapkan kebenaran, dan tetapan lain.';
        $current_page = 'pengurusan-akses';
        $units = smks3_rbac_list_units($pdo);
        $admins = smks3_rbac_list_admins($pdo);
        $catalog = smks3_rbac_permission_catalog();
        $tab = isset($_GET['tab']) ? (string) $_GET['tab'] : 'units';
        if (!in_array($tab, ['units', 'admins', 'permissions', 'lain-lain'], true)) {
            $tab = 'units';
        }

        $selectedUnitId = isset($_GET['unit']) ? (int) $_GET['unit'] : (int) ($units[0]['id'] ?? 0);
        $selectedPermissions = $selectedUnitId > 0 ? smks3_rbac_unit_permissions($pdo, $selectedUnitId) : [];
        $publicExternalDocs = smks3_public_external_docs_enabled();

        $this->render('pages/pengurusan-akses', get_defined_vars());
    }
}
