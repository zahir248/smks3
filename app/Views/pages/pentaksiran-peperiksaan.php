<?php
$meta = is_array($kurikulum_meta ?? null) ? $kurikulum_meta : ['intro' => '', 'sections' => []];
$sections = is_array($meta['sections'] ?? null) ? $meta['sections'] : [];
$pageKey = (string) ($kurikulum_page_key ?? '');
$bySection = is_array($kurikulum_by_section ?? null) ? $kurikulum_by_section : [];
?>
<section class="page-section">
    <div class="container"
         <?php if (!empty($is_editor)): ?>
         data-edit-block="kurikulum_meta"
         data-edit-label="Sunting pengenalan"
         data-edit-hint="Teks pengenalan di bahagian atas halaman sahaja."
         data-page-key="<?= htmlspecialchars($pageKey, ENT_QUOTES, 'UTF-8') ?>"
         data-intro="<?= htmlspecialchars((string) ($meta['intro'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
         <?php endif; ?>>
        <?php if (trim((string) ($meta['intro'] ?? '')) !== ''): ?>
            <p class="text-center text-muted lead mb-4">
                <?= htmlspecialchars((string) $meta['intro']) ?>
                <a href="contact" class="text-decoration-none fw-semibold">Hubungi kami</a> untuk pertanyaan lanjut.
            </p>
        <?php endif; ?>
    </div>
</section>

<?php
$sectionLayout = [
    'dalaman' => 'col-lg-8 mx-auto',
    'spm' => 'col-lg-8 mx-auto',
    'pbd' => 'col-md-5',
];
$sectionLabels = [
    'dalaman' => 'Unit Peperiksaan Dalaman',
    'spm' => 'Peperiksaan Umum / SPM',
    'pbd' => 'Pentaksiran Bilik Darjah (PBD)',
];
foreach (['dalaman', 'spm', 'pbd'] as $sectionKey):
    $sec = is_array($sections[$sectionKey] ?? null) ? $sections[$sectionKey] : ['title' => '', 'subtitle' => ''];
    $cards = is_array($bySection[$sectionKey] ?? null) ? $bySection[$sectionKey] : [];
    $col = $sectionLayout[$sectionKey] ?? 'col-md-6 col-lg-4';
    $sectionLabel = $sectionLabels[$sectionKey] ?? $sectionKey;
?>
<section class="page-section">
    <div class="container">
        <div class="text-center mb-5"
             <?php if (!empty($is_editor)): ?>
             data-edit-block="kurikulum_section"
             data-edit-label="Sunting tajuk: <?= htmlspecialchars($sectionLabel, ENT_QUOTES, 'UTF-8') ?>"
             data-edit-hint="Tajuk dan subtajuk untuk bahagian ini sahaja."
             data-page-key="<?= htmlspecialchars($pageKey, ENT_QUOTES, 'UTF-8') ?>"
             data-section-key="<?= htmlspecialchars($sectionKey, ENT_QUOTES, 'UTF-8') ?>"
             data-title="<?= htmlspecialchars((string) ($sec['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-subtitle="<?= htmlspecialchars((string) ($sec['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="fw-bold" data-bind="kurikulum_section_title"><?= htmlspecialchars((string) ($sec['title'] ?? '')) ?></h2>
            <?php if (trim((string) ($sec['subtitle'] ?? '')) !== ''): ?>
                <p class="text-muted" data-bind="kurikulum_section_subtitle"><?= htmlspecialchars((string) $sec['subtitle']) ?></p>
            <?php endif; ?>
        </div>
        <?php
        $section_key = $sectionKey;
        $col_class = $col;
        $row_class = 'row g-4 justify-content-center';
        smks3_view_include(VIEW_PATH . '/partials/kurikulum-cards.php', compact(
            'is_editor',
            'kurikulum_page_key',
            'cards',
            'section_key',
            'col_class',
            'row_class'
        ));
        ?>
    </div>
</section>
<?php endforeach; ?>
