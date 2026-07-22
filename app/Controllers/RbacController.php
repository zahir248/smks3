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
        smks3_ensure_activity_log_schema($pdo);

        $page_title = 'Pengurusan Akses (RBAC)';
        $page_lead = 'Cipta unit, daftar admin, tetapkan kebenaran, lihat log aktiviti, dan tetapan lain.';
        $current_page = 'pengurusan-akses';
        $meta_robots = 'noindex, nofollow';
        $units = smks3_rbac_list_units($pdo);
        $admins = smks3_rbac_list_admins($pdo);
        $catalog = smks3_rbac_permission_catalog();
        $tab = isset($_GET['tab']) ? (string) $_GET['tab'] : 'units';
        if (!in_array($tab, ['units', 'admins', 'permissions', 'log', 'lain-lain'], true)) {
            $tab = 'units';
        }

        $selectedAdminId = isset($_GET['admin']) ? (int) $_GET['admin'] : (int) ($admins[0]['id'] ?? 0);
        if ($selectedAdminId > 0) {
            $found = false;
            foreach ($admins as $a) {
                if ((int) ($a['id'] ?? 0) === $selectedAdminId) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $selectedAdminId = (int) ($admins[0]['id'] ?? 0);
            }
        }
        $selectedPermissions = $selectedAdminId > 0 ? smks3_rbac_admin_permissions($pdo, $selectedAdminId) : [];
        $publicExternalDocs = smks3_public_external_docs_enabled();

        $logActors = [];
        try {
            $actorStmt = $pdo->query(
                "SELECT id, username, role FROM users WHERE role IN ('admin','superadmin') ORDER BY role DESC, username ASC"
            );
            $logActors = $actorStmt ? ($actorStmt->fetchAll(\PDO::FETCH_ASSOC) ?: []) : [];
        } catch (\Throwable $e) {
            $logActors = [];
        }

        $this->render('pages/pengurusan-akses', get_defined_vars());
    }
}
